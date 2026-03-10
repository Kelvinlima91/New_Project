<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

// 👇 ADD THESE MISSING IMPORTS
use App\Models\Complaint;
use App\Models\Attachment;
use App\Models\UserLimit;
use App\Models\Company;      // Add this
use App\Models\Interaction;  // Add this

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'nif',
        'document_type',
        'document_number',
        'full_name',
        'birth_date',
        'gender',
        'email',
        'primary_phone',
        'secondary_phone',
        'island',
        'county',
        'parish',
        'locality',
        'street_address',
        'postal_code',
        'password',
        'preferred_contact',
        'preferred_language',
        'accepted_terms',
        'allows_notifications',
        'allows_statistical_sharing'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'verified' => 'boolean',
        'blocked' => 'boolean',
        'accepted_terms' => 'boolean',
        'allows_notifications' => 'boolean',
        'allows_statistical_sharing' => 'boolean',
        'verified_at' => 'datetime',
        'terms_accepted_at' => 'datetime',
        'last_login_at' => 'datetime',
        'blocked_until' => 'datetime',
    ];

    /**
     * Relationship: User has many Complaints
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class);  // Now recognized
    }

    /**
     * Relationship: User has many Attachments
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);  // Now recognized
    }

    /**
     * Relationship: User has many Interactions
     */
    public function interactions()
    {
        return $this->hasMany(Interaction::class);  // Now recognized
    }

    /**
     * Relationship: User has many Company relationships (if they represent companies)
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_users');  // Now recognized
    }

    /**
     * Relationship: User has daily limit
     */
    public function dailyLimit()
    {
        return $this->hasOne(UserLimit::class)->where('date', today());  // Now recognized
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [
            $this->street_address,
            $this->locality,
            $this->parish,
            $this->county,
            $this->island,
            $this->postal_code
        ];

        return implode(', ', array_filter($parts));
    }

    /**
     * Get island name
     */
    public function getIslandNameAttribute(): string
    {
        $islands = [
            'S' => 'Santiago',
            'SV' => 'São Vicente',
            'SA' => 'Sal',
            'BV' => 'Boa Vista',
            'MA' => 'Maio',
            'FO' => 'Fogo',
            'BR' => 'Brava',
            'SN' => 'Santo Antão',
            'SL' => 'São Nicolau',
        ];

        return $islands[$this->island] ?? $this->island;
    }

    /**
     * Check if user can make a complaint
     */
    public function canMakeComplaint(): bool
    {
        return !$this->blocked &&
               $this->verified &&
               $this->accepted_terms;
    }

    /**
     * Increment complaint count
     */
    public function incrementComplaintCount(): void
    {
        $this->increment('total_complaints');
    }
}
