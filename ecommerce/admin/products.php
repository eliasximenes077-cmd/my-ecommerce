<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_admin_login();

// Add Product Process
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name        = sanitize($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $price       = (float)$_POST['price'];
    $stock       = (int)$_POST['stock'];
    $description = sanitize($_POST['description']);
    $slug        = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

    $stmt = $pdo->prepare("INSERT INTO products (category_id, name, slug, description, price, stock) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$category_id, $name, $slug, $description, $price, $stock]);
    $product_id = $pdo->lastInsertId();

    // File Upload handling
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/products/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_filename = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_filename;
        $db_image_path = "uploads/products/" . $file_filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $stmt_img = $pdo->prepare("INSERT INTO product_images (product_id, image_url, is_primary) VALUES (?, ?, 1)");
            $stmt_img->execute([$product_id, $db_image_path]);
        }
    }
    header("Location: products.php");
    exit();
}

// Delete Product
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: products.php");
    exit();
}

$products = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tl">
<head>
    <title>Jestaun Produtu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container">
        <a href="index.php" class="btn btn-secondary mb-3">← Fali ba Dashboard</a>
        <h2 class="fw-bold mb-4">Gestão de Produtos</h2>

        <div class="card p-4 shadow-sm mb-4 border-0">
            <h4>Aumenta Produtu Foun</h4>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Naran Produtu</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Kategoria</label>
                        <select name="category_id" class="form-select" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= sanitize($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Folin ($)</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Stok</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Imajem (Primary)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Deskrisaun</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <button type="submit" name="add_product" class="btn btn-primary">Rai Produtu</button>
            </form>
        </div>

        <table class="table bg-white rounded shadow-sm align-middle">
            <thead>
                <tr>
                    <th>ID</th><th>Naran</th><th>Kategoria</th><th>Folin</th><th>Stok</th><th>Accao</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= sanitize($p['name']) ?></td>
                    <td><?= sanitize($p['category_name']) ?></td>
                    <td>$<?= number_format($p['price'], 2) ?></td>
                    <td><?= $p['stock'] ?></td>
                    <td>
                        <a href="products.php?delete=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Serteza atu hamoos?')">Hamoos</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>