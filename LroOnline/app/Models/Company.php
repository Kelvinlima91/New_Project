<?php
// app/Models/Company.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'nif',
        'legal_name',
        'trading_name',
        'main_sector',
        'regulatory_body',
        'contact_email',
        'contact_phone',
        'website',
        'island',
        'county',
        'parish',
        'locality',
        'street_address',
        'representative_name',
        'representative_email',
        'representative_phone',
        'active',
        'total_complaints',
        'complaints_last_30_days',
        'resolution_rate',
    ];

    protected $casts = [
        'active' => 'boolean',
        'resolution_rate' => 'decimal:2',
    ];

    /**
     * Relationship: Company has many Complaints
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Get active complaints only
     */
    public function activeComplaints(): HasMany
    {
        return $this->complaints()->whereNotIn('status', ['resolved', 'archived']);
    }

    /**
     * Get island name from code
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
     * Update company statistics
     */
    public function updateStatistics(): void
    {
        $total = $this->complaints()->count();
        $resolved = $this->complaints()->where('status', 'resolved')->count();
        $recent = $this->complaints()
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $rate = $total > 0 ? ($resolved / $total) * 100 : 0;

        $this->update([
            'total_complaints' => $total,
            'complaints_last_30_days' => $recent,
            'resolution_rate' => round($rate, 2),
        ]);
    }
}
