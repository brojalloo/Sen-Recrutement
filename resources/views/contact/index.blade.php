@extends('layouts.app')

@section('content')
<div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); min-height: calc(100vh - 200px);">
  <div class="container py-5">
    <div class="row g-4">
      <!-- Left: Contact Info -->
      <div class="col-lg-5">
        <div class="sticky-top" style="top: 100px;">
          <h1 class="display-6 fw-bold mb-3">
            <i class="bi bi-envelope-fill text-gradient"></i> 
            Contactez-nous
          </h1>
          <p class="text-muted mb-5">
            Une question, une suggestion ou besoin d'aide ? Écrivez-nous, nous sommes là pour vous répondre rapidement.
          </p>

          <!-- Contact Cards -->
          <div class="d-flex flex-column gap-4">
            <div class="card border-0 shadow-sm rounded-premium">
              <div class="card-body p-4">
                <div class="d-flex align-items-start">
                  <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                    <i class="bi bi-geo-alt-fill"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-1">Adresse</h6>
                    <p class="text-muted mb-0">Dakar, Sénégal</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="card border-0 shadow-sm rounded-premium">
              <div class="card-body p-4">
                <div class="d-flex align-items-start">
                  <div class="avatar-circle bg-success bg-opacity-10 text-success me-3">
                    <i class="bi bi-envelope-fill"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-1">Email</h6>
                    <a href="mailto:contact@senrecrutement.sn" class="text-muted text-decoration-none">
                      contact@senrecrutement.sn
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="card border-0 shadow-sm rounded-premium">
              <div class="card-body p-4">
                <div class="d-flex align-items-start">
                  <div class="avatar-circle bg-warning bg-opacity-10 text-warning me-3">
                    <i class="bi bi-telephone-fill"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-1">Téléphone</h6>
                    <p class="text-muted mb-0">+221 33 XXX XX XX</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Social Links -->
          <div class="mt-5">
            <h6 class="fw-bold mb-3">Suivez-nous</h6>
            <div class="d-flex gap-3">
              <a href="#" class="social-link-contact" title="LinkedIn">
                <i class="bi bi-linkedin"></i>
              </a>
              <a href="#" class="social-link-contact" title="Twitter">
                <i class="bi bi-twitter-x"></i>
              </a>
              <a href="#" class="social-link-contact" title="Facebook">
                <i class="bi bi-facebook"></i>
              </a>
              <a href="#" class="social-link-contact" title="Instagram">
                <i class="bi bi-instagram"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Contact Form -->
      <div class="col-lg-7">
        <div class="card border-0 shadow-premium rounded-premium">
          <div class="card-body p-5">
            <h3 class="fw-bold mb-4">
              <i class="bi bi-send me-2 text-primary"></i>
              Envoyez-nous un message
            </h3>

            @if(session('status'))
              <div class="alert alert-success border-0 rounded-3 mb-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
              </div>
            @endif

            <form method="POST" action="{{ route('contact.send') }}">
              @csrf
              <div class="row g-4">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-person me-2 text-primary"></i>Nom complet
                  </label>
                  <input type="text" name="name" class="form-control form-control-lg" value="{{ old('name') }}" placeholder="Votre nom" required>
                  @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-envelope me-2 text-primary"></i>Email
                  </label>
                  <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}" placeholder="votre@email.com" required>
                  @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-tag me-2 text-primary"></i>Sujet
                  </label>
                  <input type="text" name="subject" class="form-control form-control-lg" value="{{ old('subject') }}" placeholder="Objet de votre message" required>
                  @error('subject')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-chat-left-text me-2 text-primary"></i>Message
                  </label>
                  <textarea name="message" class="form-control form-control-lg" rows="6" placeholder="Écrivez votre message ici..." required>{{ old('message') }}</textarea>
                  @error('message')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                  <button class="btn btn-primary btn-lg w-100" type="submit">
                    <i class="bi bi-send me-2"></i>Envoyer le message
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.avatar-circle {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  flex-shrink: 0;
}
.social-link-contact {
  width: 45px;
  height: 45px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(79, 70, 229, 0.1);
  border: 2px solid rgba(79, 70, 229, 0.2);
  border-radius: 12px;
  color: #4f46e5;
  font-size: 1.25rem;
  transition: all 0.3s ease;
  text-decoration: none;
}
.social-link-contact:hover {
  background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
  border-color: transparent;
  color: white;
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
}
</style>
@endsection
