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
                    <h2 class="mb-4">Factures</h2>
                    <p class="lead">Accédez à vos devis, interventions, factures et documents en toute simplicité.</p>
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="/devis" class="devis-btn"><i class="bi bi-file-earmark-text"></i> Mes devis</a>
                        <a href="/interventions" class="devis-btn"><i class="bi bi-calendar-check"></i> Mes interventions</a>
                        <a href="/factures" class="devis-btn"><i class="bi bi-receipt"></i> Mes factures</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>