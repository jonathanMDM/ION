<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportRequest;

class SupportController extends Controller
{
    /**
     * Display the support form.
     */
    public function index()
    {
        return view('support.index');
    }

    /**
     * Handle support request submission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|in:technical,billing,general',
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // 5MB max
        ]);

        $user = Auth::user();
        $company = $user->company;

        // Prepare email data
        $emailData = [
            'subject' => $validated['subject'],
            'category' => ucfirst($validated['category']),
            'message' => $validated['message'],
            'user_name' => $user->name,
            'user_email' => $user->email,
            'company_name' => $company ? $company->name : 'Sin empresa',
            'submitted_at' => now()->format('Y-m-d H:i:s'),
        ];

        // Handle attachment if present
        $attachmentPath = null;
        $attachmentName = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('support_attachments', 'public');
            $attachmentName = $request->file('attachment')->getClientOriginalName();
            $emailData['attachment_path'] = storage_path('app/public/' . $attachmentPath);
            $emailData['attachment_name'] = $attachmentName;
        }

        try {
            // Save to database first
            $supportRequest = SupportRequest::create([
                'user_id' => $user->id,
                'company_id' => $company ? $company->id : null,
                'subject' => $validated['subject'],
                'category' => $validated['category'],
                'message' => $validated['message'],
                'attachment_path' => $attachmentPath,
                'attachment_name' => $attachmentName,
                'status' => 'pending',
            ]);

            // Create notifications for all superadmins (ALWAYS execute this)
            $superadmins = \App\Models\User::all()->filter(function($u) {
                return $u->isSuperAdmin();
            });
            
            foreach ($superadmins as $superadmin) {
                \App\Models\Notification::create([
                    'user_id' => $superadmin->id,
                    'type' => 'support_request_created',
                    'title' => 'Nueva Solicitud de Soporte',
                    'message' => "Usuario {$user->name} creó una solicitud: \"{$validated['subject']}\"",
                    'data' => [
                        'support_request_id' => $supportRequest->id,
                        'user_id' => $user->id,
                        'category' => $validated['category'],
                    ],
                ]);
            }

            // Try to send emails (but don't fail if email is not configured)
            try {
                // Send email to support
                Mail::send('emails.support_request', $emailData, function ($message) use ($emailData, $attachmentPath) {
                    $message->to(config('mail.support_email', 'support@ioninventory.com'))
                        ->subject('Solicitud de Soporte: ' . $emailData['subject'])
                        ->replyTo($emailData['user_email'], $emailData['user_name']);

                    if ($attachmentPath && isset($emailData['attachment_path'])) {
                        $message->attach($emailData['attachment_path'], [
                            'as' => $emailData['attachment_name']
                        ]);
                    }
                });

                // Send confirmation email to user
                Mail::send('emails.support_confirmation', $emailData, function ($message) use ($emailData) {
                    $message->to($emailData['user_email'], $emailData['user_name'])
                        ->subject('Confirmación: Solicitud de Soporte Recibida');
                });
            } catch (\Exception $emailError) {
                // Log email error but don't fail the request
                \Log::warning('Failed to send support email: ' . $emailError->getMessage());
            }

            return redirect()->route('support.index')
                ->with('success', 'Tu solicitud de soporte ha sido registrada exitosamente. El equipo de soporte la revisará pronto.');
        } catch (\Exception $e) {
            \Log::error('Error creating support request: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Hubo un error al registrar tu solicitud. Por favor, intenta nuevamente.');
        }
    }

    /**
     * Display a specific support request.
     */
    /**
     * Display a specific support request.
     */
    public function show(SupportRequest $supportRequest)
    {
        // Ensure user owns this support request
        if ($supportRequest->user_id !== Auth::id()) {
            abort(403);
        }

        $supportRequest->load('messages.user');

        return view('support.show', compact('supportRequest'));
    }

    /**
     * Handle user response to support request.
     */
    public function respond(Request $request, SupportRequest $supportRequest)
    {
        // Ensure user owns this support request
        if ($supportRequest->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'user_response' => 'required|string|max:5000',
        ]);

        // Create new message
        \App\Models\SupportMessage::create([
            'support_request_id' => $supportRequest->id,
            'user_id' => Auth::id(),
            'message' => $validated['user_response'],
            'is_admin_reply' => false,
        ]);

        // Update request status if needed (e.g. reopen if closed)
        if ($supportRequest->status === 'resolved') {
            $supportRequest->update(['status' => 'in_progress']);
        }

        // Create notifications for all superadmins
        $user = Auth::user();
        $superadmins = \App\Models\User::all()->filter(function($user) {
            return $user->isSuperAdmin();
        });
        
        foreach ($superadmins as $superadmin) {
            \App\Models\Notification::create([
                'user_id' => $superadmin->id,
                'type' => 'support_user_response',
                'title' => 'Respuesta de Usuario en Soporte',
                'message' => "Usuario {$user->name} respondió a la solicitud #{$supportRequest->id}: \"{$supportRequest->subject}\"",
                'data' => [
                    'support_request_id' => $supportRequest->id,
                    'user_id' => $user->id,
                ],
            ]);
        }

        return redirect()->route('support.show', $supportRequest)
            ->with('success', 'Tu respuesta ha sido enviada al equipo de soporte.');
    }

    /**
     * Mark support request as resolved by user.
     */
    public function resolve(SupportRequest $supportRequest)
    {
        // Ensure user owns this support request
        if ($supportRequest->user_id !== Auth::id()) {
            abort(403);
        }

        $supportRequest->resolveByUser();

        // Notify superadmins
        $user = Auth::user();
        $superadmins = \App\Models\User::all()->filter(function($user) {
            return $user->isSuperAdmin();
        });
        
        foreach ($superadmins as $superadmin) {
            \App\Models\Notification::create([
                'user_id' => $superadmin->id,
                'type' => 'support_resolved',
                'title' => 'Solicitud Resuelta por Usuario',
                'message' => "Usuario {$user->name} marcó como resuelta la solicitud #{$supportRequest->id}",
                'data' => [
                    'support_request_id' => $supportRequest->id,
                    'user_id' => $user->id,
                ],
            ]);
        }

        return redirect()->route('support.show', $supportRequest)
            ->with('success', 'Solicitud marcada como resuelta.');
    }
}
