<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');

$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();
session_start();
$secteur = $_SESSION['idcompte'];


foreach ($_POST as $k => $v) {
  ${$k} = $v;
  // echo '$' . $k . ' = ' . $v . '</br>';
}
$req = "select * from client_chantier where idcli = '" . $idcli . "' ";
$res = $conn->oneRow($req);
$coordonnees = ($res['civilite'] . " " . $res['prenom'] . " " . $res['nom'] . "</br>" . $res['adresse'] . " " . $res['cp'] . " " . $res['ville'] . "</br>Téléphone : " . $res['telephone'] . "</br>Portable : " . $res['portable'] . "</br>Email : " . $res['email'] . "</br>");
$coordo_mail = strEncoding($res['civilite'] . " " . $res['prenom'] . " " . $res['nom'] . "\r\n" . $res['adresse'] . " " . $res['cp'] . " " . $res['ville'] . "\r\n" . $res['telephone'] . "\r\n" . $res['portable'] . "\r\n" . $res['email']);
$nom_cli = ($res['prenom'] . " " . $res['nom']);
?>
<div class="">
  <div class="row">
    <div class="col-md-6">
      <form autocomplete="off" id="env_coor" method="POST">
        <p class="titre mb-2">Informations partagées</p>
        <div class="card card-body bg-mag mb-4">
          <p class=""><?php echo $coordonnees; ?></p>
        </div>
        <p class="titre">Destinataire(s) *</p>
        <label class="form-check-label">
          <?php $req = "select * from COLLABORATEURS where CS = '" . $secteur . "' and actif='oui' and emailcollabo !='' order by Numero desc ";
          $res = $conn->allRow($req);
          foreach ($res as $r) { ?>

            <div class="btn btn-mag-n mb-2 l-5" id="copie<?php echo $r['Numero']; ?>" value="<?= $r['emailcollabo']; ?>">
              <?php echo ($r['prenom']); ?>
            </div>

            <script type="text/javascript">
              $("#copie<?php echo $r['Numero']; ?>").click(function() {
                var desti_email = $('#email').val();
                var em = desti_email + ', ' + $("#copie<?php echo $r['Numero']; ?>").attr('value');
                $('#email').val(em);
              });
            </script>
          <?php } ?>
        </label>
        <div class="input-group">
          <span class="input-group-text l-9" id="chute-email">Email(s)</span>
          <input type="text" class="form-control" id="email" placeholder="Email destinataire" name="email" value="<?= $r['emailcollabo']; ?>">
          <input type="hidden" class="form-control" id="coord" name="coord" value="<?php echo $coordonnees; ?>">
          <input type="hidden" class="form-control" id="mail_dis" name="mail_dis" value="mail_coor">
          <input type="hidden" class="form-control" id="nom_cli" name="nom_cli" value="<?php echo $nom_cli; ?>">
        </div>
        <p class="petit text-right text-primary mb-4">Séparez les emails avec une virgule.</p>
        <p class="titre mb-2">Message complémentaire</p>
        <div class="input-group mb-2">
          <span class="input-group-text l-9">Infos</span>
          <textarea class="form-control" placeholder="" name="commentaire"></textarea>
        </div>
        <p class="text-right">


          <button type="reset" class="btn btn-mag-n mr-2"><i class="bx bx-reset icon-bar"></i> Mise à zéro</button>
          <button type="button" class="btn btn-mag-n text-primary pull-right " onclick="ajaxForm('#env_coor','../src/pages/contacts/contacts_partage_envoi.php','sub-target','attente_target');"><i class='bx bx-share-alt icon-bar'></i> Envoi de l'adresse</button>

          <input name="validation" id="validation" type="hidden" value="444bcb3a3fcf8389296c49467f27e1d6">
        </p>


      </form>

    </div>
    <div class="col-md-6">
      <div id="sub-target">
      </div>
    </div>
  </div>
</div>