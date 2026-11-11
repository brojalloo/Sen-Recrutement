@extends('layouts.app')

@push('styles')
    @vite('resources/css/pages/job-index.css')
@endpush

@section('content')
<div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); min-height: calc(100vh - 200px);">
  <div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
      <h1 class="display-5 fw-bold mb-3">
        <i class="bi bi-briefcase-fill text-gradient"></i> 
        Découvrez nos offres d'emploi
      </h1>
      <p class="text-muted mb-0">Trouvez l'opportunité qui correspond à vos compétences</p>
    </div>

    <!-- Search Filters -->
    <div class="card border-0 shadow-premium rounded-premium mb-5">
      <div class="card-body p-4">
        <form class="row g-3" method="GET">
          <div class="col-lg-4 col-md-6">
            <label class="form-label fw-semibold">
              <i class="bi bi-search me-2 text-primary"></i>Mot-clé
            </label>
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control form-control-lg" placeholder="Ex: Développeur, Manager...">
          </div>
          <div class="col-lg-3 col-md-6">
            <label class="form-label fw-semibold">
              <i class="bi bi-geo-alt me-2 text-primary"></i>Localisation
            </label>
            <input type="text" name="location" value="{{ request('location') }}" class="form-control form-control-lg" placeholder="Ex: Dakar, Thiès...">
          </div>
          <div class="col-lg-3 col-md-6">
            <label class="form-label fw-semibold">
              <i class="bi bi-briefcase me-2 text-primary"></i>Type de contrat
            </label>
            <select name="type" class="form-select form-select-lg">
              <option value="">Tous les types</option>
              @foreach(\App\Models\Job::TYPES as $key=>$label)
                <option value="{{ $key }}" @selected(request('type')===$key)>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-lg-2 col-md-6 d-flex align-items-end">
            <button class="btn btn-primary btn-lg w-100" type="submit">
              <i class="bi bi-funnel me-2"></i>Filtrer
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Results Count -->
    <div class="mb-4">
      <p class="text-muted">
        <i class="bi bi-info-circle me-2"></i>
        <strong>{{ $jobs->total() }}</strong> offre(s) trouvée(s)
      </p>
    </div>

    <!-- Jobs Grid -->
    <div class="row g-4 mb-5">
      @forelse($jobs as $job)
        <div class="col-lg-4 col-md-6">
          <div class="card border-0 shadow-sm rounded-premium h-100 job-card">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="avatar-circle bg-primary bg-opacity-10 text-primary">
                  <i class="bi bi-building"></i>
                </div>
                @if($job->type)
                  <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2">
                    {{ $job->type }}
                  </span>
                @endif
              </div>
              
              <h5 class="card-title fw-bold mb-2">{{ $job->title }}</h5>
              <p class="text-muted mb-3">
                <i class="bi bi-building me-1"></i>{{ $job->company }}
              </p>
              
              <p class="card-text text-muted small mb-3">
                {{ \Illuminate\Support\Str::limit($job->description, 100) }}
              </p>
              
              <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="badge bg-light text-dark border">
                  <i class="bi bi-geo-alt me-1"></i>{{ $job->location }}
                </span>
                @if($job->salary)
                  <span class="badge bg-light text-dark border">
                    <i class="bi bi-currency-dollar me-1"></i>{{ $job->salary }}
                  </span>
                @endif
              </div>
              
              <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-primary w-100">
                <i class="bi bi-eye me-2"></i>Voir le détail
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="card border-0 shadow-premium rounded-premium">
            <div class="card-body text-center py-5">
              <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
              <h5 class="fw-bold mb-2">Aucune offre trouvée</h5>
              <p class="text-muted mb-4">Essayez de modifier vos critères de recherche</p>
              <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser les filtres
              </a>
            </div>
          </div>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($jobs->hasPages())
      <div class="d-flex justify-content-center">
        {{ $jobs->links() }}
      </div>
    @endif
  </div>
</div>

@endsection
