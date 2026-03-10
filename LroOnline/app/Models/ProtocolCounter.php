<?php
// app/Models/ProtocolCounter.php (NOT Protocol_Counters.php)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProtocolCounter extends Model
{
    use HasFactory;

    protected $table = 'protocol_counters';

    protected $fillable = [
        'year',
        'island',
        'last_number',
    ];

    protected $casts = [
        'year' => 'integer',
        'last_number' => 'integer',
    ];
}
