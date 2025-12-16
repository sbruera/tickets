<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Enums\TransportMode;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAgent()) {
            $tickets = Ticket::where('assigned_agent_id', $user->id)->latest()->get();
        } else {
            $tickets = $user->tickets()->latest()->get();
        }

        return Inertia::render('tickets/Index', [
            'tickets' => $tickets,
        ]);
    }

    public function agentIndex()
    {
        $user = Auth::user();
        abort_unless($user->isAgent(), 403);

        $tickets = Ticket::latest()->get();

        return Inertia::render('tickets/AgentIndex', [
            'tickets' => $tickets,
        ]);
    }

    public function assign(Ticket $ticket)
    {
        $user = Auth::user();
        abort_unless($user->isAgent(), 403);

        $ticket->assigned_agent_id = $user->id;
        $ticket->save();

        return redirect()->back();
    }

    public function create()
    {
        return Inertia::render('tickets/Create', [
            'types' => collect(TicketType::cases())->map(fn($e) => ['name' => $e->name, 'value' => $e->value]),
            'transportModes' => collect(TransportMode::cases())->map(fn($e) => ['name' => $e->name, 'value' => $e->value]),
        ]);
    }

    public function store(Request $request)
    {
        $ticket = Ticket::create([
            'name' => $request->name,
            'type' => $request->type,
            'transport_mode' => $request->transport_mode,
            'product' => $request->product,
            'country' => $request->country,
            'status' => TicketStatus::NEW,
            'user_id' => Auth::user()->id,
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $filePath = $file->store("tickets/{$ticket->id}", 'private');
                Document::create([
                    'ticket_id' => $ticket->id,
                    'uploaded_by_id' => Auth::user()->id,
                    'file_path' => $filePath,
                    'type' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('tickets.show', $ticket);
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        return Inertia::render('tickets/Show', [
            'ticket' => $ticket->load(['documents', 'documentRequests']),
            'canRequestDocuments' => Auth::user()->isAgent(),
        ]);
    }

    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        return Inertia::render('tickets/Edit', [
            'ticket' => $ticket,
            'statuses' => collect(TicketStatus::cases())->map(fn($e) => ['name' => $e->name, 'value' => $e->value]),
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        $ticket->status = $request->status;
        $ticket->save();

        return redirect()->route('tickets.show', $ticket);
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);
        $ticket->delete();

        return redirect()->route('tickets.index');
    }
}
