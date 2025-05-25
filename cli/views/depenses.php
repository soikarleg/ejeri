<?php
// Vue Bootstrap 5 pour la liste des dépenses
// Fichier : cli/views/depenses.php
?>

<?php include 'partials/header.php'; ?>
<div class="container mt-4">
    <h2>Liste des dépenses</h2>
    <a href="depense_create.php" class="btn btn-primary mb-3">Nouvelle dépense</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Montant</th>
                <th>Catégorie</th>
                <th>Description</th>
                <th>Commentaire</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($depenses as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['id']) ?></td>
                    <td><?= htmlspecialchars($d['date_depense']) ?></td>
                    <td><?= htmlspecialchars($d['montant']) ?></td>
                    <td><?= htmlspecialchars($d['categorie']) ?></td>
                    <td><?= htmlspecialchars($d['description']) ?></td>
                    <td><?= htmlspecialchars($d['commentaire']) ?></td>
                    <td>
                        <a href="depense_edit.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-warning">Éditer</a>
                        <a href="depense_delete.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette dépense ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'partials/footer.php'; ?>