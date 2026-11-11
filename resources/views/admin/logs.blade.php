@extends('layouts.app')

@push('styles')
    @vite('resources/css/pages/admin-logs.css')
@endpush

@section('content')
<div class="page-surface">
  <div class="container py-5">
    <!-- Header -->
    <div class="mb-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h1 class="display-6 fw-bold mb-2">
            <i class="bi bi-activity text-gradient"></i> 
            Logs d'administration
          </h1>
          <p class="text-muted mb-0">{{ $logs->total() }} enregistrement(s) d'activité</p>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('admin.logs.export') }}" class="btn btn-success">
            <i class="bi bi-download me-2"></i>Exporter CSV
          </a>
          <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
          </a>
        </div>
      </div>
    </div>

    <!-- Logs Timeline -->
    <div class="card border-0 shadow-premium rounded-premium">
      <div class="card-header bg-white border-0 py-4">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0">Historique des actions</h5>
          <div class="d-flex gap-2">
            <input type="search" class="form-control" placeholder="Rechercher dans les logs..." style="width: 300px;">
            <input type="date" class="form-control" style="width: 180px;">
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
              <tr>
                <th class="px-4 py-3 fw-semibold">ID</th>
                <th class="py-3 fw-semibold">ADMINISTRATEUR</th>
                <th class="py-3 fw-semibold">ACTION</th>
                <th class="py-3 fw-semibold">DATE & HEURE</th>
                <th class="py-3 fw-semibold text-end px-4">DÉTAILS</th>
              </tr>
            </thead>
            <tbody>
              @forelse($logs as $log)
                <tr>
                  <td class="px-4 text-muted">#{{ $log->id }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar-circle bg-warning bg-opacity-10 text-warning me-3">
                        <i class="bi bi-person-gear"></i>
                      </div>
                      <div>
                        <h6 class="mb-0 fw-semibold">Admin #{{ $log->user_id }}</h6>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">
                        <i class="bi bi-lightning-charge me-1"></i>{{ $log->action }}
                      </span>
                    </div>
                  </td>
                  <td>
                    <div class="text-muted">
                      <i class="bi bi-calendar3 me-1"></i>
                      {{ $log->created_at->format('d/m/Y') }}
                      <span class="ms-2">
                        <i class="bi bi-clock me-1"></i>
                        {{ $log->created_at->format('H:i') }}
                      </span>
                    </div>
                  </td>
                  <td class="text-end px-4">
                    <button class="btn btn-sm btn-outline-primary" title="Voir détails">
                      <i class="bi bi-info-circle"></i>
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted mb-0">Aucun log trouvé</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer bg-white border-0 py-3">
        {{ $logs->links() }}
      </div>
    </div>
  </div>
</div>

@endsection
