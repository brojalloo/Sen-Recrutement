@extends('layouts.app')

@push('styles')
    @vite('resources/css/pages/auth-login.css')
@endpush

@section('content')
<div class="auth-wrapper">
  <div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-75">
      <div class="col-lg-10">
        <div class="card shadow-premium rounded-premium border-0">
          <div class="row g-0">
            <!-- Illustration Side -->
            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center p-5" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.05), rgba(6, 182, 212, 0.05));">
              <div class="text-center">
                <img src="{{ asset('assets/images/remote-work.svg') }}" class="img-fluid mb-4" alt="Connexion" style="max-width: 320px;">
                <h3 class="fw-bold mb-3">Content de vous revoir !</h3>
                <p class="text-muted">Accédez à votre espace personnel et gérez vos opportunités professionnelles.</p>
              </div>
            </div>
            
            <!-- Form Side -->
            <div class="col-lg-6 p-5">
              <div class="mb-4">
                <h2 class="fw-bold mb-2">Connexion</h2>
                <p class="text-muted">Bienvenue sur votre plateforme de recrutement</p>
              </div>

              @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="bi bi-exclamation-triangle me-2"></i>
                  <strong>Erreur!</strong> Vérifiez vos identifiants.
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif

              <form method="POST" action="{{ url('/login') }}" class="auth-form">
                @csrf
                
                <div class="mb-4">
                  <label for="email" class="form-label fw-semibold">
                    <i class="bi bi-envelope me-2 text-primary"></i>Adresse email
                  </label>
                  <input type="email" name="email" id="email" 
                         class="form-control form-control-lg @error('email') is-invalid @enderror" 
                         placeholder="votreemail@exemple.com" 
                         value="{{ old('email') }}" 
                         required autofocus>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-4">
                  <label for="password" class="form-label fw-semibold">
                    <i class="bi bi-lock me-2 text-primary"></i>Mot de passe
                  </label>
                  <div class="position-relative">
                    <input type="password" name="password" id="password" 
                           class="form-control form-control-lg @error('password') is-invalid @enderror" 
                           placeholder="••••••••" 
                           required>
                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted" 
                            data-toggle-password="password" tabindex="-1">
                      <i class="bi bi-eye" id="password-icon"></i>
                    </button>
                  </div>
                  @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                  </div>
                  <a href="{{ route('password.request') }}" class="text-decoration-none">
                    <small>Mot de passe oublié ?</small>
                  </a>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                  <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                </button>

                <div class="text-center">
                  <p class="text-muted mb-0">
                    Pas encore de compte ? 
                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                      Créer un compte
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


@endsection
