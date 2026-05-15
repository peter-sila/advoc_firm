<?php
session_start();

// Change these credentials to secure values before deployment.
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'Admin@2026');

function is_admin_logged_in() {
    return !empty($_SESSION['admin_logged_in']);
}

function require_admin() {
    if (!is_admin_logged_in()) {
        header('Location: /admin_login.php');
        exit;
    }
}

function login_admin($username, $password) {
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = ADMIN_USERNAME;
        return true;
    }
    return false;
}

function logout_admin() {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}
