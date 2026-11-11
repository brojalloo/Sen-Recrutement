<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use App\Notifications\NewApplicationReceived;
use App\Support\NotificationDispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function applyForm($id)
    {
        $job = Job::visible()->findOrFail($id);

        return view('candidate.apply', compact('job'));
    }

    public function applyStore(Request $request, NotificationDispatcher $notifications, $id)
    {
        $job = Job::visible()->findOrFail($id);
        $userId = Auth::id();

        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:5000'],
            'cover_letter' => ['nullable', 'string', 'max:10000'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        // Doublé par un index unique sur (job_id, user_id) : ce contrôle sert à
        // rendre un message clair, pas à garantir l'unicité.
        $already = Application::where('job_id', $job->id)->where('user_id', $userId)->exists();

        if ($already) {
            return back()
                ->withInput()
                ->withErrors(['job_id' => 'Vous avez déjà postulé à cette offre.']);
        }

        // Disque privé : le disque `public` est servi sans authentification via
        // le lien symbolique `public/storage`, ce qui rendrait le CV
        // téléchargeable par n'importe qui connaissant l'URL.
        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'local');
        }

        $application = Application::create([
            'job_id' => $job->id,
            'user_id' => $userId,
            'message' => $data['message'] ?? null,
            'cover_letter' => $data['cover_letter'] ?? null,
            'cv_path' => $cvPath,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        // Prévenir le recruteur. Un échec d'envoi est consigné, pas avalé,
        // mais n'annule pas la candidature.
        $recruiter = User::find($job->recruiter_id);
        if ($recruiter) {
            $notifications->send($recruiter, new NewApplicationReceived($application));
        }

        return redirect()->route('candidate.dashboard')->with('status', 'Candidature envoyée avec succès !');
    }
}
