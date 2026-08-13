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

        // Check if user has CV
        if (! $user->cv_path || ! Storage::disk('public')->exists($user->cv_path)) {
            return back()->with('error', 'Le CV n\'est pas disponible.');
        }

        // Check permissions (recruiter can download CV of candidates who applied to their jobs)
        if (Auth::user()->role === 'recruiter') {
            $hasApplied = Application::whereHas('job', function ($q) {
                $q->where('recruiter_id', Auth::id());
            })->where('user_id', $userId)->exists();

            if (! $hasApplied && Auth::id() !== $userId) {
                abort(403, 'Accès non autorisé');
            }
        } elseif (Auth::user()->role === 'admin' || Auth::id() === $userId) {
            // Admin and user himself can download
        } else {
            abort(403, 'Accès non autorisé');
        }

        $filePath = storage_path('app/public/'.$user->cv_path);
        $fileName = ($user->full_name ?? $user->name).'_CV.'.pathinfo($user->cv_path, PATHINFO_EXTENSION);

        return response()->download($filePath, $fileName);
    }
}
