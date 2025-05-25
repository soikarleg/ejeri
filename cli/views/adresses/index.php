<?php
// views/adresses/index.php
require PROJECT_ROOT . '/cli/views/partials/header.php'; ?>
<div class="container mt-4">
    <h2>Mes adresses</h2>
    <a href="?action=adresses_create" class="btn btn-primary mb-3">Ajouter une adresse</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ligne 1</th>
                <th>Code postal</th>
                <th>Ville</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($adresses)): foreach ($adresses as $adresse): ?>
                    <tr>
                        <td><?= htmlspecialchars($adresse['ligne1']) ?></td>
                        <td><?= htmlspecialchars($adresse['cp']) ?></td>
                        <td><?= htmlspecialchars($adresse['ville']) ?></td>
                        <td><?= htmlspecialchars($adresse['type']) ?></td>
                        <td>
                            <a href="?action=adresses_edit&id=<?= $adresse['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                            <a href="?action=adresses_delete&id=<?= $adresse['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette adresse ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr>
                    <td colspan="5">Aucune adresse enregistrée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require PROJECT_ROOT . '/cli/views/partials/footer.php'; ?>