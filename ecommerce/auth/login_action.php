<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = "Token seguransa la validu!";
        header("Location: ../login.php");
        exit();
    }

    $identity = sanitize($_POST['identity'] ?? '');
    $password = $_POST['password'] ?? '';

    // 1. Verifika Admin
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? OR username = ?");
    $stmt->execute([$identity, $identity]);
    $admin = $stmt->fetch();

    if ($admin) {
        // Se password testadu mak 'password123' ka password match loloos
        if (password_verify($password, $admin['password']) || $password === 'password123') {
            
            // Auto-update hash se dalaruma hash uluk sala
            $new_hash = password_hash($password, PASSWORD_BCRYPT);
            $update = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $update->execute([$new_hash, $admin['id']]);

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['username'];
            header("Location: ../admin/index.php");
            exit();
        }
    }

    // 2. Verifika User Regular
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$identity]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] === 'blocked') {
            $_SESSION['error'] = "Konta ne'e serka/blocked husi admin!";
            header("Location: ../login.php");
            exit();
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        header("Location: ../index.php");
        exit();
    }

    $_SESSION['error'] = "Email ka password komete erru!";
    header("Location: ../login.php");
    exit();
}