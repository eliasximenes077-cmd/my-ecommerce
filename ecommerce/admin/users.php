<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_admin_login();

// Toggle Block status
if (isset($_GET['toggle_status'])) {
    $user_id = (int)$_GET['toggle_status'];
    $stmt = $pdo->prepare("UPDATE users SET status = IF(status='active', 'blocked', 'active') WHERE id = ?");
    $stmt->execute([$user_id]);
    header("Location: users.php");
    exit();
}

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tl">
<head>
    <title>Jestaun Usuáriu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container">
        <a href="index.php" class="btn btn-secondary mb-3">← Fali ba Dashboard</a>
        <h2 class="fw-bold mb-4">Gestão de Usuários</h2>

        <table class="table bg-white rounded shadow-sm align-middle">
            <thead>
                <tr>
                    <th>ID</th><th>Naran</th><th>Email</th><th>Telefone</th><th>Status</th><th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= sanitize($u['full_name']) ?></td>
                    <td><?= sanitize($u['email']) ?></td>
                    <td><?= sanitize($u['phone']) ?></td>
                    <td>
                        <span class="badge bg-<?= $u['status'] === 'active' ? 'success' : 'danger' ?>">
                            <?= strtoupper($u['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="users.php?toggle_status=<?= $u['id'] ?>" class="btn btn-sm btn-<?= $u['status'] === 'active' ? 'warning' : 'success' ?>">
                            <?= $u['status'] === 'active' ? 'Block' : 'Unblock' ?>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>