<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
$secteur = $_SESSION['idcompte'];
$idusers = $_SESSION['idusers'];
//include $chemin . '/inc/error.php';
//  error_reporting(\E_ALL);
//  ini_set('display_errors', 'stdout');
$magesquo = new Magesquo($secteur);
$pour_users = $magesquo->bilanData($secteur, $idusers, 'users_infos');
$eval_user = Dec_0($pour_users['pourcentage']);

$pour_idcompte = $magesquo->bilanData($secteur, $idusers, 'idcompte');
$eval_idcompte = Dec_0($pour_idcompte['pourcentage']);

$idcompte = new Idcompte($secteur);
$users = new Users($idusers);
?>
<div id="action">
    <?php
    $infos_compte = $idcompte->askIdcompte($secteur);
    foreach ($infos_compte as $key => $value) {
        if (!empty($key)) {
            //echo 'c_' . $key . '</br>';
            ${'c_' . $key} = $value;
        } else {
            ${'c_' . $key} = '';
        }
    }
    $infos_user = $users->askIduser($idusers);
    foreach ($infos_user as $key => $value) {
        if (!empty($key)) {
            ${'u_' . $key} = $value;
        } else {
            ${'u_' . $key} = '';
        }
    }
    ?>
    <div class="row">
        <div class="col-md-3">
            <div class="border-fiche mb-2">
                <a href="/modifier_administrateur" class="small pull-right"><?= $evaluser = $eval_user < 100 ? 'Ajouter' : 'Modifier' ?></a>
                <p class="titre_menu_item">Administrateur du compte</p>

                <p class="small text-muted mb-2"><?= AffDate($u_time) ?></p>

                <p class="text-bold"><?= 'N° ' . $u_id . ' - ' . $u_prenom . ' ' . $u_nom  ?></p>

                <p><?= $u_adresse ?></p>
                <p><?= $u_cp . ' ' . $u_ville ?></p>
                <p><?= afficherTiret($u_telephone, $u_portable) ?></p>

                <p><?= $u_email ?></p>
            </div>
            <div class="border-fiche mb-2">
                <!-- <i class='bx bxs-edit pointer'></i> -->
                <a href="/modifier_entreprise" class="small pull-right"><?= $evalcompte = $eval_idcompte < 100 ? 'Ajouter les informations' : 'Modifier' ?></a>
                <p class="titre_menu_item">Entreprise</p>
                <p class="small text-muted mb-2"><?= AffDate($c_time_maj) ?></p>
                <p class="text-bold  text-color-change"><?= $c_idcompte ?>
                </p>
                <p><?= $c_statut ?></p>
                <p><?= $c_denomination ?></p>
                <p><?= $c_adresse ?></p>
                <p><?= $c_cp . ' ' . $c_ville ?></p>
                <p><?= $c_telephone ?></p>
                <p class="mb-2"><?= $c_email ?></p>
                <p> <?= $c_siret . $c_nic ?></p>
                <p><?= $c_naf ?></p>
                <p class="small">Ces informations seront visibles sur les devis et les factures.</p>
            </div>

        </div>
        <div class="col-md-9">
            <?php
            switch ($url) {

                case 'modifier_administrateur':
                    include $chemin . '/src/pages/parametres/parametres_referent.php';
                    break;
                case 'modifier_entreprise':
                    include $chemin . '/src/pages/parametres/parametres_secteur.php';
                    break;
                default:
                    include $chemin . '/src/pages/parametres/parametres_prems.php';
                    break;
            }
            ?>
            <!-- <div class="border-fiche">
                <p class="titre_menu_item mb-2">Infos tarifaires</p>
                <?= ($pour_users['pourcentage']); ?>
                <?= (Dec_0($pour_idcompte['pourcentage'])); ?>
                <p><span class="" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_tarifs.php', '3', 'attente_target');"><i class='bx bxs-edit pointer'></i></span> Tarif de référence<span class="pull-right"><?= Dec_2($c_tarif_horaire_base, '€'); ?></span></p>
                <p><span class="" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_tva.php', '3', 'attente_target');"><i class='bx bxs-edit pointer'></i></span> Taux de TVA global<span class="pull-right"><?= Dec_2($c_tva_base, '%'); ?></span></p>
                <p><span class="" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_tva.php', '3', 'attente_target');"><i class='bx bxs-edit pointer'></i></span> % HNF<span class="pull-right"><?= Dec_2($c_pourcentage_hnf, '%'); ?></span></p>
            </div> -->

            <!-- <div class="border-fiche">
                <p class="pull-right" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_devis.php', '4', 'attente_target');"><i class='bx bxs-edit pointer'></i></p>
                <p class="titre_menu_item mb-2">Devis</p>
                <p>Racine N° de devis <span class="pull-right"><?= $c_dev_racine; ?></span></p>
                <p class="mb-4">Validité devis <span class="pull-right"><?= $c_validite_devis; ?></span></p>
                <p class="pull-right" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_facture.php', '4', 'attente_target');"><i class='bx bxs-edit pointer'></i></p>
                <p class="titre_menu_item mb-2">Factures & Avoirs</p>
                <p>Racine N° de facture <span class="pull-right"><?= $c_fac_racine; ?></span></p>
                <p>Racine N° d'avoir <span class="pull-right"><?= $c_avo_racine; ?></span></p>
                <p>Mode <span class="pull-right"><?= $c_modreg; ?></span></p>
                <p>Délai <span class="pull-right"><?= $c_delpai; ?> - (<?= $c_delj ?>)</span></p>
            </div> -->

            <!-- <div class="border-fiche">
                <p class="titre_menu_item">Logos</p>
                <img src="../../documents/img/<?= $secteur ?>/logo.png" alt="Logo" width="50%" height="auto" srcset="">
                <input type="file" id="fileInput" class="custom-file-input">
<label for="fileInput" class="custom-file-label">Choisissez un fichier</label>
            </div> -->

            <!-- <div class="border-fiche">
                <p class="titre_menu_item">Bug & Améliorations</p>
                <div class="scroll-s">
                    <?php
                    $reqbug = "select * from bug where idcompte= '$secteur'";
                    $bug = $conn->allRow($reqbug);
                    foreach ($bug as $b) {
                        echo $b['message'] . '<br>';
                    }
                    ?></div>
            </div> -->
        </div>
    </div>
</div>