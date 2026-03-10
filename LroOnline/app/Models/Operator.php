<?php
// app/Models/Operator.php (NOT Operators.php)

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Operator extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'operators';

    protected $fillable = [
        'nif',
        'full_name',
        'institutional_email',
        'institutional_phone',
        'institution',
        'department',
        'position',
        'access_profile',
        'responsible_sectors',
        'active',
        'hired_at',
        'terminated_at',
        'password_hash',
        'salt',
        'requires_2fa',
    ];

    protected $hidden = [
        'password_hash',
        'salt',
        'remember_token',
    ];

    protected $casts = [
        'active' => 'boolean',
        'requires_2fa' => 'boolean',
        'hired_at' => 'date',
        'terminated_at' => 'date',
        'last_login_at' => 'datetime',
        'responsible_sectors' => 'array',
    ];

    /**
     * Get the authentication password
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
