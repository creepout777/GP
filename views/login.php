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
            <form id="login-form">
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" class="form-control" placeholder="votre@email.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" class="form-control" placeholder="Votre mot de passe" required>
                    </div>
                </div>

                <div class="remember-forgot">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember">
                        <label for="remember">Se souvenir de moi</label>
                    </div>
                    <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>

                <div class="divider">
                    <span>Ou continuer avec</span>
                </div>

                <button type="button" class="btn btn-google">
                    <i class="fab fa-google"></i> Google
                </button>
            </form>

            <div class="login-footer">
                <p>Pas encore de compte ? <a href="#" id="register-link">S'inscrire</a></p>
            </div>
        </div>
    </div>

    <script src="../assets/js/login-script.js"></script>
</body>
</html>
