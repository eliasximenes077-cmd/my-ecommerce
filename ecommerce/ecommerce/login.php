<?php require_once 'includes/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">Login User / Admin</h3>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= sanitize($_SESSION['error']); unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= sanitize($_SESSION['success']); unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <form action="auth/login_action.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                        <div class="mb-3">
                            <label class="form-label">Email / Username</label>
                            <input type="text" name="identity" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Entra / Login</button>
                    </form>
                    <div class="text-center mt-3">
                        <small>Seidauk iha konta? <a href="register.php">Regista iha ne'e</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>