<?php // views/register.php 
include __DIR__ . '/partials/header.php';

?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="contact">
                <h2 class="mb-4 text-center text-enooki">Créer un compte client</h2>

                <form method="post" action="?action=register-ajax" class="php-email-form">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
                    <!-- <div class="mb-3">
                        <input type="text" placeholder="Nom complet" class="form-control" id="fullname" name="fullname" required>
                    </div> -->
                    <div class="mb-3">
                        <input type="email" placeholder="Email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" placeholder="Mot de passe" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" placeholder="Confirmer le mot de passe" class="form-control" id="password_confirm" name="password_confirm" required>
                    </div>

                    <div class="text-center form-check mb-3 text-start">

                        <label class="form-check-label small" for="accept_cgv"><input class="form-check-input" type="checkbox" value="1" id="accept_cgv" name="accept_cgv" required>
                            J'ai pris connaissance et j'accepte les <a href="/cgv" target="_blank">Conditions Générales de Vente</a>
                        </label>
                    </div>
                    <div class="loading" style="display:none"></div>
                    <div class="error-message" style="display:<?php echo !empty($error) ? 'block' : 'none'; ?>;">
                        <?php if (!empty($error)) echo htmlspecialchars($error); ?>
                    </div>
                    <div class="sent-message" style="display:none">Votre compte a été créé. Vérifiez votre email pour activer votre accès.</div>

                    <div class="text-center">
                        <button type="submit" class="btn-enooki" disabled="disabled">Créer votre compte</button>
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
    // Validation directe du formulaire d'inscription (sans feedback d'erreur avant interaction)
    (function() {
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');
        const cgv = document.getElementById('accept_cgv');
        const form = document.querySelector('.php-email-form');
        const errorDiv = form.querySelector('.error-message');
        const submitBtn = form.querySelector('button[type="submit"]');

        let touched = {
            email: false,
            password: false,
            passwordConfirm: false,
            cgv: false
        };

        function validateEmail(value) {
            return /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/.test(value);
        }

        function validatePassword(value) {
            return value.length >= 8;
        }

        function validatePasswordMatch() {
            return password.value === passwordConfirm.value && password.value.length > 0;
        }

        function validateCGV() {
            return cgv.checked;
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
            let validEmail = validateEmail(email.value);
            let validPassword = validatePassword(password.value);
            let validPasswordMatch = validatePasswordMatch();
            let validCGV = validateCGV();
            let valid = validEmail && validPassword && validPasswordMatch && validCGV;

            // Feedback visuel Bootstrap
            email.classList.toggle('is-invalid', touched.email && !validEmail);
            email.classList.toggle('is-valid', touched.email && validEmail);
            password.classList.toggle('is-invalid', touched.password && !validPassword);
            password.classList.toggle('is-valid', touched.password && validPassword);
            passwordConfirm.classList.toggle('is-invalid', touched.passwordConfirm && !validPasswordMatch);
            passwordConfirm.classList.toggle('is-valid', touched.passwordConfirm && validPasswordMatch);
            cgv.classList.toggle('is-invalid', touched.cgv && !validCGV);

            // Gestion des messages d'erreur
            if (!validEmail && touched.email) {
                showError('Veuillez saisir une adresse email valide.');
            } else if (!validPassword && touched.password) {
                showError('Le mot de passe doit contenir au moins 8 caractères.');
            } else if (!validPasswordMatch && touched.passwordConfirm) {
                showError('Les mots de passe ne correspondent pas.');
            } else if (!validCGV && touched.cgv) {
                showError('Vous devez accepter les Conditions Générales de Vente.');
            } else {
                clearError();
            }
            submitBtn.disabled = !valid;
        }

        // Gestion des événements
        email.addEventListener('input', function() {
            touched.email = true;
            updateState();
        });
        password.addEventListener('input', function() {
            touched.password = true;
            updateState();
        });
        passwordConfirm.addEventListener('input', function() {
            touched.passwordConfirm = true;
            updateState();
        });
        cgv.addEventListener('change', function() {
            touched.cgv = true;
            updateState();
        });

        // Initialisation
        updateState();
    })();
</script>