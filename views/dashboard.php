<?php
ob_start();
require_once '../includes/session_check.php';
require_login();
require_once '../classes/Tache.php';
require_once '../includes/config.php';

global $pdo;
$db = $pdo;

$tache = new Tache($db);
$tache->utilisateurId = $_SESSION['user_id'] ?? 0;
if (!$tache->utilisateurId) die('Erreur : utilisateur non connecté.');

// Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

// Map HTML select to DB ENUM
function mapPriority($html_priority) {
    switch ($html_priority) {
        case 'high': return 'Haute';
        case 'low': return 'Basse';
        case 'medium':
        default: return 'Moyenne';
    }
}

// Default status
function mapStatus() {
    return 'En attente';
}

// Check if editing a task
$editing_task = null;
if (isset($_GET['edit_id'])) {
    $tache->id = (int)$_GET['edit_id'];
    $editing_task = $tache->getTacheById();
}

// Handle add/edit form submission
$task_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_title'])) {
    $tache->titre = filter_input(INPUT_POST, 'task_title', FILTER_SANITIZE_STRING);
    $tache->description = filter_input(INPUT_POST, 'task_description', FILTER_SANITIZE_STRING) ?: null;
    $tache->priorite = mapPriority(filter_input(INPUT_POST, 'task_priority', FILTER_SANITIZE_STRING));

    try {
        if (!empty($_POST['task_id'])) {
            // Edit existing task
            $tache->id = (int)$_POST['task_id'];
            $existing_task = $tache->getTacheById();
            $tache->status = $existing_task['status'] ?? 'En attente';
            if ($tache->modifierTache()) {
                header('Location: dashboard.php?msg=edited');
                exit;
            } else $task_error = "Erreur lors de la modification de la tâche.";
        } else {
            // Add new task
            $tache->status = mapStatus();
            if ($tache->ajouterTache()) {
                header('Location: dashboard.php?msg=success');
                exit;
            } else $task_error = "Erreur lors de l'ajout de la tâche.";
        }
    } catch (PDOException $e) {
        $task_error = "Erreur PDO : " . $e->getMessage();
    }
}

// Fetch all tasks
$tasks = $tache->getAllTache();
$total_tasks = count($tasks);
$completed_tasks = 0;
$pending_tasks = 0;
foreach ($tasks as $task) {
    if ($task['status'] === 'Terminé') $completed_tasks++;
    else $pending_tasks++;
}

// Render task HTML
function renderTask($task) {
    $priority_class = strtolower($task['priorite']);
    $status_class = ($task['status'] === 'Terminé') ? 'completed' : 'pending';

    $actions = '
        <a href="dashboard.php?edit_id=' . $task['id'] . '" class="btn-action edit"><i class="fas fa-edit"></i></a>
        <a href="actions/delete_task.php?id=' . $task['id'] . '" class="btn-action delete" onclick="return confirm(\'Confirmer la suppression ?\')"><i class="fas fa-trash"></i></a>
        <a href="actions/toggle_status.php?id=' . $task['id'] . '" class="btn-action toggle"><i class="fas fa-check"></i></a>
    ';

    $description_html = !empty($task['description']) ? '<p>' . htmlspecialchars($task['description']) . '</p>' : '';

    return '
        <div class="task-item ' . $status_class . ' ' . $priority_class . '" data-status="' . $status_class . '" data-priority="' . $priority_class . '">
            <div class="task-content">
                <input type="checkbox" ' . ($status_class === 'completed' ? 'checked' : '') . ' disabled>
                <div class="task-text">
                    <h4>' . htmlspecialchars($task['titre']) . '</h4>
                    ' . $description_html . '
                    <span class="priority-badge ' . $priority_class . '">' . htmlspecialchars($task['priorite']) . '</span>
                </div>
            </div>
            <div class="task-actions">' . $actions . '</div>
        </div>
    ';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TaskFlow - Gestionnaire de Tâches</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/dashboard-style.css">
<script src="../../assets/js/dashboard-script.js"></script>
</head>
<body>
<div class="background-area">
    <ul class="circles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>
<div class="container">
    <header>
        <h1><i class="fas fa-tasks"></i> TaskFlow</h1>
        <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Utilisateur'); ?>.
            <a href="?action=logout" style="color: #ff6b6b; margin-left: 10px;">Déconnexion</a>
        </p>
    </header>

    <div class="stats">
        <div class="stat-item"><div class="stat-value"><?php echo $total_tasks; ?></div><div class="stat-label">Total</div></div>
        <div class="stat-item"><div class="stat-value"><?php echo $pending_tasks; ?></div><div class="stat-label">En attente</div></div>
        <div class="stat-item"><div class="stat-value"><?php echo $completed_tasks; ?></div><div class="stat-label">Terminées</div></div>
    </div>

    <?php if ($task_error): ?>
        <div style="color: red; text-align: center; margin-bottom: 15px;"><?php echo htmlspecialchars($task_error); ?></div>
    <?php elseif (isset($_GET['msg'])): ?>
        <div style="color: green; text-align: center; margin-bottom: 15px;">
            <?php echo ($_GET['msg']==='success') ? 'Tâche ajoutée avec succès !' : 'Tâche modifiée avec succès !'; ?>
        </div>
    <?php endif; ?>

    <div class="task-form">
        <form action="dashboard.php" method="POST">
            <input type="hidden" name="task_id" value="<?php echo $editing_task['id'] ?? ''; ?>">

            <div class="form-group">
                <label for="task-title">Titre de la tâche</label>
                <input type="text" id="task-title" name="task_title" class="form-control"
                       placeholder="Que devez-vous faire ?" required
                       value="<?php echo htmlspecialchars($editing_task['titre'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="task-description">Description (optionnel)</label>
                <textarea id="task-description" name="task_description" class="form-control" rows="2"
                          placeholder="Détails de la tâche..."><?php echo htmlspecialchars($editing_task['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="task-priority">Priorité</label>
                <select id="task-priority" name="task_priority" class="form-control">
                    <option value="low" <?php echo (isset($editing_task['priorite']) && $editing_task['priorite']=='Basse')?'selected':''; ?>>Basse</option>
                    <option value="medium" <?php echo (isset($editing_task['priorite']) && $editing_task['priorite']=='Moyenne')?'selected':''; ?>>Moyenne</option>
                    <option value="high" <?php echo (isset($editing_task['priorite']) && $editing_task['priorite']=='Haute')?'selected':''; ?>>Haute</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-<?php echo $editing_task ? 'edit' : 'plus'; ?>"></i>
                <?php echo $editing_task ? 'Modifier la tâche' : 'Ajouter la tâche'; ?>
            </button>
            <?php if ($editing_task): ?>
                <a href="dashboard.php" class="btn btn-secondary">Annuler</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="filters">
        <button class="filter-btn active" data-filter="all">Toutes</button>
        <button class="filter-btn" data-filter="pending">En attente</button>
        <button class="filter-btn" data-filter="completed">Terminées</button>
        <button class="filter-btn" data-filter="high">Haute priorité</button>
    </div>

    <div class="task-list">
        <?php if ($total_tasks > 0): ?>
            <?php foreach ($tasks as $task) echo renderTask($task); ?>
        <?php else: ?>
            <div class="empty-state"><i class="fas fa-clipboard-list"></i><h3>Aucune tâche pour le moment</h3><p>Commencez par ajouter votre première tâche !</p></div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

