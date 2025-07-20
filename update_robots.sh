#!/bin/bash

# Script pour mettre à jour automatiquement le robots.txt avec les pages de villes

ROBOTS_FILE="/media/otto/stock3/INFORMATIQUE/ejeri.fr/robots.txt"
VILLES_DIR="/media/otto/stock3/INFORMATIQUE/ejeri.fr/services/villes"

# Fonction pour générer la section des pages de villes dans robots.txt
generate_robots_villes_section() {
    echo "# Pages de villes autorisées pour l'indexation"
    
    # Lister tous les fichiers .php dans le dossier villes et les trier
    if [[ -d "$VILLES_DIR" ]]; then
        find "$VILLES_DIR" -name "*.php" -type f | sort | while read -r file; do
            # Extraire le nom du fichier sans le chemin complet
            filename=$(basename "$file")
            echo "Allow: /services/villes/$filename"
        done
    else
        echo "# Aucun fichier de ville trouvé"
    fi
}

# Fonction pour mettre à jour le robots.txt
update_robots_txt() {
    if [[ ! -f "$ROBOTS_FILE" ]]; then
        echo "❌ Fichier robots.txt non trouvé: $ROBOTS_FILE"
        exit 1
    fi
    
    echo "🤖 Mise à jour du fichier robots.txt..."
    
    # Créer un fichier temporaire
    temp_file=$(mktemp)
    
    # Copier le début du fichier jusqu'à la section des villes
    sed '/# Pages de villes autorisées pour l.indexation/,$d' "$ROBOTS_FILE" > "$temp_file"
    
    # Ajouter la nouvelle section des villes
    generate_robots_villes_section >> "$temp_file"
    
    # Ajouter la fin du fichier (favicon, sitemap, etc.)
    echo "" >> "$temp_file"
    echo "Allow: /favicon.png" >> "$temp_file"
    echo "Allow: /robots.txt" >> "$temp_file"
    echo "Allow: /sitemap.xml" >> "$temp_file"
    
    # Remplacer le fichier original
    mv "$temp_file" "$ROBOTS_FILE"
    
    echo "✅ robots.txt mis à jour avec $(find "$VILLES_DIR" -name "*.php" | wc -l) pages de villes"
}

# Fonction principale
main() {
    echo "🔄 Mise à jour automatique du robots.txt pour EJERI Jardins"
    echo "📁 Répertoire des villes: $VILLES_DIR"
    echo "🤖 Fichier robots.txt: $ROBOTS_FILE"
    echo ""
    
    update_robots_txt
    
    echo ""
    echo "📊 Statistiques:"
    echo "- Nombre de pages de villes: $(find "$VILLES_DIR" -name "*.php" 2>/dev/null | wc -l)"
    echo "- Taille du robots.txt: $(wc -l < "$ROBOTS_FILE") lignes"
    
    echo ""
    echo "💡 Pour utiliser ce script automatiquement après génération des pages:"
    echo "   ./generate_ville_pages.sh && ./update_robots.sh"
}

# Vérifications préalables
if [[ ! -d "$VILLES_DIR" ]]; then
    echo "⚠️  Répertoire des villes non trouvé: $VILLES_DIR"
    echo "   Exécutez d'abord le script generate_ville_pages.sh"
    exit 1
fi

# Exécution
main "$@"
