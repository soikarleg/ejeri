#!/bin/bash

# Script d'automatisation pour mettre à jour sitemap.xml avec toutes les pages de villes
# Usage: ./update_sitemap.sh

# Configuration
VILLES_DIR="/media/otto/stock3/INFORMATIQUE/ejeri.fr/services/villes"
SITEMAP_FILE="/media/otto/stock3/INFORMATIQUE/ejeri.fr/sitemap.xml"
BASE_URL="https://ejeri.fr/services/villes"
DATE=$(date +%Y-%m-%d)

echo "🗺️  Mise à jour du sitemap.xml avec toutes les pages de villes..."

# Vérifier si le dossier villes existe
if [ ! -d "$VILLES_DIR" ]; then
    echo "❌ Erreur: Le dossier $VILLES_DIR n'existe pas"
    exit 1
fi

# Créer le backup du sitemap actuel
cp "$SITEMAP_FILE" "${SITEMAP_FILE}.backup.$(date +%Y%m%d_%H%M%S)"

# Générer la section des pages de villes
echo "📋 Génération de la liste des pages de villes..."

# Début de la section sitemap
cat > /tmp/villes_sitemap.xml << EOF
  <!-- Nouvelle page ajoutée -->
  <url>
    <loc>https://ejeri.fr/services/villes.php</loc>
    <lastmod>$DATE</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>

  <!-- Pages de villes individuelles -->
EOF

# Ajouter chaque page de ville au sitemap
for file in "$VILLES_DIR"/*.php; do
    if [ -f "$file" ]; then
        filename=$(basename "$file" .php)
        cat >> /tmp/villes_sitemap.xml << EOF
  <url>
    <loc>$BASE_URL/$filename.php</loc>
    <lastmod>$DATE</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
EOF
    fi
done

# Fermer la section sitemap
echo "" >> /tmp/villes_sitemap.xml
echo "</urlset>" >> /tmp/villes_sitemap.xml

# Créer le nouveau sitemap.xml
# Extraire la partie avant les pages de villes
awk '1; /<!-- Nouvelle page ajoutée -->/ {exit}' "$SITEMAP_FILE" | head -n -1 > /tmp/sitemap_header.xml

# Combiner header + nouvelles pages de villes
cat /tmp/sitemap_header.xml /tmp/villes_sitemap.xml > "$SITEMAP_FILE"

# Nettoyer les fichiers temporaires
rm -f /tmp/villes_sitemap.xml /tmp/sitemap_header.xml

echo "✅ Sitemap.xml mis à jour avec $(ls "$VILLES_DIR"/*.php | wc -l) pages de villes"
echo "📅 Date de mise à jour: $DATE"
echo "📁 Fichier sauvegardé: ${SITEMAP_FILE}.backup.$(date +%Y%m%d_%H%M%S)"

# Vérifier la validité du XML (optionnel)
if command -v xmllint &> /dev/null; then
    if xmllint --noout "$SITEMAP_FILE" 2>/dev/null; then
        echo "✅ Sitemap.xml est valide"
    else
        echo "⚠️  Attention: Le sitemap.xml généré contient des erreurs XML"
    fi
else
    echo "ℹ️  xmllint non disponible - validation XML non effectuée"
fi

echo "🎯 Sitemap.xml mis à jour avec succès!"
