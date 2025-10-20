<?php
// session_check.php
// Inclure ce fichier en haut des pages protégées pour vérifier si l'utilisateur est connecté


# USAGE:
# 1. Include this file at the top of any protected page:
#      require_once 'session.php';
#
# 2. Use `require_login();` on pages that require the user to be logged in:
#      require_login();
#
# 3. After successful login, set:
#      $_SESSION['user_id'] = $userId;
#
# 4. User will be automatically logged out after 20 minutes of inactivity.

ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
session_name('SECURESESSID');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$fingerprint = hash('sha256', $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
if (!isset($_SESSION['fingerprint'])) {
    $_SESSION['fingerprint'] = $fingerprint;
} elseif ($_SESSION['fingerprint'] !== $fingerprint) {
    session_unset();
    session_destroy();
    exit;
}

$timeout = 20 * 60;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)) {
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}
# $_SESSION['fingerprint']     - Hash of user-agent and IP to prevent hijacking
# $_SESSION['LAST_ACTIVITY']   - Timestamp of last user interaction for timeout
# $_SESSION['initiated']       - Tracks whether session ID was regenerated
# $_SESSION['user_id']         - User login identifier (used by require_login)

# function require_login()     - Redirects to login.php if user is not authenticated
?>
