<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function downloadCV(string $userId): StreamedResponse|RedirectResponse
    {
        $user = User::findOrFail($userId);

        if (! $user->cv_path || ! Storage::disk('local')->exists($user->cv_path)) {
            return back()->with('error', 'Le CV n\'est pas disponible.');
        }

        // La cascade de if/elseif qui vivait ici est passée dans UserPolicy :
        // c'est la même question que pour le CV de candidature, elle mérite
        // une seule réponse, à un seul endroit.
        $this->authorize('downloadCv', $user);

        $fileName = ($user->full_name ?? $user->name).'_CV.'.pathinfo($user->cv_path, PATHINFO_EXTENSION);

        return Storage::disk('local')->download($user->cv_path, $fileName);
    }
}
