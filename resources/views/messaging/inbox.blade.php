@extends('layouts.app')

@push('styles')
    @vite('resources/css/pages/messaging-inbox.css')
@endpush

@section('content')
<div class="page-surface">
  <div class="container py-5">
    <!-- Header -->
    <div class="mb-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h1 class="display-6 fw-bold mb-2">
            <i class="bi bi-inbox-fill text-gradient"></i> 
            Boîte de réception
          </h1>
          <p class="text-muted mb-0">{{ $messages->total() }} message(s)</p>
        </div>
        <a href="{{ route('messages.compose') }}" class="btn btn-primary">
          <i class="bi bi-plus-circle me-2"></i>Nouveau message
        </a>
      </div>
    </div>

    <!-- Messages Table -->
    <div class="card border-0 shadow-premium rounded-premium">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
              <tr>
                <th class="px-4 py-3 fw-semibold">EXPÉDITEUR</th>
                <th class="py-3 fw-semibold">SUJET</th>
                <th class="py-3 fw-semibold">DATE</th>
                <th class="py-3 fw-semibold">STATUT</th>
                <th class="py-3 fw-semibold text-end px-4">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @forelse($messages as $m)
                <tr class="{{ !$m->is_read ? 'table-active' : '' }}">
                  <td class="px-4">
                    <div class="d-flex align-items-center">
                      <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                        <i class="bi bi-person"></i>
                      </div>
                      <div>
                        <h6 class="mb-0 fw-semibold">{{ optional(\App\Models\User::find($m->sender_id))->full_name ?? 'Utilisateur' }}</h6>
                        <small class="text-muted">{{ optional(\App\Models\User::find($m->sender_id))->email ?? 'N/A' }}</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="fw-semibold">{{ $m->subject }}</span>
                  </td>
                  <td class="text-muted">
                    <i class="bi bi-calendar me-1"></i>{{ $m->created_at->format('d/m/Y H:i') }}
                  </td>
                  <td>
                    @if($m->is_read)
                      <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                        <i class="bi bi-check-circle me-1"></i>Lu
                      </span>
                    @else
                      <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2">
                        <i class="bi bi-envelope me-1"></i>Non lu
                      </span>
                    @endif
                  </td>
                  <td class="text-end px-4">
                    @if(!$m->is_read)
                      <form method="POST" action="{{ route('messages.read', $m->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-success" title="Marquer comme lu">
                          <i class="bi bi-check2"></i>
                        </button>
                      </form>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted mb-0">Aucun message dans votre boîte de réception</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer bg-white border-0 py-3">
        {{ $messages->links() }}
      </div>
    </div>
  </div>
</div>

@endsection
