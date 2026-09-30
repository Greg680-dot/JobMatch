<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visite extends Model
{
    protected $fillable = [
        'ip_address',
        'country',
        'country_code',
        'city',
        'device_type',
        'os',
        'browser',
        'path',
        'user_id',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}