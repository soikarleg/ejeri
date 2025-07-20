#!/bin/bash

# Script combiné : Génération des pages de villes + Mise à jour robots.txt
# Pour EJERI Jardins - Automatisation complète SEO

echo "🚀 EJERI Jardins - Génération complète des pages SEO"
echo "=================================================="

# Répertoire de travail
cd /media/otto/stock3/INFORMATIQUE/ejeri.fr

echo ""
echo "📄 Étape 1: Génération des pages de villes..."
if [[ -x "./generate_ville_pages.sh" ]]; then
    ./generate_ville_pages.sh
    if [[ $? -eq 0 ]]; then
        echo "✅ Pages de villes générées avec succès"
    else
        echo "❌ Erreur lors de la génération des pages"
        exit 1
    fi
else
    echo "❌ Script generate_ville_pages.sh non trouvé ou non exécutable"
    exit 1
fi

echo ""
echo "🤖 Étape 2: Mise à jour du robots.txt..."
if [[ -x "./update_robots.sh" ]]; then
    ./update_robots.sh
    if [[ $? -eq 0 ]]; then
        echo "✅ robots.txt mis à jour avec succès"
    else
        echo "❌ Erreur lors de la mise à jour du robots.txt"
        exit 1
    fi
else
    echo "❌ Script update_robots.sh non trouvé ou non exécutable"
    exit 1
fi

echo ""
echo "📊 Résumé final:"
echo "- Pages générées: $(find services/villes/ -name "*.php" 2>/dev/null | wc -l)"
echo "- Lignes robots.txt: $(wc -l < robots.txt 2>/dev/null || echo '0')"
echo "- Taille totale: $(du -sh services/villes/ 2>/dev/null | cut -f1 || echo '0')"

echo ""
echo "🎯 Prochaines étapes suggérées:"
echo "1. Vérifier quelques pages générées"
echo "2. Tester les liens depuis villes.php"
echo "3. Valider le robots.txt"
echo "4. Générer/mettre à jour le sitemap.xml"

echo ""
echo "✅ Génération complète terminée avec succès !"
