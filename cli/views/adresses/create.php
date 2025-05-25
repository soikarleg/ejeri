<?php
// views/adresses/create.php
require PROJECT_ROOT . '/cli/views/partials/header.php'; ?>
<div class="container mt-4">
    <h2>Ajouter une adresse</h2>
    <form method="post" action="?action=adresses_store">
        <div class="mb-3">
            <label for="ligne1" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="ligne1" name="ligne1" required>
        </div>
        <div class="mb-3">
            <label for="cp" class="form-label">Code postal</label>
            <input type="text" class="form-control" id="cp" name="cp" required>
        </div>
        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select class="form-select" id="type" name="type">
                <option value="livraison">Livraison</option>
                <option value="facturation">Facturation</option>
                <option value="autre">Autre</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="?action=adresses" class="btn btn-secondary">Annuler</a>
    </form>
</div>
<?php require PROJECT_ROOT . '/cli/views/partials/footer.php'; ?>