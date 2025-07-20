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
  <title>EJERI Jardins - Mentions Légales</title>
  <link rel="icon" type="image/x-icon" href="../favicon.png">
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
  <link href="../assets/img/png/enooki_ico_blanc.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
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
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!---->
  <link href="../shared/assets/css/enooki-min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Preload des ressources critiques -->
  <link rel="preload" href="../assets/vendor/bootstrap/css/bootstrap.min.css" as="style">
  <link rel="preload" href="/shared/assets/css/enooki-min.css?time=<?= time() ?>" as="style">
  <link rel="preload" href="../assets/img/logo/logo_ejeri.png" as="image">

  <!-- MDB 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.0.0/mdb.min.css" rel="stylesheet" />-->
  <!-- Main CSS File -->
  <!-- <link href="../assets/css/main.css" rel="stylesheet"> -->
  <!-- =======================================================
  * Template Name: Selecao
  * Template URL: https://bootstrapmade.com/selecao-bootstrap-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">
  <header class="bg-dark text-white text-center py-5">
    <h1>Mentions Légales</h1>
    <!-- <p>Découvrez les villes où nous proposons nos services de jardinage à domicile.</p> -->
    <a href="/" class="devis-btn mt-4">Retour site</a>
    <p class="small text-white mb-4">Dernière mise à jour : 20 juillet 2025</p>

  </header>
  <main class="container my-0 mb-4" id="main-content">
    <h3 class="visually-hidden">Mentions Légales EJERI Jardins</h3>
    <section class="cgv">

      <div class="row">
        <div class="col-md-8 mx-auto">

          <h3 class="mt-4">Éditeur du site</h3>
          <p>Ce site est édité par EJERI Jardins, Entreprise de Services à la Personne.<br>
            SIRET : 80291044800033<br>
            Siège social : 3, place de l'Eglise, 45740 Lailly-en-val<br>
            Contact : contact@ejeri.fr</p>

          <h3 class="mt-4">Directeur de la publication</h3>
          <p>François Le Guilly-Auffret, gérant de CS Loire Atlantique / EJERI Jardins.</p>

          <h3 class="mt-4">Hébergeur du site</h3>
          <p>7, place de la Gare, BP 70109, 57200 Sarreguemines Cedex, 431 303 775 RCS Sarreguemines, tél. : 0970 808 911, info@ionos.fr</p>

          <h3 class="mt-4">Propriété intellectuelle</h3>
          <p>Le contenu du site (textes, images, vidéos, logo, charte graphique) est la propriété exclusive de EJERI Jardins. Toute reproduction totale ou partielle est interdite sans autorisation préalable.</p>

          <h3 class="mt-4">Liens externes</h3>
          <p>Les liens hypertextes présents sur le site peuvent renvoyer vers d’autres sites. EJERI Jardins décline toute responsabilité quant au contenu de ces sites tiers.</p>

          <h3 class="mt-4">Responsabilité</h3>
          <p>EJERI Jardins s’efforce de fournir des informations précises mais ne peut garantir l’exactitude ou l’exhaustivité des informations diffusées sur le site.</p>

          <h3 class="mt-4">Protection des données</h3>
          <p>Pour en savoir plus sur la gestion des données personnelles, veuillez consulter notre <a href="../legal/confidentialite.php">politique de confidentialité</a>.</p>

        </div>
      </div>



    </section>


  </main>
  <?php
  include '../sections/footer.php';
  ?>
  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>
  <!-- Preloader -->
  <div id="preloader"></div>
  <!-- Vendor JS Files -->
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate-mini.js"></script>
  <script src="../assets/vendor/aos/aos.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
  <!-- MDB 
  <script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.0.0/mdb.umd.min.js">
  </script>-->
  <!-- Main JS File -->
  <script src="../assets/js/main.js"></script>

  <!-- Géolocalisation Secteur (version stable) - DÉSACTIVÉ -->
  <!-- <script src="/shared/assets/js/secteur-geolocalisation-stable.js"></script> -->

  <!-- Recherche équipe par code postal -->
  <script src="../shared/assets/js/teamlocal-codepostal.js?time=<?= time() ?>"></script>
</body>

</html>