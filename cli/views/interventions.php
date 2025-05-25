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
                    <h2 class="mb-4">Interventions</h2>
                    <p class="">Accédez à vos devis, interventions, factures et documents en toute simplicité.</p>
                    <a href="/interventions_demande" class="btn btn-primary">Demandez une intervention</a>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>