<?php
require_once 'includes/header.php';

$search = sanitize($_GET['q'] ?? '');
$cat_id = sanitize($_GET['category'] ?? '');

$query = "SELECT p.*, pi.image_url FROM products p 
          LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1 
          WHERE p.status = 'active'";
$params = [];

if ($search !== '') {
    $query .= " AND p.name LIKE ?";
    $params[] = "%$search%";
}

if ($cat_id !== '') {
    $query .= " AND p.category_id = ?";
    $params[] = $cat_id;
}

$query .= " ORDER BY p.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Fetch Categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="container">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Kategoria</h5>
                    <ul class="list-group list-group-flush">
                        <a href="shop.php" class="list-group-item list-group-item-action <?= empty($cat_id) ? 'active' : '' ?>">Hotu-Hotu</a>
                        <?php foreach($categories as $cat): ?>
                            <a href="shop.php?category=<?= $cat['id'] ?>" class="list-group-item list-group-item-action <?= $cat_id == $cat['id'] ? 'active' : '' ?>">
                                <?= sanitize($cat['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Product List -->
        <div class="col-md-9">
            <h3 class="fw-bold mb-4">Lista Roupa</h3>
            <div class="row g-4">
                <?php if (empty($products)): ?>
                    <div class="col-12"><p class="alert alert-info">La hetan produtu ne'ebé parese ho fasilidade ne'e.</p></div>
                <?php endif; ?>
                <?php foreach ($products as $p): ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="card h-100 product-card shadow-sm">
                            <img src="<?= $p['image_url'] ? sanitize($p['image_url']) : 'https://via.placeholder.com/300x300?text=No+Image' ?>" class="card-img-top" alt="<?= sanitize($p['name']) ?>">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title text-truncate"><?= sanitize($p['name']) ?></h6>
                                <p class="fw-bold text-danger mb-3">$<?= number_format($p['price'], 2) ?></p>
                                <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-outline-dark mt-auto w-100">Detailu</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>