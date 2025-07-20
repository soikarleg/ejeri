#!/bin/bash

# Script de génération automatique des pages de villes pour EJERI Jardins
# Amélioration SEO avec pages dédiées par ville

# Configuration
SERVICES_DIR="/media/otto/stock3/INFORMATIQUE/ejeri.fr/services"
VILLES_DIR="$SERVICES_DIR/villes"
JSON_DIR="/media/otto/stock3/INFORMATIQUE/ejeri.fr/shared/json"

# Créer le répertoire des villes s'il n'existe pas
mkdir -p "$VILLES_DIR"

# Fonction pour nettoyer le nom de ville pour les URLs
clean_ville_name() {
    local ville="$1"
    echo "$ville" | tr '[:upper:]' '[:lower:]' | \
    sed 's/[éèê]/e/g; s/[àâ]/a/g; s/[ôö]/o/g; s/[ùûü]/u/g; s/[îï]/i/g; s/ç/c/g; s/[^a-z0-9]//g'
}

# Fonction pour déterminer la région selon le code postal
get_region_from_cp() {
    local cp="$1"
    
    case "${cp:0:2}" in
        "33")
            echo "Gironde"
            ;;
        "45")
            echo "Loiret"
            ;;
        "41")
            # Distinguer Loir-et-Cher et Sologne
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

# Fonction pour obtenir les coordonnées GPS selon la région
get_coordinates() {
    local region="$1"
    local ville_name="$2"
    
    case "$region" in
        "Gironde")
            # Coordonnées centrées sur Mios (33380)
            echo "44.6469,-0.8686"
            ;;
        "Loiret"|"Loir-et-Cher")
            # Coordonnées centrées sur Lailly-en-Val (45230)
            echo "47.7608,1.6931"
            ;;
        "Vendée")
            # Coordonnées centrées sur Cugand (85610)
            echo "47.0666,-1.2394"
            ;;
        "Sologne")
            # Coordonnées centrées sur Chaumont-sur-Tharonne (41600)
            echo "47.6167,1.9000"
            ;;
        *)
            # Coordonnées par défaut (Bordeaux)
            echo "44.8378,-0.5792"
            ;;
    esac
}

# Fonction pour générer une page de ville
generate_ville_page() {
    local ville_name="$1"
    local ville_cp="$2"
    local ville_region="$3"
    local clean_name=$(clean_ville_name "$ville_name")
    local file_path="$VILLES_DIR/${clean_name}.php"
    
    # Obtenir les coordonnées GPS appropriées
    local coordinates=$(get_coordinates "$ville_region" "$ville_name")
    local latitude=$(echo "$coordinates" | cut -d',' -f1)
    local longitude=$(echo "$coordinates" | cut -d',' -f2)
    
    cat > "$file_path" << EOF
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Services de jardinage professionnel à $ville_name ($ville_cp). Tonte de pelouse, taille de haies, entretien d'espaces verts. Devis gratuit et service à domicile.">
    <meta name="keywords" content="jardinier $ville_name, entretien jardin $ville_name, tonte pelouse $ville_cp, taille haies $ville_name, jardinage $ville_region">
    <meta name="author" content="EJERI Jardins">
    <title>Jardinier à $ville_name ($ville_cp) - EJERI Jardins | Services à domicile</title>
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://ejeri.fr/services/villes/${clean_name}.php">
    <meta property="og:title" content="Jardinier à $ville_name - EJERI Jardins">
    <meta property="og:description" content="Services de jardinage professionnel à $ville_name. Tonte, taille, entretien. Devis gratuit.">
    <meta property="og:image" content="https://ejeri.fr/assets/img/jardinage-${clean_name}.jpg">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://ejeri.fr/services/villes/${clean_name}.php">
    <meta property="twitter:title" content="Jardinier à $ville_name - EJERI Jardins">
    <meta property="twitter:description" content="Services de jardinage professionnel à $ville_name. Tonte, taille, entretien. Devis gratuit.">
    
    <link rel="canonical" href="https://ejeri.fr/services/villes/${clean_name}.php">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../shared/assets/css/enooki-min.css">
    
    <!-- JSON-LD Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "EJERI Jardins - $ville_name",
        "image": "https://ejeri.fr/assets/img/logo-ejeri.png",
        "telephone": "+33-X-XX-XX-XX-XX",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Zone d'intervention",
            "addressLocality": "$ville_name",
            "postalCode": "$ville_cp",
            "addressCountry": "FR",
            "addressRegion": "$ville_region"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": $latitude,
            "longitude": $longitude
        },
        "url": "https://ejeri.fr/services/villes/${clean_name}.php",
        "sameAs": [
            "https://www.facebook.com/ejeri.jardins",
            "https://www.instagram.com/ejeri.jardins"
        ],
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": [
                "Monday",
                "Tuesday", 
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday"
            ],
            "opens": "08:00",
            "closes": "18:00"
        },
        "serviceArea": {
            "@type": "GeoCircle",
            "geoMidpoint": {
                "@type": "GeoCoordinates",
                "latitude": $latitude,
                "longitude": $longitude
            },
            "geoRadius": "25000"
        },
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Services de jardinage",
            "itemListElement": [
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Tonte de pelouse à $ville_name",
                        "description": "Service de tonte de pelouse professionnel"
                    }
                },
                {
                    "@type": "Offer", 
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Taille de haies à $ville_name",
                        "description": "Taille et entretien de haies et arbustes"
                    }
                }
            ]
        },
        "areaServed": "$ville_name et ses environs",
        "description": "EJERI Jardins propose des services de jardinage professionnel à $ville_name : tonte de pelouse, taille de haies, entretien d'espaces verts. Intervention rapide et devis gratuit."
    }
    </script>
</head>

<body>
    <!-- Header -->
    <header class="bg-dark text-white">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="../../">
                    <i class="bi bi-flower1"></i> EJERI Jardins
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="../../">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../#contact">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Hero Section -->
        <section class="py-5 text-center">
            <div class="container">
                <h1 class="display-4 mb-3">
                    <i class="bi bi-geo-alt text-success"></i>
                    Jardinier à $ville_name ($ville_cp)
                </h1>
                <p class="lead mb-4">
                    Services professionnels de jardinage et entretien d'espaces verts à $ville_name
                </p>
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <p class="fs-5">
                            Découvrez nos services de jardinage à domicile à $ville_name : 
                            tonte de pelouse, taille de haies, entretien d'arbustes et bien plus encore.
                        </p>
                    </div>
                </div>
                <!--onclick="document.getElementById('sujet').value = 'Devis de broyage'" data-mdb-ripple-init-->
                <a href="../../#contact" class="btn btn-success btn-lg mt-3">
                    <i class="bi bi-telephone"></i> Devis Gratuit
                </a>
            </div>
        </section>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../../">Accueil</a></li>
                <li class="breadcrumb-item"><a href="../">Services</a></li>
                <li class="breadcrumb-item"><a href="../villes.php">Villes</a></li>
                <li class="breadcrumb-item active" aria-current="page">$ville_name</li>
            </ol>
        </nav>

        <!-- Services Section -->
        <section class="row mb-5">
            <div class="col-lg-8">
                <h2 class="h3 mb-4">
                    <i class="bi bi-scissors text-success"></i>
                    Nos services de jardinage à $ville_name
                </h2>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h3 class="h5 card-title">
                                    <i class="bi bi-tree text-success"></i>
                                    Tonte de pelouse
                                </h3>
                                <p class="card-text">
                                    Tonte professionnelle de votre pelouse à $ville_name. 
                                    Matériel adapté pour tous types de terrains.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h3 class="h5 card-title">
                                    <i class="bi bi-flower2 text-success"></i>
                                    Taille de haies
                                </h3>
                                <p class="card-text">
                                    Taille et formation de vos haies et arbustes. 
                                    Intervention selon les périodes optimales.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h3 class="h5 card-title">
                                    <i class="bi bi-brush text-success"></i>
                                    Entretien d'espaces verts
                                </h3>
                                <p class="card-text">
                                    Entretien complet de vos espaces verts : 
                                    désherbage, plantation, arrosage.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h3 class="h5 card-title">
                                    <i class="bi bi-tools text-success"></i>
                                    Petit bricolage jardin
                                </h3>
                                <p class="card-text">
                                    Installation de bordures, petites réparations, 
                                    aménagements légers de votre jardin.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h3 class="h5 card-title">
                            <i class="bi bi-info-circle text-primary"></i>
                            Pourquoi choisir EJERI Jardins ?
                        </h3>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success"></i>
                                Devis gratuit et sans engagement
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success"></i>
                                Interventions rapides à $ville_name
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success"></i>
                                Matériel professionnel
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success"></i>
                                Service à la personne (réduction fiscale 50%)
                            </li>
                        </ul>
                        
                        <div class="mt-4">
                            <h4 class="h6">Zone d'intervention</h4>
                            <p class="small text-muted">
                                $ville_name ($ville_cp) et communes environnantes dans un rayon de 25km.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Zone géographique -->
        <section class="mb-5">
            <div class="card">
                <div class="card-body">
                    <h2 class="h4 mb-3">
                        <i class="bi bi-map text-primary"></i>
                        Jardinage à $ville_name et alentours
                    </h2>
                    <p>
                        EJERI Jardins intervient à <strong>$ville_name ($ville_cp)</strong> 
                        et dans toutes les communes avoisinantes de $ville_region. 
                        Notre équipe se déplace rapidement pour tous vos besoins de jardinage.
                    </p>
                    <p>
                        Que vous habitiez en centre-ville de $ville_name ou dans les quartiers périphériques, 
                        nous adaptons nos services à votre environnement et à vos contraintes.
                    </p>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="text-center py-5 bg-success text-white rounded">
            <div class="container">
                <h2 class="h3 mb-3">Besoin d'un jardinier à $ville_name ?</h2>
                <p class="lead mb-4">
                    Contactez-nous dès maintenant pour un devis personnalisé et gratuit
                </p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <a href="../../#contact" class="btn btn-light btn-lg me-3">
                            <i class="bi bi-telephone"></i> Appeler maintenant
                        </a>
                        <a href="../../#contact" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-envelope"></i> Demande de devis
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>EJERI Jardins</h5>
                    <p class="small">
                        Services de jardinage professionnel à $ville_name et en $ville_region.
                        Entreprise de services à la personne.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="small mb-0">
                        &copy; 2025 EJERI Jardins. Tous droits réservés.
                    </p>
                    <p class="small">
                        <a href="../../mentions-legales.php" class="text-white-50">Mentions légales</a> |
                        <a href="../../politique-confidentialite.php" class="text-white-50">Confidentialité</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
EOF
    
    echo "✅ Page générée: $file_path"
}

# Fonction pour traiter un fichier JSON
process_json_file() {
    local json_file="$1"
    local region_name="$2"
    
    if [[ ! -f "$json_file" ]]; then
        echo "⚠️  Fichier JSON non trouvé: $json_file"
        return
    fi
    
    echo "📄 Traitement du fichier: $json_file"
    
    # Lire le JSON et générer les pages
    while IFS= read -r line; do
        if [[ "$line" =~ \"ville\"[[:space:]]*:[[:space:]]*\"([^\"]+)\" ]]; then
            ville_name="${BASH_REMATCH[1]}"
        fi
        if [[ "$line" =~ \"cp\"[[:space:]]*:[[:space:]]*\"([^\"]+)\" ]]; then
            ville_cp="${BASH_REMATCH[1]}"
            
            # Générer la page quand on a ville et cp
            if [[ -n "$ville_name" && -n "$ville_cp" ]]; then
                # Déterminer automatiquement la région si non spécifiée
                local auto_region=$(get_region_from_cp "$ville_cp")
                local final_region="${region_name:-$auto_region}"
                
                generate_ville_page "$ville_name" "$ville_cp" "$final_region"
                ville_name=""
                ville_cp=""
            fi
        fi
    done < "$json_file"
}

# Fonction principale
main() {
    echo "🚀 Génération des pages de villes pour EJERI Jardins"
    echo "📁 Répertoire de destination: $VILLES_DIR"
    
    # Traitement des fichiers JSON existants avec détection automatique des régions
    if [[ -f "$JSON_DIR/point_cugand.json" ]]; then
        process_json_file "$JSON_DIR/point_cugand.json" "Vendée"
    fi
    
    if [[ -f "$JSON_DIR/villes_gironde.json" ]]; then
        process_json_file "$JSON_DIR/villes_gironde.json" "Gironde"
    fi
    
    if [[ -f "$JSON_DIR/villes_loiret.json" ]]; then
        process_json_file "$JSON_DIR/villes_loiret.json" "Loiret"
    fi
    
    if [[ -f "$JSON_DIR/villes_loir_et_cher.json" ]]; then
        process_json_file "$JSON_DIR/villes_loir_et_cher.json" "Loir-et-Cher"
    fi
    
    # Générer également des pages pour les villes codées en dur avec coordonnées spécifiques
    echo "📄 Génération des pages pour les villes codées en dur..."
    
    # Gironde (coordonnées centrées sur Mios)
    generate_ville_page "Mios" "33380" "Gironde"
    generate_ville_page "Gujan-Mestras" "33470" "Gironde"
    generate_ville_page "Arès" "33460" "Gironde"
    generate_ville_page "Saint-Loubès" "33450" "Gironde"
    generate_ville_page "Ambarès-et-Lagrave" "33440" "Gironde"
    generate_ville_page "Bazas" "33430" "Gironde"
    generate_ville_page "Branne" "33420" "Gironde"
    generate_ville_page "Cadillac" "33410" "Gironde"
    generate_ville_page "Talence" "33400" "Gironde"
    generate_ville_page "Blaye" "33390" "Gironde"
    
    # Loiret (coordonnées centrées sur Lailly-en-Val)
    generate_ville_page "Baule" "45370" "Loiret"
    generate_ville_page "Beaugency" "45190" "Loiret"
    generate_ville_page "Chaingy" "45380" "Loiret"
    generate_ville_page "Cléry-Saint-André" "45370" "Loiret"
    generate_ville_page "Cravant" "45130" "Loiret"
    generate_ville_page "Dry" "45370" "Loiret"
    generate_ville_page "Lailly-en-Val" "45230" "Loiret"
    
    # Sologne (coordonnées centrées sur Chaumont-sur-Tharonne)
    generate_ville_page "Chaumont-sur-Tharonne" "41600" "Sologne"
    generate_ville_page "Lamotte-Beuvron" "41600" "Sologne"
    generate_ville_page "Nouan-le-Fuzelier" "41600" "Sologne"
    generate_ville_page "Vouzon" "41600" "Sologne"
    generate_ville_page "Neung-sur-Beuvron" "41210" "Sologne"
    generate_ville_page "Soings-en-Sologne" "41230" "Sologne"
    
    echo ""
    echo "✅ Génération terminée !"
    echo "📊 $(find "$VILLES_DIR" -name "*.php" | wc -l) pages générées"
    echo ""
    echo "💡 Pour utiliser ce script :"
    echo "   chmod +x generate_ville_pages.sh"
    echo "   ./generate_ville_pages.sh"
    echo ""
    echo "🔗 Les liens dans villes.php pointent maintenant vers ces pages"
}

# Vérification des permissions d'écriture
if [[ ! -w "$SERVICES_DIR" ]]; then
    echo "❌ Erreur: Impossible d'écrire dans $SERVICES_DIR"
    echo "   Vérifiez les permissions du répertoire"
    exit 1
fi

# Exécution du script
main "$@"
