@extends('layouts.app')

@section('content')
<div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); min-height: calc(100vh - 200px);">
  <div class="container py-5">
    <!-- Header -->
    <div class="mb-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h1 class="display-6 fw-bold mb-2">
            <i class="bi bi-briefcase-fill text-gradient"></i> 
            Gestion des offres d'emploi
          </h1>
          <p class="text-muted mb-0">{{ $jobs->total() }} offre(s) publiée(s)</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
      </div>
    </div>

    <!-- Jobs Table -->
    <div class="card border-0 shadow-premium rounded-premium">
      <div class="card-header bg-white border-0 py-4">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0">Liste des offres</h5>
          <div class="d-flex gap-2">
            <input type="search" class="form-control" placeholder="Rechercher une offre..." style="width: 300px;">
            <select class="form-select" style="width: 150px;">
              <option>Tous types</option>
              <option>CDI</option>
              <option>CDD</option>
              <option>Stage</option>
              <option>Freelance</option>
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
                <th class="py-3 fw-semibold">OFFRE</th>
                <th class="py-3 fw-semibold">ENTREPRISE</th>
                <th class="py-3 fw-semibold">LOCALISATION</th>
                <th class="py-3 fw-semibold">TYPE</th>
                <th class="py-3 fw-semibold">SALAIRE</th>
                <th class="py-3 fw-semibold">PUBLIÉE LE</th>
                <th class="py-3 fw-semibold text-end px-4">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @forelse($jobs as $job)
                <tr>
                  <td class="px-4 text-muted">#{{ $job->id }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar-circle bg-success bg-opacity-10 text-success me-3">
                        <i class="bi bi-briefcase"></i>
                      </div>
                      <div>
                        <h6 class="mb-0 fw-semibold">{{ $job->title }}</h6>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="text-muted">
                      <i class="bi bi-building me-1"></i>{{ $job->company }}
                    </span>
                  </td>
                  <td>
                    <span class="text-muted">
                      <i class="bi bi-geo-alt me-1"></i>{{ $job->location }}
                    </span>
                  </td>
                  <td>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2">
                      {{ $job->type }}
                    </span>
                  </td>
                  <td>
                    @if($job->salary)
                      <span class="text-success fw-semibold">
                        <i class="bi bi-currency-dollar"></i>{{ $job->salary }}
                      </span>
                    @else
                      <span class="text-muted">N/A</span>
                    @endif
                  </td>
                  <td class="text-muted">
                    <i class="bi bi-calendar me-1"></i>{{ $job->created_at->format('d/m/Y') }}
                  </td>
                  <td class="text-end px-4">
                    <div class="btn-group">
                      <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-sm btn-outline-primary" title="Voir" target="_blank">
                        <i class="bi bi-eye"></i>
                      </a>
                      <button class="btn btn-sm btn-outline-danger" title="Supprimer">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted mb-0">Aucune offre trouvée</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer bg-white border-0 py-3">
        {{ $jobs->links() }}
      </div>
    </div>
  </div>
</div>

<style>
.avatar-circle {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
}
.table tbody tr {
  transition: all 0.3s ease;
}
.table tbody tr:hover {
  background-color: rgba(79, 70, 229, 0.02);
  transform: scale(1.005);
}
</style>
@endsection
