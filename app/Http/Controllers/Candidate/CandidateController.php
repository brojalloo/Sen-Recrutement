<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CandidateController extends Controller
{
    public function dashboard(): View
    {
        $userId = Auth::id();
        $recentApplications = Application::query()->where('user_id', $userId)->orderByDesc('created_at')->limit(5)->get();
        $recommendedJobs = Job::visible()->orderByDesc('created_at')->limit(4)->get();

        return view('candidate.dashboard', compact('recentApplications', 'recommendedJobs'));
    }
}
