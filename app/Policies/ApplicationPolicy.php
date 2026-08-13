<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    /**
     * Qui peut télécharger le CV joint à une candidature : le candidat qui
     * l'a envoyé, et le recruteur propriétaire de l'offre.
     */
    public function downloadCv(User $user, Application $application): bool
    {
        if ($application->user_id === $user->id) {
            return true;
        }

        return $application->job?->recruiter_id === $user->id;
    }
}
