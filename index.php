<?php
require_once 'Utilisateur.php';

try {
    $pdo = new PDO('mysql:host=localhost;port=8889;dbname=gestion_utilisateur;charset=utf8', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion réussie à la base de données.<br><br>";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$u1 = new Utilisateur("Hamza", "hamza@gmail.com", "1234");
$u1->ajouterUtilisateur($pdo);

Utilisateur::connecterUtilisateur($pdo, "hamza@gmail.com", "1234");
?>
