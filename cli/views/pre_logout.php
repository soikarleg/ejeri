<?php // views/dashboard.php 
include __DIR__ . '/partials/header.php'; ?>

<div class="container py-5 mt-4">
    <div class="row mt-4">
        <div class="col-md-3">
            <?php
            include PROJECT_ROOT . '/cli/views/partials/sidemenu.php'; ?>
        </div>
        <div class="col-md-9">
            <div class="">
                <div class="">
                    <h2 class="mb-4">Confirmation</h2>
                    <p class="">Confirmez-vous la déconnexion ?</p>
                    
                    <a href="/dashboard" class="btn btn-danger">Non, retourner au tableau de bord</a>
                    <a href="/logout" class="btn btn-success">Oui</a>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>