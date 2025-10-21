<?php
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

