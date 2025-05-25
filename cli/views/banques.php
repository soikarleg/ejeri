<?php
// Vue Bootstrap 5 pour la liste des comptes bancaires
// Fichier : cli/views/banques.php
?>

<?php include 'partials/header.php'; ?>
<div class="container mt-4">
    <h2>Comptes bancaires</h2>
    <a href="banque_create.php" class="btn btn-primary mb-3">Nouveau compte</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>IBAN</th>
                <th>BIC</th>
                <th>Titulaire</th>
                <th>Commentaire</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($banques as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['id']) ?></td>
                    <td><?= htmlspecialchars($b['nom']) ?></td>
                    <td><?= htmlspecialchars($b['iban']) ?></td>
                    <td><?= htmlspecialchars($b['bic']) ?></td>
                    <td><?= htmlspecialchars($b['titulaire']) ?></td>
                    <td><?= htmlspecialchars($b['commentaire']) ?></td>
                    <td>
                        <a href="banque_edit.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-warning">Éditer</a>
                        <a href="banque_delete.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce compte ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'partials/footer.php'; ?>