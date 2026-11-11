import 'bootstrap';

/*
 * Tout le JavaScript de l'interface vit ici.
 *
 * Il était réparti en trois blocs <script> inline (le layout, login, register)
 * et six attributs onclick/onsubmit. Tant qu'il restait du script inline, la
 * directive CSP `script-src` devait autoriser 'unsafe-inline', ce qui la rend
 * inutile contre le XSS. Les gestionnaires sont donc délégués sur document et
 * pilotés par des attributs data-*.
 */

// --- Thème clair/sombre -----------------------------------------------------

const html = document.documentElement;

function applyTheme(theme) {
    html.setAttribute('data-theme', theme);

    const icon = document.getElementById('themeIcon');
    if (icon) {
        icon.className = theme === 'dark' ? 'bi bi-moon-stars-fill' : 'bi bi-sun-fill';
    }
}

applyTheme(localStorage.getItem('theme') || 'light');

// --- Effet de la barre de navigation au défilement ---------------------------

window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar-premium');
    if (navbar) {
        navbar.classList.toggle('scrolled', window.scrollY > 50);
    }
});

// --- Gestionnaires délégués --------------------------------------------------

document.addEventListener('click', (event) => {
    const themeToggle = event.target.closest('#themeToggle');
    if (themeToggle) {
        const next = html.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        localStorage.setItem('theme', next);
        applyTheme(next);
        return;
    }

    // Bouton œil des champs mot de passe : data-toggle-password="<id du champ>"
    const reveal = event.target.closest('[data-toggle-password]');
    if (reveal) {
        const input = document.getElementById(reveal.dataset.togglePassword);
        const icon = document.getElementById(`${reveal.dataset.togglePassword}-icon`);
        if (!input) {
            return;
        }

        const hidden = input.type === 'password';
        input.type = hidden ? 'text' : 'password';
        icon?.classList.replace(hidden ? 'bi-eye' : 'bi-eye-slash', hidden ? 'bi-eye-slash' : 'bi-eye');
    }
});

// Confirmation avant une action destructrice : data-confirm="<question>"
document.addEventListener('submit', (event) => {
    const form = event.target.closest('[data-confirm]');
    if (form && !window.confirm(form.dataset.confirm)) {
        event.preventDefault();
    }
});
