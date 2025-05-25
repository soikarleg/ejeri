<?php
include __DIR__ . '/partials/header.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="contact">
                <h2 class="mb-4 text-center text-enooki">Mot de passe oublié</h2>
                <form method="post" action="?action=forgot-password-ajax" class="php-email-form">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
                    <div class="mb-3">
                        <input type="email" placeholder="Votre email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="loading" style="display:none"></div>
                    <div class="error-message" style="display:none"></div>
                    <div class="sent-message" style="display:none">Un email de réinitialisation a été envoyé si l'adresse existe.</div>
                   
                    <div class="text-center">
                        <button type="submit" class="btn-enooki">Réinitialiser le mot de passe</button>
                    </div> <div class="text-center mt-4">
                        <a href="/login" class="link-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>