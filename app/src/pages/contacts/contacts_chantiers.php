<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include $chemin . '/inc/error.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$conn = new Magesquo($secteur);
$dataClient = new Clients($secteur);
$purge = new DataValidator();
$nom = "";
$prenom = "";
$civilite = "";
$email = "";
$telephone = "";
$portable = "";
$passcli = "";
$action = '';
foreach ($_GET as $k => $v) {
    ${$k} = $v;
    //echo '$' . $k . '= ' . $v . '<br class=""> ';
};
$ajouter_chantier = "";
$modifier_infos = "";
foreach ($_POST as $k => $v) {
    ${$k} = $purge->purgeStr($v);
    // echo '$' . $k . '= ' . $v . '<br class=""> ';
};

$chantiers = $dataClient->chantierClient($idcli);
foreach ($chantiers as $chantier) {
    foreach ($chantier as $k => $v) {
        ${$k} = is_array($v) ? $v : stripslashes($v !== null ? $v : '');
        //echo '$'.$k . ' = ' . $v . '<br>';
    }
}
// $idcompte = B24003
// $idcli = 104
// $civilite = M. et Mme
// $nom = GORDAS
// $prenom = Michel
// $email = francois@ejeri.fr
// $telephone = 0238451578
// $portable = 0665677503
// $datecrea = 08-03-2025
// $time_maj = 2025-03-08 20:06:49
// $idcompte = B24003
// $idcli = 104
// $actif = 1
// $id_i = 3
// $id_c = 3
// $type =
// $categorie =
// $connu =
// $time_maj = 2025-03-08 00:14:47
// $correspondance_mention = 
// $correspondance_idcli = 104
// $correspondance_idcompte = B24003
// $correspondance_adresse = 41 Rue Colbert
// $correspondance_cp = 59800
// $correspondance_ville = Lille
// $chantier_id = 4
// $chantier_idcli = 104
// $chantier_idcompte = B24003
// $chantier_adresse = 41 Rue Colbert
// $chantier_cp = 59800
// $chantier_ville = Lille
// $chantier_id = 5
// $chantier_idcli = 104
// $chantier_idcompte = B24003
// $chantier_adresse = 5 chemin de la Messe
// $chantier_cp = 45740
// $chantier_ville = Lailly-en-Val
?>

<?php
if ($passcli == "XXXX") {
    echo $pas_de_client;
} else {
?>
    <div class="row">
        <div class="col-md-3">
           
          
            
                <?php
                $i = 0;
                foreach ($chantiers as $c) {
                    foreach ($c as $key => $value) {
                        //echo $key . ' ' . $value . '*** ';
                    }
                    $i++;
                ?><div class="border-fiche mb-2 ">
                    <a href="fiche_client?idcli=<?= $idcli ?>&action=chantier_modification&idchantier=<?= $c['chantier_id'] ?>" class="small pull-right">Modifier</a>
                    <p class="text-bold text-primary">Chantier N° <?= $c['chantier_idcli'] . '-' . $i ?></p>
                    <p><?= $c['chantier_adresse'] ?></p>
                    <p class="mb-2"><?= $c['chantier_cp'] . ' ' . $c['chantier_ville'] ?></p></div>
                <?php
                }
                ?>
                <a href="fiche_client?idcli=<?= $idcli ?>&action=chantier_plus">Ajouter un chantier</a>
            
           
         
        </div>
    
       
    </div>
    <div class="mt-4">
        <div id="action" class="mt-bot">
        </div>
    </div>
    <script>
        $(function() {
            $('.bx').tooltip();
        });
    </script>
<?php
}
?>