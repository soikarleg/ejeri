# Documentation du Script de Génération des Pages de Villes

## 🎯 Objectif
Ce script automatise la création de pages SEO-optimisées pour chaque ville desservie par EJERI Jardins.

## 🚀 Utilisation

### Exécution simple
```bash
cd /media/otto/stock3/INFORMATIQUE/ejeri.fr
./generate_ville_pages.sh
```

### Vérification du résultat
```bash
# Compter les pages générées
find services/villes/ -name "*.php" | wc -l

# Lister les pages créées
ls -la services/villes/
```

## 📁 Structure générée

```
services/villes/
├── mios.php                 # Page pour Mios (33380)
├── gujenmestras.php         # Page pour Gujan-Mestras (33470)
├── ares.php                 # Page pour Arès (33460)
├── saintloubes.php          # Page pour Saint-Loubès (33450)
└── ... (toutes les autres villes)
```

## 🔧 Fonctionnalités du script

### 1. Nettoyage automatique des noms
- Suppression des accents (é → e, à → a, etc.)
- Conversion en minuscules
- Suppression des caractères spéciaux
- Exemple: "Saint-Loubès" → "saintloubes.php"

### 2. Pages SEO-optimisées
Chaque page générée contient :
- **Meta descriptions** personnalisées
- **Balises title** optimisées
- **Schema.org JSON-LD** complet
- **Open Graph** et **Twitter Cards**
- **Breadcrumbs** de navigation
- **URLs canoniques**

### 3. Sources de données
Le script traite automatiquement :
- `shared/json/point_cugand.json`
- `shared/json/villes_gironde.json`
- `shared/json/villes_loiret.json`
- `shared/json/villes_loir_et_cher.json`
- Villes codées en dur dans le script

### 4. Structure de page complète
- **Header** avec navigation
- **Hero section** avec titre H1 optimisé
- **Services** présentés en cartes
- **Sidebar** avec avantages
- **Call-to-action** pour conversion
- **Footer** avec liens utiles

## 🎨 Personnalisation

### Modifier le template de page
Éditez la fonction `generate_ville_page()` dans le script pour :
- Changer le design
- Ajouter des sections
- Modifier les meta-données

### Ajouter de nouvelles villes
1. **Via JSON** : Ajoutez les villes dans les fichiers JSON existants
2. **Directement** : Ajoutez des appels `generate_ville_page()` dans la fonction `main()`

### Exemple d'ajout manuel :
```bash
generate_ville_page "Nouvelle-Ville" "12345" "Région"
```

## 📊 Optimisations SEO incluses

### 1. Schema.org LocalBusiness
- Informations complètes de l'entreprise
- Zone de service géolocalisée
- Catalogue d'offres de services
- Horaires d'ouverture
- Coordonnées géographiques

### 2. Meta-données avancées
- Descriptions uniques par ville
- Keywords géolocalisés
- Balises Open Graph
- Twitter Cards

### 3. Structure HTML sémantique
- Balises H1, H2, H3 hiérarchisées
- Breadcrumbs pour la navigation
- Liens internes optimisés
- Images avec attributs alt

## 🔗 Intégration avec villes.php

Les liens générés dans `villes.php` correspondent exactement aux pages créées :
```php
// Dans villes.php
href="../services/villes/<?= strtolower(str_replace(...)) ?>.php"

// Correspond aux fichiers générés par le script
services/villes/nomville.php
```

## 🚀 Workflow recommandé

1. **Mise à jour des données** : Modifiez les fichiers JSON
2. **Régénération** : Exécutez le script
3. **Vérification** : Testez quelques pages générées
4. **Déploiement** : Synchronisez avec le serveur

## ⚠️ Points d'attention

- Le script écrase les pages existantes (sauvegardez si nécessaire)
- Vérifiez les permissions d'écriture sur `/services/villes/`
- Les coordonnées GPS sont génériques (à adapter si besoin)
- Les numéros de téléphone sont des placeholders

## 🔧 Maintenance

### Nettoyage des anciennes pages
```bash
# Supprimer toutes les pages générées
rm -rf services/villes/*.php

# Régénérer
./generate_ville_pages.sh
```

### Debug des problèmes
```bash
# Vérifier les permissions
ls -la services/
ls -la shared/json/

# Tester le script avec debug
bash -x generate_ville_pages.sh
```
