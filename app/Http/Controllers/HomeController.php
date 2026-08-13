<?php

namespace App\Http\Controllers;

use App\Models\Job;

class HomeController extends Controller
{
    public function index()
    {
        $recentJobs = Job::visible()->orderByDesc('created_at')->limit(6)->get();
        $popularJobs = Job::visible()->orderByDesc('views')->limit(6)->get();

        return view('home.index', compact('recentJobs', 'popularJobs'));
    }
}
