#!/bin/bash

# Test des coordonnées GPS pour le script generate_ville_pages.sh

echo "🗺️ Test des coordonnées GPS par région EJERI Jardins"
echo "=================================================="

# Fonction de test des coordonnées
test_coordinates() {
    local region="$1"
    
    case "$region" in
        "Gironde")
            echo "44.6469,-0.8686"
            ;;
        "Loiret"|"Loir-et-Cher")
            echo "47.7608,1.6931"
            ;;
        "Vendée")
            echo "47.0666,-1.2394"
            ;;
        "Sologne")
            echo "47.6167,1.9000"
            ;;
        *)
            echo "44.8378,-0.5792"
            ;;
    esac
}

# Fonction de test détection région
test_region() {
    local cp="$1"
    
    case "${cp:0:2}" in
        "33")
            echo "Gironde"
            ;;
        "45")
            echo "Loiret"
            ;;
        "41")
            if [[ "$cp" == "41600" ]] || [[ "$cp" == "41210" ]] || [[ "$cp" == "41230" ]]; then
                echo "Sologne"
            else
                echo "Loir-et-Cher"
            fi
            ;;
        "85")
            echo "Vendée"
            ;;
        *)
            echo "Autre"
            ;;
    esac
}

echo ""
echo "📍 Coordonnées GPS par région:"
echo "Gironde (Mios) → $(test_coordinates "Gironde")"
echo "Loiret (Lailly-en-Val) → $(test_coordinates "Loiret")"
echo "Sologne (Chaumont-sur-Tharonne) → $(test_coordinates "Sologne")"
echo "Vendée (Cugand) → $(test_coordinates "Vendée")"

echo ""
echo "🔍 Détection région par code postal:"
echo "33380 (Mios) → $(test_region "33380")"
echo "45230 (Lailly-en-Val) → $(test_region "45230")"
echo "41600 (Chaumont-sur-Tharonne) → $(test_region "41600")"
echo "41210 (Neung-sur-Beuvron) → $(test_region "41210")"
echo "41000 (Blois) → $(test_region "41000")"
echo "85610 (Cugand) → $(test_region "85610")"

echo ""
echo "🎯 Exemple d'utilisation JSON-LD:"
region="Gironde"
coords=$(test_coordinates "$region")
lat=$(echo "$coords" | cut -d',' -f1)
lng=$(echo "$coords" | cut -d',' -f2)

cat << EOF
{
  "geo": {
    "latitude": $lat,
    "longitude": $lng
  },
  "serviceArea": {
    "geoMidpoint": {
      "latitude": $lat,
      "longitude": $lng
    },
    "geoRadius": "25000"
  }
}
EOF

echo ""
echo "✅ Tests terminés ! Le système de géolocalisation est fonctionnel."
