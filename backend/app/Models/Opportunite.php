<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opportunite extends Model
{
    protected $fillable = [
        'source_id',
        'titre',
        'entreprise',
        'localisation',
        'type_contrat',
        'description',
        'salaire_indicatif',
        'teletravail',
        'url_source',
        'deduplication_hash',
        'embedding',
        'date_publication',
    ];

    protected $casts = [
        'salaire_indicatif' => 'float',
        'teletravail' => 'boolean',
        'embedding' => 'array',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(JobMatch::class);
    }

    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }
}
