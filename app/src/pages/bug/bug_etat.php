<?php
session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];

//require $chemin.'/inc/function.php';


$bugs = new Bugs($iduser,$secteur);

error_reporting(\E_ALL);
ini_set('display_errors', 'stdout');
include $chemin . '/inc/error.php';

foreach ($_GET as $key => $value) {
    ${$key} = ($value);
    //echo '$' . $key . ' = ' . $value . '<br>';
}

foreach ($_SESSION as $key => $value) {
    ${$key} = ($value);
    //echo '$' . $key . ' = ' . $value . '<br>';
}



$mybugs = $bugs->askBugs();
// pretty($mybugs);
// var_dump($mybugs);





if ($page != '') {

?>
    <div class="row">
        <div class="col-md-12"><p class="mb-4">Explication de la page ici Lorem ipsum dolor, sit amet consectetur adipisicing elit. Earum dignissimos deleniti doloremque laudantium nesciunt aliquam, consequatur, commodi, quam possimus quo maiores alias atque. Molestiae eaque veniam tempore consequatur quo reprehenderit!</p></div>
        <div class="col-md-3">
            <form action="/bug" method="get">


                <div class="input-group mb-2 ">
                    <span class="input-group-text l-9" id="page">Page</span>
                    <input type="text" class="form-control" name="page" placeholder="Page" aria-label="Page" aria-describedby="page" value="<?= $page ?>" readonly>
                </div>
                <div class="input-group mb-2 ">
                    <span class="input-group-text l-9" id="type">Type</span>
                    <select class="form-control" aria-label="Type" name="type">
                        <option value="anomalie" selected>Anomalie</option>
                        <option value="amelioration">Amélioration</option>
                        <option value="autre">Autre</option>

                    </select>
                </div>
                <div class="input-group mb-2 ">
                    <span class="input-group-text l-9">Message</span>
                    <textarea class="form-control" name="message"></textarea>
                </div>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="iduser" value="<?= $iduser ?>" readonly>
                <input type="hidden" name="idcompte" value="<?= $idcompte ?>" readonly>
                <input type="submit" class="form-control l-50 pull-right" value="Envoyer votre message">
            </form>
        </div>
        <div class="col-md-9">

            <?php
            include $chemin . '/src/pages/bug/bug_liste.php';
            ?>
        </div>

    </div>
<?php
}
?>