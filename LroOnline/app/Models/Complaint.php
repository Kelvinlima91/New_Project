<?php
// app/Models/Complaint.php (NOT Complaints.php)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Company;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'complaints';

    protected $fillable = [
        'user_id',
        'company_id',
        'anonymous_phone',
        'protocol_number',
        'access_code',
        'category',
        'title',
        'description',
        'incident_date',
        'incident_location',
        'previous_attempts',
        'attempt_channel',
        'expected_resolution',
        'urgency',
        'status',
        'company_response_deadline',
        'final_resolution_deadline',
        'sent_to_company_at',
        'company_responded_at',
        'closed_at',
        'due_date',
        'days_open',
        'response_time_hours',
        'entry_channel',
        'anonymous',
        'allow_company_contact',
        'sms_verified',
        'priority_score',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'sent_to_company_at' => 'datetime',
        'company_responded_at' => 'datetime',
        'closed_at' => 'datetime',
        'due_date' => 'datetime',
        'anonymous' => 'boolean',
        'allow_company_contact' => 'boolean',
        'sms_verified' => 'boolean',
        'days_open' => 'integer',
        'priority_score' => 'integer',
    ];

    /**
     * Relationship: Complaint belongs to a User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Complaint belongs to a Company
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class);
    }

    /**
     * Relationship: Complaint has many Attachments
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Relationship: Complaint has many Interactions
     */
    public function interactions(): HasMany
    {
        return $this->hasMany(interactions::class);
    }

    /**
     * Get formatted protocol number
     */
    public function getFormattedProtocolAttribute(): string
    {
        return $this->protocol_number;
    }

    /**
     * Check if complaint is overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->due_date || $this->status === 'resolved') {
            return false;
        }
        return now()->greaterThan($this->due_date);
    }
}
