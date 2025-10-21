<?php
// Start output buffering as the very first command to protect against stray output
ob_start(); 

require_once '../includes/session_check.php';
require_once '../classes/Utilisateur.php';
require_once '../includes/config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- FIX: Removed instantiation of non-existent Database class ---
    // The includes/config.php file creates the PDO connection and stores it in the global $pdo variable.
    global $pdo;
    $db = $pdo; 
    
    // Check if the connection object ($db) is available before proceeding
    if (!$db) {
        $error_message = "Erreur de connexion à la base de données. Vérifiez includes/config.php.";
    } else {
        $utilisateur = new Utilisateur($db);

        $utilisateur->email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $utilisateur->password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if (empty($utilisateur->email) || empty($utilisateur->password)) {
            $error_message = "Veuillez entrer votre email et votre mot de passe.";
        } elseif ($utilisateur->connecterUtilisateur()) {
            $_SESSION['user_id'] = $utilisateur->id;
            $_SESSION['username'] = $utilisateur->username;
            header('Location: dashboard.php'); 
            exit;
        } else {
            $error_message = "Email ou mot de passe invalide.";
        }
    }
}

if (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
    $error_message = "Votre session a expiré en raison de l'inactivité. Veuillez vous reconnecter.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - Connexion</title>
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
            <p>Connectez-vous à votre espace</p>
        </div>

        <div class="login-card">
            <?php if (!empty($error_message)): ?>
                <div style="color: red; text-align: center; margin-bottom: 15px;">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <form id="login-form" action="login.php" method="POST">
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" class="form-control" placeholder="votre@email.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Votre mot de passe" required>
                    </div>
                </div>

                <div class="remember-forgot">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Se souvenir de moi</label>
                    </div>
             
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>

            </form>

            <div class="login-footer">
                <p>Pas encore de compte ? <a href="register.php" id="register-link">S'inscrire</a></p>
            </div>
        </div>
    </div>

    <script src="../assets/js/login-script.js"></script>
</body>
</html>

