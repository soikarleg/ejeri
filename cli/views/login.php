<?php // views/login.php 
include __DIR__ . '/partials/header.php'; ?>
<!-- <p class="text-danger small">Session: <?= session_id() ?> | client_id: <?= $_SESSION['client_id'] ?? 'null' ?></p> -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="contact ">
                <div class="">
                    <h2 class="mb-4 text-center text-enooki">Espace client</h2>
                    
                    <form method="post" action="?action=login-ajax" class="php-email-form" id="login-form">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
                        <div class="mb-3">
                            <input type="email" placeholder="Email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" placeholder="Mot de passe" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="loading" style="display:none"></div>
                        <div class="text-center error-message" style="display:none"></div>
                        <div class="sent-message" style="display:none">Connexion réussie.</div>
                        <div class="text-center">

                            <button type="submit" class="devis-btn">Accès à votre compte client</button>
                        </div>
                        <!-- <div class="text-center mt-4">
                            <a href="https://enooki.com" target="_parent" class="btn-enooki">Retour vers le site</a>
                        </div> -->
                    </form>

                    <div class="text-center mt-3">
                        <a href="/register" class="link-secondary me-3">Créer un compte</a>
                        <a href="/forgot-password" class="link-secondary">Mot de passe oublié&nbsp;?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>