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
  <title>EJERI Jardins - Politique de Confidentialité</title>
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
    <h1>Politique de Confidentialité</h1>
    <!-- <p>Découvrez les villes où nous proposons nos services de jardinage à domicile.</p> -->
    <a href="/" class="devis-btn mt-4">Retour site</a>
    <p class="small text-white mb-4">Dernière mise à jour : 20 juillet 2025</p>

  </header>
  <main class="container my-0 mb-4" id="main-content">
    <h3 class="visually-hidden">Politique de Confidentialité EJERI Jardins</h3>
    <section class="cgv">

      <div class="row">
        <div class="col-md-8 mx-auto">

          <p>Cette politique de confidentialité décrit comment EJERI Jardins collecte, utilise et protège les informations personnelles que vous nous transmettez dans le cadre de nos prestations et via notre site internet.</p>

          <h3 class="mt-4">1. Responsable du traitement</h3>
          <p>EJERI Jardins, SIRET : 80291044800033, est responsable du traitement des données collectées. <br>contact@ejeri.fr,<br>02.38.45.15.78.</p>

          <h3 class="mt-4">2. Données collectées</h3>
          <p>Nous collectons : nom, prénom, adresse, téléphone, adresse email, informations de facturation, historique de prestations. Via le site, nous ne collectons que le cookie de session, qui est nécessaire au bon fonctionnement du site, rien de plus.</p>

          <h3 class="mt-4">3. Finalités</h3>
          <ul>
            <li>Gestion des devis, prestations, factures, attestations fiscales</li>
            <li>Respect des obligations légales (comptabilité, fiscalité)</li>
            <li>Communication avec le client</li>
            <li>Statistiques internes</li>
            <li>Amélioration des services</li>
            <li>Gestion des demandes de contact</li>
            <li>Gestion des avis clients</li>
            <li>Gestion des cookies techniques pour le bon fonctionnement du site</li>
            
          </ul>

          <h3 class="mt-4">4. Base légale</h3>
          <p>Le traitement des données repose sur l’exécution contractuelle (prestation demandée), les obligations légales (comptabilité, fiscalité), et l’intérêt légitime (amélioration de nos services).</p>

          <h3 class="mt-4">5. Durée de conservation</h3>
          <p>Les données clients sont conservées 6 ans après la dernière prestation, sauf obligation légale plus longue.</p>

          <h3 class="mt-4">6. Partage des données</h3>
          <p>Les données sont uniquement utilisées par EJERI Jardins. Aucun transfert de données à des tiers n’est effectué, sauf obligations légales ou prestataires techniques (hébergeur, logiciel de facturation sécurisé).</p>

          <h3 class="mt-4">7. Droits des personnes</h3>
          <p>Conformément au RGPD, vous disposez :</p>
          <ul>
            <li>d’un droit d’accès, rectification, effacement</li>
            <li>d’un droit d’opposition et de limitation</li>
            <li>d’un droit de portabilité</li>
            <li>du droit d’introduire une réclamation auprès de la CNIL (<a href="https://www.cnil.fr">www.cnil.fr</a>)</li>
          </ul>
          <p>Pour exercer ces droits : contact@ejeri.fr.</p>

          <h3 class="mt-4">8. Cookies</h3>
          <p>Nous utilisons des cookies techniques pour le bon fonctionnement du site. Vous pouvez désactiver les cookies via les paramètres de votre navigateur. Aucune donnée personnelle n’est exploitée à des fins publicitaires.</p>

          <h3 class="mt-4">9. Sécurité</h3>
          <p>Nous mettons en œuvre les moyens techniques adaptés pour sécuriser vos données (accès restreint, hébergement sécurisé).</p>

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