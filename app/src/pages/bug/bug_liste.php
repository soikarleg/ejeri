<?php
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];

//require $chemin.'/inc/error.php';

$bugs = new Bugs($iduser, $secteur);

$message = '';
$act = '';
foreach ($_GET as $key => $value) {
    ${$key} = ($value);
    //echo '$' . $key . ' = ' . $value . '<br>';
}

foreach ($_SESSION as $key => $value) {
    ${$key} = ($value);
    //echo '$' . $key . ' = ' . $value . '<br>';
}

$token = $_SESSION['csrf_token'];

if ($message != '') {
    $insert = $bugs->insertBug($secteur, $_GET);
}

if (isset($act) && $act == 'eff') {
    $bugs->deleteBug($del);
}

//prettyc($insert);
$mybugs = $bugs->askBugs();
//prettyc($mybugs);
// var_dump($mybugs);

?>
<div class="row">


    <?php
    foreach ($mybugs as $v) {

        $type = $bugs->getIcon($v['type'], $v['idbug']);
        $statut = $bugs->getIcon($v['statut']);
    ?>
        <div class="col-md-6">
            <div class="border-dot  mb-2 py-2 px-3">

            
                <pre class="pull-right small">page : <?=  $v['page'] ?></pre>
                <a href="/bug?page=<?= $v['page'] ?>&del=<?= $v['idbug'] ?>&act=eff" class="text-danger pull-right"><i class='bx bx-x'></i></a>
                <p class="case"><?= $type ?><?= $statut  ?></p>
                
                
                <pre class="">"<?= $v['message'] ?>"</pre>
                
            </div>
        </div>
    <?php
    }
    ?>
</div>