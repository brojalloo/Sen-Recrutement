<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

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
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
