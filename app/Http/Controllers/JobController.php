<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = 12;
        $query = Job::visible();

        // Filters
        if ($keyword = $request->input('keyword')) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%$keyword%")
                    ->orWhere('description', 'like', "%$keyword%")
                    ->orWhere('company', 'like', "%$keyword%");
            });
        }
        if ($location = $request->input('location')) {
            $query->where('location', 'like', "%$location%");
        }
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($experience = $request->input('experience')) {
            $query->where('experience', $experience);
        }
        if ($salaryMin = $request->input('salary_min')) {
            $query->where('salary_max', '>=', $salaryMin);
        }

        $jobs = $query->orderByDesc('created_at')->paginate($perPage)->withQueryString();

        return view('job.index', compact('jobs'));
    }

    public function show(string $id): View
    {
        $job = Job::visible()->findOrFail($id);

        return view('job.show', compact('job'));
    }
}
