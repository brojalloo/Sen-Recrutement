<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\View\View;

class JobAdminController extends Controller
{
    public function index(): View
    {
        $jobs = Job::query()->orderBy('id', 'desc')->paginate(20);

        return view('admin.jobs', compact('jobs'));
    }
}
