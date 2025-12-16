<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadDocumentRequest;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function store(UploadDocumentRequest $request, Ticket $ticket)
    {
        $this->authorize('upload', $ticket);

        $file = $request->file('document');
        $filePath = $file->store("tickets/{$ticket->id}", 'private');

        Document::create([
            'ticket_id' => $ticket->id,
            'uploaded_by_id' => Auth::user()->id,
            'file_path' => $filePath,
            'type' => $request->type ?? $file->getClientOriginalName(),
        ]);

        return redirect()->route('tickets.show', $ticket);
    }

    public function show(Document $document)
    {
        $this->authorize('view', $document);
        return Storage::disk('private')->download($document->file_path);
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);
        Storage::disk('private')->delete($document->file_path);
        $document->delete();

        return redirect()->back();
    }
}
