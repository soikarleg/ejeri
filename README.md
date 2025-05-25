# Uniformisation de la charte graphique Enooki

## CSS commun et symlinks

La charte graphique Enooki est centralisée dans le fichier unique :

```
/shared/assets/css/enooki.css
```

Chaque module principal (`app/`, `pro/`, `cli/`, `admin/`, `manop/`, `webapp/`) utilise ce CSS via un **lien symbolique** :

```
app/assets/css/enooki.css  →  ../../shared/assets/css/enooki.css
pro/assets/css/enooki.css  →  ../../shared/assets/css/enooki.css
cli/assets/css/enooki.css  →  ../../shared/assets/css/enooki.css
...etc.
```

## Script d’automatisation

Pour créer ou réparer automatiquement les symlinks dans tous les modules, utilise le script :

```
./symlink_enooki_css.fish
```

Ce script :
- Supprime toute copie locale de `enooki.css`
- Crée le dossier `assets/css` s’il n’existe pas
- Crée le lien symbolique vers le CSS commun

## Inclusion dans le HTML

Dans chaque module, le CSS commun doit être inclus dans le `<head>` :

```html
<link href="assets/css/enooki.css" rel="stylesheet">
```

Vérifie que cette ligne est bien présente dans le `<head>` de chaque fichier principal (`index.php`, `inc/head.php`, etc.).

## Bonnes pratiques

- **Ne modifie jamais** le CSS dans chaque module : toute modification doit se faire dans `/shared/assets/css/enooki.css`.
- Les polices Google Fonts sont déjà importées dans ce fichier CSS commun : inutile de les importer dans le HTML.
- Pour toute personnalisation locale, crée un fichier `ourstyle.css` dans chaque module si besoin, mais conserve la charte Enooki dans le CSS commun.

## Vérification

Pour vérifier la bonne prise en compte du CSS commun :
- Ouvre chaque module dans le navigateur et vérifie l’affichage.
- Vérifie la présence du symlink dans chaque module :
  - `ls -l app/assets/css/enooki.css` (doit afficher un lien symbolique)
- Vérifie la présence de la balise `<link href="assets/css/enooki.css" rel="stylesheet">` dans le `<head>`.

## Dépannage

- Si le style n’est pas appliqué, relance le script `symlink_enooki_css.fish`.
- Si besoin, supprime manuellement les anciennes copies de `enooki.css` dans les modules.

## Pour aller plus loin

- Un script de vérification automatique peut être intégré à la CI/CD pour garantir la cohérence à chaque mise à jour (voir équipe technique).
- Pour toute question ou évolution, documente les changements dans ce README.

---

*Dernière mise à jour : 22/05/2025*