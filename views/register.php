<?php
ob_start();

require_once '../includes/session_check.php';
require_once '../classes/Utilisateur.php';
require_once '../includes/config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $pdo;
    $db = $pdo;

    if (!$db) {
        $error_message = "Erreur de connexion à la base de données. L'inscription n'est pas possible.";
    } else {
        $utilisateur = new Utilisateur($db);

        $utilisateur->username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $utilisateur->email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $password_confirm = filter_input(INPUT_POST, 'password_confirm', FILTER_SANITIZE_STRING);

        if (empty($utilisateur->username) || empty($utilisateur->email) || empty($password) || empty($password_confirm)) {
            $error_message = "Veuillez remplir tous les champs.";
        } elseif ($password !== $password_confirm) {
            $error_message = "Les mots de passe ne correspondent pas.";
        } else {
            $utilisateur->password = $password; 

            if ($utilisateur->ajouterUtilisateur()) {
                $success_message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                // Clear inputs for security
                $utilisateur->username = '';
                $utilisateur->email = '';
            } else {
                $error_message = "Erreur lors de l'inscription. L'email est peut-être déjà utilisé.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - Inscription</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/login-style.css">
</head>
<body>
    <button class="theme-toggle" id="theme-toggle">
        <i class="fas fa-moon"></i>
    </button>

    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-tasks"></i> TaskFlow</h1>
            <p>Créez votre compte</p>
        </div>

        <div class="login-card">
            <?php if (!empty($error_message)): ?>
                <div style="color: red; text-align: center; margin-bottom: 15px;">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php elseif (!empty($success_message)): ?>
                 <div style="color: green; text-align: center; margin-bottom: 15px;">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <form id="register-form" action="register.php" method="POST">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Votre nom" required value="<?php echo htmlspecialchars($utilisateur->username ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" class="form-control" placeholder="votre@email.com" required value="<?php echo htmlspecialchars($utilisateur->email ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Confirmer mot de passe</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Confirmer mot de passe" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> S'inscrire
                </button>
            </form>

            <div class="login-footer">
                <p>Déjà un compte ? <a href="login.php" id="login-link">Se connecter</a></p>
            </div>
        </div>
    </div>

    <script src="../assets/js/login-script.js"></script>
</body>
</html>

