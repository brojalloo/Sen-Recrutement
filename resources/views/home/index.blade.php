@extends('layouts.app')

@section('content')
<section class="hero">
  <div class="container">
    <div class="row align-items-center py-5">
      <div class="col-lg-7 mb-4 mb-lg-0">
        <h1 class="display-4 fw-bold mb-4">
          Trouvez l'emploi<br>
          <span class="text-gradient">qui vous correspond</span>
        </h1>
        <p class="lead mb-5">
          Des milliers d'offres de qualité, des entreprises réputées au Sénégal. 
          Une candidature simplifiée pour votre réussite professionnelle.
        </p>
        <form class="row g-3 search-form" method="GET" action="{{ route('jobs.index') }}">
          {{-- Étiquettes masquées visuellement : la barre de recherche ne
               montre que des placeholders, qu'un lecteur d'écran n'annonce
               pas de façon fiable. La mise en page reste inchangée. --}}
          <div class="col-md-5">
            <label for="recherche-keyword" class="visually-hidden">Poste, compétence ou entreprise</label>
            <input id="recherche-keyword" name="keyword" class="form-control" placeholder="Poste, compétence, entreprise...">
          </div>
          <div class="col-md-4">
            <label for="recherche-location" class="visually-hidden">Localisation</label>
            <input id="recherche-location" name="location" class="form-control" placeholder="Dakar, Thiès, Saint-Louis...">
          </div>
          <div class="col-md-3">
            <button class="btn btn-primary w-100" type="submit">
              <i class="bi bi-search me-2"></i>Rechercher
            </button>
          </div>
        </form>
      </div>
      <div class="col-lg-5 d-none d-lg-block text-center">
        <img src="{{ asset('assets/images/work-from-home.svg') }}" class="img-fluid" alt="Illustration professionnelle" style="max-height: 400px;">
      </div>
    </div>
  </div>
</section>

<div class="container py-5">
  <div class="text-center mb-5">
    <h2 class="display-6 fw-bold mb-3">Offres d'emploi récentes</h2>
    <p class="lead text-muted">Découvrez les dernières opportunités publiées</p>
  </div>
  
  <div class="row g-4 mb-5">
    @foreach($recentJobs as $job)
      <div class="col-lg-4 col-md-6">
        <div class="card h-100 animate-slide-in">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <h5 class="card-title mb-0">{{ $job->title }}</h5>
              @if($job->type)
                <span class="badge badge-soft ms-2">{{ $job->type }}</span>
              @endif
            </div>
            <div class="mb-3">
              <p class="text-muted mb-2">
                <i class="bi bi-building me-2"></i>{{ $job->company }}
              </p>
              <p class="text-muted mb-2">
                <i class="bi bi-geo-alt me-2"></i>{{ $job->location }}
              </p>
              @if($job->salary)
                <p class="text-muted mb-0">
                  <i class="bi bi-currency-dollar me-2"></i>{{ $job->salary }}
                </p>
              @endif
            </div>
            <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-primary w-100">
              Voir l'offre <i class="bi bi-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-center mb-5">
    <h2 class="display-6 fw-bold mb-3">Offres populaires</h2>
    <p class="lead text-muted">Les opportunités les plus consultées cette semaine</p>
  </div>
  
  <div class="row g-4">
    @foreach($popularJobs as $job)
      <div class="col-lg-4 col-md-6">
        <div class="card h-100 animate-slide-in">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <h5 class="card-title mb-0">{{ $job->title }}</h5>
              @if($job->type)
                <span class="badge badge-soft ms-2">{{ $job->type }}</span>
              @endif
            </div>
            <div class="mb-3">
              <p class="text-muted mb-2">
                <i class="bi bi-building me-2"></i>{{ $job->company }}
              </p>
              <p class="text-muted mb-2">
                <i class="bi bi-geo-alt me-2"></i>{{ $job->location }}
              </p>
              @if($job->salary)
                <p class="text-muted mb-0">
                  <i class="bi bi-currency-dollar me-2"></i>{{ $job->salary }}
                </p>
              @endif
            </div>
            <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-primary w-100">
              Voir l'offre <i class="bi bi-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
