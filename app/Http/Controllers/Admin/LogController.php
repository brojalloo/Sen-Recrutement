<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogController extends Controller
{
    public function index()
    {
        $logs = AdminLog::query()->orderBy('id', 'desc')->paginate(50);

        return view('admin.logs', compact('logs'));
    }

    public function export(): StreamedResponse
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Admin ID', 'Action', 'Created At']);
            AdminLog::orderBy('id')->chunk(500, function ($chunk) use ($handle) {
                foreach ($chunk as $log) {
                    fputcsv($handle, [
                        $log->id,
                        $log->admin_id ?? '',
                        $log->action ?? '',
                        $log->created_at ?? '',
                    ]);
                }
            });
            fclose($handle);
        });
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="admin_logs.csv"');

        return $response;
    }
}
