<nav class="navbar fixed-bottom bg-body-tertiary">
    <div class="container-fluid">
      <div class="mybandeau nav-link" style="min-height:45px">
        <!-- ../assets/img/logo_m_t.png -->
        <p class="text-white" id="horloge"></p>
        <?php
        if ($url != "bug" && $url != "bugs_ajouter" && $url != "bugs_liste" && $url != "bugs_resolus") {
        ?>
          <a href="/bug?page=<?= $pageref ?>" class="btn btn-mag-n" style="margin-left:50px"><i class='bx bx-bug mt-1 text-warning'></i>Signaler un bug sur ou suggérer une amélioration sur la page '<?= Tronque($pageref,40,3) ?>'</a>
          <p class="btn btn-mag-n"></p>
        <?php
        }
        ?>

      </div>
    </div>
  </nav>


<script src="../assets/js/horloge.js?<?= time(); ?>"></script>
<script>
  var h = date_heure('horloge');
  console.log('Heure : ' + h);
</script>