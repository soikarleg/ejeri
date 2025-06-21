<?php // views/dashboard.php 
include PROJECT_ROOT . '/cli/views/partials/header.php';

$compte_id = $_SESSION['client_id'];
?>
<div class="container py-5 mt-4">
    <div class="row mt-4">
        <div class="col-md-3">
            <?php
            include PROJECT_ROOT . '/cli/views/partials/sidemenu.php'; ?>
        </div>
        <div class="col-md-9">
            <div class="">
                <div class="">
                    <!-- <h2 class="mb-2">Synthèse</h2>
                    <p class="">Présentation de l'activité ici Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum cupiditate esse tempora reiciendis asperiores ullam animi explicabo iusto accusamus harum!</p> -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card ">
                                <div class="card-header compte">
                                    <p class="text-warning">Interventions</p>
                                </div>
                                <div class="card-body compte">

                                    <p>Nombre d'interventions : <?= $nombre_interventions ?></p>
                                    <p>Montant total : <?= $montant_total_interventions ?> €</p>
                                    <a href="#" class="card-link">Card link</a>
                                    <a href="#" class="card-link">Another link</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card ">
                                <div class="card-header compte">
                                    <p class="text-warning">Facturations</p>
                                </div>
                                <div class="card-body compte">
                                    <p>Nombre de facturations : <?= $nombre_facturations ?></p>
                                    <p>Montant total : <?= $montant_total_facturations ?> €</p>
                                    <a href="#" class="card-link">Card link</a>
                                    <a href="#" class="card-link">Another link</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card ">
                                <div class="card-header compte">
                                    <p class="text-warning">Paiements</p>
                                </div>
                                <div class="card-body compte">
                                    <p>Nombre de paiements : <?= $nombre_paiements ?></p>
                                    <p>Montant total : <?= $montant_total_paiements ?> €</p>
                                    <a href="#" class="card-link">Card link</a>
                                    <a href="#" class="card-link">Another link</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    include __DIR__ . '/partials/footer.php';
                    ?>