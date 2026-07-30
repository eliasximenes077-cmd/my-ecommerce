<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = sanitize($_POST['name']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

    $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
    $stmt->execute([$name, $slug]);
    header("Location: categories.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: categories.php");
    exit();
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tl">
<head>
    <title>Jestaun Kategoria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container">
        <a href="index.php" class="btn btn-secondary mb-3">← Fali ba Dashboard</a>
        <h2 class="fw-bold mb-4">Gestão de Categorias</h2>

        <div class="card p-4 shadow-sm mb-4 border-0">
            <form method="POST">
                <div class="input-group">
                    <input type="text" name="name" class="form-control" placeholder="Naran Kategoria Foun..." required>
                    <button type="submit" name="add_category" class="btn btn-primary">Aumenta</button>
                </div>
            </form>
        </div>

        <table class="table bg-white rounded shadow-sm">
            <thead>
                <tr><th>ID</th><th>Naran Kategoria</th><th>Slug</th><th>Accao</th></tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><?= sanitize($cat['name']) ?></td>
                    <td><?= sanitize($cat['slug']) ?></td>
                    <td><a href="categories.php?delete=<?= $cat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Serteza?')">Hamoos</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>