<?php

namespace App\Http\Controllers\Recruiter;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::query()->where('recruiter_id', Auth::id())->orderByDesc('created_at')->paginate(20);

        return view('recruiter.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('recruiter.jobs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'company' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'salary_min' => ['nullable', 'numeric'],
            'salary_max' => ['nullable', 'numeric'],
        ]);
        $job = new Job($data);
        $job->recruiter_id = Auth::id();
        $job->status = 'active';
        $job->approval_status = 'pending'; // En attente d'approbation par l'admin
        $job->is_active = true;
        $job->posted_at = now();
        $job->save();

        return redirect()->route('recruiter.jobs.index')->with('status', 'Offre créée et envoyée pour approbation');
    }

    public function edit($id)
    {
        $job = Job::where('recruiter_id', Auth::id())->findOrFail($id);

        return view('recruiter.jobs.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        $job = Job::where('recruiter_id', Auth::id())->findOrFail($id);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'company' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'salary_min' => ['nullable', 'numeric'],
            'salary_max' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string'],
        ]);
        $job->fill($data);
        $job->save();

        return redirect()->route('recruiter.jobs.index')->with('status', 'Offre mise à jour');
    }

    public function destroy($id)
    {
        $job = Job::where('recruiter_id', Auth::id())->findOrFail($id);
        $job->delete();

        return redirect()->route('recruiter.jobs.index')->with('status', 'Offre supprimée');
    }

    public function uploadLogo(Request $request, $id)
    {
        $job = Job::where('recruiter_id', Auth::id())->findOrFail($id);
        $data = $request->validate([
            'logo' => ['required', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);
        $path = $request->file('logo')->store('logos', 'public');
        $job->company_logo = Storage::disk('public')->url($path);
        $job->save();

        return back()->with('status', 'Logo mis à jour');
    }
}
