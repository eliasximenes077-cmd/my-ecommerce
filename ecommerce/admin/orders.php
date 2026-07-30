<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_admin_login();

// Update Order Status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status   = sanitize($_POST['order_status']);
    $payment  = sanitize($_POST['payment_status']);

    $stmt = $pdo->prepare("UPDATE orders SET order_status = ?, payment_status = ? WHERE id = ?");
    $stmt->execute([$status, $payment, $order_id]);
    header("Location: orders.php");
    exit();
}

$orders = $pdo->query("SELECT o.*, u.full_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tl">
<head>
    <title>Jestaun Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container">
        <a href="index.php" class="btn btn-secondary mb-3">← Fali ba Dashboard</a>
        <h2 class="fw-bold mb-4">Gestão de Pedidos (Orders)</h2>

        <table class="table bg-white rounded shadow-sm align-middle">
            <thead>
                <tr>
                    <th>Order #</th><th>Kliente</th><th>Total</th><th>Status Pagamentu</th><th>Status Envio</th><th>Atualiza</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td><strong><?= $o['order_number'] ?></strong></td>
                    <td><?= sanitize($o['full_name']) ?></td>
                    <td>$<?= number_format($o['total_amount'], 2) ?></td>
                    <td>
                        <span class="badge bg-<?= $o['payment_status'] === 'paid' ? 'success' : 'danger' ?>">
                            <?= strtoupper($o['payment_status']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-info text-dark"><?= strtoupper($o['order_status']) ?></span>
                    </td>
                    <td>
                        <form method="POST" class="d-flex gap-2">
                            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                            <select name="payment_status" class="form-select form-select-sm">
                                <option value="unpaid" <?= $o['payment_status'] == 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                <option value="paid" <?= $o['payment_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                            </select>
                            <select name="order_status" class="form-select form-select-sm">
                                <option value="pending" <?= $o['order_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="processing" <?= $o['order_status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= $o['order_status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $o['order_status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-sm btn-primary">Rai</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>