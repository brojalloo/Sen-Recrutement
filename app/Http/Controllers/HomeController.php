<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class HomeController extends Controller
{
    public function index()
    {
        $recentJobs = Job::query()
            ->where(function($q){ $q->where('status','active')->orWhere('is_active',true); })
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();
        $popularJobs = Job::query()
            ->where(function($q){ $q->where('status','active')->orWhere('is_active',true); })
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        return view('home.index', compact('recentJobs','popularJobs'));
    }
}
