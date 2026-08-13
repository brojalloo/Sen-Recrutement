<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->orderBy('id', 'desc')->paginate(20);

        return view('admin.users', compact('users'));
    }
}
