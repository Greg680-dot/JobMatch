<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidature extends Model
{
    protected $fillable = [
        'user_id',
        'opportunite_id',
        'match_id',
        'objet_email',
        'lettre_motivation',
        'cv_adaptation_tips',
        'suggested_skills',
        'statut',
        'mode_envoi',
        'date_envoi',
        'notes_candidat',
    ];

    protected $casts = [
        'cv_adaptation_tips' => 'array',
        'suggested_skills' => 'array',
        'date_envoi' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function opportunite(): BelongsTo
    {
        return $this->belongsTo(Opportunite::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(JobMatch::class, 'match_id');
    }
}
