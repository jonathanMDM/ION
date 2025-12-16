<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportTicketNote;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get tickets for user's company
        $tickets = SupportTicket::with(['user', 'superadmin'])
            ->where('company_id', $user->company_id)
            ->latest()
            ->paginate(15);

        // Statistics
        $stats = [
            'total' => SupportTicket::where('company_id', $user->company_id)->count(),
            'open' => SupportTicket::where('company_id', $user->company_id)->where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('company_id', $user->company_id)->where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('company_id', $user->company_id)->where('status', 'resolved')->count(),
        ];

        return view('support.index', compact('tickets', 'stats'));
    }

    public function create()
    {
        return view('support.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:technical,configuration,query,error,other',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $user = auth()->user();

        $ticket = SupportTicket::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'contact_type' => 'email', // Default for client-created tickets
            'status' => 'open',
        ]);

        return redirect()->route('support.show', $ticket)
            ->with('success', 'Ticket creado exitosamente. Nuestro equipo lo atenderá pronto.');
    }

    public function show(SupportTicket $ticket)
    {
        // Ensure user can only see their company's tickets
        if ($ticket->company_id !== auth()->user()->company_id) {
            abort(403, 'No autorizado');
        }

        $ticket->load(['company', 'user', 'superadmin', 'notes.user']);

        return view('support.show', compact('ticket'));
    }

    public function addNote(Request $request, SupportTicket $ticket)
    {
        // Ensure user can only add notes to their company's tickets
        if ($ticket->company_id !== auth()->user()->company_id) {
            abort(403, 'No autorizado');
        }

        $validated = $request->validate([
            'note' => 'required|string',
        ]);

        $ticket->notes()->create([
            'user_id' => auth()->id(),
            'note' => $validated['note'],
            'is_internal' => false, // Client notes are never internal
        ]);

        return redirect()->back()->with('success', 'Comentario agregado exitosamente');
    }
}
