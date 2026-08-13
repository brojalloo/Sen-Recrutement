<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class CandidateController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $recentApplications = Application::query()->where('user_id', $userId)->orderByDesc('created_at')->limit(5)->get();
        $recommendedJobs = Job::query()->orderByDesc('created_at')->limit(4)->get();

        return view('candidate.dashboard', compact('recentApplications', 'recommendedJobs'));
    }
}
