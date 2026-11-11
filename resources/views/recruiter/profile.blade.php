@extends('layouts.app')

@section('content')
<div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); min-height: calc(100vh - 200px);">
    <div class="container py-5">
        <!-- Header -->
        <div class="mb-5">
            <h1 class="display-6 fw-bold mb-2">
                <i class="bi bi-building text-gradient"></i> 
                Mon Profil Recruteur
            </h1>
            <p class="text-muted mb-0">Gérez les informations de votre entreprise et votre profil</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success border-0 rounded-3 mb-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
            </div>
        @endif

        <div class="row g-4">
            <!-- Profile Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-premium rounded-premium">
                    <div class="card-header bg-white border-0 py-4">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-person-circle me-2 text-primary"></i>
                            Informations personnelles
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('recruiter.profile.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label fw-semibold">
                                        <i class="bi bi-person me-2 text-primary"></i>Prénom
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label fw-semibold">
                                        <i class="bi bi-person me-2 text-primary"></i>Nom
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="bi bi-envelope me-2 text-primary"></i>Email
                                    </label>
                                    <input type="email" class="form-control form-control-lg" id="email" value="{{ $user->email }}" readonly style="background-color: #f3f4f6;">
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold">
                                        <i class="bi bi-telephone me-2 text-primary"></i>Téléphone
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+221 XX XXX XX XX">
                                </div>

                                <div class="col-12">
                                    <hr class="my-4">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-building-fill me-2 text-info"></i>
                                        Informations de l'entreprise
                                    </h6>
                                </div>

                                <div class="col-12">
                                    <label for="company_name" class="form-label fw-semibold">
                                        <i class="bi bi-building me-2 text-primary"></i>Nom de l'entreprise
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}" placeholder="Ex: TechCorp SARL">
                                </div>
                                <div class="col-12">
                                    <label for="company_description" class="form-label fw-semibold">
                                        <i class="bi bi-card-text me-2 text-primary"></i>Description de l'entreprise
                                    </label>
                                    <textarea class="form-control" id="company_description" name="company_description" rows="5" placeholder="Décrivez votre entreprise, vos activités, votre culture d'entreprise...">{{ old('company_description', $user->company_description) }}</textarea>
                                    <small class="text-muted">Cette description sera visible par les candidats</small>
                                </div>

                                <div class="col-12">
                                    <hr class="my-4">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-shield-lock me-2 text-warning"></i>
                                        Modifier le mot de passe
                                    </h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold">Nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Laissez vide pour ne pas modifier">
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-semibold">Confirmer le mot de passe</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="bi bi-check-circle me-2"></i>Mettre à jour le profil
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Company Card -->
                <div class="card border-0 shadow-premium rounded-premium mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-building text-info me-2"></i>
                            Votre Entreprise
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($user->company_name)
                            <div class="text-center mb-3">
                                <div class="avatar-company bg-gradient-primary text-white mb-3 mx-auto">
                                    <i class="bi bi-building fs-3"></i>
                                </div>
                                <h5 class="fw-bold mb-1">{{ $user->company_name }}</h5>
                                <p class="text-muted small mb-0">{{ $user->full_name }}</p>
                            </div>
                            <hr>
                            <div class="d-grid gap-2">
                                <a href="{{ route('recruiter.jobs.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-briefcase me-2"></i>Mes Offres
                                </a>
                                <a href="{{ route('recruiter.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-building-x fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0 small">Complétez les informations de votre entreprise</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="card border-0 shadow-premium rounded-premium mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-graph-up text-success me-2"></i>
                            Statistiques
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="text-muted small mb-0">Offres actives</p>
                                <h4 class="mb-0 fw-bold text-primary">{{ $activeJobsCount }}</h4>
                            </div>
                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-briefcase"></i>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-0">Candidatures reçues</p>
                                <h4 class="mb-0 fw-bold text-success">{{ $receivedApplicationsCount }}</h4>
                            </div>
                            <div class="avatar-circle bg-success bg-opacity-10 text-success">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="card border-0 shadow-sm rounded-premium" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body p-4 text-white">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-lightbulb me-2"></i>
                            Conseils Recruteur
                        </h6>
                        <ul class="small mb-0 ps-3">
                            <li class="mb-2">Complétez la description de votre entreprise pour attirer les meilleurs talents</li>
                            <li class="mb-2">Gardez vos coordonnées à jour</li>
                            <li class="mb-2">Répondez rapidement aux candidatures</li>
                            <li>Publiez des offres détaillées et attractives</li>
                        </ul>
                    </div>
                </div>
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
    flex-shrink: 0;
}

.avatar-company {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
}
</style>
@endsection
