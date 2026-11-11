<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->orderBy('id', 'desc')->paginate(20);

        // Les compteurs étaient calculés sur la collection paginée, donc sur
        // les 20 utilisateurs de la page courante : faux dès le 21ᵉ inscrit.
        $counts = User::query()
            ->selectRaw('role, count(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $roleCounts = [
            'candidate' => (int) $counts->get('candidate', 0),
            'recruiter' => (int) $counts->get('recruiter', 0),
            'admin' => (int) $counts->get('admin', 0),
        ];

        return view('admin.users', compact('users', 'roleCounts'));
    }
}
