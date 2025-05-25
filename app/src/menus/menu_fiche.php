<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
$secteur = $_SESSION['idcompte'];
//include $chemin . '/inc/error.php';
$term = null;
$magesquo = new Magesquo($secteur);
$contact = new Clients($secteur);
$chantiers = new Chantiers($secteur);
$nbr_chantiers = $chantiers->chantierClient($idcli);
$nombre_chantiers = count($nbr_chantiers);
$client = $contact->showNomClient($idcli);


$params = ['idcli' => $idcli, 'idcompte' => $secteur];
$client_suivant = "SELECT MIN(idcli) AS suivant FROM client WHERE idcompte = :idcompte AND idcli > :idcli;";
$suivant = $magesquo->oneRow($client_suivant, $params);
//pretty($suivant);
$client_precedent = "SELECT MAX(idcli) AS precedent FROM client WHERE idcompte = :idcompte AND idcli < :idcli;";
$precedent = $magesquo->oneRow($client_precedent, $params);
//pretty($precedent);

if (!empty($client)) {
    $client = $client;
?>


   

    <li class="nav-item">
        <a href="/fiche_client?idcli=<?= $idcli ?>" class="btn btn-mag-n text-primary" style="font-weight:700; min-width:300px"><?= $contact->showNomClient($idcli)  ?></a>
    </li> <div class="vertical-align-middle mr-2 ml-2" style="margin-top:0px">
        <?php if (!empty($precedent['precedent'])): ?>
            <a href="fiche_client?idcli=<?= $precedent['precedent'] ?>" class='bx bxs-left-arrow pointer' title="Client précédent N° <?= $precedent['precedent'] ?>"></a>
        <?php else: ?>
            <span class="vertical-align-middle"><i class='bx bxs-error-circle text-warning'></i></span>
        <?php endif; ?>
        <span class="puce-tab vertical-align-middle"><small>N° <?= $idcli ?></small></span>
        <?php if (!empty($suivant['suivant'])): ?>
            <a href="fiche_client?idcli=<?= $suivant['suivant'] ?>" class='bx bxs-right-arrow pointer mr-4' title="Client suivant N° <?= $suivant['suivant'] ?>"></a>
        <?php else: ?>
            <span class="vertical-align-middle mr-4"><i class='bx bxs-error-circle text-danger'></i></span>
        <?php endif; ?>
    </div>
    <li class="nav-item">
        <a href="/fiche_client?idcli=<?= $idcli ?>" class="btn btn-mag-n"><i class="bx bx-line-chart" style="margin-top:3px"></i>Synthèse </a>
    </li>
    <li class="nav-item">
        <a href="fiche_client?idcli=<?= $idcli ?>&action=contacts_chantiers" class="btn btn-mag-n"><i class="bx bx-buildings" style="margin-top:3px"></i>Chantiers <span class="mypills-menu" style="margin-top:3px"><?= $nombre_chantiers ?></span></a>
    </li>
    <li class="nav-item">
        <a href="/fiche_client?idcli=<?= $idcli ?>&action=contacts_devis" class="btn btn-mag-n"><i class="bx bx-file" style="margin-top:3px"></i>Devis</a>
    </li>
    <li class="nav-item">
        <a href="/contacts_production?idcli=<?= $idcli ?>" class="btn btn-mag-n"><i class="bx bx-qr" style="margin-top:3px"></i>Productions</a>
    </li>
    <li class="nav-item">
        <a href="/contacts_factures?idcli=<?= $idcli ?>" class="btn btn-mag-n"><i class="bx bx-qr" style="margin-top:3px"></i>Factures</a>
    </li>
    <li class="nav-item">
        <a href="/contacts_reglements?idcli=<?= $idcli ?>" class="btn btn-mag-n"><i class="bx bx-qr" style="margin-top:3px"></i>Règlements</a>
    </li>
    <li class="nav-item">
        <a href="/contacts_notes?idcli=<?= $idcli ?>" class="btn btn-mag-n"><i class="bx bx-edit" style="margin-top:3px"></i>Notes</a>
    </li>
    <!-- <li class="nav-item">
        <a href="/contacts_documents?idcli=<?= $idcli ?>" class="btn btn-mag-n">Documents</a>
    </li> -->
    <li class="nav-item">
        <a href="/contacts_partage?idcli=<?= $idcli ?>" class="btn btn-mag-n"><i class="bx bx-share-alt" style="margin-top:3px"></i>Partage</a>
    </li>
    <li class="nav-item">
        <a href="/contacts_impression?idcli=<?= $idcli ?>" class="btn btn-mag-n"><i class="bx bx-printer" style="margin-top:3px"></i>Impression fiche</a>
    </li>


<?php
} else {
    $client = 'Aucun client avec cet ID : ' . $idcli;
?>
    <li class="nav-item text-primary">
        <a href="/fiche_client?idcli=<?= $idcli ?>" class="btn btn-mag-n text-primary"><i class="bx  bxs-user" style="margin-top:4px"></i><b><?= $client ?></b></a>
    </li>
<?php
}
?>
<p></p>
<script>
    $(function() {
        $('.bx').tooltip();
    });
</script>