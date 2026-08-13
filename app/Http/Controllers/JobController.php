<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 12;
        $query = Job::query();

        // Uniquement les offres approuvées et actives
        $query->where('approval_status', 'approved')
            ->where(function ($q) {
                $q->where('status', 'active')->orWhere('is_active', true);
            });

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

    public function show($id)
    {
        // Vérifier que l'offre est approuvée pour le public
        $job = Job::where('approval_status', 'approved')->findOrFail($id);

        return view('job.show', compact('job'));
    }
}
