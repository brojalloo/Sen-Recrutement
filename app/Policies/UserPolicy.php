<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class UserPolicy
{
    /**
     * Qui peut télécharger le CV attaché au profil d'un utilisateur :
     * l'intéressé, un admin, et le recruteur auprès duquel il a candidaté.
     *
     * Cette règle était écrite en une cascade de `if/elseif` dans le
     * contrôleur, distincte de celle du CV de candidature alors qu'elle répond
     * à la même question. La rassembler ici la rend relisable et testable.
     */
    public function downloadCv(User $user, User $owner): bool
    {
        if ($user->id === $owner->id || $user->role === 'admin') {
            return true;
        }

        if ($user->role !== 'recruiter') {
            return false;
        }

        return Application::query()
            ->where('user_id', $owner->id)
            ->whereHas('job', fn ($query) => $query->where('recruiter_id', $user->id))
            ->exists();
    }
}
