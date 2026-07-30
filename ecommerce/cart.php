<?php
require_once 'includes/header.php';

// Cart Actions (Update / Remove)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_id'])) {
        unset($_SESSION['cart'][$_POST['remove_id']]);
    }
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['qty'] as $p_id => $q) {
            if ($q <= 0) {
                unset($_SESSION['cart'][$p_id]);
            } else {
                $_SESSION['cart'][$p_id] = (int)$q;
            }
        }
    }
}

$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $cart_items = $stmt->fetchAll();
}
?>

<div class="container">
    <h3 class="fw-bold mb-4"><i class="fa-solid fa-cart-shopping me-2"></i>Karrinho Kompras</h3>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-warning">Ita-nia karrinho mamuk hela. <a href="shop.php">Buka roupa iha ne'e</a>.</div>
    <?php else: ?>
        <form method="POST">
            <div class="table-responsive">
                <table class="table align-middle bg-white rounded shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Produtu</th>
                            <th>Folin</th>
                            <th>Kuantidade</th>
                            <th>Subtotal</th>
                            <th>Accao</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): 
                            $qty = $_SESSION['cart'][$item['id']];
                            $subtotal = $item['price'] * $qty;
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td><?= sanitize($item['name']) ?></td>
                                <td>$<?= number_format($item['price'], 2) ?></td>
                                <td style="width: 120px;">
                                    <input type="number" name="qty[<?= $item['id'] ?>]" class="form-control" value="<?= $qty ?>" min="1">
                                </td>
                                <td class="fw-bold">$<?= number_format($subtotal, 2) ?></td>
                                <td>
                                    <button type="submit" name="remove_id" value="<?= $item['id'] ?>" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <button type="submit" name="update_cart" class="btn btn-secondary">Atualiza Karrinho</button>
                <div class="text-end">
                    <h4>Total: <span class="text-danger fw-bold">$<?= number_format($total, 2) ?></span></h4>
                    <a href="checkout.php" class="btn btn-warning btn-lg mt-2">Continuar ba Checkout</a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>