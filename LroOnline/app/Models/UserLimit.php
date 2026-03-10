<?php
// app/Models/UserLimit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLimit extends Model
{
    use HasFactory;

    protected $table = 'user_limits';

    protected $fillable = [
        'user_id',
        'date',
        'daily_counter',
        'monthly_counter',
        'blocked',
        'blocked_until',
    ];

    protected $casts = [
        'date' => 'date',
        'daily_counter' => 'integer',
        'monthly_counter' => 'integer',
        'blocked' => 'boolean',
        'blocked_until' => 'datetime',
    ];

    /**
     * Relationship: UserLimit belongs to a User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user is currently blocked
     */
    public function isBlocked(): bool
    {
        return $this->blocked && $this->blocked_until && now()->lt($this->blocked_until);
    }

    /**
     * Increment daily counter
     */
    public function incrementDaily(): void
    {
        $this->increment('daily_counter');
    }

    /**
     * Increment monthly counter
     */
    public function incrementMonthly(): void
    {
        $this->increment('monthly_counter');
    }

    /**
     * Block user until specified time
     */
    public function blockUntil(\Carbon\Carbon $until): void
    {
        $this->update([
            'blocked' => true,
            'blocked_until' => $until,
        ]);
    }

    /**
     * Unblock user
     */
    public function unblock(): void
    {
        $this->update([
            'blocked' => false,
            'blocked_until' => null,
        ]);
    }

    /**
     * Reset daily counter
     */
    public function resetDaily(): void
    {
        $this->update(['daily_counter' => 0]);
    }

    /**
     * Reset monthly counter
     */
    public function resetMonthly(): void
    {
        $this->update(['monthly_counter' => 0]);
    }
}
