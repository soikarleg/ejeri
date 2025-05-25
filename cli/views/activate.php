<?php
// Vue d’activation de compte client Enooki (module cli)
// Variables attendues : $success (bool), $message (string)
require __DIR__ . '/partials/header.php';
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-4 mt-4">
            <div class="">
                <div class="text-center">
                    <?php if ($success): ?>
                        <h3 class="text-success mb-3"><i class="bi bi-check-circle"></i> Activation réussie</h3>
                    <?php else: ?>
                        <h3 class="text-danger mb-3"><i class="bi bi-x-circle"></i> Activation impossible</h3>
                    <?php endif; ?>
                    <p class="mb-4"><?= htmlspecialchars($message) ?></p>
                    <a href="login" class="devis-btn">Se connecter</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>