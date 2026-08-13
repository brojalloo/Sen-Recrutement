<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use App\Notifications\NewApplicationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function applyForm($id)
    {
        $job = Job::findOrFail($id);

        return view('candidate.apply', compact('job'));
    }

    public function applyStore(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $userId = Auth::id();

        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:5000'],
            'cover_letter' => ['nullable', 'string', 'max:10000'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
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

        // Envoyer une notification par email au recruteur
        try {
            $recruiter = User::find($job->recruiter_id);
            if ($recruiter) {
                $recruiter->notify(new NewApplicationReceived($application));
            }
        } catch (\Exception $e) {
            // Ignorer les erreurs d'envoi d'email
        }

        return redirect()->route('candidate.dashboard')->with('status', 'Candidature envoyée avec succès !');
    }
}
