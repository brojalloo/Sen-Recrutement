<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function downloadCv($applicationId)
    {
        $app = Application::with('job')->findOrFail($applicationId);

        if (! $app->cv_path) {
            abort(404);
        }

        $this->authorize('downloadCv', $app);

        if (! Storage::disk('local')->exists($app->cv_path)) {
            abort(404);
        }

        return Storage::disk('local')->download($app->cv_path);
    }
}
