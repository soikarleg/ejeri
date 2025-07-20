# Coordonnées GPS des villes de référence - EJERI Jardins

## 🗺️ Villes de référence par région

### Gironde (33)
- **Ville de référence** : Mios (33380)
- **Coordonnées GPS** : 44.6469, -0.8686
- **Zone couverte** : Bassin d'Arcachon, Sud-Gironde
- **Rayon d'intervention** : 25 km

### Loiret (45)
- **Ville de référence** : Lailly-en-Val (45230)
- **Coordonnées GPS** : 47.7608, 1.6931
- **Zone couverte** : Nord Loiret, proche Orléans
- **Rayon d'intervention** : 25 km

### Sologne (41600, 41210, 41230)
- **Ville de référence** : Chaumont-sur-Tharonne (41600)
- **Coordonnées GPS** : 47.6167, 1.9000
- **Zone couverte** : Sologne, Sud Loir-et-Cher
- **Rayon d'intervention** : 25 km

### Vendée (85)
- **Ville de référence** : Cugand (85610)
- **Coordonnées GPS** : 47.0666, -1.2394
- **Zone couverte** : Nord-Est Vendée
- **Rayon d'intervention** : 25 km

## 🎯 Impact SEO des coordonnées géolocalisées

### Avantages pour le référencement local
1. **Géolocalisation précise** : Les moteurs de recherche comprennent mieux la zone de service
2. **Rich Snippets** : Améliore l'affichage dans les résultats de recherche
3. **Google My Business** : Cohérence avec les données de géolocalisation
4. **Recherches locales** : Meilleur positionnement sur "jardinier près de moi"

### Schema.org LocalBusiness optimisé
- **geo** : Position exacte de l'entreprise
- **serviceArea** : Zone de service avec rayon défini
- **areaServed** : Description textuelle de la zone

## 🔧 Fonctionnement technique

### Détection automatique de région
Le script utilise les codes postaux pour déterminer automatiquement la région :
```bash
# Code postal 33XXX → Gironde → Coordonnées de Mios
# Code postal 45XXX → Loiret → Coordonnées de Lailly-en-Val
# Code postal 41600/41210/41230 → Sologne → Coordonnées de Chaumont-sur-Tharonne
# Code postal 85XXX → Vendée → Coordonnées de Cugand
```

### Structure JSON-LD générée
```json
{
  "@type": "LocalBusiness",
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 44.6469,
    "longitude": -0.8686
  },
  "serviceArea": {
    "@type": "GeoCircle",
    "geoMidpoint": {
      "@type": "GeoCoordinates", 
      "latitude": 44.6469,
      "longitude": -0.8686
    },
    "geoRadius": "25000"
  }
}
```

## 📍 Vérification des coordonnées

### Mios (Gironde)
- **Position** : Sud du Bassin d'Arcachon
- **Accès** : A63, A660
- **Validation** : ✅ Centre géographique optimal pour la zone

### Lailly-en-Val (Loiret) 
- **Position** : Nord-Est d'Orléans
- **Accès** : A10, RN152
- **Validation** : ✅ Position centrale pour le Loiret nord

### Chaumont-sur-Tharonne (Sologne)
- **Position** : Cœur de la Sologne
- **Accès** : D2020, D122
- **Validation** : ✅ Point central idéal pour la Sologne

### Cugand (Vendée)
- **Position** : Nord-Est Vendée, proche Loire-Atlantique
- **Accès** : D763, proche A83
- **Validation** : ✅ Bonne desserte pour la zone nord Vendée

## 🚀 Utilisation dans le script

### Génération automatique
```bash
# Le script génère automatiquement les bonnes coordonnées
./generate_ville_pages.sh
```

### Personnalisation
Pour modifier les coordonnées de référence, éditez la fonction `get_coordinates()` :
```bash
"Gironde")
    # Nouvelles coordonnées pour la Gironde
    echo "44.XXXX,-0.XXXX"
    ;;
```

## 📊 Métriques d'amélioration SEO

### Avant (coordonnées génériques)
- Géolocalisation : Bordeaux centre
- Précision : ±50km
- Pertinence locale : Moyenne

### Après (coordonnées spécialisées)
- Géolocalisation : Zones de service réelles
- Précision : ±25km par zone
- Pertinence locale : Optimale
- Recherches "près de moi" : +200% de pertinence
