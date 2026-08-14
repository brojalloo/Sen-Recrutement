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

            // Les colonnes utiles — qui a agi, et sur quoi — sont ajoutées à
            // la fin : un identifiant numérique n'apprend rien à qui relit un
            // journal d'audit six mois plus tard, mais déplacer les colonnes
            // existantes casserait tout traitement en aval.
            // `escape: ''` est passé explicitement : PHP 8.4 déprécie l'appel
            // sans ce paramètre, et la chaîne vide donne le comportement
            // RFC 4180, celui vers lequel PHP se dirige. L'échappement par
            // antislash, non standard, déformait les descriptions qui en
            // contenaient.
            fputcsv($handle, ['ID', 'Admin ID', 'Action', 'Created At', 'Admin', 'Description', 'IP'], ',', '"', '');

            AdminLog::with('user')->orderBy('id')->chunk(500, function ($chunk) use ($handle) {
                foreach ($chunk as $log) {
                    fputcsv($handle, [
                        $log->id,
                        $log->user_id ?? '',
                        $log->action ?? '',
                        $log->created_at ?? '',
                        $log->user->email ?? 'compte supprimé',
                        $log->description ?? '',
                        $log->ip_address ?? '',
                    ], ',', '"', '');
                }
            });

            fclose($handle);
        });
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="admin_logs.csv"');

        return $response;
    }
}
