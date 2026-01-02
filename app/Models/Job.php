<?php

namespace App\Models;

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
