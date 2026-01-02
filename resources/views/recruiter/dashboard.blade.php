@extends('layouts.app')

@section('content')
<div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); min-height: calc(100vh - 200px);">
  <div class="container py-5">
    <!-- Header -->
    <div class="mb-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h1 class="display-6 fw-bold mb-2">
            <i class="bi bi-speedometer2 text-gradient"></i> 
            Dashboard Recruteur
          </h1>
          <p class="text-muted mb-0">Gérez vos offres et candidatures</p>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('recruiter.profile.edit') }}" class="btn btn-outline-primary">
            <i class="bi bi-person-gear me-2"></i>Mon Profil
          </a>
          <a href="{{ route('recruiter.jobs.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Nouvelle offre
          </a>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
      <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-premium rounded-premium h-100 stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
          <div class="card-body text-white">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <p class="mb-1 opacity-75">Total offres</p>
                <h2 class="display-5 fw-bold mb-0">{{ $stats['total_jobs'] ?? 0 }}</h2>
              </div>
              <div class="stat-icon">
                <i class="bi bi-briefcase-fill fs-1 opacity-50"></i>
              </div>
            </div>
            <div class="d-flex align-items-center">
              <i class="bi bi-arrow-up me-2"></i>
              <small>Offres publiées</small>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-premium rounded-premium h-100 stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
          <div class="card-body text-white">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <p class="mb-1 opacity-75">Offres actives</p>
                <h2 class="display-5 fw-bold mb-0">{{ $stats['active_jobs'] ?? 0 }}</h2>
              </div>
              <div class="stat-icon">
                <i class="bi bi-check-circle-fill fs-1 opacity-50"></i>
              </div>
            </div>
            <div class="d-flex align-items-center">
              <i class="bi bi-arrow-up me-2"></i>
              <small>En ligne actuellement</small>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-premium rounded-premium h-100 stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
          <div class="card-body text-white">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <p class="mb-1 opacity-75">Candidatures</p>
                <h2 class="display-5 fw-bold mb-0">{{ $stats['total_applications'] ?? 0 }}</h2>
              </div>
              <div class="stat-icon">
                <i class="bi bi-file-earmark-text-fill fs-1 opacity-50"></i>
              </div>
            </div>
            <div class="d-flex align-items-center">
              <i class="bi bi-arrow-up me-2"></i>
              <small>Candidatures reçues</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Grid -->
    <div class="row g-4">
      <!-- Recent Jobs -->
      <div class="col-lg-6">
        <div class="card border-0 shadow-premium rounded-premium">
          <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0">
              <i class="bi bi-briefcase text-success me-2"></i>
              Mes dernières offres
            </h5>
          </div>
          <div class="card-body p-0">
            <div class="list-group list-group-flush">
              @forelse($jobs as $j)
                <a href="{{ route('recruiter.jobs.edit', $j->id) }}" class="list-group-item list-group-item-action py-3">
                  <div class="d-flex align-items-start">
                    <div class="avatar-circle bg-success bg-opacity-10 text-success me-3">
                      <i class="bi bi-briefcase"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1 fw-semibold">{{ $j->title }}</h6>
                      <small class="text-muted">
                        <i class="bi bi-building me-1"></i>{{ $j->company }}
                        <span class="mx-2">•</span>
                        <i class="bi bi-geo-alt me-1"></i>{{ $j->location }}
                      </small>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                  </div>
                </a>
              @empty
                <div class="list-group-item text-center text-muted py-5">
                  <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                  <p class="mb-0">Aucune offre publiée</p>
                  <a href="{{ route('recruiter.jobs.create') }}" class="btn btn-sm btn-primary mt-3">
                    <i class="bi bi-plus-circle me-1"></i>Publier une offre
                  </a>
                </div>
              @endforelse
            </div>
          </div>
          <div class="card-footer bg-white border-0 text-center py-3">
            <a href="{{ route('recruiter.jobs.index') }}" class="text-decoration-none fw-semibold">
              Gérer toutes mes offres <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Recent Applications -->
      <div class="col-lg-6">
        <div class="card border-0 shadow-premium rounded-premium">
          <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0">
              <i class="bi bi-person-lines-fill text-primary me-2"></i>
              Dernières candidatures
            </h5>
          </div>
          <div class="card-body p-0">
            <div class="list-group list-group-flush">
              @forelse($applications as $a)
                <div class="list-group-item py-3">
                  <div class="d-flex align-items-start">
                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                      <i class="bi bi-person"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1 fw-semibold">{{ optional($a->user)->full_name ?? optional($a->user)->name ?? 'Utilisateur' }}</h6>
                      <small class="text-muted">
                        <i class="bi bi-briefcase me-1"></i>{{ optional($a->job)->title ?? 'Offre supprimée' }}
                      </small>
                      <div class="mt-2 d-flex gap-2 align-items-center flex-wrap">
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
                        
                        @if(optional($a->user)->cv_path)
                          <a href="{{ route('download.cv', $a->user->id) }}" class="btn btn-sm btn-outline-primary" title="Télécharger CV">
                            <i class="bi bi-file-earmark-pdf me-1"></i>CV
                          </a>
                        @else
                          <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                            <i class="bi bi-file-earmark-x me-1"></i>Pas de CV
                          </span>
                        @endif

                        @if($a->status === 'pending')
                          <form method="POST" action="{{ route('recruiter.applications.accept', $a->id) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-success" title="Accepter la candidature">
                              <i class="bi bi-check-lg me-1"></i>Accepter
                            </button>
                          </form>
                          <form method="POST" action="{{ route('recruiter.applications.reject', $a->id) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-danger" title="Refuser la candidature" onclick="return confirm('Êtes-vous sûr de vouloir refuser cette candidature ?')">
                              <i class="bi bi-x-lg me-1"></i>Refuser
                            </button>
                          </form>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <div class="list-group-item text-center text-muted py-5">
                  <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                  <p class="mb-0">Aucune candidature pour le moment</p>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.stat-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  overflow: hidden;
  position: relative;
}
.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.25) !important;
}
.stat-card::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -20%;
  width: 200px;
  height: 200px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  transition: transform 0.6s ease;
}
.stat-card:hover::before {
  transform: scale(1.5);
}
.avatar-circle {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  flex-shrink: 0;
}
.list-group-item {
  transition: all 0.3s ease;
}
.list-group-item:hover {
  background-color: rgba(79, 70, 229, 0.03);
}
</style>
@endsection
