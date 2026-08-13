<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'recruitment_jobs';

    protected $fillable = [
        'recruiter_id',
        'title',
        'description',
        'company',
        'company_logo',
        'location',
        'type',
        'salary',
        'salary_min',
        'salary_max',
        'experience',
        'skills',
        'requirements',
        'benefits',
        'status',
        'approval_status',
        'is_active',
        'expires_at',
        'expiration_date',
        'start_date',
        'posted_at',
        'views',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'expiration_date' => 'datetime',
        'start_date' => 'datetime',
        'posted_at' => 'datetime',
        'is_active' => 'boolean',
        'salary' => 'float',
        'salary_min' => 'float',
        'salary_max' => 'float',
        'views' => 'integer',
    ];

    // Types de contrat
    public const TYPES = [
        'CDI' => 'CDI (Contrat à Durée Indéterminée)',
        'CDD' => 'CDD (Contrat à Durée Déterminée)',
        'Stage' => 'Stage',
        'Alternance' => 'Alternance',
        'Freelance' => 'Freelance',
        'Interim' => 'Intérim',
    ];

    /**
     * Offres visibles du public : approuvées par un admin et actives.
     *
     * Source de vérité unique. Dupliquer ce filtre dans les contrôleurs est ce
     * qui a laissé les offres en attente et rejetées s'afficher en page
     * d'accueil et sur le tableau de bord candidat.
     *
     * @param  Builder<Job>  $query
     * @return Builder<Job>
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('approval_status', 'approved')
            ->where(function (Builder $q) {
                $q->where('status', 'active')->orWhere('is_active', true);
            });
    }

    // Relations
    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
