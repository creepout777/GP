// Éléments DOM
const themeToggle = document.getElementById('theme-toggle');
const themeIcon = themeToggle.querySelector('i');
// Removed loginForm reference as we no longer attach a JS submit listener to it.
const registerLink = document.getElementById('register-link');

// Gestion du dark mode
function initTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        themeIcon.className = 'fas fa-sun';
    } else {
        document.body.classList.remove('dark-mode');
        themeIcon.className = 'fas fa-moon';
    }
}

function toggleTheme() {
    document.body.classList.toggle('dark-mode');
    
    if (document.body.classList.contains('dark-mode')) {
        themeIcon.className = 'fas fa-sun';
        localStorage.setItem('theme', 'dark');
    } else {
        themeIcon.className = 'fas fa-moon';
        localStorage.setItem('theme', 'light');
    }
}

// *** The handleLogin function has been removed entirely ***
// This function contained the 'event.preventDefault()' which broke PHP submission 
// and the 'window.location.href = 'todo-app.html'' redirect.

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    
    // Animation au chargement
    const loginCard = document.querySelector('.login-card');
    if (loginCard) {
        loginCard.style.opacity = '0';
        loginCard.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            loginCard.style.transition = 'all 0.5s ease';
            loginCard.style.opacity = '1';
            loginCard.style.transform = 'translateY(0)';
        }, 100);
    }
});

// Événements
themeToggle.addEventListener('click', toggleTheme);

// *** The loginForm.addEventListener('submit', handleLogin) line has been removed ***

