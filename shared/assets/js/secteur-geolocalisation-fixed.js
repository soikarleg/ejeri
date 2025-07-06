/**
 * Système de géolocalisation par secteur EJERI
 * Gère l'affichage des informations de secteur selon l'IP utilisateur
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
                this.afficherSecteurInfo(data.secteur);
                this.afficherTeamSecteur(data.secteur);
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
                this.afficherSecteurInfo(data.secteur);
                this.afficherTeamSecteur(data.secteur);
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
     * Affiche les informations du secteur dans l'interface
     */
    afficherSecteurInfo(secteur) {
        if (!this.secteurContainer) return;

        const html = `
            <div class="card border-0 myshadow">
                <div class="card-header compte">
                    <i class="bi bi-geo-alt-fill me-2"></i>
                    ${secteur.nom}
                </div>
                <div class="card-body compte">
                    <div class="mb-3">
                        <i class="bi bi-house-door me-2 text-warning"></i>
                        <small>${secteur.contact.adresse}</small>
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-telephone me-2 text-success"></i>
                        <a href="tel:${secteur.contact.telephone}" class="text-decoration-none text-white">
                            ${secteur.contact.telephone}
                        </a>
                    </div>
                    <div class="mb-0">
                        <i class="bi bi-envelope me-2 text-info"></i>
                        <a href="mailto:${secteur.contact.email}" class="text-decoration-none text-white">
                            ${secteur.contact.email}
                        </a>
                    </div>
                </div>
            </div>
        `;
        
        this.secteurContainer.innerHTML = html;
    }

    /**
     * Affiche les informations de l'équipe du secteur
     */
    afficherTeamSecteur(secteur) {
        if (!this.teamContainer) return;

        const responsable = secteur.responsable;
        
        const html = `
            <div class="col-lg-6 col-md-8 mx-auto" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 myshadow h-100">
                    <div class="text-center p-4">
                        <img src="${responsable.photo}" 
                             alt="${responsable.nom}" 
                             class="rounded-circle mb-3" 
                             style="width: 120px; height: 120px; object-fit: cover;"
                             onerror="this.src='/shared/assets/img/team/default-avatar.jpg'">
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
        
        this.teamContainer.innerHTML = html;
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
     * Affiche l'indicateur de chargement seulement si nécessaire
     */
    afficherChargementSiNecessaire() {
        if (this.secteurContainer && !this.secteurContainer.innerHTML.includes('spinner')) {
            this.secteurContainer.innerHTML = `
                <div class="card border-0 myshadow">
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
                <div class="col-12 text-center">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Chargement équipe...</span>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Affiche le secteur par défaut en cas d'erreur
     */
    afficherSecteurDefaut() {
        const secteurDefaut = {
            nom: "EJERI Jardins",
            contact: {
                adresse: "Nous intervenons dans 5 secteurs",
                telephone: "02 40 89 15 42",
                email: "contact@ejeri.fr"
            },
            responsable: {
                nom: "Équipe EJERI Jardins",
                titre: "Service client",
                photo: "/shared/assets/img/team/equipe-ejeri.jpg",
                telephone: "02 40 89 15 42",
                email: "contact@ejeri.fr"
            }
        };
        
        this.afficherSecteurInfo(secteurDefaut);
        this.afficherTeamSecteur(secteurDefaut);
    }

    /**
     * Affiche une notification à l'utilisateur
     */
    afficherNotification(message, type = 'info') {
        // Supprimer les notifications existantes
        const existingAlerts = document.querySelectorAll('.notification-secteur');
        existingAlerts.forEach(alert => alert.remove());

        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
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
}

// Auto-initialisation avec protection contre les doubles chargements
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si déjà initialisé
    if (window.secteurGeo) {
        return;
    }
    
    window.secteurGeo = new SecteurGeolocalisation();
    
    // Charger le secteur automatiquement avec un petit délai
    setTimeout(() => {
        window.secteurGeo.chargerSecteurAuto();
        window.secteurGeo.chargerSelecteurSecteurs();
    }, 500);
});
