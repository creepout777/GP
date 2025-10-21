document.addEventListener('DOMContentLoaded', () => {
    const taskList = document.getElementById('task-list');
    const filterButtons = document.querySelectorAll('.filter-btn');

    if (!taskList) return;

    // --- FILTER BUTTONS ---
    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.getAttribute('data-filter');
            filterTasks(filter, taskList);
        });
    });

    // --- TOGGLE STATUS ---
    taskList.addEventListener('click', e => {
        const toggleBtn = e.target.closest('.btn-action.toggle');
        if (!toggleBtn) return;

        e.preventDefault();

        const taskItem = toggleBtn.closest('.task-item');
        const taskId = toggleBtn.getAttribute('href').split('id=')[1];

        fetch(`actions/toggle_status.php?id=${taskId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const isCompleted = data.newStatus === 'Terminé';

                    // Update DOM classes and attributes
                    taskItem.setAttribute('data-status', isCompleted ? 'completed' : 'pending');
                    taskItem.classList.toggle('completed', isCompleted);
                    taskItem.classList.toggle('pending', !isCompleted);

                    // Update checkbox
                    const checkbox = taskItem.querySelector('input[type="checkbox"]');
                    if (checkbox) checkbox.checked = isCompleted;

                    // Reapply active filter
                    const activeFilterBtn = document.querySelector('.filter-btn.active');
                    if (activeFilterBtn) {
                        filterTasks(activeFilterBtn.getAttribute('data-filter'), taskList);
                    }

                } else {
                    alert('Erreur lors de la mise à jour du statut: ' + (data.message || ''));
                }
            })
            .catch(err => console.error('Fetch error:', err));
    });

    // --- INITIAL FILTER ---
    const activeBtn = document.querySelector('.filter-btn.active');
    if (activeBtn) {
        filterTasks(activeBtn.getAttribute('data-filter'), taskList);
    }
});

/**
 * Filter tasks by status or priority
 * @param {string} filter - 'all' | 'pending' | 'completed' | 'high'
 * @param {HTMLElement} taskList
 */
function filterTasks(filter, taskList) {
    const taskItems = taskList.querySelectorAll('.task-item');
    let visibleCount = 0;

    // Remove existing empty state
    const existingEmpty = taskList.querySelector('.empty-state-js');
    if (existingEmpty) existingEmpty.remove();

    taskItems.forEach(task => {
        const status = task.getAttribute('data-status');      // 'pending' or 'completed'
        const priority = task.getAttribute('data-priority');  // 'basse', 'moyenne', 'haute'

        let show = false;
        switch (filter) {
            case 'all': show = true; break;
            case 'pending': show = status === 'pending'; break;
            case 'completed': show = status === 'completed'; break;
            case 'high': show = status === 'pending' && priority === 'haute'; break;
        }

        task.style.display = show ? 'flex' : 'none';
        if (show) visibleCount++;
    });

    if (visibleCount === 0 && taskItems.length > 0) {
        taskList.insertAdjacentHTML('beforeend', `
            <div class="empty-state empty-state-js">
                <i class="fas fa-clipboard-list"></i>
                <h3>Aucune tâche trouvée pour ce filtre</h3>
                <p>Essayez de modifier vos filtres.</p>
            </div>
        `);
    }
}

