<?php 
require_once 'includes/header.php'; 

// Foti Produtu Destaque 8 husi Database
$stmt = $pdo->prepare("SELECT p.*, pi.image_url FROM products p 
                        LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1 
                        WHERE p.status = 'active' ORDER BY p.id DESC LIMIT 8");
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!-- Hero Banner -->
<div class="hero-banner text-center mb-5">
    <div class="container">
        <h1 class="display-4 fw-bold">Moda Foun & Elegante <?= date('Y') ?></h1>
        <p class="lead">Buka roupa kualidade aas ho folin ne'ebé rasoavel liu iha Timor-Leste.</p>
        <a href="shop.php" class="btn btn-warning btn-lg fw-bold px-4">Sosa Agora</a>
    </div>
</div>

<!-- Featured Products -->
<div class="container my-5">
    <h2 class="fw-bold mb-4">Produtu Foun Hotu</h2>
    <div class="row g-4">
        <?php if (empty($products)): ?>
            <div class="col-12"><p class="text-muted">Seidauk iha produtu ne'ebé tau iha ne'e.</p></div>
        <?php endif; ?>
        <?php foreach ($products as $p): ?>
            <div class="col-md-3 col-sm-6">
                <div class="card h-100 product-card shadow-sm">
                    <img src="<?= $p['image_url'] ? sanitize($p['image_url']) : 'https://via.placeholder.com/300x300?text=No+Image' ?>" class="card-img-top" alt="<?= sanitize($p['name']) ?>">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title text-truncate"><?= sanitize($p['name']) ?></h6>
                        <p class="fw-bold text-danger mb-3">$<?= number_format($p['price'], 2) ?></p>
                        <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-outline-dark mt-auto w-100">Haree Detailu</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>