<?php
// Vue Bootstrap 5 pour la liste des paiements
// Fichier : cli/views/paiements.php
?>

<?php include 'partials/header.php'; ?>
<div class="container mt-4">
    <h2>Liste des paiements</h2>
    <a href="paiement_create.php" class="btn btn-primary mb-3">Nouveau paiement</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Facture</th>
                <th>Montant</th>
                <th>Date</th>
                <th>Mode</th>
                <th>Référence</th>
                <th>Commentaire</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($paiements as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id']) ?></td>
                    <td><?= htmlspecialchars($p['facture_id']) ?></td>
                    <td><?= htmlspecialchars($p['montant']) ?></td>
                    <td><?= htmlspecialchars($p['date_paiement']) ?></td>
                    <td><?= htmlspecialchars($p['mode']) ?></td>
                    <td><?= htmlspecialchars($p['reference']) ?></td>
                    <td><?= htmlspecialchars($p['commentaire']) ?></td>
                    <td>
                        <a href="paiement_edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Éditer</a>
                        <a href="paiement_delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce paiement ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'partials/footer.php'; ?>