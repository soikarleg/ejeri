<div class="sidemenu">
    <div>
        <p>N° client : <?= htmlspecialchars($_SESSION['client_id']) ?></p>
        <?php
        $prenom = isset($prenom) && is_string($prenom) ? trim($prenom) : '';
        $nom = isset($nom) && is_string($nom) ? trim($nom) : '';
        ?>
        <?php if (!empty($prenom) && !empty($nom)) : ?>
            <p>
                <strong><?= htmlspecialchars($prenom) . ' ' . htmlspecialchars($nom) ?></strong>
                <br>
                <a href="/modifier_contact?action=mod_nom&id=<?= htmlspecialchars($_SESSION['client_id']) ?>" class="small">Modifier</a>
            </p>

        <?php else : ?>
            <p>Aucun nom et prénom<br>
                <a href="/modifier_contact?action=ajout_nom" class="small">Ajouter votre nom</a>
            </p>
        <?php endif; ?>
        <?php if (!empty($adresses)) : ?>
            <?php foreach ($adresses as $adresse) : ?>
                <?php if (isset($adresse['ligne1'], $adresse['cp'], $adresse['ville'], $adresse['pays'])) : ?>
                    <p>
                        <strong> <?= htmlspecialchars(ucfirst($adresse['type'])) ?></strong><br>
                        <?= htmlspecialchars($adresse['ligne1']) ?><br>
                        <?= htmlspecialchars($adresse['cp']) ?> <?= htmlspecialchars($adresse['ville']) ?><br>
                        <?= htmlspecialchars($adresse['pays']) ?><br>
                        <a href="/modifier_contact?action=mod_adresse&id=<?= htmlspecialchars($adresse['id']) ?>" class="small">Modifier</a>
                    </p>
                <?php else : ?>
                    <p>Adresse invalide.</p>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <p> </p>Aucune adresse enregistrée.<br>
        <?php endif; ?>
        <a href="/modifier_contact?action=ajout_adresse&id=<?= htmlspecialchars($_SESSION['client_id']) ?>" class="small">Ajouter une adresse</a><br>
        <?php if (!empty($telephones)) : ?>
            <?php foreach ($telephones as $tel) : ?>
                <p><?= isset($tel['numero']) ? htmlspecialchars($tel['numero']) : 'Numéro non disponible' ?><br></p>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="mt-4">Aucun téléphone enregistré.<br>
                <a href="/modifier_contact?action=ajout_telephone&id=<?= htmlspecialchars($_SESSION['client_id']) ?>" class="small">Ajouter votre numéro de téléphone</a>
            </p>
        <?php endif; ?>
        <p class="mt-4"><a href="/pre_logout" class="text-warning mt-4">Déconnexion</a></p> 
        
    </div>
</div>