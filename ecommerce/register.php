<?php require_once 'includes/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">Regista Konta Foun</h3>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= sanitize($_SESSION['error']); unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <form action="auth/register_action.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                        <div class="mb-3">
                            <label class="form-label">Naran Kompletu</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nuremu Telefone</label>
                            <input type="text" name="phone" class="form-control" placeholder="+670..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" minlength="6" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Kria Konta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>