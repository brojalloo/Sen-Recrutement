@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Créer une offre</h1>
  <form method="POST" action="{{ route('recruiter.jobs.store') }}">
    @csrf
    <div class="mb-3"><label class="form-label">Titre</label><input name="title" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Entreprise</label><input name="company" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Localisation</label><input name="location" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Type</label><input name="type" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Salaire min</label><input name="salary_min" type="number" step="0.01" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Salaire max</label><input name="salary_max" type="number" step="0.01" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" rows="6" class="form-control" required></textarea></div>
    <button class="btn btn-primary" type="submit">Créer</button>
  </form>
</div>
@endsection
