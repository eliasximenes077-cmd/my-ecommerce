<?php
require_once 'includes/header.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<div class='container'><p class='alert alert-danger'>Produtu la hetan!</p></div>";
    require_once 'includes/footer.php';
    exit();
}

// Fetch images
$stmt_img = $pdo->prepare("SELECT image_url FROM product_images WHERE product_id = ?");
$stmt_img->execute([$id]);
$images = $stmt_img->fetchAll();

// Add to cart action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $qty = (int)($_POST['quantity'] ?? 1);
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    echo "<script>window.location.href='cart.php';</script>";
    exit();
}
?>

<div class="container">
    <div class="row bg-white p-4 rounded shadow-sm">
        <div class="col-md-6">
            <img src="<?= !empty($images) ? sanitize($images[0]['image_url']) : 'https://via.placeholder.com/500x500' ?>" class="img-fluid rounded w-100" alt="Product Image">
        </div>
        <div class="col-md-6">
            <span class="badge bg-secondary mb-2"><?= sanitize($product['category_name']) ?></span>
            <h2 class="fw-bold"><?= sanitize($product['name']) ?></h2>
            <h3 class="text-danger fw-bold my-3">$<?= number_format($product['price'], 2) ?></h3>
            <p class="text-muted"><?= nl2br(sanitize($product['description'])) ?></p>
            <p>Stok: <strong><?= $product['stock'] ?> pesas</strong></p>

            <form method="POST" class="mt-4">
                <div class="mb-3" style="width: 120px;">
                    <label class="form-label">Kuantidade</label>
                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?= $product['stock'] ?>">
                </div>
                <button type="submit" name="add_to_cart" class="btn btn-warning btn-lg px-4" <?= $product['stock'] < 1 ? 'disabled' : '' ?>>
                    <i class="fa-solid fa-cart-plus me-2"></i>Aumenta ba Karrinho
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>