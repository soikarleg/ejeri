<?php
// Vue pour la saisie du nouveau mot de passe après clic sur le lien reçu par email
include __DIR__ . '/partials/header.php';
$token = $_GET['token'] ?? '';
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="contact">
                <h2 class="mb-4 text-center text-enooki">Nouveau mot de passe</h2>
                <form method="post" action="?action=new-password-ajax" class="php-email-form">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <div class="mb-3">
                        <input type="password" placeholder="Nouveau mot de passe" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" placeholder="Confirmer le mot de passe" class="form-control" id="password_confirm" name="password_confirm" required>
                    </div>
                    <div class="loading" style="display:none"></div>
                    <div class="error-message" style="display:none"></div>
                    <div class="sent-message" style="display:none">Votre mot de passe a été réinitialisé avec succès.</div>
                    <div class="text-center">
                        <button type="submit" class="btn-enooki">Valider</button>
                    </div>
                    <div class="text-center mt-4">
                        <a href="/login" class="link-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
<script>
    // Validation simple côté client (exemple)
    (function() {
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');
        const form = document.querySelector('.php-email-form');
        const errorDiv = form.querySelector('.error-message');
        const submitBtn = form.querySelector('button[type="submit"]');

        function validatePassword(value) {
            return value.length >= 8;
        }

        function validatePasswordMatch() {
            return password.value === passwordConfirm.value && password.value.length > 0;
        }

        function showError(msg) {
            errorDiv.style.display = 'block';
            errorDiv.textContent = msg;
        }

        function clearError() {
            errorDiv.style.display = 'none';
            errorDiv.textContent = '';
        }

        function updateState() {
            let validPassword = validatePassword(password.value);
            let validPasswordMatch = validatePasswordMatch();
            let valid = validPassword && validPasswordMatch;
            if (!validPassword && password.value.length > 0) {
                showError('Le mot de passe doit contenir au moins 8 caractères.');
            } else if (!validPasswordMatch && passwordConfirm.value.length > 0) {
                showError('Les mots de passe ne correspondent pas.');
            } else {
                clearError();
            }
            submitBtn.disabled = !valid;
        }
        password.addEventListener('input', updateState);
        passwordConfirm.addEventListener('input', updateState);
        updateState();
    })();
</script>