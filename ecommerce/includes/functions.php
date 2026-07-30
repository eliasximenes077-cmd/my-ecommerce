<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security: Sanitize output against XSS
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Generate CSRF Token
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF Token
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Check if user is logged in
function is_user_logged_in() {
    return isset($_SESSION['user_id']);
}

// Check if admin is logged in
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}

// Force login for user pages
function require_user_login() {
    if (!is_user_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

// Force login for admin pages
function require_admin_login() {
    if (!is_admin_logged_in()) {
        header("Location: ../login.php");
        exit();
    }
}
?>