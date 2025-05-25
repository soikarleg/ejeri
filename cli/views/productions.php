<?php
// Vue Bootstrap 5 pour la liste des productions
// Fichier : cli/views/productions.php
?>

<?php include 'partials/header.php'; ?>
<div class="container mt-4">
    <h2>Liste des productions</h2>
    <a href="production_create.php" class="btn btn-primary mb-3">Nouvelle production</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Intervention</th>
                <th>Intervenant</th>
                <th>Heures</th>
                <th>Statut</th>
                <th>Commentaire</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productions as $prod): ?>
                <tr>
                    <td><?= htmlspecialchars($prod['id']) ?></td>
                    <td><?= htmlspecialchars($prod['event_id']) ?></td>
                    <td><?= htmlspecialchars($prod['intervenant_id']) ?></td>
                    <td><?= htmlspecialchars($prod['heures']) ?></td>
                    <td><?= htmlspecialchars($prod['statut']) ?></td>
                    <td><?= htmlspecialchars($prod['commentaire']) ?></td>
                    <td>
                        <a href="production_edit.php?id=<?= $prod['id'] ?>" class="btn btn-sm btn-warning">Éditer</a>
                        <a href="production_delete.php?id=<?= $prod['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette production ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'partials/footer.php'; ?>