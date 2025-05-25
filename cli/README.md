# Routing MVC et URLs propres (Enooki CLI)

## Fonctionnement des URLs propres

- Toutes les URLs propres (ex : `/login`, `/devis`, `/factures`, etc.) sont réécrites par le fichier `.htaccess` vers `index.php?action=...`.
- Exemple :
  - `/login` → `index.php?action=login`
  - `/devis` → `index.php?action=devis`
  - `/dashboard` → `index.php?action=dashboard`
- Le paramètre `action` est récupéré dans `index.php` via `$_GET['action']`.
- Le routeur PHP (`index.php`) utilise ce paramètre pour appeler le bon contrôleur/vues.

## Extrait du .htaccess
```apache
RewriteEngine On
RewriteBase /

# Pour toute URL propre (ex : /devis/123)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php?action=$1 [QSA,L]
```

## Extrait du routeur PHP
```php
$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'login':
        $controller->login();
        break;
    // ...
    default:
        $controller->dashboard();
        break;
}
```

## Ajouter une nouvelle route
- Ajouter une nouvelle règle dans le switch PHP (ex : `case 'profil': ...`).
- Les liens dans les vues doivent pointer vers `/profil` (et non `index.php?action=profil`).

## Bonnes pratiques
- Toujours utiliser les URLs propres dans les liens et redirections.
- Pour des routes dynamiques (ex : `/devis/123`), adapter le .htaccess et le routeur PHP pour extraire l’ID.

## Convention MVC et structure des vues

- Le fichier `index.php` ne contient que le routeur PHP (aucun HTML, ni balise `<html>`, `<body>`, `<header>`, `<footer>`, etc.).
- Chaque vue (ex : `login.php`, `dashboard.php`) inclut les partiels `header.php` et `footer.php` pour générer le HTML complet.
- Toute inclusion de vue doit passer par le contrôleur, jamais en direct dans le routeur ou dans le squelette principal.
- Cela garantit qu'il n'y a jamais de doublon de header/footer ou de balises HTML.

**À retenir :**
- Le contrôleur appelle la vue qui gère l'affichage complet.
- Le routeur (`index.php`) ne fait qu'appeler le contrôleur selon l'action.
- Ne jamais inclure de section ou de vue directement dans `index.php`.
