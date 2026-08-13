@extends('layouts.app')

@section('content')
<div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); min-height: calc(100vh - 200px);">
    <div class="container py-5">
        <!-- Header -->
        <div class="mb-5">
            <h1 class="display-6 fw-bold mb-2">
                <i class="bi bi-person-gear text-gradient"></i> 
                Mon Profil
            </h1>
            <p class="text-muted mb-0">Gérez vos informations personnelles et votre CV</p>
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
                        <form method="POST" action="{{ route('candidate.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label fw-semibold">
                                        <i class="bi bi-person me-2 text-primary"></i>Prénom
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label fw-semibold">
                                        <i class="bi bi-person me-2 text-primary"></i>Nom
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}">
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
                                    <label for="address" class="form-label fw-semibold">
                                        <i class="bi bi-geo-alt me-2 text-primary"></i>Adresse
                                    </label>
                                    <textarea class="form-control" id="address" name="address" rows="2" placeholder="Votre adresse complète">{{ old('address', $user->address) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label for="bio" class="form-label fw-semibold">
                                        <i class="bi bi-card-text me-2 text-primary"></i>Bio / Résumé professionnel
                                    </label>
                                    <textarea class="form-control" id="bio" name="bio" rows="4" placeholder="Parlez-nous de votre parcours et de vos compétences...">{{ old('bio', $user->bio) }}</textarea>
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

                                <div class="col-12">
                                    <hr class="my-4">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>
                                        Curriculum Vitae (CV)
                                    </h6>
                                </div>

                                <div class="col-12">
                                    <label for="cv" class="form-label fw-semibold">Télécharger votre CV</label>
                                    <input type="file" class="form-control" id="cv" name="cv" accept=".pdf,.doc,.docx">
                                    <small class="text-muted">Format accepté: PDF, DOC, DOCX (max 5MB)</small>
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
                <!-- CV Card -->
                <div class="card border-0 shadow-premium rounded-premium mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                            Mon CV
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($user->cv_path)
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-circle bg-success bg-opacity-10 text-success me-3">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold">CV en ligne</p>
                                    <small class="text-muted">{{ basename($user->cv_path) }}</small>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('download.cv', $user->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-download me-2"></i>Télécharger mon CV
                                </a>
                                <form method="POST" action="{{ route('candidate.profile.deleteCV') }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre CV ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                        <i class="bi bi-trash me-2"></i>Supprimer le CV
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-file-earmark-x fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0 small">Aucun CV téléchargé</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="card border-0 shadow-sm rounded-premium" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body p-4 text-white">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-lightbulb me-2"></i>
                            Conseils
                        </h6>
                        <ul class="small mb-0 ps-3">
                            <li class="mb-2">Mettez à jour votre CV régulièrement</li>
                            <li class="mb-2">Ajoutez une bio professionnelle complète</li>
                            <li class="mb-2">Assurez-vous que vos coordonnées sont à jour</li>
                            <li>Utilisez un CV au format PDF pour une meilleure compatibilité</li>
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
</style>
@endsection
