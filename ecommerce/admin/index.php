<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_admin_login();

$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_orders   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_users    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_revenue  = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE payment_status = 'paid'")->fetchColumn() ?: 0.00;
?>
<!DOCTYPE html>
<html lang="tl">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-dark text-white p-3 min-vh-100" style="width: 260px;">
        <h4 class="text-warning text-center fw-bold py-2">ADMIN PANEL</h4>
        <hr class="border-secondary">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-1"><a href="index.php" class="nav-link active"><i class="fa-solid fa-gauge me-2"></i>Dashboard</a></li>
            <li class="nav-item mb-1"><a href="products.php" class="nav-link text-white"><i class="fa-solid fa-shirt me-2"></i>Produtu</a></li>
            <li class="nav-item mb-1"><a href="categories.php" class="nav-link text-white"><i class="fa-solid fa-list me-2"></i>Kategoria</a></li>
            <li class="nav-item mb-1"><a href="orders.php" class="nav-link text-white"><i class="fa-solid fa-cart-shopping me-2"></i>Pedidu / Orders</a></li>
            <li class="nav-item mb-1"><a href="users.php" class="nav-link text-white"><i class="fa-solid fa-users me-2"></i>Usuáriu</a></li>
            <li class="nav-item mt-4"><a href="../logout.php" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i>Sai / Logout</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="flex-fill p-4 bg-light">
        <h2>Dashboard Overview</h2>
        <div class="row g-3 my-3">
            <div class="col-md-3">
                <div class="card bg-primary text-white p-3 shadow-sm border-0">
                    <h6>Total Produtu</h6>
                    <h3><?= $total_products ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white p-3 shadow-sm border-0">
                    <h6>Total Pedidu</h6>
                    <h3><?= $total_orders ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark p-3 shadow-sm border-0">
                    <h6>Total Usuáriu</h6>
                    <h3><?= $total_users ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white p-3 shadow-sm border-0">
                    <h6>Receita / Revenue</h6>
                    <h3>$<?= number_format($total_revenue, 2) ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>