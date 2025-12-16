<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DocumentRequest extends Model
{
    protected $fillable = [
        'ticket_id',
        'requested_by_id',
        'document_type',
        'fulfilled_at',
    ];

    protected $dates = [
        'fulfilled_at',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_id');
    }

    public function document() : HasOne
    {
        return $this->hasOne(Document::class, 'type', 'document_type')
        ->whereColumn('ticket_id', 'ticket_id');
    }
}
