<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'job_id',
        'user_id',
        'message',
        'cover_letter',
        'cv_path',
        'status',
        'notes',
        'reviewed_at',
        'reviewed_by',
        'applied_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'applied_at' => 'datetime',
    ];

    public const STATUS = [
        'pending' => 'En attente',
        'en attente' => 'En attente',
        'reviewing' => "En cours d'examen",
        'shortlisted' => 'Présélectionné',
        'interview' => 'Entretien prévu',
        'offered' => 'Offre envoyée',
        'accepted' => 'Acceptée',
        'accepté' => 'Acceptée',
        'rejected' => 'Refusée',
        'refusé' => 'Refusée',
        'withdrawn' => 'Retirée',
    ];

    // Relations
    /**
     * @return BelongsTo<Job, $this>
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
