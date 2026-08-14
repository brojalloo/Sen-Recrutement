@extends('layouts.app')

@push('styles')
    @vite('resources/css/pages/admin-dashboard.css')
@endpush

@section('content')
<div class="admin-dashboard page-surface">
  <div class="container py-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
      <div>
        <h1 class="display-6 fw-bold mb-2">
          <i class="bi bi-speedometer2 text-gradient"></i> 
          Dashboard Administration
        </h1>
        <p class="text-muted mb-0">Vue d'ensemble et gestion de la plateforme</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
          <i class="bi bi-people me-2"></i>Utilisateurs
        </a>
        <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-primary">
          <i class="bi bi-briefcase me-2"></i>Offres
        </a>
        <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-primary">
          <i class="bi bi-file-text me-2"></i>Logs
        </a>
      </div>
    </div>

    @if(session('status'))
      <div class="alert alert-success border-0 rounded-3 mb-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
      </div>
    @endif

    <!-- Stats Cards Row 1 -->
    <div class="row g-4 mb-4">
      <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-premium rounded-premium h-100 stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
          <div class="card-body text-white">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <p class="mb-1 opacity-75 small">Total Utilisateurs</p>
                <h2 class="display-5 fw-bold mb-0">{{ $stats['users']['total'] ?? 0 }}</h2>
              </div>
              <div class="stat-icon">
                <i class="bi bi-people-fill fs-1 opacity-50"></i>
              </div>
            </div>
            <div class="d-flex gap-3">
              <small><i class="bi bi-person me-1"></i>{{ $stats['users']['candidates'] ?? 0 }} candidats</small>
              <small><i class="bi bi-building me-1"></i>{{ $stats['users']['recruiters'] ?? 0 }} recruteurs</small>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-premium rounded-premium h-100 stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
          <div class="card-body text-white">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <p class="mb-1 opacity-75 small">Offres d'emploi</p>
                <h2 class="display-5 fw-bold mb-0">{{ $stats['jobs']['total'] ?? 0 }}</h2>
              </div>
              <div class="stat-icon">
                <i class="bi bi-briefcase-fill fs-1 opacity-50"></i>
              </div>
            </div>
            <div class="d-flex gap-2">
              <span class="badge bg-white bg-opacity-25">{{ $stats['jobs']['pending'] ?? 0 }} en attente</span>
              <span class="badge bg-white bg-opacity-25">{{ $stats['jobs']['approved'] ?? 0 }} approuvées</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-premium rounded-premium h-100 stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
          <div class="card-body text-white">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <p class="mb-1 opacity-75 small">Candidatures</p>
                <h2 class="display-5 fw-bold mb-0">{{ $stats['applications']['total'] ?? 0 }}</h2>
              </div>
              <div class="stat-icon">
                <i class="bi bi-file-earmark-text-fill fs-1 opacity-50"></i>
              </div>
            </div>
            <div class="d-flex gap-2">
              <span class="badge bg-white bg-opacity-25">{{ $stats['applications']['pending'] ?? 0 }} en attente</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-premium rounded-premium h-100 stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
          <div class="card-body text-white">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <p class="mb-1 opacity-75 small">Taux de réussite</p>
                <h2 class="display-5 fw-bold mb-0">{{ $stats['applications']['total'] > 0 ? round(($stats['applications']['accepted'] / $stats['applications']['total']) * 100) : 0 }}%</h2>
              </div>
              <div class="stat-icon">
                <i class="bi bi-graph-up-arrow fs-1 opacity-50"></i>
              </div>
            </div>
            <div class="d-flex gap-2">
              <span class="badge bg-white bg-opacity-25">{{ $stats['applications']['accepted'] ?? 0 }} acceptées</span>
              <span class="badge bg-white bg-opacity-25">{{ $stats['applications']['rejected'] ?? 0 }} refusées</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards Row 2 - Status -->
    <div class="row g-4 mb-5">
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-premium">
          <div class="card-body text-center py-4">
            <div class="mb-2">
              <i class="bi bi-person-check text-success fs-1"></i>
            </div>
            <h4 class="fw-bold mb-1">{{ $stats['users']['active'] ?? 0 }}</h4>
            <p class="text-muted mb-0 small">Utilisateurs actifs</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-premium">
          <div class="card-body text-center py-4">
            <div class="mb-2">
              <i class="bi bi-person-x text-danger fs-1"></i>
            </div>
            <h4 class="fw-bold mb-1">{{ $stats['users']['inactive'] ?? 0 }}</h4>
            <p class="text-muted mb-0 small">Utilisateurs inactifs</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-premium">
          <div class="card-body text-center py-4">
            <div class="mb-2">
              <i class="bi bi-clipboard-check text-primary fs-1"></i>
            </div>
            <h4 class="fw-bold mb-1">{{ $stats['jobs']['active'] ?? 0 }}</h4>
            <p class="text-muted mb-0 small">Offres actives</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Grid -->
    <div class="row g-4">
      <!-- Pending Jobs Approval -->
      <div class="col-lg-8">
        <div class="card border-0 shadow-premium rounded-premium">
          <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">
              <i class="bi bi-clock-history text-warning me-2"></i>
              Offres en attente d'approbation
            </h5>
            <span class="badge bg-warning">{{ $pendingJobs->count() }}</span>
          </div>
          <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
            <div class="list-group list-group-flush">
              @forelse($pendingJobs as $job)
                <div class="list-group-item py-3">
                  <div class="row g-2">
                    <div class="col-12">
                      <h6 class="mb-1 fw-semibold text-truncate" style="max-width: 100%;">{{ $job->title }}</h6>
                      <div class="text-muted small mb-2">
                        <div class="d-flex flex-wrap gap-2">
                          <span><i class="bi bi-building me-1"></i>{{ Str::limit($job->company, 20) }}</span>
                          <span><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($job->location, 20) }}</span>
                          <span><i class="bi bi-clock me-1"></i>{{ $job->created_at->diffForHumans() }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="d-flex gap-2 flex-wrap">
                        <form method="POST" action="{{ route('admin.jobs.approve', $job->id) }}" class="d-inline">
                          @csrf
                          @method('PUT')
                          <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-check-lg me-1"></i>Approuver
                          </button>
                        </form>
                        <form method="POST" action="{{ route('admin.jobs.reject', $job->id) }}" class="d-inline" data-confirm="Êtes-vous sûr de vouloir rejeter cette offre ?">
                          @csrf
                          @method('PUT')
                          <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-x-lg me-1"></i>Rejeter
                          </button>
                        </form>
                        <a href="{{ route('admin.jobs.index') }}" class="btn btn-sm btn-outline-secondary">
                          <i class="bi bi-eye me-1"></i>Détails
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <div class="list-group-item text-center text-muted py-5">
                  <i class="bi bi-check-circle fs-1 text-success d-block mb-3"></i>
                  <p class="mb-0">Aucune offre en attente d'approbation</p>
                </div>
              @endforelse
            </div>
          </div>
          @if($pendingJobs->count() > 0)
            <div class="card-footer bg-white border-0 text-center py-3">
              <a href="{{ route('admin.jobs.index') }}" class="text-decoration-none fw-semibold">
                Voir toutes les offres <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
          @endif
        </div>
      </div>

      <!-- Recent Users & Quick Actions -->
      <div class="col-lg-4">
        <!-- Recent Users -->
        <div class="card border-0 shadow-premium rounded-premium mb-4">
          <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0">
              <i class="bi bi-person-plus text-primary me-2"></i>
              Derniers utilisateurs
            </h5>
          </div>
          <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
            <div class="list-group list-group-flush">
              @forelse($recentUsers as $u)
                <div class="list-group-item py-3">
                  <div class="d-flex align-items-start justify-content-between gap-2">
                    <div class="d-flex align-items-center flex-grow-1 min-width-0">
                      <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2 flex-shrink-0">
                        <i class="bi bi-person"></i>
                      </div>
                      <div class="min-width-0 flex-grow-1">
                        <small class="fw-semibold d-block text-truncate">{{ $u->full_name ?? $u->name }}</small>
                        <small class="text-muted text-truncate d-block">{{ $u->role }}</small>
                      </div>
                    </div>
                    <div class="flex-shrink-0">
                      @if($u->status === 'active')
                        <span class="badge bg-success bg-opacity-10 text-success">Actif</span>
                      @else
                        <span class="badge bg-danger bg-opacity-10 text-danger">Inactif</span>
                      @endif
                    </div>
                  </div>
                </div>
              @empty
                <div class="list-group-item text-center text-muted py-4">
                  <p class="mb-0 small">Aucun utilisateur</p>
                </div>
              @endforelse
            </div>
          </div>
          <div class="card-footer bg-white border-0 text-center py-3">
            <a href="{{ route('admin.users.index') }}" class="text-decoration-none fw-semibold small">
              Gérer les utilisateurs <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>
        </div>

        <!-- Recent Logs -->
        <div class="card border-0 shadow-premium rounded-premium">
          <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0">
              <i class="bi bi-activity text-info me-2"></i>
              Activité récente
            </h5>
          </div>
          <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
            <div class="list-group list-group-flush">
              @forelse($recentLogs as $log)
                <div class="list-group-item py-2">
                  <small class="d-block fw-semibold">{{ $log->description }}</small>
                  <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>{{ $log->created_at->diffForHumans() }}
                  </small>
                </div>
              @empty
                <div class="list-group-item text-center text-muted py-4">
                  <p class="mb-0 small">Aucune activité</p>
                </div>
              @endforelse
            </div>
          </div>
          <div class="card-footer bg-white border-0 text-center py-3">
            <a href="{{ route('admin.logs.index') }}" class="text-decoration-none fw-semibold small">
              Voir tous les logs <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
