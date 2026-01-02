<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Application;
use App\Models\Job;

class FileController extends Controller
{
    public function downloadCv($applicationId)
    {
        $app = Application::findOrFail($applicationId);
        if (!$app->cv_path) {
            abort(404);
        }
        $userId = Auth::id();
        $isApplicant = $app->user_id === $userId;
        $job = Job::find($app->job_id);
        $isRecruiter = $job && $job->recruiter_id === $userId;
        if (!$isApplicant && !$isRecruiter) {
            abort(403);
        }
        return Storage::disk('public')->download($app->cv_path);
    }
}
