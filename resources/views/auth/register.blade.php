@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
  <div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-75">
      <div class="col-lg-10">
        <div class="card shadow-premium rounded-premium border-0">
          <div class="row g-0">
            <!-- Illustration Side -->
            <div class="col-lg-5 d-none d-lg-flex align-items-center justify-content-center p-5" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.05), rgba(6, 182, 212, 0.05));">
              <div class="text-center">
                <img src="https://illustrations.popsy.co/violet/success.svg" class="img-fluid mb-4" alt="Inscription" style="max-width: 280px;">
                <h3 class="fw-bold mb-3">Rejoignez-nous !</h3>
                <p class="text-muted">Créez votre compte et accédez à des milliers d'opportunités professionnelles.</p>
                <div class="mt-4">
                  <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-check-circle-fill text-success fs-5 me-3"></i>
                    <span class="text-start">Gratuit et sans engagement</span>
                  </div>
                  <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-check-circle-fill text-success fs-5 me-3"></i>
                    <span class="text-start">Accès à toutes les offres</span>
                  </div>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill text-success fs-5 me-3"></i>
                    <span class="text-start">Candidature simplifiée</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Form Side -->
            <div class="col-lg-7 p-5">
              <div class="mb-4">
                <h2 class="fw-bold mb-2">Créer un compte</h2>
                <p class="text-muted">Commencez votre aventure professionnelle</p>
              </div>

              @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="bi bi-exclamation-triangle me-2"></i>
                  <strong>Erreur!</strong> Veuillez corriger les champs suivants.
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif

              <form method="POST" action="{{ url('/register') }}" class="auth-form">
                @csrf
                
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                      <i class="bi bi-person me-2 text-primary"></i>Prénom
                    </label>
                    <input type="text" name="first_name" 
                           class="form-control @error('first_name') is-invalid @enderror" 
                           placeholder="Jean" 
                           value="{{ old('first_name') }}">
                    @error('first_name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                      <i class="bi bi-person me-2 text-primary"></i>Nom
                    </label>
                    <input type="text" name="last_name" 
                           class="form-control @error('last_name') is-invalid @enderror" 
                           placeholder="Dupont" 
                           value="{{ old('last_name') }}">
                    @error('last_name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-envelope me-2 text-primary"></i>Adresse email
                  </label>
                  <input type="email" name="email" 
                         class="form-control @error('email') is-invalid @enderror" 
                         placeholder="votreemail@exemple.com" 
                         value="{{ old('email') }}" 
                         required>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                      <i class="bi bi-lock me-2 text-primary"></i>Mot de passe
                    </label>
                    <div class="position-relative">
                      <input type="password" name="password" id="password" 
                             class="form-control @error('password') is-invalid @enderror" 
                             placeholder="••••••••" 
                             required>
                      <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted" 
                              onclick="togglePassword('password')" tabindex="-1">
                        <i class="bi bi-eye" id="password-icon"></i>
                      </button>
                    </div>
                    @error('password')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Minimum 8 caractères</small>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                      <i class="bi bi-lock-fill me-2 text-primary"></i>Confirmer
                    </label>
                    <div class="position-relative">
                      <input type="password" name="password_confirmation" id="password_confirmation" 
                             class="form-control" 
                             placeholder="••••••••" 
                             required>
                      <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted" 
                              onclick="togglePassword('password_confirmation')" tabindex="-1">
                        <i class="bi bi-eye" id="password_confirmation-icon"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-person-badge me-2 text-primary"></i>Je suis
                  </label>
                  <div class="role-selection">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <input type="radio" class="btn-check" name="role" id="role-candidate" value="candidate" {{ old('role', 'candidate') == 'candidate' ? 'checked' : '' }} required>
                        <label class="btn btn-outline-primary w-100 py-3" for="role-candidate">
                          <i class="bi bi-person-circle fs-4 d-block mb-2"></i>
                          <strong>Candidat</strong>
                          <small class="d-block text-muted">Je cherche un emploi</small>
                        </label>
                      </div>
                      <div class="col-md-6">
                        <input type="radio" class="btn-check" name="role" id="role-recruiter" value="recruiter" {{ old('role') == 'recruiter' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary w-100 py-3" for="role-recruiter">
                          <i class="bi bi-building fs-4 d-block mb-2"></i>
                          <strong>Recruteur</strong>
                          <small class="d-block text-muted">Je recrute des talents</small>
                        </label>
                      </div>
                    </div>
                  </div>
                  @error('role')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-check mb-4">
                  <input type="checkbox" class="form-check-input" id="terms" required>
                  <label class="form-check-label small" for="terms">
                    J'accepte les <a href="#" class="text-decoration-none">conditions d'utilisation</a> 
                    et la <a href="#" class="text-decoration-none">politique de confidentialité</a>
                  </label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                  <i class="bi bi-person-plus me-2"></i>Créer mon compte
                </button>

                <div class="text-center">
                  <p class="text-muted mb-0">
                    Vous avez déjà un compte ? 
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                      Se connecter
                    </a>
                  </p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function togglePassword(id) {
  const input = document.getElementById(id);
  const icon = document.getElementById(id + '-icon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('bi-eye', 'bi-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.replace('bi-eye-slash', 'bi-eye');
  }
}
</script>

<style>
.auth-wrapper {
  background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
  min-height: calc(100vh - 200px);
}
.min-vh-75 {
  min-height: 75vh;
}
.auth-form .form-control:focus {
  border-color: var(--color-primary);
  box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}
.role-selection .btn-check:checked + .btn {
  background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
  color: white;
  border-color: var(--color-primary);
}
</style>
@endsection
