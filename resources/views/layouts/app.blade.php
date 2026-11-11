<!doctype html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','SEN Recrutement - Plateforme Professionnelle')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  {{-- Styles propres à la page : plusieurs pages définissent les mêmes
       sélecteurs avec des valeurs différentes, chacune ne charge donc que
       les siens. --}}
  @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-premium sticky-top">
  <div class="container py-2">
    <a class="navbar-brand d-flex align-items-center navbar-brand-premium" href="{{ route('home') }}">
      <i class="bi bi-briefcase-fill me-2" style="font-size: 1.75rem;"></i>
      <span>SEN Recrutement</span>
    </a>
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <li class="nav-item">
          <a class="nav-link nav-link-premium {{ request()->routeIs('jobs.index') ? 'active' : '' }}" href="{{ route('jobs.index') }}">
            <i class="bi bi-briefcase me-1"></i>Offres
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-premium {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}">
            <i class="bi bi-envelope me-1"></i>Contact
          </a>
        </li>
        @auth
          <li class="nav-item">
            <a class="nav-link nav-link-premium position-relative {{ request()->routeIs('messages.*') ? 'active' : '' }}" href="{{ route('messages.inbox') }}">
              <i class="bi bi-chat-dots me-1"></i>Messages
              {{-- <span class="notification-badge">3</span> --}}
            </a>
          </li>
          <li class="nav-item me-2">
            <button id="themeToggle" class="btn btn-sm btn-outline-secondary border-0 theme-toggle" title="Changer le thème">
              <i class="bi bi-sun-fill" id="themeIcon"></i>
            </button>
          </li>
          <li class="nav-item dropdown ms-lg-2">
            <a class="nav-link nav-link-premium dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
              <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1rem;">
                <i class="bi bi-person-fill"></i>
              </div>
              <span>{{ Auth::user()->full_name ?? Auth::user()->name }}</span>
              @if(Auth::user()->role === 'admin')
                <span class="badge-user">Admin</span>
              @elseif(Auth::user()->role === 'recruiter')
                <span class="badge-user" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">Recruteur</span>
              @else
                <span class="badge-user" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">Candidat</span>
              @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-premium">
              @if(Auth::user()->role === 'admin')
                <li>
                  <a class="dropdown-item dropdown-item-premium" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Admin
                  </a>
                </li>
              @endif
              @if(Auth::user()->role === 'candidate')
                <li>
                  <a class="dropdown-item dropdown-item-premium" href="{{ route('candidate.dashboard') }}">
                    <i class="bi bi-grid me-2 text-primary"></i>Mon Dashboard
                  </a>
                </li>
                <li>
                  <a class="dropdown-item dropdown-item-premium" href="{{ route('candidate.profile.edit') }}">
                    <i class="bi bi-person-gear me-2 text-primary"></i>Mon Profil
                  </a>
                </li>
              @endif
              @if(Auth::user()->role === 'recruiter')
                <li>
                  <a class="dropdown-item dropdown-item-premium" href="{{ route('recruiter.dashboard') }}">
                    <i class="bi bi-grid me-2 text-primary"></i>Mon Dashboard
                  </a>
                </li>
                <li>
                  <a class="dropdown-item dropdown-item-premium" href="{{ route('recruiter.jobs.index') }}">
                    <i class="bi bi-briefcase me-2 text-success"></i>Mes Offres
                  </a>
                </li>
                <li>
                  <a class="dropdown-item dropdown-item-premium" href="{{ route('recruiter.profile.edit') }}">
                    <i class="bi bi-building-gear me-2 text-primary"></i>Mon Profil
                  </a>
                </li>
              @endif
              <li><hr class="dropdown-divider my-2"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}" class="px-2">@csrf
                  <button class="btn btn-sm btn-outline-danger w-100 d-flex align-items-center justify-content-center" type="submit">
                    <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                  </button>
                </form>
              </li>
            </ul>
          </li>
        @else
          <li class="nav-item me-2">
            <button id="themeToggle" class="btn btn-sm btn-outline-secondary border-0 theme-toggle" title="Changer le thème">
              <i class="bi bi-sun-fill" id="themeIcon"></i>
            </button>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-link-premium" href="{{ route('login') }}">
              <i class="bi bi-box-arrow-in-right me-1"></i>Connexion
            </a>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-nav-primary" href="{{ route('register') }}">
              <i class="bi bi-person-plus me-1"></i>Inscription
            </a>
          </li>
        @endauth
      </ul>
    </div>
  </div>
  @if(session('status'))
    <div class="w-100 px-3 pb-2">
      <div class="alert alert-success mb-0 text-center rounded-3 border-0 shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
      </div>
    </div>
  @endif
</nav>


<main>
  @yield('content')
</main>

<footer class="footer-premium">
  <div class="footer-wave">
    <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
      <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path>
      <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path>
      <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path>
    </svg>
  </div>

  <div class="footer-content">
    <div class="container">
      <div class="row g-4">
        <!-- Brand Section -->
        <div class="col-lg-4 col-md-6">
          <div class="footer-brand mb-4">
            <h4 class="fw-bold mb-3">
              <i class="bi bi-briefcase-fill me-2"></i>
              <span class="text-gradient">SEN Recrutement</span>
            </h4>
            <p class="footer-text mb-4">
              Connectant talents et entreprises pour construire l'avenir professionnel du Sénégal. Votre parcours commence ici.
            </p>
            
            <!-- Social Links -->
            <div class="social-links d-flex gap-3">
              <a href="#" class="social-link" title="LinkedIn">
                <i class="bi bi-linkedin"></i>
              </a>
              <a href="#" class="social-link" title="Twitter">
                <i class="bi bi-twitter-x"></i>
              </a>
              <a href="#" class="social-link" title="Facebook">
                <i class="bi bi-facebook"></i>
              </a>
              <a href="#" class="social-link" title="Instagram">
                <i class="bi bi-instagram"></i>
              </a>
            </div>

            <!-- Stats -->
            <div class="footer-stats mt-4 d-flex gap-4">
              <div>
                <h5 class="fw-bold mb-0 text-gradient">500+</h5>
                <small class="footer-text">Offres actives</small>
              </div>
              <div>
                <h5 class="fw-bold mb-0 text-gradient">1K+</h5>
                <small class="footer-text">Utilisateurs</small>
              </div>
              <div>
                <h5 class="fw-bold mb-0 text-gradient">100+</h5>
                <small class="footer-text">Entreprises</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Navigation -->
        <div class="col-lg-2 col-md-6 col-6">
          <h6 class="footer-heading mb-3">
            <i class="bi bi-compass me-2"></i>Navigation
          </h6>
          <ul class="footer-links list-unstyled">
            <li><a href="{{ route('home') }}"><i class="bi bi-chevron-right me-1"></i>Accueil</a></li>
            <li><a href="{{ route('jobs.index') }}"><i class="bi bi-chevron-right me-1"></i>Offres d'emploi</a></li>
            <li><a href="{{ route('contact.index') }}"><i class="bi bi-chevron-right me-1"></i>Contact</a></li>
            @guest
              <li><a href="{{ route('register') }}"><i class="bi bi-chevron-right me-1"></i>Inscription</a></li>
            @endguest
          </ul>
        </div>

        <!-- For Candidates -->
        <div class="col-lg-2 col-md-6 col-6">
          <h6 class="footer-heading mb-3">
            <i class="bi bi-person-check me-2"></i>Candidats
          </h6>
          <ul class="footer-links list-unstyled">
            @auth
              @if(Auth::user()->role === 'candidate')
                <li><a href="{{ route('candidate.dashboard') }}"><i class="bi bi-chevron-right me-1"></i>Mon espace</a></li>
                <li><a href="{{ route('candidate.profile.edit') }}"><i class="bi bi-chevron-right me-1"></i>Mon profil</a></li>
              @endif
            @else
              <li><a href="{{ route('register') }}"><i class="bi bi-chevron-right me-1"></i>Créer un compte</a></li>
            @endauth
            <li><a href="{{ route('jobs.index') }}"><i class="bi bi-chevron-right me-1"></i>Rechercher emploi</a></li>
            <li><a href="{{ route('messages.inbox') }}"><i class="bi bi-chevron-right me-1"></i>Messages</a></li>
          </ul>
        </div>

        <!-- For Recruiters -->
        <div class="col-lg-2 col-md-6 col-6">
          <h6 class="footer-heading mb-3">
            <i class="bi bi-building me-2"></i>Recruteurs
          </h6>
          <ul class="footer-links list-unstyled">
            @auth
              @if(Auth::user()->role === 'recruiter')
                <li><a href="{{ route('recruiter.dashboard') }}"><i class="bi bi-chevron-right me-1"></i>Mon espace</a></li>
                <li><a href="{{ route('recruiter.jobs.index') }}"><i class="bi bi-chevron-right me-1"></i>Mes offres</a></li>
              @endif
            @else
              <li><a href="{{ route('register') }}"><i class="bi bi-chevron-right me-1"></i>Créer un compte</a></li>
            @endauth
            <li><a href="{{ route('recruiter.jobs.create') }}"><i class="bi bi-chevron-right me-1"></i>Publier offre</a></li>
            <li><a href="{{ route('contact.index') }}"><i class="bi bi-chevron-right me-1"></i>Nous contacter</a></li>
          </ul>
        </div>

        <!-- Contact -->
        <div class="col-lg-2 col-md-6 col-6">
          <h6 class="footer-heading mb-3">
            <i class="bi bi-telephone me-2"></i>Contact
          </h6>
          <ul class="footer-contact list-unstyled">
            <li>
              <i class="bi bi-geo-alt-fill"></i>
              <span>Dakar, Sénégal</span>
            </li>
            <li>
              <i class="bi bi-envelope-fill"></i>
              <a href="mailto:contact@senrecrutement.sn">contact@senrecrutement.sn</a>
            </li>
            <li>
              <i class="bi bi-phone-fill"></i>
              <span>+221 33 XXX XX XX</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Footer Bottom -->
      <div class="footer-bottom mt-5 pt-4">
        <div class="row align-items-center">
          <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <p class="mb-0 footer-text">
              <i class="bi bi-c-circle me-1"></i>{{ date('Y') }} SEN Recrutement. 
              <span class="d-inline-block ms-2">Fait avec <i class="bi bi-heart-fill text-danger"></i> au Sénégal</span>
            </p>
          </div>
          <div class="col-md-6 text-center text-md-end">
            <div class="footer-legal-links">
              <a href="#">Conditions</a>
              <span class="separator">•</span>
              <a href="#">Confidentialité</a>
              <span class="separator">•</span>
              <a href="#">FAQ</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>


</body>
</html>
