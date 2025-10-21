<?php
require_once '../../includes/session_check.php';
require_login();
require_once '../../includes/config.php';

global $pdo;
$db = $pdo;

if (!isset($_GET['id'])) {
    header('Location: ../dashboard.php');
    exit;
}

$taskId = (int)$_GET['id'];
$userId = $_SESSION['user_id'] ?? 0;

// Toggle status using a single SQL query
$sql = "
    UPDATE taches
    SET status = CASE 
                    WHEN status = 'En attente' THEN 'Terminé'
                    WHEN status = 'Terminé' THEN 'En attente'
                    ELSE status
                 END
    WHERE id = :id AND utilisateurId = :userId
";

$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

if ($stmt->execute()) {
    header('Location: ../dashboard.php?msg=statuschanged');
    exit;
} else {
    header('Location: ../dashboard.php?msg=error');
    exit;
}

