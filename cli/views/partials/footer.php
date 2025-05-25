<?php // views/partials/footer.php 
?>
</main>
<footer id="footer" class="footer dark-background mt-auto py-3">
    <div class="container">
         <?php if (!Auth::isAuthenticated()): ?>
        <div class="row gy-4">
            <div class="col-lg-3">
                <div class="q-links">
                    <a href="https://enooki.com">Retour au site enooki.com</a>
                    <!-- <a href="/mentions"><i class="bi bi-patch-check"></i> Mentions légales</a>
                    <a href="/cgv"><i class="bi bi-patch-check"></i> Conditions générales de ventes</a>
                    <a href="/confidence"><i class="bi bi-incognito"></i> Politique de confidentialité</a>-->
                </div>
            </div>
            <div class="col-lg-3">
                <div class="q-links">
                    <a href="/mentions"><i class="bi bi-patch-check"></i> Mentions légales</a>

                </div>
            </div>
            <div class="col-lg-3">
                <div class="q-links">
                   
                    <a href="/cgv"><i class="bi bi-patch-check"></i> Conditions générales de ventes</a>
                   
                </div>
            </div>
            <div class="col-lg-3">
                <div class="q-links">
                 
                    <a href="/confidence"><i class="bi bi-incognito"></i> Politique de confidentialité</a>
                </div>
            </div>
 <?php endif; ?>
        </div>
    </div>
    <div class="container"></div>
    <div class="container">
        <div class="copyright">
            <span>Copyright</span> <strong class="px-1 sitename">enooki</strong> <span>Tous droits réservés</span>
        </div>
        <div class="credits">
            Conçu par <a href="https://bootstrapmade.com/">BootstrapMade</a> Distribué par <a href="https://themewagon.com">ThemeWagon</a>
        </div>
    </div>
</footer>
<style>
    html,
    body {
        height: 100%;
    }

    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    main {
        flex: 1 0 auto;
    }

    .footer {
        flex-shrink: 0;
    }
</style>
<div id="preloader"></div>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.0.0/mdb.umd.min.js"></script>
<script src="assets/js/main.js"></script>
</body>

</html>