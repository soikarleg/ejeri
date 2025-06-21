<?php
// views/adresses/edit.php
require PROJECT_ROOT . '/cli/views/partials/header.php'; ?>
<div class="container mt-4">
    <h2>Modifier l'adresse</h2>
    <form method="post" action="?action=adresses_update">
        <input type="hidden" name="id" value="<?= htmlspecialchars($adresse['id']) ?>">
        <div class="mb-3">
            <label for="ligne1" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="ligne1" name="ligne1" value="<?= htmlspecialchars($adresse['ligne1']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="cp" class="form-label">Code postal</label>
            <input type="text" class="form-control" id="cp" name="cp" value="<?= htmlspecialchars($adresse['cp']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville" value="<?= htmlspecialchars($adresse['ville']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/dashboard" class="btn btn-secondary">Annuler</a>
    </form>
</div>
<?php require PROJECT_ROOT . '/cli/views/partials/footer.php'; ?>