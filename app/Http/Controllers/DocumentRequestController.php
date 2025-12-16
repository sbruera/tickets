<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentRequestController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $this->authorize('request', $ticket);

        DocumentRequest::create([
            'ticket_id' => $ticket->id,
            'requested_by_id' => Auth::user()->id,
            'document_type' => $request->document_type,
        ]);

        return redirect()->route('tickets.show', $ticket);
    }

    public function fulfill(DocumentRequest $documentRequest)
    {
        $documentRequest->fulfilled_at = now();
        $documentRequest->save();

        return redirect()->back();
    }

    public function destroy(DocumentRequest $documentRequest)
    {
        $ticket = $documentRequest->ticket;
        $this->authorize('request', $ticket);

        $documentRequest->delete();

        return redirect()->back();
    }
}
