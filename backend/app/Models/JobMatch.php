<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JobMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'user_id',
        'opportunite_id',
        'score_pertinence',
        'passed_hard_filters',
        'rejection_reason',
        'matching_skills',
        'missing_skills',
        'summary_explanation',
        'statut',
    ];

    protected $casts = [
        'score_pertinence' => 'float',
        'passed_hard_filters' => 'boolean',
        'matching_skills' => 'array',
        'missing_skills' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function opportunite(): BelongsTo
    {
        return $this->belongsTo(Opportunite::class);
    }

    public function candidature(): HasOne
    {
        return $this->hasOne(Candidature::class, 'match_id');
    }
}
