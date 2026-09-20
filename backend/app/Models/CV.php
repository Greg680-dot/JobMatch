<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CV extends Model
{
    protected $table = 'cvs';

    protected $fillable = [
        'user_id',
        'original_filename',
        'raw_text',
        'parsed_data',
        'embedding',
        'is_default',
    ];

    protected $casts = [
        'parsed_data' => 'array',
        'embedding' => 'array',
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
