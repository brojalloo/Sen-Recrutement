<?php

namespace App\Http\Controllers\Recruiter;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobRequest;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(): View
    {
        $jobs = Job::query()->where('recruiter_id', Auth::id())->orderByDesc('created_at')->paginate(20);

        return view('recruiter.jobs.index', compact('jobs'));
    }

    public function create(): View
    {
        return view('recruiter.jobs.create');
    }

    public function store(JobRequest $request): RedirectResponse
    {
        $job = new Job($request->validated());
        // La route est derrière le middleware `auth` : l'utilisateur est
        // forcément là. `user()->id` le dit au typage, là où `Auth::id()`
        // reste nullable.
        $job->recruiter_id = $request->user()->id;
        $job->status = 'active';
        $job->approval_status = 'pending'; // En attente d'approbation par l'admin
        $job->is_active = true;
        $job->posted_at = now();
        $job->save();

        return redirect()->route('recruiter.jobs.index')->with('status', 'Offre créée et envoyée pour approbation');
    }

    public function edit(string $id): View
    {
        $job = Job::where('recruiter_id', Auth::id())->findOrFail($id);

        return view('recruiter.jobs.edit', compact('job'));
    }

    public function update(JobRequest $request, string $id): RedirectResponse
    {
        $job = Job::where('recruiter_id', Auth::id())->findOrFail($id);
        $job->fill($request->validated());
        $job->save();

        return redirect()->route('recruiter.jobs.index')->with('status', 'Offre mise à jour');
    }

    public function destroy(string $id): RedirectResponse
    {
        $job = Job::where('recruiter_id', Auth::id())->findOrFail($id);
        $job->delete();

        return redirect()->route('recruiter.jobs.index')->with('status', 'Offre supprimée');
    }

    public function uploadLogo(Request $request, string $id): RedirectResponse
    {
        $job = Job::where('recruiter_id', Auth::id())->findOrFail($id);
        $data = $request->validate([
            'logo' => ['required', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);
        // Même garde-fou que pour l'avatar : `store()` renvoie false en cas
        // d'échec d'écriture, et `url(false)` enregistrait une URL cassée.
        $path = $request->file('logo')->store('logos', 'public');

        if ($path === false) {
            return back()->with('error', "Le logo n'a pas pu être enregistré. Réessayez.");
        }

        $job->company_logo = Storage::disk('public')->url($path);
        $job->save();

        return back()->with('status', 'Logo mis à jour');
    }
}
