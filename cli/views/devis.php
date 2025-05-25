<?php // views/dashboard.php 
require_once __DIR__ . '/../../shared/helpers/function.php';
include __DIR__ . '/partials/header.php'; ?>

<div class="container py-5 mt-4">
    <div class="row mt-4">
        <div class="col-md-3">
            <?php include __DIR__ . '/partials/sidemenu.php'; ?>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header compte">
                    <p class="text-warning">Devis</p>
                </div>

                <div class="card-body compte">
                    <table class="table compte table-hover">
                        <thead>
                            <tr class="compte">
                                <th>Numéro</th>
                                <th>Date</th>
                                <th></th>

                                <th class="text-right">Montant TTC</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($devis)): ?>
                                <tr class="compte">
                                    <td colspan="5" class="text-center compte">Aucun devis trouvé.</td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($devis as $d): ?>
                                <tr class="compte">
                                    <td><?= htmlspecialchars($d['numero']) ?></td>
                                    <td><?= htmlspecialchars(AffDate($d['date_emission'])) ?></td>
                                    <td> <?php
                                            switch ($d['statut']) {
                                                case 'attente':
                                                    $badge = '<span class="badge bg-primary">En attente</span>';
                                                    break;
                                                case 'valide':
                                                    $badge = '<span class="badge bg-success">Validé</span>';
                                                    break;
                                                case 'refus':
                                                    $badge = '<span class="badge bg-danger">Refusé</span>';
                                                    break;
                                                case 'brouillon':
                                                default:
                                                    $badge = '<span class="badge bg-secondary">Brouillon</span>';
                                                    break;
                                            }
                                            echo $badge;
                                            ?></td>
                                    <td class="text-right"><?= htmlspecialchars($d['montant_ttc']) ?> €</td>
                                    <td class="text-right"> <a target="_blank" href="/devis_pdf?numero=<?= htmlspecialchars($d['numero']) ?>">📁</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="/devis_nouveau" class="small">Demandez un nouveau devis</a>
                </div>

            </div>

        </div>
    </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>