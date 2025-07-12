<section id="team" class="team section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Votre contact local</h2>
        <p><span><img src="assets/img/png/enooki_ico_blanc.png" alt="" height="35px" style="margin-top:-5px;margin-right:10px"></span>L'équipe de votre secteur</p>
    </div>

    <div class="container">
        <!-- Sélecteur manuel de secteur (optionnel) -->
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <div class="card border-0 myshadow">
                    <div class="card-body">
                        <label for="secteur-selecteur" class="form-label">
                            <i class="bi bi-geo-alt me-2"></i>Choisir un secteur
                        </label>
                        <select id="secteur-selecteur" class="form-select">
                            <option value="">Détection automatique...</option>
                        </select>
                        <small class="text-muted">
                            Votre secteur est détecté automatiquement selon votre localisation
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zone d'affichage de l'équipe du secteur -->
        <div class="row gy-4" id="team-secteur">
            <!-- Le contenu sera chargé via JavaScript -->
            <div class="col-12 text-center">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Chargement de votre équipe locale...</span>
                </div>
            </div>
        </div>

        <!-- Équipe complète (masquée par défaut, affichée via bouton) -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <button id="btn-show-all-team" class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#all-team" aria-expanded="false">
                    <i class="bi bi-people me-2"></i>Voir toute l'équipe EJERI
                </button>
            </div>
        </div>

        <div class="collapse mt-4" id="all-team">
            <div class="row gy-4">
                <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
                    <div class="team-member">
                        <div class="member-img">
                            <img src="assets/img/enooki_photos/clisson.png" class="img-fluid" alt="Entretien de jardins Clisson" />
                        </div>
                        <div class="member-info">
                            <h4>Renaud</h4>
                            <span>Jardinier Nantes/Clisson</span>
                            <p>+33659238028</p>
                            <a href="mailto:renaud@enooki.fr">renaud@enooki.fr</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
                    <div class="team-member">
                        <div class="member-img">
                            <img src="assets/img/enooki_photos/beaugency.png" class="img-fluid" alt="Entretien de jardins Beaugency">
                        </div>
                        <div class="member-info">
                            <h4>François</h4>
                            <span>Jardinier Orléans/Beaugency</span>
                            <p>+33665677503</p>
                            <a href="mailto:francois@enooki.fr">francois@enooki.fr</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="300">
                    <div class="team-member">
                        <div class="member-img">
                            <img src="assets/img/enooki_photos/lailly.png" class="img-fluid" alt="Entretien de jardins Lailly-en-Val">
                        </div>
                        <div class="member-info">
                            <h4>Adrien</h4>
                            <span>Jardinier Orléans/Lailly-en-Val</span>
                            <p>+33665677503</p>
                            <a href="mailto:adrien@enooki.fr">adrien@enooki.fr</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="400">
                    <div class="team-member">
                        <div class="member-img">
                            <img src="assets/img/enooki_photos/enooki.png" class="img-fluid" alt="Entretien de jardins - Cugand Cholet La Roche-sur-Yon Lamotte-Beuvron">
                            <div class="social p-2">
                                <p class="text-white">Contact pro 0238451578</p>
                            </div>
                        </div>
                        <div class="member-info">
                            <h4>Rejoignez-nous</h4>
                            <span>N'hésitez pas à nous contacter</span>
                            <p>+33238451578</p>
                            <a href="mailto:contact@enooki.fr">contact@enooki.fr</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <img src="assets/img/svg/grass_noir_1.svg" alt="Fond - Entretien de jardins" srcset="" style="width:100%;margin: -20px 0px -1px auto">
</section>