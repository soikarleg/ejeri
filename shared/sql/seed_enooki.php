<?php
// Script de seed pour la base Enooki (données fictives pour tests)
// Usage : via navigateur (protégé par mot de passe)

// --- CONFIG ---
define('SEED_PASSWORD', 'enooki2024cestsurquecavoislejouren2025'); // À personnaliser

// Protection par mot de passe
if (php_sapi_name() !== 'cli') {
    $ok = false;
    if (isset($_POST['password'])) {
        $ok = ($_POST['password'] === SEED_PASSWORD);
    } elseif (isset($_GET['password'])) {
        $ok = ($_GET['password'] === SEED_PASSWORD);
    }
    if (!$ok) {
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Seed Enooki</title></head><body style="background:#404356;color:#fff;font-family:sans-serif;text-align:center;padding:2em;">';
        echo '<h2>Seed Enooki (protégé)</h2>';
        echo '<form method="post"><input type="password" name="password" placeholder="Mot de passe" style="padding:8px;" autofocus> <button type="submit">Lancer le seed</button></form>';
        echo '<p style="color:#aaa;font-size:small;">Accès protégé</p>';
        echo '</body></html>';
        exit;
    }
}

try {
    $pdo = new PDO(
        'mysql:host=;dbname=dbs14147264;charset=utf8mb4',
        '', // À remplacer
        '',  // À remplacer
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    echo '<div style="color:red;">Erreur connexion DB : ' . htmlspecialchars($e->getMessage()) . '</div>';
    exit;
}

function randomEmail($prefix = 'user')
{
    return $prefix . rand(1000, 9999) . '@test.fr';
}
function randomPhone()
{
    return '06' . rand(100000000, 999999999);
}
function randomDate($start = '-2 years', $end = 'now')
{
    $ts = rand(strtotime($start), strtotime($end));
    return date('Y-m-d', $ts);
}

// --- Début HTML ---
echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Seed Enooki</title></head><body style="background:#404356;color:#fff;font-family:sans-serif;padding:2em;">';
echo '<h2>Seed Enooki</h2>';

try {
    // 1. Organisation (succursale)
    $pdo->exec("INSERT IGNORE INTO organisations (nom, couleur) VALUES ('Succursale Demo', '#009400')");
    $compte_id = $pdo->lastInsertId();
    if (!$compte_id) {
        $compte_id = $pdo->query("SELECT id FROM organisations WHERE nom='Succursale Demo'")->fetchColumn();
    }
    echo '<div>Organisation créée/présente, id=' . htmlspecialchars($compte_id) . '</div>';

    // 2. Users
    for ($i = 1; $i <= 5; $i++) {
        $email = randomEmail('user');
        $role = ($i === 1 ? 'admin' : ($i === 2 ? 'intervenant' : 'client'));
        $pdo->prepare("INSERT IGNORE INTO users (email, password, role, is_active, compte_id) VALUES (?, ?, ?, ?, ?)")
            ->execute([
                $email,
                password_hash('Test1234!', PASSWORD_DEFAULT),
                $role,
                1,
                $compte_id
            ]);
    }
    $users = $pdo->query("SELECT idcli FROM users WHERE compte_id=$compte_id")->fetchAll(PDO::FETCH_COLUMN);
    echo '<div>' . count($users) . ' users insérés</div>';

    // 3. Clients
    for ($i = 1; $i <= 5; $i++) {
        $email = randomEmail('client');
        $pdo->prepare("INSERT IGNORE INTO clients (idcli, compte_id, email, nom, prenom, telephone, date_creation) VALUES (?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                $users[$i - 1],
                $compte_id,
                $email,
                "NomClient$i",
                "Prenom$i",
                randomPhone(),
                date('Y-m-d H:i:s')
            ]);
    }
    $clients = $pdo->query("SELECT id FROM clients WHERE compte_id=$compte_id")->fetchAll(PDO::FETCH_COLUMN);
    echo '<div>' . count($clients) . ' clients insérés</div>';

    // 4. Intervenants
    for ($i = 1; $i <= 3; $i++) {
        $email = randomEmail('intervenant');
        $pdo->prepare("INSERT IGNORE INTO intervenants (idinter, compte_id, civilite, nom, prenom, email, telephone, date_creation) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                $users[$i],
                $compte_id,
                ($i % 2 == 0 ? 'Mme' : 'M.'),
                "NomInter$i",
                "PrenomInter$i",
                $email,
                randomPhone(),
                date('Y-m-d H:i:s')
            ]);
    }
    $intervenants = $pdo->query("SELECT id FROM intervenants WHERE compte_id=$compte_id")->fetchAll(PDO::FETCH_COLUMN);
    echo '<div>' . count($intervenants) . ' intervenants insérés</div>';

    // 5. Adresses (liées à clients)
    foreach ($clients as $cid) {
        $pdo->prepare("INSERT IGNORE INTO adresses (compte_id, type, ligne1, ligne2, cp, ville, pays, client_id, date_creation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                $compte_id,
                'facturation',
                "12 rue Test $cid",
                '',
                '68000',
                "Ville$cid",
                'France',
                $cid,
                date('Y-m-d H:i:s')
            ]);
    }
    $adresses = $pdo->query("SELECT id FROM adresses WHERE compte_id=$compte_id")->fetchAll(PDO::FETCH_COLUMN);
    echo '<div>' . count($adresses) . ' adresses insérées</div>';

    // 6. Banque
    for ($i = 1; $i <= 2; $i++) {
        $pdo->prepare("INSERT IGNORE INTO banque (compte_id, nom, iban, bic, api_bridge_id, webhook_url, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                $compte_id,
                "Banque $i",
                'FR76' . rand(10000000000, 99999999999),
                'BNPAFRPP',
                'bridge_' . rand(1000, 9999),
                'https://webhook.test/' . rand(1000, 9999),
                date('Y-m-d H:i:s')
            ]);
    }
    $banques = $pdo->query("SELECT id FROM banque WHERE compte_id=$compte_id")->fetchAll(PDO::FETCH_COLUMN);
    echo '<div>' . count($banques) . ' banques insérées</div>';

    // 7. Dépenses
    foreach ($banques as $bid) {
        for ($i = 1; $i <= 2; $i++) {
            $pdo->prepare("INSERT IGNORE INTO depenses (compte_id, banque_id, date_depense, montant, libelle, categorie, api_bridge_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
                ->execute([
                    $compte_id,
                    $bid,
                    randomDate('-1 year'),
                    rand(50, 500),
                    'Achat test',
                    'Fournitures',
                    'bridge_' . rand(1000, 9999),
                    date('Y-m-d H:i:s')
                ]);
        }
    }
    echo '<div>Dépenses insérées</div>';

    // 8. Factures
    foreach ($clients as $cid) {
        $num = 'FCT-' . rand(10000, 99999);
        $pdo->prepare("INSERT IGNORE INTO factures (compte_id, client_id, numero, date_emission, date_echeance, statut, montant_ht, montant_tva, montant_ttc, objet, commentaire, lien_pdf, date_paiement, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                $compte_id,
                $cid,
                $num,
                randomDate(),
                randomDate('+1 month'),
                'envoyee',
                rand(100, 500),
                rand(20, 100),
                rand(120, 600),
                'Prestation jardinage',
                'Commentaire test',
                '',
                null,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s')
            ]);
    }
    $factures = $pdo->query("SELECT id, client_id FROM factures WHERE compte_id=$compte_id")->fetchAll(PDO::FETCH_ASSOC);
    echo '<div>' . count($factures) . ' factures insérées</div>';

    // 9. Devis
    foreach ($clients as $cid) {
        $num = 'DEV-' . rand(10000, 99999);
        $pdo->prepare("INSERT IGNORE INTO devis (organisation_id, client_id, intervenant_id,numero, date_emission, montant_ht,  montant_ttc, statut,commentaire) VALUES (?, ?, ?, ?, ?,?, ?, ?, ?)")
            ->execute([
                $compte_id,
                $cid,
                '5',
                $num,
                randomDate(),
                rand(80, 400),

                rand(90, 480),
                'envoye',
                'blolkjjkhgkhg'
            ]);
    }
    $devis = $pdo->query("SELECT id FROM devis WHERE organisation_id=$compte_id")->fetchAll(PDO::FETCH_COLUMN);
    echo '<div>' . count($devis) . ' devis insérés</div>';

    // 10. Corps factures
    foreach ($factures as $f) {
        for ($i = 1; $i <= 3; $i++) {
            $pdo->prepare("INSERT IGNORE INTO corps_factures (facture_id, designation, quantite, prix_unitaire, tva, ordre) VALUES (?, ?, ?, ?, ?, ?)")
                ->execute([
                    $f['id'],
                    'Prestation ' . $i,
                    rand(1, 5),
                    rand(50, 200),
                    20.0,
                    $i
                ]);
        }
    }
    echo '<div>Corps factures insérés</div>';

    // 11. Corps devis
    foreach ($devis as $did) {
        for ($i = 1; $i <= 3; $i++) {
            $pdo->prepare("INSERT IGNORE INTO corps_devis (devis_id, designation, quantite, prix_unitaire, tva, ordre) VALUES (?, ?, ?, ?, ?, ?)")
                ->execute([
                    $did['id'],
                    'Prestation ' . $i,
                    rand(1, 5),
                    rand(50, 200),
                    20.0,
                    $i
                ]);
        }
    }
    echo '<div>Corps devis insérés</div>';

    // 12. Paiements
    foreach ($factures as $f) {
        $pdo->prepare("INSERT IGNORE INTO paiement (compte_id, client_id, facture_id, date_paiement, montant, moyen, etat, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                $compte_id,
                $f['client_id'],
                $f['id'],
                randomDate(),
                rand(120, 600),
                'CB',
                'total',
                date('Y-m-d H:i:s')
            ]);
    }
    echo '<div>Paiements insérés</div>';

    // 13. Lien paiement
    foreach ($factures as $f) {
        $pdo->prepare("INSERT IGNORE INTO lien_paiement (compte_id, client_id, facture_id, mollie_id, url, statut, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                $compte_id,
                $f['client_id'],
                $f['id'],
                'mollie_' . rand(1000, 9999),
                'https://pay.test/' . rand(1000, 9999),
                'en_attente',
                date('Y-m-d H:i:s')
            ]);
    }
    echo '<div>Liens paiement insérés</div>';

    // 14. Event (interventions)
    foreach ($clients as $cid) {
        $inter = $intervenants[array_rand($intervenants)];
        $adr = $adresses[array_rand($adresses)];
        $pdo->prepare("INSERT IGNORE INTO event (compte_id, intervenant_id, client_id, adresse_id, date_debut, date_fin, heure_debut, heure_fin, description, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                $compte_id,
                $inter,
                $cid,
                $adr,
                randomDate(),
                randomDate('+1 day'),
                '08:00',
                '12:00',
                'Intervention test',
                date('Y-m-d H:i:s')
            ]);
    }
    echo '<div>Interventions insérées</div>';

    // 15. Production
    foreach ($clients as $cid) {
        $inter = $intervenants[array_rand($intervenants)];
        $adr = $adresses[array_rand($adresses)];
        $pdo->prepare("INSERT IGNORE INTO production (compte_id, client_id, intervenant_id, adresse_id, date_production, heures, description, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                $compte_id,
                $cid,
                $inter,
                $adr,
                randomDate('-6 months'),
                rand(1, 8),
                'Production test',
                date('Y-m-d H:i:s')
            ]);
    }
    echo '<div>Productions insérées</div>';

    // 16. Demande (contact)
    for ($i = 1; $i <= 2; $i++) {
        $pdo->prepare("INSERT IGNORE INTO demande (prenom, nom) VALUES (?, ?)")
            ->execute([
                'PrenomDem' . $i,
                'NomDem' . $i
            ]);
    }
    echo '<div>Demandes insérées</div>';

    echo '<hr><b>Seed terminé pour le compte_id ' . htmlspecialchars($compte_id) . ' !</b>';
} catch (Exception $e) {
    echo '<div style="color:red;">Erreur : ' . htmlspecialchars($e->getMessage()) . '</div>';
}
echo '<br><a href="?">Revenir</a>';
echo '</body></html>';
// ...fin...
