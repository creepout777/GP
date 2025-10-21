// Données de démonstration
let tasks = [
    {
        id: 1,
        title: "Finaliser le rapport trimestriel",
        description: "Rassembler les données des ventes et préparer le document pour la réunion",
        priority: "high",
        completed: false,
        date: "2023-05-15"
    },
    {
        id: 2,
        title: "Acheter des fournitures de bureau",
        description: "Stylos, papier, enveloppes et agrafeuse",
        priority: "low",
        completed: true,
        date: "2023-05-10"
    },
    {
        id: 3,
        title: "Préparer la présentation client",
        description: "Créer les diapositives pour la réunion de jeudi",
        priority: "medium",
        completed: false,
        date: "2023-05-18"
    }
];

// Éléments DOM
const taskForm = document.getElementById('add-task-form');
const taskList = document.getElementById('task-list');
const filterButtons = document.querySelectorAll('.filter-btn');
const totalTasksEl = document.getElementById('total-tasks');
const pendingTasksEl = document.getElementById('pending-tasks');
const completedTasksEl = document.getElementById('completed-tasks');

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    renderTasks();
    updateStats();
});

// Ajouter une nouvelle tâche
taskForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const titleInput = document.getElementById('task-title');
    const descriptionInput = document.getElementById('task-description');
    const priorityInput = document.getElementById('task-priority');
    
    const newTask = {
        id: Date.now(),
        title: titleInput.value,
        description: descriptionInput.value,
        priority: priorityInput.value,
        completed: false,
        date: new Date().toISOString().split('T')[0]
    };
    
    tasks.push(newTask);
    
    // Rendre les tâches avec le filtre actif pour maintenir la vue
    const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
    renderTasks(activeFilter);
    updateStats();
    
    // Réinitialiser le formulaire
    taskForm.reset();
    document.getElementById('task-priority').value = 'medium'; // S'assurer que le default est maintenu
});

// Filtrer les tâches
filterButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Mettre à jour les boutons actifs
        filterButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        
        // Filtrer et rendre les tâches
        const filter = button.getAttribute('data-filter');
        renderTasks(filter);
    });
});

// Rendu des tâches
function renderTasks(filter = 'all') {
    let filteredTasks = tasks.slice().sort((a, b) => {
        // Optionnel: Trier par complétion (incomplète en premier) et ensuite par priorité
        if (a.completed !== b.completed) {
            return a.completed ? 1 : -1;
        }
        const priorityOrder = { 'high': 3, 'medium': 2, 'low': 1 };
        return priorityOrder[b.priority] - priorityOrder[a.priority];
    });
    
    if (filter === 'pending') {
        filteredTasks = filteredTasks.filter(task => !task.completed);
    } else if (filter === 'completed') {
        filteredTasks = filteredTasks.filter(task => task.completed);
    } else if (filter === 'high') {
        filteredTasks = filteredTasks.filter(task => task.priority === 'high' && !task.completed);
    }
    
    if (filteredTasks.length === 0) {
        taskList.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3>Aucune tâche trouvée</h3>
                <p>Essayez de modifier vos filtres ou ajoutez une nouvelle tâche</p>
            </div>
        `;
        return;
    }
    
    taskList.innerHTML = '';
    
    filteredTasks.forEach(task => {
        const taskItem = document.createElement('div');
        taskItem.className = `task-item ${task.completed ? 'completed' : ''}`;
        
        const priorityClass = `task-priority priority-${task.priority}`;
        const priorityText = 
            task.priority === 'high' ? 'Élevée' : 
            task.priority === 'medium' ? 'Moyenne' : 'Faible';
        
        taskItem.innerHTML = `
            <div class="task-checkbox ${task.completed ? 'checked' : ''}" data-id="${task.id}">
                ${task.completed ? '<i class="fas fa-check"></i>' : ''}
            </div>
            <div class="task-content">
                <div class="task-title ${task.completed ? 'completed' : ''}">${task.title}</div>
                ${task.description ? `<div class="task-description">${task.description}</div>` : ''}
                <div class="task-date">Ajoutée le ${formatDate(task.date)}</div>
                <div class="${priorityClass}">${priorityText}</div>
            </div>
            <div class="task-actions">
                <button class="action-btn edit-btn" data-id="${task.id}">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn delete-btn" data-id="${task.id}">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        
        taskList.appendChild(taskItem);
    });
    
    // Ajouter les écouteurs d'événements pour les nouvelles tâches
    document.querySelectorAll('.task-checkbox').forEach(checkbox => {
        checkbox.addEventListener('click', toggleTask);
    });
    
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', editTask);
    });
    
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', deleteTask);
    });
}

// Basculer l'état d'une tâche (complétée/incomplète)
function toggleTask(e) {
    const taskId = parseInt(e.currentTarget.getAttribute('data-id'));
    const taskIndex = tasks.findIndex(task => task.id === taskId);
    
    if (taskIndex !== -1) {
        tasks[taskIndex].completed = !tasks[taskIndex].completed;
        const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
        renderTasks(activeFilter);
        updateStats();
    }
}

// Modifier une tâche (simulé: charge dans le formulaire pour re-soumission)
function editTask(e) {
    const taskId = parseInt(e.currentTarget.getAttribute('data-id'));
    const taskIndex = tasks.findIndex(task => task.id === taskId);
    
    if (taskIndex !== -1) {
        const task = tasks[taskIndex];
        
        // Remplir le formulaire avec les données de la tâche
        document.getElementById('task-title').value = task.title;
        document.getElementById('task-description').value = task.description || '';
        document.getElementById('task-priority').value = task.priority;
        
        // Supprimer la tâche existante (l'utilisateur la soumettra comme nouvelle/mise à jour)
        tasks.splice(taskIndex, 1);
        
        const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
        renderTasks(activeFilter);
        updateStats();

        // Faire défiler vers le formulaire pour faciliter la mise à jour
        taskForm.scrollIntoView({ behavior: 'smooth' });
        document.getElementById('task-title').focus();
    }
}

// Supprimer une tâche
function deleteTask(e) {
    const taskId = parseInt(e.currentTarget.getAttribute('data-id'));
    const taskIndex = tasks.findIndex(task => task.id === taskId);
    
    if (taskIndex !== -1) {
        tasks.splice(taskIndex, 1);
        const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
        renderTasks(activeFilter);
        updateStats();
    }
}

// Mettre à jour les statistiques
function updateStats() {
    const totalTasks = tasks.length;
    const completedTasks = tasks.filter(task => task.completed).length;
    const pendingTasks = totalTasks - completedTasks;
    
    totalTasksEl.textContent = totalTasks;
    pendingTasksEl.textContent = pendingTasks;
    completedTasksEl.textContent = completedTasks;
}

// Formater la date
function formatDate(dateString) {
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
}
