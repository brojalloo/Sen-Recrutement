<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if (Auth::attempt($credentials, false)) {
            $request->session()->regenerate();
            $user = Auth::user();
            Log::info('Login', ['user' => $user->email]);

            // Redirection par rôle
            return match ($user->role ?? null) {
                'admin' => redirect()->route('admin.dashboard'),
                'candidate' => redirect()->route('candidate.dashboard'),
                'recruiter' => redirect()->route('recruiter.dashboard'),
                default => redirect()->route('home'),
            };
        }

        return back()->withErrors(['email' => 'Email ou mot de passe incorrect'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6'],
            // Les comptes admin ne se créent pas en libre-service : voir le seeder.
            'role' => ['required', 'in:candidate,recruiter'],
        ]);

        $user = new User;
        $user->first_name = $data['first_name'] ?? null;
        $user->last_name = $data['last_name'] ?? null;
        $user->name = trim(($data['first_name'] ?? '').' '.($data['last_name'] ?? ''));
        $user->email = $data['email'];
        $user->password = $data['password']; // cast 'hashed' applied
        $user->role = $data['role'];
        $user->status = 'active';
        $user->save();

        Auth::login($user);

        return redirect()->route('home');
    }
}
