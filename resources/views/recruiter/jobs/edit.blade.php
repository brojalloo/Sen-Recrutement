@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Modifier l'offre</h1>
  <form method="POST" action="{{ route('recruiter.jobs.update',$job->id) }}">
    @csrf @method('PUT')
    <div class="mb-3"><label class="form-label">Titre</label><input name="title" class="form-control" value="{{ $job->title }}" required></div>
    <div class="mb-3"><label class="form-label">Entreprise</label><input name="company" class="form-control" value="{{ $job->company }}" required></div>
    <div class="mb-3"><label class="form-label">Localisation</label><input name="location" class="form-control" value="{{ $job->location }}"></div>
    <div class="mb-3"><label class="form-label">Type</label><input name="type" class="form-control" value="{{ $job->type }}"></div>
    <div class="mb-3"><label class="form-label">Salaire min</label><input name="salary_min" type="number" step="0.01" class="form-control" value="{{ $job->salary_min }}"></div>
    <div class="mb-3"><label class="form-label">Salaire max</label><input name="salary_max" type="number" step="0.01" class="form-control" value="{{ $job->salary_max }}"></div>
    <div class="mb-3"><label class="form-label">Statut</label><input name="status" class="form-control" value="{{ $job->status }}"></div>
    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" rows="6" class="form-control" required>{{ $job->description }}</textarea></div>
    <button class="btn btn-primary" type="submit">Enregistrer</button>
  </form>

  <hr>
  <h2 class="h5">Logo entreprise</h2>
  <form method="POST" action="{{ route('recruiter.jobs.logo',$job->id) }}" enctype="multipart/form-data">@csrf
    <input type="file" name="logo" class="form-control mb-2">
    <button class="btn btn-outline-secondary" type="submit">Mettre à jour le logo</button>
  </form>
</div>
@endsection
