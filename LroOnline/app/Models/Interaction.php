<?php
// app/Models/Interaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interaction extends Model
{
    use HasFactory;

    protected $table = 'interactions';

    protected $fillable = [
        'complaint_id',
        'interaction_type',
        'source',
        'user_id',
        'company_id',
        'operator_id',
        'message',
        'action_performed',
        'attachment_ids',
        'ip_address',
        'user_agent',
        'satisfactory',
        'settlement_proposal',
        'interacted_at',
    ];

    protected $casts = [
        'interacted_at' => 'datetime',
        'satisfactory' => 'boolean',
        'attachment_ids' => 'array',
    ];

    /**
     * Relationship: Interaction belongs to a Complaint
     */
    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    /**
     * Relationship: Interaction belongs to a User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Interaction belongs to a Company
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship: Interaction belongs to an Operator
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(operators::class);
    }
}
