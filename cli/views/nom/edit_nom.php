<?php // views/dashboard.php 
include PROJECT_ROOT . '/cli/views/partials/header.php';

$compte_id = $_SESSION['client_id'];
//pretty($client);
?>
<div class="container py-5 mt-4">
    <div class="row mt-4">
        <div class="col-md-3">
            <?php
            include PROJECT_ROOT . '/cli/views/partials/sidemenu.php'; ?>
        </div>
        <div class="col-md-4">
            <!-- <h2>Modifier le nom</h2> -->
            <form method="post" action="?action=nom_update">
                <input type="hidden" name="id" value="<?= htmlspecialchars($client['id']) ?>">
                <!-- <div class="input-group mb-3">
                    <span class="input-group-text l-9" id="basic-addon1">Civilité</span>
                    <input type="text" class="form-control" placeholder="Civilité" aria-label="Civilité" aria-describedby="basic-addon1" value="<?= htmlspecialchars($client['civilite']) ?>">
                </div> -->
                <div class="input-group mb-3">
                    <span class="input-group-text l-9" id="basic-addon1">Nom</span>
                    <input type="text" name="nom" class="form-control" placeholder="Nom" aria-label="Nom" aria-describedby="basic-addon1" value="<?= htmlspecialchars($client['nom']) ?>">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text l-9" id="basic-addon1">Prenom</span>
                    <input type="text" name="prenom" class="form-control" placeholder="Prenom" aria-label="Prenom" aria-describedby="basic-addon1" value="<?= htmlspecialchars($client['prenom']) ?>">
                </div>

                <a href="/dashboard" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-success">Modifier le nom</button>
<?php
if ($update_success) {
    echo '<div class="alert alert-success mt-3">Nom mis à jour avec succès.</div>';
} elseif ($update_error) {
    echo '<div class="alert alert-danger mt-3">Erreur lors de la mise à jour du nom.</div>';
}
?>
            </form>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/partials/footer.php';
?>