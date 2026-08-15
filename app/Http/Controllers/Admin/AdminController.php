<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'users' => [
                'total' => User::query()->count(),
                'candidates' => User::query()->where('role', 'candidate')->count(),
                'recruiters' => User::query()->where('role', 'recruiter')->count(),
                'active' => User::query()->where('status', 'active')->count(),
                'inactive' => User::query()->where('status', '!=', 'active')->count(),
            ],
            'jobs' => [
                'total' => Job::query()->count(),
                'pending' => Job::query()->where('approval_status', 'pending')->count(),
                'approved' => Job::query()->where('approval_status', 'approved')->count(),
                'rejected' => Job::query()->where('approval_status', 'rejected')->count(),
                'active' => Job::query()->where('is_active', true)->where('approval_status', 'approved')->count(),
            ],
            'applications' => [
                'total' => Application::query()->count(),
                'pending' => Application::query()->where('status', 'pending')->count(),
                'accepted' => Application::query()->where('status', 'accepted')->count(),
                'rejected' => Application::query()->where('status', 'rejected')->count(),
            ],
        ];

        $recentUsers = User::query()->orderByDesc('created_at')->limit(5)->get();
        $pendingJobs = Job::query()->where('approval_status', 'pending')->orderByDesc('created_at')->limit(10)->get();
        $recentLogs = AdminLog::query()->orderByDesc('created_at')->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'pendingJobs', 'recentLogs'));
    }

    public function approveJob(string $id): RedirectResponse
    {
        $job = Job::findOrFail($id);
        $job->update(['approval_status' => 'approved']);

        AdminLog::create([
            'user_id' => auth()->id(),
            'action' => 'approve_job',
            'description' => "Offre approuvée : {$job->title}",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('status', 'Offre approuvée avec succès !');
    }

    public function rejectJob(string $id): RedirectResponse
    {
        $job = Job::findOrFail($id);
        $job->update(['approval_status' => 'rejected']);

        AdminLog::create([
            'user_id' => auth()->id(),
            'action' => 'reject_job',
            'description' => "Offre rejetée : {$job->title}",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('status', 'Offre rejetée.');
    }

    public function toggleUserStatus(string $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        AdminLog::create([
            'user_id' => auth()->id(),
            'action' => 'toggle_user_status',
            'description' => "Statut utilisateur modifié : {$user->email} -> {$newStatus}",
            'ip_address' => request()->ip(),
        ]);

        $message = $newStatus === 'active' ? 'activé' : 'désactivé';

        return back()->with('status', "Utilisateur {$message} avec succès !");
    }
}
