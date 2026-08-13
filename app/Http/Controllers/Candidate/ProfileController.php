<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('candidate.profile', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'bio' => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
        ]);

        // Handle CV upload
        if ($request->hasFile('cv')) {
            // Delete old CV if exists
            if ($user->cv_path && Storage::disk('public')->exists($user->cv_path)) {
                Storage::disk('public')->delete($user->cv_path);
            }

            // Store new CV
            $cvPath = $request->file('cv')->store('cvs', 'public');
            $data['cv_path'] = $cvPath;
        }

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['cv']); // Remove cv from data array
        $user->update($data);

        return back()->with('status', 'Profil mis à jour avec succès.');
    }

    public function deleteCV()
    {
        $user = Auth::user();

        if ($user->cv_path && Storage::disk('public')->exists($user->cv_path)) {
            Storage::disk('public')->delete($user->cv_path);
            $user->update(['cv_path' => null]);
        }

        return back()->with('status', 'CV supprimé avec succès.');
    }
}
