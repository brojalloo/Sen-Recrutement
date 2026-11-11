<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function downloadCV($userId)
    {
        $user = User::findOrFail($userId);

        // Le paramètre de route arrive en chaîne : comparer en strict à
        // Auth::id() (entier) refusait au propriétaire son propre CV.
        $userId = (int) $userId;
        $isOwner = Auth::id() === $userId;

        // Check if user has CV
        if (! $user->cv_path || ! Storage::disk('local')->exists($user->cv_path)) {
            return back()->with('error', 'Le CV n\'est pas disponible.');
        }

        // Check permissions (recruiter can download CV of candidates who applied to their jobs)
        if (Auth::user()->role === 'recruiter') {
            $hasApplied = Application::whereHas('job', function ($q) {
                $q->where('recruiter_id', Auth::id());
            })->where('user_id', $userId)->exists();

            if (! $hasApplied && ! $isOwner) {
                abort(403, 'Accès non autorisé');
            }
        } elseif (Auth::user()->role === 'admin' || $isOwner) {
            // Admin and user himself can download
        } else {
            abort(403, 'Accès non autorisé');
        }

        $fileName = ($user->full_name ?? $user->name).'_CV.'.pathinfo($user->cv_path, PATHINFO_EXTENSION);

        return Storage::disk('local')->download($user->cv_path, $fileName);
    }
}
