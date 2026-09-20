<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    protected $fillable = [
        'nom',
        'type_source',
        'url_cible',
        'config_selectors',
        'is_active',
        'last_run_at',
    ];

    protected $casts = [
        'config_selectors' => 'array',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
    ];

    public function opportunites(): HasMany
    {
        return $this->hasMany(Opportunite::class);
    }
}
