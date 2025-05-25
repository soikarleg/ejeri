<?php
// Vue Bootstrap 5 pour la liste des événements (interventions)
// Fichier : cli/views/events.php
?>

<?php include 'partials/header.php'; ?>
<div class="container mt-4">
    <h2>Liste des interventions</h2>
    <a href="event_create.php" class="btn btn-primary mb-3">Nouvelle intervention</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Intervenant</th>
                <th>Client</th>
                <th>Adresse</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Heure début</th>
                <th>Heure fin</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['id']) ?></td>
                    <td><?= htmlspecialchars($event['intervenant_id']) ?></td>
                    <td><?= htmlspecialchars($event['client_id']) ?></td>
                    <td><?= htmlspecialchars($event['adresse_id']) ?></td>
                    <td><?= htmlspecialchars($event['date_debut']) ?></td>
                    <td><?= htmlspecialchars($event['date_fin']) ?></td>
                    <td><?= htmlspecialchars($event['heure_debut']) ?></td>
                    <td><?= htmlspecialchars($event['heure_fin']) ?></td>
                    <td><?= htmlspecialchars($event['description']) ?></td>
                    <td>
                        <a href="event_edit.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-warning">Éditer</a>
                        <a href="event_delete.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette intervention ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'partials/footer.php'; ?>