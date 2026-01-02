<!doctype html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','SEN Recrutement - Plateforme Professionnelle')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet">
  <style>
    .navbar-premium {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
      border-bottom: 1px solid rgba(79, 70, 229, 0.1);
      transition: all 0.3s ease;
    }
    .navbar-premium.scrolled {
      background: rgba(255, 255, 255, 0.98);
      box-shadow: 0 8px 40px rgba(0, 0, 0, 0.12);
    }
    .navbar-brand-premium {
      font-weight: 700;
      font-size: 1.5rem;
      background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      transition: all 0.3s ease;
    }
    .navbar-brand-premium:hover {
      transform: scale(1.05);
    }
    .nav-link-premium {
      position: relative;
      font-weight: 600;
      color: #374151 !important;
      padding: 0.5rem 1rem !important;
      transition: all 0.3s ease;
    }
    .nav-link-premium::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0;
      height: 3px;
      background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
      transform: translateX(-50%);
      transition: width 0.3s ease;
      border-radius: 2px;
    }
    .nav-link-premium:hover::after,
    .nav-link-premium.active::after {
      width: 80%;
    }
    .nav-link-premium:hover {
      color: #4f46e5 !important;
    }
    .btn-nav-primary {
      background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
      border: none;
      padding: 0.5rem 1.5rem;
      border-radius: 12px;
      font-weight: 600;
      color: white;
      box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
      transition: all 0.3s ease;
    }
    .btn-nav-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
    }
    .dropdown-menu-premium {
      border: none;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      border-radius: 16px;
      padding: 0.5rem;
      margin-top: 0.5rem;
      animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .dropdown-item-premium {
      border-radius: 8px;
      padding: 0.75rem 1rem;
      font-weight: 500;
      transition: all 0.2s ease;
    }
    .dropdown-item-premium:hover {
      background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%);
      transform: translateX(5px);
    }
    .badge-user {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      margin-left: 0.5rem;
    }
    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: #ef4444;
      color: white;
      font-size: 0.65rem;
      padding: 0.15rem 0.4rem;
      border-radius: 10px;
      font-weight: 700;
    }
  </style>
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

<script>
  // Navbar scroll effect
  window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar-premium');
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  // Theme Toggle
  const themeToggle = document.getElementById('themeToggle');
  const themeIcon = document.getElementById('themeIcon');
  const html = document.documentElement;

  // Charger le thème depuis localStorage
  const currentTheme = localStorage.getItem('theme') || 'light';
  html.setAttribute('data-theme', currentTheme);
  updateThemeIcon(currentTheme);

  // Toggle theme
  if (themeToggle) {
    themeToggle.addEventListener('click', function() {
      const theme = html.getAttribute('data-theme');
      const newTheme = theme === 'light' ? 'dark' : 'light';
      html.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      updateThemeIcon(newTheme);
    });
  }

  function updateThemeIcon(theme) {
    if (themeIcon) {
      if (theme === 'dark') {
        themeIcon.className = 'bi bi-moon-stars-fill';
      } else {
        themeIcon.className = 'bi bi-sun-fill';
      }
    }
  }
</script>

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

<style>
.footer-premium {
  position: relative;
  background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
  color: #e2e8f0;
  overflow: hidden;
}

.footer-wave {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  overflow: hidden;
  line-height: 0;
  transform: rotate(180deg);
}

.footer-wave svg {
  position: relative;
  display: block;
  width: calc(100% + 1.3px);
  height: 80px;
  fill: #f9fafb;
}

.footer-content {
  position: relative;
  padding: 5rem 0 2rem;
  z-index: 1;
}

.footer-brand .text-gradient {
  background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.footer-text {
  color: #94a3b8;
  line-height: 1.7;
}

.social-links {
  display: flex;
  gap: 0.75rem;
}

.social-link {
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
}

.social-link:hover {
  background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
  border-color: transparent;
  color: white;
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
}

.footer-stats {
  padding-top: 1rem;
  border-top: 1px solid rgba(148, 163, 184, 0.2);
}

.footer-heading {
  color: #f1f5f9;
  font-weight: 700;
  font-size: 1rem;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
}

.footer-heading i {
  color: #4f46e5;
}

.footer-links li {
  margin-bottom: 0.75rem;
}

.footer-links a {
  color: #94a3b8;
  text-decoration: none;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
}

.footer-links a:hover {
  color: #4f46e5;
  transform: translateX(5px);
}

.footer-links a i {
  font-size: 0.75rem;
  transition: transform 0.3s ease;
}

.footer-links a:hover i {
  transform: translateX(3px);
}

.footer-contact li {
  margin-bottom: 1rem;
  display: flex;
  align-items: start;
  gap: 0.75rem;
  color: #94a3b8;
}

.footer-contact i {
  color: #4f46e5;
  font-size: 1.1rem;
  margin-top: 2px;
}

.footer-contact a {
  color: #94a3b8;
  text-decoration: none;
  transition: color 0.3s ease;
}

.footer-contact a:hover {
  color: #4f46e5;
}

.footer-bottom {
  border-top: 1px solid rgba(148, 163, 184, 0.2);
}

.footer-legal-links {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.footer-legal-links a {
  color: #94a3b8;
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.3s ease;
}

.footer-legal-links a:hover {
  color: #4f46e5;
}

.footer-legal-links .separator {
  color: #475569;
}

@media (max-width: 768px) {
  .footer-wave svg {
    height: 60px;
  }
  
  .footer-content {
    padding: 4rem 0 2rem;
  }
  
  .footer-stats {
    justify-content: center !important;
  }
  
  .footer-legal-links {
    flex-wrap: wrap;
  }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
