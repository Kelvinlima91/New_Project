<?php
// app/Models/PhoneLimit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneLimit extends Model
{
    use HasFactory;

    protected $table = 'phone_limits';

    protected $fillable = [
        'phone',
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
     * Check if phone is blocked
     */
    public function isBlocked(): bool
    {
        return $this->blocked && $this->blocked_until && now()->lt($this->blocked_until);
    }

    /**
     * Increment counters
     */
    public function incrementCounters(): void
    {
        $this->increment('daily_counter');
        $this->increment('monthly_counter');
    }
}
