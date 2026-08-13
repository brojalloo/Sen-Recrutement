<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'role',
        'status',
        'avatar',
        'reset_token',
        'reset_token_expires',
        'reset_expires',
        'email_verified_at',
        'last_login_at',
        'cv_path',
        'address',
        'bio',
        'company_name',
        'company_description',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'reset_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'reset_token_expires' => 'datetime',
            'reset_expires' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Accessor for full name
    public function getFullNameAttribute(): string
    {
        if (! empty($this->first_name) || ! empty($this->last_name)) {
            return trim(($this->first_name ?? '').' '.($this->last_name ?? ''));
        }

        return $this->name ?? '';
    }

    /**
     * Utilisateurs auxquels $user a le droit d'écrire.
     *
     * Un candidat ne peut joindre que les recruteurs dont il a postulé aux
     * offres, un recruteur que les candidats ayant postulé aux siennes. Sans
     * ce filtre, n'importe quel compte gratuit lit l'annuaire complet.
     *
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeContactableBy(Builder $query, self $user): Builder
    {
        if (($user->role ?? null) === 'admin') {
            return $query->whereKeyNot($user->getKey());
        }

        if (($user->role ?? null) === 'recruiter') {
            return $query->whereIn(
                'id',
                Application::query()
                    ->whereIn('job_id', Job::query()->where('recruiter_id', $user->getKey())->select('id'))
                    ->select('user_id')
            );
        }

        return $query->whereIn(
            'id',
            Job::query()
                ->whereIn('id', Application::query()->where('user_id', $user->getKey())->select('job_id'))
                ->select('recruiter_id')
        );
    }

    // Relations
    public function jobs()
    {
        return $this->hasMany(Job::class, 'recruiter_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'user_id');
    }
}
