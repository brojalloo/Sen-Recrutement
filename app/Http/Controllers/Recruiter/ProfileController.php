<?php

namespace App\Http\Controllers\Recruiter;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        // Les compteurs étaient calculés dans la vue, en chargeant toutes les
        // offres du recruteur pour les sommer en PHP. La base sait le faire.
        return view('recruiter.profile', [
            'user' => $user,
            'activeJobsCount' => $user->jobs()->where('is_active', true)->count(),
            'receivedApplicationsCount' => Application::query()
                ->whereIn('job_id', Job::query()->select('id')->where('recruiter_id', $user->id))
                ->count(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'company_description' => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('status', 'Profil mis à jour avec succès.');
    }
}
