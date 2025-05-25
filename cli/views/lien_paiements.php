<?php
// Vue Bootstrap 5 pour la liste des liens de paiement
// Fichier : cli/views/lien_paiements.php
?>

<?php include 'partials/header.php'; ?>
<div class="container mt-4">
    <h2>Liens de paiement</h2>
    <a href="lien_paiement_create.php" class="btn btn-primary mb-3">Nouveau lien</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Facture</th>
                <th>URL</th>
                <th>Statut</th>
                <th>Date création</th>
                <th>Date expiration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lien_paiements as $l): ?>
                <tr>
                    <td><?= htmlspecialchars($l['id']) ?></td>
                    <td><?= htmlspecialchars($l['facture_id']) ?></td>
                    <td><a href="<?= htmlspecialchars($l['url']) ?>" target="_blank">Lien</a></td>
                    <td><?= htmlspecialchars($l['statut']) ?></td>
                    <td><?= htmlspecialchars($l['date_creation']) ?></td>
                    <td><?= htmlspecialchars($l['date_expiration']) ?></td>
                    <td>
                        <a href="lien_paiement_edit.php?id=<?= $l['id'] ?>" class="btn btn-sm btn-warning">Éditer</a>
                        <a href="lien_paiement_delete.php?id=<?= $l['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce lien ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'partials/footer.php'; ?>