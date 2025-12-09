<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\SupportRequest;
use Illuminate\Http\Request;

class SuperadminSupportController extends Controller
{
    /**
     * Display a listing of support requests.
     */
    public function index(Request $request)
    {
        $query = SupportRequest::with(['user', 'company'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $supportRequests = $query->paginate(20);

        return view('superadmin.support.index', compact('supportRequests'));
    }

    /**
     * Display the specified support request.
     */
    public function show(SupportRequest $supportRequest)
    {
        $supportRequest->load(['user', 'company']);
        return view('superadmin.support.show', compact('supportRequest'));
    }

    /**
     * Update the status of a support request.
     */
    public function updateStatus(Request $request, SupportRequest $supportRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $oldStatus = $supportRequest->status;
        $oldNotes = $supportRequest->admin_notes;

        $supportRequest->update([
            'status' => $validated['status'],
            'resolved_at' => $validated['status'] === 'resolved' ? now() : $supportRequest->resolved_at,
        ]);

        // Create notification for user
        $notificationData = [
            'support_request_id' => $supportRequest->id,
            'old_status' => $oldStatus,
            'new_status' => $validated['status'],
        ];

        // Notification for status change
        if ($oldStatus !== $validated['status']) {
            $statusLabels = [
                'pending' => 'Pendiente',
                'in_progress' => 'En Progreso',
                'resolved' => 'Resuelto',
                'closed' => 'Cerrado',
            ];

            $notificationType = $validated['status'] === 'resolved' ? 'support_resolved' : 'support_status_changed';
            
            \App\Models\Notification::create([
                'user_id' => $supportRequest->user_id,
                'type' => $notificationType,
                'title' => 'Actualización en tu solicitud de soporte',
                'message' => "Tu solicitud #{$supportRequest->id} cambió de estado: {$statusLabels[$oldStatus]} → {$statusLabels[$validated['status']]}",
                'data' => $notificationData,
            ]);
        }

        // Create chat message if admin notes provided
        if (isset($validated['admin_notes']) && !empty($validated['admin_notes'])) {
            \App\Models\SupportMessage::create([
                'support_request_id' => $supportRequest->id,
                'user_id' => \Auth::id(),
                'message' => $validated['admin_notes'],
                'is_admin_reply' => true,
            ]);

            // Notification for admin response
            \App\Models\Notification::create([
                'user_id' => $supportRequest->user_id,
                'type' => 'support_admin_note',
                'title' => 'Respuesta del equipo de soporte',
                'message' => "El equipo de soporte respondió tu solicitud #{$supportRequest->id}: \"{$supportRequest->subject}\"",
                'data' => $notificationData,
            ]);
        }

        return redirect()->route('superadmin.support.show', $supportRequest)
            ->with('success', 'Estado de la solicitud actualizado correctamente.');
    }
}
