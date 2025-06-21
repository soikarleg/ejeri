<?php // views/partials/header.php 
require_once __DIR__ . '/../../../shared/helpers/function.php';
require_once __DIR__ . '/../../classes/Auth.php';
Auth::startSession();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>ENOOKI - Espace client</title>
    <meta name="description" content="enooki">
    <meta name="keywords" content="enooki jardins entretien tonte">
    <link href="assets/img/png/enooki_ico_blanc.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.0.0/mdb.min.css" rel="stylesheet" />

    <link href="assets/css/enooki.css?time=<?= time() ?>" rel="stylesheet">
</head>

<body class="dark-background">
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
            <a href="/" class="logo d-flex align-items-center">
                <img src="assets/img/png/enooki_jardins_blanc.png" alt="" height="100%">
            </a>
            <nav id="navmenu" class="navmenu">
                <ul>
                    <!-- <li><a href="https://enooki.com" target="_parent">Retour sur enooki.com</a></li> -->
                    <?php if (Auth::isAuthenticated()): ?>

                        <li><a href="/devis">Devis</a></li>
                        <li><a href="/interventions">Interventions</a></li>
                        <li><a href="/factures">Factures</a></li>
                        <li><a href="/paiements">Paiements</a></li>
                        <li><a href="/attestations">Attestations</a></li>
                        <li><a href="/dashboard">Synthèse</a></li>
                        <?php $_SESSION['email'] = Auth::getClientEmail();
                        if ($_SESSION['email']): ?>
                            <!-- <li><a class="" href="/compte"><?= htmlspecialchars($_SESSION['email']) ?></a></li> -->
                        <?php endif; ?>
                        <!-- <li class="text-green"><a href="/logout">Déconnexion</a></li> -->
                    <?php else: ?>
                        <!-- <li><a href="/login">Connexion</a></li> -->
                    <?php endif; ?>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
            
        </div>
    </header>
    <main class="section dark-background"><p><?=prettyc($_GET)?></p>