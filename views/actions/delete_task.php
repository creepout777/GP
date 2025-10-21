<?php
require_once __DIR__ . '/../../includes/session_check.php';
require_login();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../classes/Tache.php';

global $pdo;

if (isset($_GET['id'])) {
    $tache = new Tache($pdo);
    $tache->id = intval($_GET['id']);
    $tache->utilisateurId = $_SESSION['user_id'];

    // Vérifier la propriété de la tâche
    $task = $tache->getTacheById();
    if ($task && $task['utilisateurId'] == $_SESSION['user_id']) {
        $tache->supprimerTache();
    }
}

header('Location: ../dashboard.php');
exit;

