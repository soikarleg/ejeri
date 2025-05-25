<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();
session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
foreach ($_POST as $k => $v) {
  ${$k} = $v;
  // echo '$' . $k . ' = ' . $v . '</br>';
}
$req = "select * from client_chantier where idcli = '" . $idcli . "' ";
$res = $conn->oneRow($req);

$infos_secteur = $conn->askIdcompte($secteur, 'denomination');
$denomination = $infos_secteur['denomination'];

$infos_client = $conn->askClient($idcli);

?>
<p class="titre_menu_item">Envoi message sur <?= $emailcli ?></p>
<div class=" mb-4">
  <div class="row">
    <div class="col-md-6">
      <form autocomplete="off" id="env_coor" method="POST" enctype="multipart/form-data">
        <div class="input-group mt-2">
          <span class="input-group-text l-9 mb-2" id="chute-email">Destinataire</span>
          <input type="text" class="form-control mb-2" id="email" placeholder="Email destinataire de la copie" name="emailcli" value="<?= $emailcli ?>">
          <!-- <input type="hidden" class="form-control" id="coord" name="coord" value="<?php echo $coordonnees; ?>">
          <input type="hidden" class="form-control" id="mail_dis" name="mail_dis" value="mail_coor">
          <input type="hidden" class="form-control" id="nom_cli" name="nom_cli" value="<?= $coordo_mail; ?>"> -->
        </div>
        <p class="petit pull-right text-muted mb-2">Séparez les emails avec une virgule.</p>
        <ul class="nav">
          <li class="nav-item">
            <p class="titre mr-2" aria-current="page" href="#">Copie(s) : </p>
          </li>
          <?php
          $req = "select * from COLLABORATEURS where CS = '" . $secteur . "' and actif='oui' and emailcollabo !='' ";
          $res = $conn->allRow($req);
          ?>
          <?php foreach ($res as $index => $r) { ?>
            <li class="nav-item mb-2">
              <a class="puce-mag bg-gradient ml-2 pointer" id="copie<?php echo $r['Numero']; ?>" data-email="<?php echo $r['emailcollabo']; ?>">
                <?php echo $r['prenom']; ?>
              </a>
            </li>
            <script type="text/javascript">
              $("#copie<?php echo $r['Numero']; ?>").click(function() {
                var desti_email = $('#email').val();
                var em = desti_email + ', ' + $(this).data('email');
                $('#email').val(em);
              });
            </script>
          <?php } ?>
        </ul>
        <script type="text/javascript">
          function messageCopie(champ) {
            var message = $('#' + champ).attr('data-val');
            var em = $(".richText-editor").html() + message;
            $('.richText-editor').html(em);
          }
        </script>
        <div class="input-group mt-2 mb-2">

          <label class="input-group-text l-9 " for="inputGroupSelect01">Sujet</label>
          <input type="text" class="form-control" name="sujet" value="">
        </div>
        <p class="titre mb-2">Message</p>
        <textarea class="form-control mb-2" name="message" id="mess" cols="30" rows="5" spellcheck="false">Bonjour <?= $infos_client['civilite'] . ' ' . $infos_client['nom'] . ',<br><br><br>Cordialement,<br>' . NomColla($iduser) . '<br>' . NomSecteur($secteur) ?></textarea>
        <script>
          $('#mess').richText({
            // text formatting
            bold: true,
            italic: false,
            underline: true,
            // text alignment
            leftAlign: true,
            centerAlign: true,
            rightAlign: true,
            justify: true,
            // lists
            ol: false,
            ul: true,
            // title
            heading: false,
            // fonts
            fonts: false,
            // fontList: ["Arial",
            //   "Arial Black",
            //   "Comic Sans MS",
            //   "Courier New",
            //   "Geneva",
            //   "Georgia",
            //   "Helvetica",
            //   "Impact",
            //   "Lucida Console",
            //   "Tahoma",
            //   "Times New Roman",
            //   "Verdana"
            // ],
            fontColor: false,
            backgroundColor: false,
            fontSize: false,
            // // uploads
            imageUpload: false,
            fileUpload: false,
            // media
            videoEmbed: false,
            // link
            urls: false,
            // tables
            table: false,
            // code
            removeStyles: false,
            code: false,
            // colors
            colors: [],
            // dropdowns
            fileHTML: '',
            imageHTML: '',
            // translations
            // translations: {
            //   'title': 'Title',
            //   'white': 'White',
            //   'black': 'Black',
            //   'brown': 'Brown',
            //   'beige': 'Beige',
            //   'darkBlue': 'Dark Blue',
            //   'blue': 'Blue',
            //   'lightBlue': 'Light Blue',
            //   'darkRed': 'Dark Red',
            //   'red': 'Red',
            //   'darkGreen': 'Dark Green',
            //   'green': 'Green',
            //   'purple': 'Purple',
            //   'darkTurquois': 'Dark Turquois',
            //   'turquois': 'Turquois',
            //   'darkOrange': 'Dark Orange',
            //   'orange': 'Orange',
            //   'yellow': 'Yellow',
            //   'imageURL': 'Image URL',
            //   'fileURL': 'File URL',
            //   'linkText': 'Link text',
            //   'url': 'URL',
            //   'size': 'Size',
            //   'responsive': '<a href="https://www.jqueryscript.net/tags.php?/Responsive/">Responsive</a>',
            //   'text': 'Text',
            //   'openIn': 'Open in',
            //   'sameTab': 'Same tab',
            //   'newTab': 'New tab',
            //   'align': 'Align',
            //   'left': 'Left',
            //   'justify': 'Justify',
            //   'center': 'Center',
            //   'right': 'Right',
            //   'rows': 'Rows',
            //   'columns': 'Columns',
            //   'add': 'Add',
            //   'pleaseEnterURL': 'Please enter an URL',
            //   'videoURLnotSupported': 'Video URL not supported',
            //   'pleaseSelectImage': 'Please select an image',
            //   'pleaseSelectFile': 'Please select a file',
            //   'bold': 'Bold',
            //   'italic': 'Italic',
            //   'underline': 'Underline',
            //   'alignLeft': 'Align left',
            //   'alignCenter': 'Align centered',
            //   'alignRight': 'Align right',
            //   'addOrderedList': 'Ordered list',
            //   'addUnorderedList': 'Unordered list',
            //   'addHeading': 'Heading/title',
            //   'addFont': 'Font',
            //   'addFontColor': 'Font color',
            //   'addBackgroundColor': 'Background color',
            //   'addFontSize': 'Font size',
            //   'addImage': 'Add image',
            //   'addVideo': 'Add video',
            //   'addFile': 'Add file',
            //   'addURL': 'Add URL',
            //   'addTable': 'Add table',
            //   'removeStyles': 'Remove styles',
            //   'code': 'Show HTML code',
            //   'undo': 'Undo',
            //   'redo': 'Redo',
            //   'save': 'Save',
            //   'close': 'Close'
            // },
            // // privacy
            youtubeCookies: false,
            // // preview
            // preview: false,
            // // placeholder
            // placeholder: '',
            // dev settings
            useSingleQuotes: false,
            height: '',
            heightPercentage: 0,
            adaptiveHeight: false,
            id: "",
            class: "",
            useParagraph: true,
            maxlength: 1250,
            maxlengthIncludeHTML: false,
            callback: undefined,
            useTabForNext: false,
            save: false,
            saveCallback: undefined,
            saveOnBlur: 0,
            undoRedo: false
          });
        </script>
        <p class="titre mt-2">Pièce jointe</p>
        <div class="input-group mt-2 mb-2">
          <input type="file" class="form-control" name="fichier">
        </div>
        <p class="text-right">
          <button type="reset" class="btn btn-mag-n mr-1"><i class="bx bx-reset icon-bar"></i> Mise à zéro</button>
          <button type="button" class="btn btn-mag-n text-primary" onclick="ajaxFile('#env_coor','../src/pages/contacts/contacts_email_envoi.php','sub-target','attente_target');">Envoi du message</button>
        </p>
      </form>
    </div>
    <div class="col-md-6">
      <div id="sub-target">
      </div>
    </div>
  </div>
</div>