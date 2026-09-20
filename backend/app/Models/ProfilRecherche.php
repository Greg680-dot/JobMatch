<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilRecherche extends Model
{
    protected $table = 'profils_recherche';

    protected $fillable = [
        'user_id',
        'type_opportunite',
        'pays',
        'types_contrat',
        'localisations',
        'salaire_min',
        'keywords_must',
        'keywords_excluded',
        'teletravail_only',
    ];

    protected $casts = [
        'types_contrat' => 'array',
        'localisations' => 'array',
        'keywords_must' => 'array',
        'keywords_excluded' => 'array',
        'salaire_min' => 'float',
        'teletravail_only' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
