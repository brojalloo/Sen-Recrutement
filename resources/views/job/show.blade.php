@extends('layouts.app')

@section('content')
<div class="container">
  <h1>{{ $job->title }}</h1>
  <p class="text-muted">{{ $job->company }} — {{ $job->location }}</p>
  @if($job->company_logo)
    <img src="{{ $job->company_logo }}" alt="Logo" class="mb-3" style="max-height:80px">
  @endif
  <div class="mb-4">
    {!! nl2br(e($job->description)) !!}
  </div>
  <div class="mb-3">
    <strong>Type:</strong> {{ $job->type }}
  </div>
  @if($job->salary_min || $job->salary_max)
    <div class="mb-3">
      <strong>Salaire:</strong>
      {{ $job->salary_min ? number_format($job->salary_min,0,'',' ') : '' }}
      -
      {{ $job->salary_max ? number_format($job->salary_max,0,'',' ') : '' }} FCFA
    </div>
  @endif
  
  @auth
    @if(auth()->user()->role === 'candidate')
      <a class="btn btn-primary" href="{{ route('applications.apply.form', $job->id) }}">
        <i class="bi bi-send me-2"></i>Postuler à cette offre
      </a>
    @else
      <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>Connectez-vous en tant que candidat pour postuler à cette offre.
      </div>
    @endif
  @else
    <a class="btn btn-primary" href="{{ route('login') }}">
      <i class="bi bi-box-arrow-in-right me-2"></i>Connectez-vous pour postuler
    </a>
  @endauth
</div>
@endsection
