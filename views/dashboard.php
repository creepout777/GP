<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - Gestionnaire de Tâches</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard-style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-tasks"></i> TaskFlow</h1>
            <p>Votre gestionnaire de tâches simple et efficace</p>
        </header>

        <div class="stats">
            <div class="stat-item">
                <div class="stat-value" id="total-tasks">0</div>
                <div class="stat-label">Total</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="pending-tasks">0</div>
                <div class="stat-label">En attente</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="completed-tasks">0</div>
                <div class="stat-label">Terminées</div>
            </div>
        </div>

        <div class="task-form">
            <form id="add-task-form">
                <div class="form-group">
                    <label for="task-title">Titre de la tâche</label>
                    <input type="text" id="task-title" class="form-control" placeholder="Que devez-vous faire ?" required>
                </div>
                <div class="form-group">
                    <label for="task-description">Description (optionnel)</label>
                    <textarea id="task-description" class="form-control" rows="2" placeholder="Détails de la tâche..."></textarea>
                </div>
                <div class="form-group">
                    <label for="task-priority">Priorité</label>
                    <select id="task-priority" class="form-control">
                        <option value="low">Faible</option>
                        <option value="medium" selected>Moyenne</option>
                        <option value="high">Élevée</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter la tâche
                </button>
            </form>
        </div>

        <div class="filters">
            <button class="filter-btn active" data-filter="all">Toutes</button>
            <button class="filter-btn" data-filter="pending">En attente</button>
            <button class="filter-btn" data-filter="completed">Terminées</button>
            <button class="filter-btn" data-filter="high">Priorité élevée</button>
        </div>

        <div class="task-list" id="task-list">
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3>Aucune tâche pour le moment</h3>
                <p>Commencez par ajouter votre première tâche !</p>
            </div>
        </div>
    </div>

    <script src="../assets/js/dashboard-script.js"></script>
</body>
</html>
