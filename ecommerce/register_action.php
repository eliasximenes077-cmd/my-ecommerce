<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = "Token seguransa invalido!";
        header("Location: ../register.php");
        exit();
    }

    $full_name = sanitize($_POST['full_name'] ?? '');
    $email     = sanitize($_POST['email'] ?? '');
    $phone     = sanitize($_POST['phone'] ?? '');
    $password  = $_POST['password'] ?? '';

    // Cek se email registadu tiha ona
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Email ne'e uzadu tiha ona!";
        header("Location: ../register.php");
        exit();
    }

    // Password Hash
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$full_name, $email, $phone, $hashed_password])) {
        $_SESSION['success'] = "Konta kria ho susesu! Favór entra tiha.";
        header("Location: ../login.php");
        exit();
    } else {
        $_SESSION['error'] = "Iha erru ruma ho sistema!";
        header("Location: ../register.php");
        exit();
    }
}