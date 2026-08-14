@extends('layouts.app')

@push('styles')
    @vite('resources/css/pages/candidate-dashboard.css')
@endpush

@section('content')
<div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); min-height: calc(100vh - 200px);">
  <div class="container py-5">
    <!-- Header -->
    <div class="mb-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h1 class="display-6 fw-bold mb-2">
            <i class="bi bi-speedometer2 text-gradient"></i> 
            Dashboard Candidat
          </h1>
          <p class="text-muted mb-0">Bienvenue sur votre espace personnel</p>
        </div>
        <a href="{{ route('candidate.profile.edit') }}" class="btn btn-primary">
          <i class="bi bi-person-gear me-2"></i>Mon Profil
        </a>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
      <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-premium rounded-premium h-100 stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
          <div class="card-body text-white">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <p class="mb-1 opacity-75">Mes candidatures</p>
                <h2 class="display-5 fw-bold mb-0">{{ $recentApplications->count() }}</h2>
              </div>
              <div class="stat-icon">
                <i class="bi bi-file-earmark-text-fill fs-1 opacity-50"></i>
              </div>
            </div>
            <div class="d-flex align-items-center">
              <i class="bi bi-arrow-up me-2"></i>
              <small>Candidatures soumises</small>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-premium rounded-premium h-100 stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
          <div class="card-body text-white">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <p class="mb-1 opacity-75">Offres recommandées</p>
                <h2 class="display-5 fw-bold mb-0">{{ $recommendedJobs->count() }}</h2>
              </div>
              <div class="stat-icon">
                <i class="bi bi-star-fill fs-1 opacity-50"></i>
              </div>
            </div>
            <div class="d-flex align-items-center">
              <i class="bi bi-arrow-up me-2"></i>
              <small>Offres qui vous correspondent</small>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-premium rounded-premium h-100 stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
          <div class="card-body text-white">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <p class="mb-1 opacity-75">Profil complété</p>
                <h2 class="display-5 fw-bold mb-0">75%</h2>
              </div>
              <div class="stat-icon">
                <i class="bi bi-person-check-fill fs-1 opacity-50"></i>
              </div>
            </div>
            <div class="d-flex align-items-center">
              <i class="bi bi-graph-up me-2"></i>
              <small>Complétez pour plus de visibilité</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Grid -->
    <div class="row g-4">
      <!-- Recent Applications -->
      <div class="col-lg-6">
        <div class="card border-0 shadow-premium rounded-premium">
          <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0">
              <i class="bi bi-clock-history text-primary me-2"></i>
              Mes dernières candidatures
            </h5>
          </div>
          <div class="card-body p-0">
            <div class="list-group list-group-flush">
              @forelse($recentApplications as $a)
                <div class="list-group-item py-3">
                  <div class="d-flex align-items-start">
                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                      <i class="bi bi-briefcase"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1 fw-semibold">{{ optional($a->job)->title ?? 'Offre supprimée' }}</h6>
                      <small class="text-muted">{{ optional($a->job)->company ?? 'N/A' }}</small>
                      <div class="mt-2">
                        @if($a->status === 'pending')
                          <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2">
                            <i class="bi bi-clock me-1"></i>En attente
                          </span>
                        @elseif($a->status === 'accepted')
                          <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>Acceptée
                          </span>
                        @else
                          <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2">
                            <i class="bi bi-x-circle me-1"></i>Refusée
                          </span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <div class="list-group-item text-center text-muted py-5">
                  <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                  <p class="mb-0">Aucune candidature pour le moment</p>
                  <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-primary mt-3">
                    <i class="bi bi-search me-1"></i>Explorer les offres
                  </a>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <!-- Recommended Jobs -->
      <div class="col-lg-6">
        <div class="card border-0 shadow-premium rounded-premium">
          <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0">
              <i class="bi bi-star text-warning me-2"></i>
              Offres recommandées
            </h5>
          </div>
          <div class="card-body p-0">
            <div class="list-group list-group-flush">
              @forelse($recommendedJobs as $j)
                <a href="{{ route('jobs.show', $j->id) }}" class="list-group-item list-group-item-action py-3">
                  <div class="d-flex align-items-start">
                    <div class="avatar-circle bg-success bg-opacity-10 text-success me-3">
                      <i class="bi bi-building"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1 fw-semibold">{{ $j->title }}</h6>
                      <small class="text-muted">
                        <i class="bi bi-building me-1"></i>{{ $j->company }}
                        <span class="mx-2">•</span>
                        <i class="bi bi-geo-alt me-1"></i>{{ $j->location }}
                      </small>
                      @if($j->salary)
                        <div class="mt-2">
                          <span class="text-success fw-semibold">
                            <i class="bi bi-currency-dollar"></i>{{ $j->salary }}
                          </span>
                        </div>
                      @endif
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                  </div>
                </a>
              @empty
                <div class="list-group-item text-center text-muted py-5">
                  <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                  <p class="mb-0">Aucune offre recommandée</p>
                </div>
              @endforelse
            </div>
          </div>
          <div class="card-footer bg-white border-0 text-center py-3">
            <a href="{{ route('jobs.index') }}" class="text-decoration-none fw-semibold">
              Voir toutes les offres <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
