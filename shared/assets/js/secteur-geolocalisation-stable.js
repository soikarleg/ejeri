/**
 * Système de géolocalisation par secteur EJERI - Version Stable
 * Gère l'affichage des informations de secteur selon l'IP utilisateur
 * Version optimisée pour éviter le scintillement et les rechargements multiples
 */
class SecteurGeolocalisation {
    constructor() {
        this.apiUrl = '/api';
        this.secteurContainer = document.getElementById('secteur-info');
        this.teamContainer = document.getElementById('team-secteur');
        this.secteurSelecteur = document.getElementById('secteur-selecteur');
        this.secteurActuel = null;
        this.isLoading = false;
        this.isInitialized = false;
        this.imageCache = new Map(); // Cache pour les images préchargées
        
        this.initEventListeners();
    }

    /**
     * Initialise les événements
     */
    initEventListeners() {
        // Sélecteur manuel de secteur
        if (this.secteurSelecteur) {
            this.secteurSelecteur.addEventListener('change', (e) => {
                if (e.target.value !== this.secteurActuel?.id) {
                    this.changerSecteur(e.target.value);
                }
            });
        }

        // Bouton de rafraîchissement du cache
        const btnRefresh = document.getElementById('btn-refresh-secteur');
        if (btnRefresh) {
            btnRefresh.addEventListener('click', () => {
                this.viderCache();
            });
        }
    }

    /**
     * Précharge une image pour éviter le scintillement
     */
    async prechargerImage(src) {
        if (this.imageCache.has(src)) {
            return this.imageCache.get(src);
        }

        return new Promise((resolve) => {
            const img = new Image();
            img.onload = () => {
                this.imageCache.set(src, src);
                resolve(src);
            };
            img.onerror = () => {
                const defaultSrc = '/shared/assets/img/team/default-avatar.jpg';
                this.imageCache.set(src, defaultSrc);
                resolve(defaultSrc);
            };
            img.src = src;
        });
    }

    /**
     * Charge automatiquement le secteur selon l'IP
     */
    async chargerSecteurAuto() {
        // Éviter les rechargements multiples
        if (this.isLoading || this.isInitialized) {
            return;
        }
        
        this.isLoading = true;
        this.afficherChargementSiNecessaire();
        
        try {
            const response = await fetch(`${this.apiUrl}/secteur/by-ip`);
            const data = await response.json();
            
            if (data.success) {
                this.secteurActuel = data.secteur;
                
                // Précharger l'image avant d'afficher
                if (data.secteur.responsable?.photo) {
                    await this.prechargerImage(data.secteur.responsable.photo);
                }
                
                await this.afficherSecteurInfo(data.secteur);
                await this.afficherTeamSecteur(data.secteur);
                this.mettreAJourSelecteur(data.secteur.id);
                this.isInitialized = true;
            } else {
                console.error('Erreur API secteur:', data.error);
                this.afficherSecteurDefaut();
            }
        } catch (error) {
            console.error('Erreur réseau secteur:', error);
            this.afficherSecteurDefaut();
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Change manuellement de secteur
     */
    async changerSecteur(secteurId) {
        if (!secteurId || this.isLoading) return;

        this.isLoading = true;
        this.afficherChargementSiNecessaire();

        try {
            const response = await fetch(`${this.apiUrl}/secteur/set-force`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ secteur_id: secteurId })
            });

            const data = await response.json();
            
            if (data.success) {
                this.secteurActuel = data.secteur;
                
                // Précharger l'image avant d'afficher
                if (data.secteur.responsable?.photo) {
                    await this.prechargerImage(data.secteur.responsable.photo);
                }
                
                await this.afficherSecteurInfo(data.secteur);
                await this.afficherTeamSecteur(data.secteur);
                this.afficherNotification('Secteur sélectionné avec succès', 'success');
            } else {
                this.afficherNotification('Erreur lors du changement de secteur', 'error');
            }
        } catch (error) {
            console.error('Erreur changement secteur:', error);
            this.afficherNotification('Erreur réseau lors du changement', 'error');
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Vide le cache de géolocalisation
     */
    async viderCache() {
        if (this.isLoading) return;
        
        this.isLoading = true;
        try {
            const response = await fetch(`${this.apiUrl}/secteur/clear-cache`, {
                method: 'POST'
            });

            const data = await response.json();
            
            if (data.success) {
                this.afficherNotification('Cache vidé, rechargement...', 'success');
                this.isInitialized = false;
                this.imageCache.clear(); // Vider aussi le cache des images
                setTimeout(() => {
                    this.chargerSecteurAuto();
                }, 1000);
            }
        } catch (error) {
            console.error('Erreur vidage cache:', error);
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Affiche les informations du secteur dans l'interface (avec transition fluide)
     */
    async afficherSecteurInfo(secteur) {
        if (!this.secteurContainer) return;

        const html = `
            <div class="card border-0 myshadow secteur-card" style="opacity: 0; transition: opacity 0.3s ease;">
                <div class="card-header compte">
                    <i class="bi bi-geo-alt-fill me-2"></i>
                    ${secteur.nom}
                </div>
                <div class="card-body compte">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="bi bi-geo-alt me-2 text-primary"></i>
                                ${secteur.contact.adresse}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="bi bi-telephone-fill me-2 text-success"></i>
                                <a href="tel:${secteur.contact.telephone}" class="text-decoration-none">
                                    ${secteur.contact.telephone}
                                </a>
                            </p>
                            <p class="mb-0">
                                <i class="bi bi-envelope-fill me-2 text-info"></i>
                                <a href="mailto:${secteur.contact.email}" class="text-decoration-none">
                                    ${secteur.contact.email}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Transition fluide
        this.secteurContainer.innerHTML = html;
        
        // Animer l'apparition
        requestAnimationFrame(() => {
            const card = this.secteurContainer.querySelector('.secteur-card');
            if (card) {
                card.style.opacity = '1';
            }
        });
    }

    /**
     * Affiche les informations de l'équipe du secteur (avec gestion optimisée des images)
     */
    async afficherTeamSecteur(secteur) {
        if (!this.teamContainer) return;

        const responsable = secteur.responsable;
        
        // Récupérer l'URL de l'image (préchargée ou par défaut)
        const imageSrc = this.imageCache.get(responsable.photo) || '/shared/assets/img/team/default-avatar.jpg';
        
        const html = `
            <div class="col-lg-6 col-md-8 mx-auto team-member-container" 
                 style="opacity: 0; transition: opacity 0.3s ease;" 
                 data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 myshadow h-100">
                    <div class="text-center p-4">
                        <div class="member-image-container mb-3" style="position: relative; width: 120px; height: 120px; margin: 0 auto;">
                            <img src="${imageSrc}" 
                                 alt="${responsable.nom}" 
                                 class="rounded-circle member-image" 
                                 style="width: 120px; height: 120px; object-fit: cover; transition: all 0.3s ease;"
                                 loading="lazy">
                        </div>
                        <h5 class="card-title mb-2">${responsable.nom}</h5>
                        <p class="text-muted mb-3">${responsable.titre}</p>
                        <div class="mb-2">
                            <small class="text-primary">
                                <i class="bi bi-geo-alt me-1"></i>
                                ${secteur.nom}
                            </small>
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-telephone me-2 text-success"></i>
                            <a href="tel:${responsable.telephone}" class="text-decoration-none">
                                ${responsable.telephone}
                            </a>
                        </div>
                        <div class="mb-3">
                            <i class="bi bi-envelope me-2 text-info"></i>
                            <a href="mailto:${responsable.email}" class="text-decoration-none">
                                ${responsable.email}
                            </a>
                        </div>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="tel:${responsable.telephone}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-telephone"></i>
                            </a>
                            <a href="mailto:${responsable.email}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Transition fluide
        this.teamContainer.innerHTML = html;
        
        // Animer l'apparition
        requestAnimationFrame(() => {
            const container = this.teamContainer.querySelector('.team-member-container');
            if (container) {
                container.style.opacity = '1';
            }
        });
    }

    /**
     * Charge tous les secteurs pour le sélecteur
     */
    async chargerSelecteurSecteurs() {
        if (!this.secteurSelecteur) return;

        try {
            const response = await fetch(`${this.apiUrl}/secteur/all`);
            const data = await response.json();
            
            if (data.success) {
                let options = '<option value="">Détection automatique...</option>';
                data.secteurs.forEach(secteur => {
                    options += `<option value="${secteur.id}">${secteur.nom}</option>`;
                });
                this.secteurSelecteur.innerHTML = options;
            }
        } catch (error) {
            console.error('Erreur chargement secteurs:', error);
        }
    }

    /**
     * Met à jour le sélecteur avec le secteur actuel
     */
    mettreAJourSelecteur(secteurId) {
        if (this.secteurSelecteur && this.secteurSelecteur.value !== secteurId) {
            this.secteurSelecteur.value = secteurId;
        }
    }

    /**
     * Affiche l'indicateur de chargement seulement si nécessaire (sans scintillement)
     */
    afficherChargementSiNecessaire() {
        if (this.secteurContainer && !this.secteurContainer.innerHTML.includes('spinner')) {
            this.secteurContainer.innerHTML = `
                <div class="card border-0 myshadow" style="opacity: 1; transition: opacity 0.3s ease;">
                    <div class="card-body text-center">
                        <div class="spinner-border text-success mb-3" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mb-0">Détection de votre secteur...</p>
                    </div>
                </div>
            `;
        }

        if (this.teamContainer && !this.teamContainer.innerHTML.includes('spinner')) {
            this.teamContainer.innerHTML = `
                <div class="col-12 text-center" style="opacity: 1; transition: opacity 0.3s ease;">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Chargement équipe...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement de votre équipe locale...</p>
                </div>
            `;
        }
    }

    /**
     * Affiche un secteur par défaut en cas d'erreur
     */
    afficherSecteurDefaut() {
        if (this.secteurContainer) {
            this.secteurContainer.innerHTML = `
                <div class="card border-0 myshadow">
                    <div class="card-header compte">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        Secteur non déterminé
                    </div>
                    <div class="card-body compte">
                        <p class="mb-2">
                            <i class="bi bi-info-circle me-2 text-warning"></i>
                            Nous n'avons pas pu déterminer votre secteur automatiquement.
                        </p>
                        <p class="mb-0">
                            Veuillez utiliser le sélecteur ci-dessus pour choisir votre secteur.
                        </p>
                    </div>
                </div>
            `;
        }
        
        if (this.teamContainer) {
            this.teamContainer.innerHTML = `
                <div class="col-12 text-center">
                    <div class="card border-0 myshadow">
                        <div class="card-body">
                            <i class="bi bi-people-fill text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5>Équipe non disponible</h5>
                            <p class="text-muted">
                                Sélectionnez un secteur pour voir votre équipe locale.
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Affiche une notification temporaire
     */
    afficherNotification(message, type = 'info') {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 'alert-info';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show notification-secteur" 
                 style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', alertHtml);
        
        // Auto-suppression après 3 secondes
        setTimeout(() => {
            const alert = document.querySelector('.notification-secteur');
            if (alert) {
                alert.remove();
            }
        }, 3000);
    }

    /**
     * Obtient le secteur actuellement sélectionné
     */
    getSecteurActuel() {
        return this.secteurActuel;
    }

    /**
     * Active le mode diagnostic pour troubleshooting
     */
    async activerModeDiagnostic() {
        if (typeof window !== 'undefined' && window.location.search.includes('debug=1')) {
            console.log('🔍 Mode diagnostic activé pour SecteurGeolocalisation');
            
            try {
                const response = await fetch(`${this.apiUrl}/secteur/diagnostic`);
                const data = await response.json();
                
                if (data.success) {
                    console.log('📊 Diagnostic complet:', data.diagnostic);
                    
                    // Afficher les informations IP
                    console.log(`🌐 IP détectée: ${data.diagnostic.ip_detectee} (${data.diagnostic.type_ip})`);
                    console.log(`🏠 IP locale: ${data.diagnostic.is_local}`);
                    
                    // Afficher les tests API
                    if (data.diagnostic.test_apis) {
                        console.log('🔗 Tests APIs:');
                        Object.entries(data.diagnostic.test_apis).forEach(([api, result]) => {
                            console.log(`  ${api}: ${result.success ? '✅' : '❌'} ${result.has_coordinates ? '(coordonnées OK)' : '(pas de coordonnées)'}`);
                        });
                    }
                }
            } catch (error) {
                console.error('❌ Erreur diagnostic:', error);
            }
        }
    }
}

// Auto-initialisation avec protection renforcée contre les doubles chargements
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si déjà initialisé
    if (window.secteurGeo) {
        console.log('SecteurGeolocalisation déjà initialisé');
        return;
    }
    
    console.log('Initialisation SecteurGeolocalisation');
    window.secteurGeo = new SecteurGeolocalisation();
    
    // Activer le diagnostic si demandé
    window.secteurGeo.activerModeDiagnostic();
    
    // Charger le secteur automatiquement avec un délai plus court
    setTimeout(() => {
        if (window.secteurGeo && !window.secteurGeo.isInitialized) {
            window.secteurGeo.chargerSecteurAuto();
            window.secteurGeo.chargerSelecteurSecteurs();
        }
    }, 300);
});
