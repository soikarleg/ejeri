<div class="sidemenu">
    <div>
        <p>N° client : <?= htmlspecialchars($_SESSION['client_id']) ?></p>
        <?php
        $prenom = isset($prenom) && is_string($prenom) ? trim($prenom) : '';
        $nom = isset($nom) && is_string($nom) ? trim($nom) : '';
        ?>
        <?php if (!empty($prenom) || !empty($nom)) : ?>
            <strong><?= htmlspecialchars($prenom) . ' ' . htmlspecialchars($nom) ?></strong><br>
        <?php else : ?>
            <p>Aucun nom et prénom<br>
                <a href="/modifier_contact" class="">Ajouter votre nom</a>
            </p>
        <?php endif; ?>

        <?php if (!empty($adresses)) : ?>
            <?php foreach ($adresses as $adresse) : ?>
                <?php if (isset($adresse['ligne1'], $adresse['cp'], $adresse['ville'], $adresse['pays'])) : ?>
                    <p>
                        <?= htmlspecialchars($adresse['ligne1']) ?><br>
                        <?= htmlspecialchars($adresse['cp']) ?> <?= htmlspecialchars($adresse['ville']) ?><br>
                        <?= htmlspecialchars($adresse['pays']) ?><br>
                    </p>
                <?php else : ?>
                    <p>Adresse invalide.</p>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Aucune adresse enregistrée.<br>
                <a href="/modifier_contact" class="">Ajouter une adresse</a>
            </p>
        <?php endif; ?>

        <?php if (!empty($telephones)) : ?>
            <?php foreach ($telephones as $tel) : ?>
                <p><?= isset($tel['numero']) ? htmlspecialchars($tel['numero']) : 'Numéro non disponible' ?><br></p>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Aucun téléphone enregistré.<br>
                <a href="/modifier_contact" class="">Ajouter votre numéro de téléphone</a>
            </p>
        <?php endif; ?>

        <a href="/logout" class="text-danger">Déconnexion</a>
    </div>
</div>