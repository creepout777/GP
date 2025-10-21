// Éléments DOM
const themeToggle = document.getElementById('theme-toggle');
const themeIcon = themeToggle.querySelector('i');
const loginForm = document.getElementById('login-form');
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

// Simulation de connexion
function handleLogin(event) {
    event.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const remember = document.getElementById('remember').checked;

    // Simulation de validation
    if (email && password) {
        // Afficher un message de succès
        alert(`Connexion réussie !\nEmail: ${email}\nSe souvenir: ${remember ? 'Oui' : 'Non'}`);
        
        // Redirection vers la page principale (simulée)
        setTimeout(() => {
            window.location.href = 'todo-app.html'; // Remplacez par l'URL de votre app principale
        }, 1000);
    } else {
        alert('Veuillez remplir tous les champs');
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    initTheme();
});

// Événements
themeToggle.addEventListener('click', toggleTheme);
loginForm.addEventListener('submit', handleLogin);

registerLink.addEventListener('click', (e) => {
    e.preventDefault();
    alert('Fonctionnalité d\'inscription à implémenter');
});

// Animation au chargement
document.addEventListener('DOMContentLoaded', () => {
    // Check if the login-card exists before trying to manipulate its style
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
