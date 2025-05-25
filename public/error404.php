<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Erreur 404 - Page non trouvée</title>
    <link rel="stylesheet" href="/assets/css/ourstyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light text-center d-flex flex-column justify-content-center align-items-center vh-100">
    <div class="container">
        <h1 class="display-4 text-danger">Erreur 404</h1>
        <p class="lead">La page demandée est introuvable.</p>
        <a href="/" class="btn btn-primary mt-3">Retour à l'accueil</a>
    </div>
</body>

</html>