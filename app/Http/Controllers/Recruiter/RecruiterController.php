<?php

namespace App\Http\Controllers\Recruiter;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Notifications\ApplicationStatusChanged;
use App\Support\NotificationDispatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RecruiterController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $jobs = Job::query()->where('recruiter_id', $userId)->orderByDesc('created_at')->limit(5)->get();
        $applications = Application::query()
            ->whereIn('job_id', Job::query()->where('recruiter_id', $userId)->pluck('id'))
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
        $stats = [
            'total_jobs' => Job::query()->where('recruiter_id', $userId)->count(),
            'active_jobs' => Job::query()->where('recruiter_id', $userId)->where('status', 'active')->count(),
            'total_applications' => Application::query()->whereIn('job_id', Job::query()->where('recruiter_id', $userId)->pluck('id'))->count(),
        ];

        return view('recruiter.dashboard', compact('jobs', 'applications', 'stats'));
    }

    public function acceptApplication(NotificationDispatcher $notifications, $id): RedirectResponse
    {
        return $this->decide($notifications, $id, 'accepted', 'Candidature acceptée avec succès !');
    }

    public function rejectApplication(NotificationDispatcher $notifications, $id): RedirectResponse
    {
        return $this->decide($notifications, $id, 'rejected', 'Candidature refusée.');
    }

    /**
     * Accepter et refuser ne différaient que par deux chaînes de caractères.
     *
     * L'offre est cherchée filtrée par recruteur : sur la candidature d'un
     * autre, la réponse est 404 et non 403, pour ne pas révéler son existence.
     */
    private function decide(
        NotificationDispatcher $notifications,
        string $id,
        string $status,
        string $message,
    ): RedirectResponse {
        $application = Application::with('user')->findOrFail($id);

        Job::where('id', $application->job_id)
            ->where('recruiter_id', Auth::id())
            ->firstOrFail();

        $application->update(['status' => $status]);

        // Ne promettre l'email que s'il est effectivement parti : l'ancien
        // message l'affirmait même quand l'envoi venait d'échouer en silence.
        $notified = $application->user
            && $notifications->send($application->user, new ApplicationStatusChanged($application, $status));

        return redirect()->route('recruiter.dashboard')->with(
            'status',
            $notified
                ? $message.' Un email a été envoyé au candidat.'
                : $message.' Le candidat n\'a pas pu être prévenu par email.'
        );
    }
}
