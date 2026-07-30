<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}
?>
<!DOCTYPE html>
<html lang="tl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Online Shop</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-warning" href="index.php"><i class="fa-solid fa-bag-shopping me-2"></i>ROUPA SHOP</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="shop.php">Shop / Katálogu</a></li>
      </ul>
      
      <form class="d-flex me-3" action="shop.php" method="GET">
        <input class="form-control me-2" type="search" name="q" placeholder="Buka roupa..." value="<?= sanitize($_GET['q'] ?? '') ?>">
        <button class="btn btn-outline-warning" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
      </form>

      <div class="d-flex align-items-center gap-3">
        <a href="cart.php" class="btn btn-outline-light position-relative">
            <i class="fa-solid fa-cart-shopping"></i>
            <?php if ($cart_count > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?= $cart_count ?>
                </span>
            <?php endif; ?>
        </a>

        <?php if (is_user_logged_in()): ?>
            <span class="text-light me-2">Olá, <?= sanitize($_SESSION['user_name']) ?></span>
            <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-sm btn-outline-light">Login</a>
            <a href="register.php" class="btn btn-sm btn-warning">Regista</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<main class="py-4 min-vh-100">