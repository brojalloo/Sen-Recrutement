<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function downloadCv($applicationId)
    {
        $app = Application::findOrFail($applicationId);
        if (! $app->cv_path) {
            abort(404);
        }
        $userId = Auth::id();
        $isApplicant = $app->user_id === $userId;
        $job = Job::find($app->job_id);
        $isRecruiter = $job && $job->recruiter_id === $userId;
        if (! $isApplicant && ! $isRecruiter) {
            abort(403);
        }

        if (! Storage::disk('local')->exists($app->cv_path)) {
            abort(404);
        }

        return Storage::disk('local')->download($app->cv_path);
    }
}
