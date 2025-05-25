<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
$secteur = $_SESSION['idcompte'];

?>
<li class="nav-item">
        <p  class="btn btn-mag-n"  >Historique</p>
    </li>
<?php  

$date=date('Y')-5;

for ($i=$date;  $i < $date+6   ; $i++) { 
    
?>

    <li class=" nav-item">
        <a href="/indicateurs?annee=<?=$i?>" class="btn btn-mag-n"  ><?=$i?></a>
    </li>
    <?php
    }
    ?>
    

<script>
    $(function() {
        $('#bx').tooltip();
    });
</script>