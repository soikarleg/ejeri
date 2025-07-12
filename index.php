<?php
session_start();
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;

?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>EJERI Jardins - Entretien de jardins et réduction d'impôts</title>
  <link rel="icon" type="image/x-icon" href="favicon.png">
  <meta name="author" content="otto">
  <meta name="description" content="EJERI Jardins : entretien de jardin avec 50% de réduction d'impôts. Tonte, taille, débroussaillage par des pros qualifiés. Devis gratuit sans engagement.">
  <meta name="facebook-domain-verification" content="fqmbr0cx3qcnbvzmsi1va7pps7bxyc" />

  <!-- Canonical URL -->
  <link rel="canonical" href="https://ejeri.fr/">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://ejeri.fr">
  <meta property="og:title" content="EJERI Jardins - Entretien de jardins et réduction d'impôts 50%">
  <meta property="og:description" content="Services d'entretien de jardins avec 50% de réduction d'impôts. Tonte, taille, débroussaillage par des professionnels qualifiés.">
  <meta property="og:image" content="https://ejeri.fr/assets/img/logo/logo_ejeri.png">
  <meta property="og:locale" content="fr_FR">
  <meta property="og:site_name" content="EJERI Jardins">

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="https://ejeri.fr">
  <meta name="twitter:title" content="EJERI Jardins - Entretien de jardins et réduction d'impôts">
  <meta name="twitter:description" content="Services d'entretien de jardins avec 50% de réduction d'impôts. Professionnels qualifiés, devis gratuit.">
  <meta name="twitter:image" content="https://ejeri.fr/assets/img/logo/logo_ejeri.png">


  <meta name="keywords" content="EJERI Jardins jardins entretien tonte taille débroussaillage réduction d'impôts crédit d'impôt services à la personne">
  <!-- Favicons -->
  <link href="assets/img/png/enooki_ico_blanc.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Fredoka:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Schema.org Local Business -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "EJERI Jardins",
      "description": "Entretien de jardins et services à la personne avec 50% de réduction d'impôts",
      "url": "https://ejeri.fr",
      "telephone": "+33-1-XX-XX-XX-XX",
      "priceRange": "€€",
      "address": {
        "@type": "PostalAddress",
        "addressCountry": "FR",
        "addressLocality": "France"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 46.603354,
        "longitude": 1.888334
      },
      "openingHours": "Mo-Sa 08:00-18:00",
      "serviceType": "Entretien de jardins",
      "areaServed": "France",
      "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "Services de jardinage",
        "itemListElement": [{
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Tonte de pelouse",
              "description": "Service de tonte professionnel avec réduction d'impôts de 50%"
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Taille de haies",
              "description": "Taille et entretien de haies avec réduction d'impôts de 50%"
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Débroussaillage",
              "description": "Débroussaillage et nettoyage de terrain avec réduction d'impôts de 50%"
            }
          }
        ]
      },
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://ejeri.fr/?s={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
  </script>

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!---->
  <link href="/shared/assets/css/enooki-mini.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Preload des ressources critiques -->
  <link rel="preload" href="assets/vendor/bootstrap/css/bootstrap.min.css" as="style">
  <link rel="preload" href="/shared/assets/css/enooki-mini.css" as="style">
  <link rel="preload" href="assets/img/logo/logo_ejeri.png" as="image">

  <!-- MDB 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.0.0/mdb.min.css" rel="stylesheet" />-->
  <!-- Main CSS File -->
  <!-- <link href="assets/css/main.css" rel="stylesheet"> -->
  <!-- =======================================================
  * Template Name: Selecao
  * Template URL: https://bootstrapmade.com/selecao-bootstrap-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
      <a href="/" class="logo d-flex align-items-center" aria-label="Accueil EJERI Jardins">
        <img src="assets/img/logo/logo_ejeri.png" alt="Logo EJERI Jardins - Entretien de jardins et réduction d'impôts" height="120%">
        <h1 class="visually-hidden">EJERI Jardins - Entretien de jardins et réduction d'impôts</h1>

      </a>
      <nav id="navmenu" class="navmenu" aria-label="Menu principal">
        <ul>
          <li><a href="#hero">Accueil</a></li>
          <li><a href="#services">Nos prestations</a></li>
          <li><a href="#team">Nos jardiniers</a></li>
          <li><a href="#reduc">Réduction d'impôts</a></li>
          <li><a href="#faq">Vos questions</a></li>
          <!-- <li><a href="https://cli.ejeri.fr" class="client-btn">Espace client</a></li>-->
          <!-- <li><a href=" https://pro.magesquo.com" target="_blank">Intervenant</a></li> -->
          <!-- <li><a href="https://app.enooki.fr" target="_blank">Gestion</a></li>  -->
        </ul>
        <button class="mobile-nav-toggle d-xl-none" aria-label="Ouvrir le menu mobile"><i class="bi bi-list"></i></button>
      </nav>
    </div>
  </header>
  <main class="main" id="main-content">
    <?php
    include 'sections/hero2.php';
    include 'sections/prestations.php';
    include 'sections/reduction.php';
    include 'sections/teamlocal.php';
    include 'sections/faq.php';
    include 'sections/contact.php';
    ?>
  </main>
  <footer id="footer" class="footer dark-background">
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-4">
          <h2 class="sitename visually-hidden">EJERI Jardins - Footer</h2>
          <img src="assets/img/logo/logo_ejeri.png" alt="Logo EJERI Jardins - Entretien de jardins - Clisson, Beaugency, Lamotte-Beuvron, Cholet, La Roche-sur-Yon" width="50%">
          <p>Je ne sais pas... je ne peux pas !<br>EJERI Jardins le fait pour moi.</p>
          <div class="social-links d-flex justify-content-center" aria-label="Réseaux sociaux">
            <a href="#" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="Skype"><i class="bi bi-skype"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
        <div class="col-lg-4">
          <nav class="q-links" aria-label="Liens rapides">
            <a href="#hero"><i class="bi bi-patch-check"></i> Mentions légales</a>
            <a href="#hero"><i class="bi bi-patch-check"></i> Conditions générales de vente</a>
            <a href="#hero"><i class="bi bi-incognito"></i> Politique de confidentialité</a>
          </nav>
        </div>
        <div class="col-lg-4">
          <nav class="q-links" aria-label="Accès comptes">
            <a href="https://cli.enooki.com" target="_blank" rel="noopener"><i class="bi bi-person-check"></i> Ouvrir un compte client</a>
            <a href="https://pro.enooki.com" target="_blank" rel="noopener"><i class="bi bi-people-fill"></i> Devenir intervenant</a>
            <a href="https://app.enooki.com" target="_blank" rel="noopener"><i class="bi bi-graph-up"></i> Application de gestion</a>
          </nav>
        </div>
      </div>
    </div>
    <div class="container">
    </div>
    <div class="container">
      <div class="copyright">
        <span>Copyright</span> <strong class="px-1 sitename">enooki</strong> <span>Tous droits réservés</span>
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Conçu par <a href="https://bootstrapmade.com/" target="_blank">BootstrapMade</a> Distribué par <a
          href="https://themewagon.com" target="_blank">ThemeWagon</a>
      </div>
    </div>
    </div>
  </footer>
  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>
  <!-- Preloader -->
  <div id="preloader"></div>
  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate-mini.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <!-- MDB 
  <script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.0.0/mdb.umd.min.js">
  </script>-->
  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

  <!-- Géolocalisation Secteur (version stable) - DÉSACTIVÉ -->
  <!-- <script src="/shared/assets/js/secteur-geolocalisation-stable.js"></script> -->

  <!-- Recherche équipe par code postal -->
  <script src="/shared/assets/js/teamlocal-codepostal.js"></script>
</body>

</html>