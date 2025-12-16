<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketNote;
use App\Models\Company;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with(['company', 'user', 'superadmin'])
            ->latest();

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('ticket_number', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%')
                  ->orWhereHas('company', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $tickets = $query->paginate(15);

        // Statistics
        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
        ];

        return view('superadmin.tickets.index', compact('tickets', 'stats'));
    }

    public function create(Request $request)
    {
        $company = null;
        if ($request->filled('company_id')) {
            $company = Company::with('users')->findOrFail($request->company_id);
        }

        return view('superadmin.tickets.create', compact('company'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'user_id' => 'nullable|exists:users,id',
            'contact_type' => 'required|in:call,whatsapp,email,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:technical,configuration,query,error,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'solution' => 'nullable|string',
            'estimated_time' => 'nullable|integer|min:0',
        ]);

        $validated['superadmin_id'] = auth()->id();
        $validated['status'] = $request->filled('solution') ? 'resolved' : 'open';
        
        if ($validated['status'] === 'resolved') {
            $validated['resolved_at'] = now();
        }

        $ticket = SupportTicket::create($validated);

        return redirect()->route('superadmin.tickets.show', $ticket)
            ->with('success', 'Ticket creado exitosamente: ' . $ticket->ticket_number);
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['company', 'user', 'superadmin', 'notes.user']);

        return view('superadmin.tickets.show', compact('ticket'));
    }

    public function update(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent',
            'solution' => 'nullable|string',
            'actual_time' => 'nullable|integer|min:0',
        ]);

        // Update timestamps based on status
        if ($validated['status'] === 'resolved' && $ticket->status !== 'resolved') {
            $validated['resolved_at'] = now();
        }

        if ($validated['status'] === 'closed' && $ticket->status !== 'closed') {
            $validated['closed_at'] = now();
        }

        $ticket->update($validated);

        return redirect()->back()->with('success', 'Ticket actualizado exitosamente');
    }

    public function addNote(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'note' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['support_ticket_id'] = $ticket->id;

        $ticket->notes()->create($validated);

        return redirect()->back()->with('success', 'Nota agregada exitosamente');
    }
}
