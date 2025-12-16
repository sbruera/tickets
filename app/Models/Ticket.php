<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TicketType;
use App\Enums\TransportMode;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'name',
        'type',
        'transport_mode',
        'product',
        'country',
        'status',
        'user_id',
        'assigned_agent_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => TicketType::class,
            'transport_mode' => TransportMode::class,
            'status' => TicketStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function documentRequests(): HasMany
    {
        return $this->hasMany(DocumentRequest::class);
    }
}
