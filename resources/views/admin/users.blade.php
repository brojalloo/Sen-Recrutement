@extends('layouts.app')

@push('styles')
    @vite('resources/css/pages/admin-users.css')
@endpush

@section('content')
<div class="page-surface">
  <div class="container py-5">
    <!-- Header -->
    <div class="mb-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h1 class="display-6 fw-bold mb-2">
            <i class="bi bi-people-fill text-gradient"></i> 
            Gestion des utilisateurs
          </h1>
          <p class="text-muted mb-0">{{ $users->total() }} utilisateur(s) au total</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-premium">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                <i class="bi bi-people"></i>
              </div>
              <div>
                <small class="text-muted d-block">Total</small>
                <h4 class="fw-bold mb-0">{{ $users->total() }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-premium">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avatar-circle bg-success bg-opacity-10 text-success me-3">
                <i class="bi bi-person-check"></i>
              </div>
              <div>
                <small class="text-muted d-block">Candidats</small>
                <h4 class="fw-bold mb-0">{{ $roleCounts['candidate'] }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-premium">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avatar-circle bg-warning bg-opacity-10 text-warning me-3">
                <i class="bi bi-building"></i>
              </div>
              <div>
                <small class="text-muted d-block">Recruteurs</small>
                <h4 class="fw-bold mb-0">{{ $roleCounts['recruiter'] }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-premium">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avatar-circle bg-danger bg-opacity-10 text-danger me-3">
                <i class="bi bi-shield-check"></i>
              </div>
              <div>
                <small class="text-muted d-block">Admins</small>
                <h4 class="fw-bold mb-0">{{ $roleCounts['admin'] }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Users Table -->
    <div class="card border-0 shadow-premium rounded-premium">
      <div class="card-header bg-white border-0 py-4">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0">Liste des utilisateurs</h5>
          <div class="d-flex gap-2">
            <input type="search" class="form-control" placeholder="Rechercher..." style="width: 250px;">
            <select class="form-select" style="width: 150px;">
              <option>Tous les rôles</option>
              <option>Candidat</option>
              <option>Recruteur</option>
              <option>Admin</option>
            </select>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
              <tr>
                <th class="px-4 py-3 fw-semibold">ID</th>
                <th class="py-3 fw-semibold">UTILISATEUR</th>
                <th class="py-3 fw-semibold">EMAIL</th>
                <th class="py-3 fw-semibold">RÔLE</th>
                <th class="py-3 fw-semibold">STATUT</th>
                <th class="py-3 fw-semibold">INSCRIT LE</th>
                <th class="py-3 fw-semibold text-end px-4">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
                <tr>
                  <td class="px-4 text-muted">#{{ $user->id }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                        <i class="bi bi-person"></i>
                      </div>
                      <div>
                        <h6 class="mb-0 fw-semibold">{{ $user->full_name }}</h6>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="text-muted">
                      <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                    </span>
                  </td>
                  <td>
                    @if($user->role === 'admin')
                      <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2">
                        <i class="bi bi-shield-check me-1"></i>Admin
                      </span>
                    @elseif($user->role === 'recruiter')
                      <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2">
                        <i class="bi bi-building me-1"></i>Recruteur
                      </span>
                    @else
                      <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">
                        <i class="bi bi-person-check me-1"></i>Candidat
                      </span>
                    @endif
                  </td>
                  <td>
                    @if($user->status === 'active')
                      <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                        <i class="bi bi-check-circle me-1"></i>Actif
                      </span>
                    @else
                      <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2">
                        <i class="bi bi-x-circle me-1"></i>Inactif
                      </span>
                    @endif
                  </td>
                  <td class="text-muted">
                    <i class="bi bi-calendar me-1"></i>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : 'N/A' }}
                  </td>
                  <td class="text-end px-4">
                    <div class="btn-group" role="group">
                      <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm {{ $user->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $user->status === 'active' ? 'Désactiver' : 'Activer' }}">
                          <i class="bi {{ $user->status === 'active' ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                        </button>
                      </form>
                      <button class="btn btn-sm btn-outline-primary" title="Voir" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                        <i class="bi bi-eye"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted mb-0">Aucun utilisateur trouvé</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer bg-white border-0 py-3">
        {{ $users->links() }}
      </div>
    </div>
  </div>
</div>

<!-- Modals pour chaque utilisateur -->
@foreach($users as $user)
<div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-labelledby="userModalLabel{{ $user->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-gradient-primary text-white">
        <h5 class="modal-title" id="userModalLabel{{ $user->id }}">
          <i class="bi bi-person-circle me-2"></i>Détails de l'utilisateur
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <div class="col-md-6">
            <div class="info-group">
              <label class="text-muted small mb-1"><i class="bi bi-person me-1"></i>Nom complet</label>
              <p class="fw-semibold mb-0">{{ $user->name }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-group">
              <label class="text-muted small mb-1"><i class="bi bi-envelope me-1"></i>Email</label>
              <p class="fw-semibold mb-0">{{ $user->email }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-group">
              <label class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i>Téléphone</label>
              <p class="fw-semibold mb-0">{{ $user->phone ?? 'Non renseigné' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-group">
              <label class="text-muted small mb-1"><i class="bi bi-shield-check me-1"></i>Rôle</label>
              <p class="mb-0">
                @if($user->role === 'admin')
                  <span class="badge bg-danger">Administrateur</span>
                @elseif($user->role === 'recruiter')
                  <span class="badge bg-primary">Recruteur</span>
                @else
                  <span class="badge bg-info">Candidat</span>
                @endif
              </p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-group">
              <label class="text-muted small mb-1"><i class="bi bi-toggle-on me-1"></i>Statut</label>
              <p class="mb-0">
                @if($user->status === 'active')
                  <span class="badge bg-success">Actif</span>
                @else
                  <span class="badge bg-danger">Inactif</span>
                @endif
              </p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-group">
              <label class="text-muted small mb-1"><i class="bi bi-calendar me-1"></i>Date d'inscription</label>
              <p class="fw-semibold mb-0">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y à H:i') }}</p>
            </div>
          </div>
          @if($user->address)
          <div class="col-12">
            <div class="info-group">
              <label class="text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i>Adresse</label>
              <p class="fw-semibold mb-0">{{ $user->address }}</p>
            </div>
          </div>
          @endif
          @if($user->bio)
          <div class="col-12">
            <div class="info-group">
              <label class="text-muted small mb-1"><i class="bi bi-file-text me-1"></i>Biographie</label>
              <p class="mb-0">{{ $user->bio }}</p>
            </div>
          </div>
          @endif
          @if($user->role === 'recruiter' && $user->company_name)
          <div class="col-md-6">
            <div class="info-group">
              <label class="text-muted small mb-1"><i class="bi bi-building me-1"></i>Entreprise</label>
              <p class="fw-semibold mb-0">{{ $user->company_name }}</p>
            </div>
          </div>
          @endif
          @if($user->role === 'recruiter' && $user->company_description)
          <div class="col-12">
            <div class="info-group">
              <label class="text-muted small mb-1"><i class="bi bi-info-circle me-1"></i>Description de l'entreprise</label>
              <p class="mb-0">{{ $user->company_description }}</p>
            </div>
          </div>
          @endif
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
@endforeach

@endsection
