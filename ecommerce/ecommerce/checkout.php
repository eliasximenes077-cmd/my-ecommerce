<?php
require_once 'includes/header.php';
require_user_login();

if (empty($_SESSION['cart'])) {
    header("Location: shop.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Calculate total
$ids = implode(',', array_keys($_SESSION['cart']));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
$cart_items = $stmt->fetchAll();

$total_amount = 0;
foreach ($cart_items as $item) {
     $total_amount += $item['price'] * $_SESSION['cart'][$item['id']];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = sanitize($_POST['address'] ?? '');
    $payment = sanitize($_POST['payment_method'] ?? 'cod');
    $order_num = 'ORD-' . strtoupper(bin2hex(random_bytes(4)));

    $pdo->beginTransaction();
    try {
        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, order_number, total_amount, payment_method, shipping_address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $order_num, $total_amount, $payment, $address]);
        $order_id = $pdo->lastInsertId();

        // Insert order items & Update stock
        foreach ($cart_items as $item) {
            $qty = $_SESSION['cart'][$item['id']];
            $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt_item->execute([$order_id, $item['id'], $qty, $item['price']]);

            // Deduct Stock
            $stmt_stock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $stmt_stock->execute([$qty, $item['id']]);
        }

        $pdo->commit();
        unset($_SESSION['cart']);
        echo "<div class='container'><div class='alert alert-success'>Susesu! Ita-nia Pedidu ida ne'e procesadu ho Nomeru: <strong>$order_num</strong></div></div>";
        require_once 'includes/footer.php';
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Erru prosesu checkout: " . $e->getMessage();
    }
}
?>

<div class="container">
    <h3 class="fw-bold mb-4">Checkout Process</h3>
    <?php if (isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4">
                <form method="POST">
                    <h5 class="fw-bold mb-3">Enderesu Envio</h5>
                    <div class="mb-3">
                        <textarea name="address" class="form-control" rows="3" placeholder="Hatama enderesu kompletu..." required></textarea>
                    </div>
                    <h5 class="fw-bold mb-3">Metodu Pagamentu</h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" checked>
                        <label class="form-check-label" for="cod">Cash on Delivery (Selu wainhira simu sasán)</label>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="radio" name="payment_method" value="bank_transfer" id="bank">
                        <label class="form-check-label" for="bank">Transferénsia Bankária</label>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 btn-lg">Konfirma & Selu</button>
                </form>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-3">Resumu Pedidu</h5>
                <ul class="list-group list-group-flush mb-3">
                    <?php foreach ($cart_items as $item): 
                        $qty = $_SESSION['cart'][$item['id']];
                    ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <?= sanitize($item['name']) ?> (x<?= $qty ?>)
                            <span>$<?= number_format($item['price'] * $qty, 2) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total:</span>
                    <span class="text-danger">$<?= number_format($total_amount, 2) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>