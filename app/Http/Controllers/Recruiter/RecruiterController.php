<?php

namespace App\Http\Controllers\Recruiter;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Support\Facades\Auth;

class RecruiterController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $jobs = Job::query()->where('recruiter_id', $userId)->orderByDesc('created_at')->limit(5)->get();

        // Les offres du recruteur servent quatre fois : une sous-requête plutôt
        // que quatre `pluck` successifs, et le filtrage reste fait par la base.
        $ownJobIds = Job::query()->select('id')->where('recruiter_id', $userId);

        $applications = Application::query()
            ->with(['user', 'job'])
            ->whereIn('job_id', $ownJobIds)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $stats = [
            'total_jobs' => Job::query()->where('recruiter_id', $userId)->count(),
            'active_jobs' => Job::query()->where('recruiter_id', $userId)->where('status', 'active')->count(),
            'total_applications' => Application::query()->whereIn('job_id', $ownJobIds)->count(),
        ];

        return view('recruiter.dashboard', compact('jobs', 'applications', 'stats'));
    }

    public function acceptApplication($id)
    {
        $application = Application::findOrFail($id);

        // Vérifier que l'offre appartient au recruteur connecté
        $job = Job::where('id', $application->job_id)
            ->where('recruiter_id', Auth::id())
            ->firstOrFail();

        $application->update(['status' => 'accepted']);

        // Envoyer une notification par email au candidat
        try {
            $application->user->notify(new ApplicationStatusChanged($application, 'accepted'));
        } catch (\Exception $e) {
            // Ignorer les erreurs d'envoi d'email
        }

        return redirect()->route('recruiter.dashboard')
            ->with('status', 'Candidature acceptée avec succès ! Un email a été envoyé au candidat.');
    }

    public function rejectApplication($id)
    {
        $application = Application::findOrFail($id);

        // Vérifier que l'offre appartient au recruteur connecté
        $job = Job::where('id', $application->job_id)
            ->where('recruiter_id', Auth::id())
            ->firstOrFail();

        $application->update(['status' => 'rejected']);

        // Envoyer une notification par email au candidat
        try {
            $application->user->notify(new ApplicationStatusChanged($application, 'rejected'));
        } catch (\Exception $e) {
            // Ignorer les erreurs d'envoi d'email
        }

        return redirect()->route('recruiter.dashboard')
            ->with('status', 'Candidature refusée. Un email a été envoyé au candidat.');
    }
}
