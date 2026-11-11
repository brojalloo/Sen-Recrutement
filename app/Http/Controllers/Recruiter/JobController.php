<?php

namespace App\Http\Controllers\Recruiter;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobRequest;
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

    public function store(JobRequest $request)
    {
        $job = new Job($request->validated());
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

    public function update(JobRequest $request, $id)
    {
        $job = Job::where('recruiter_id', Auth::id())->findOrFail($id);
        $job->fill($request->validated());
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
