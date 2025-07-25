<section id="contact" class="contact section dark-background" style="padding-top:200px;padding-bottom:200px">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Nous contacter</h2>
        <p><span><img src="assets/img/png/enooki_ico_noir.png" alt="EJERI Jardins - Demande de devis gratuit" height="35px" style="margin-top:-5px;margin-right:10px"></span>Demande de devis ou de renseignements</p>
    </div><!-- End Section Title -->
    <div class="container" data-aos="fade" data-aos-delay="100">

        <div class="row gy-4">
            <div class="col-lg-8">
                <form action="forms/message.php" method="post" class="php-email-form" data-aos="fade-up"
                    data-aos-delay="200">
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <input type="text" name="prenom" class="form-control" placeholder="Prénom">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="nom" class="form-control" placeholder="Nom" required="">
                        </div>
                        <div class="col-md-6 ">
                            <input type="email" class="form-control" name="email" placeholder="Email" required="">
                        </div>
                        <div class="col-md-6 ">
                            <input type="text" class="form-control" name="telephone" placeholder="Téléphone">
                        </div>
                        <div class="col-md-6 ">
                            <input type="text" class="form-control" id="cp_form" name="code_postal" placeholder="Code postal">
                        </div>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="sujet" id="sujet" placeholder="Sujet" required="">
                        </div>

                        <input type="hidden" name="token" id="token" value="<?= $token ?>" required="">

                        <input type="hidden" name="myadresse" id="myadresse" required="">

                        <div class="col-md-12">
                            <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                        </div>
                        <div class="col-md-12 text-center">
                            <div class="loading">Envoi du message</div>
                            <div class="error-message"></div>
                            <div class="sent-message">Votre message a été envoyé. Nous y répondrons dès que possible. Merci.</div>
                            <button type="submit" class="btn-enooki">Envoyer votre demande</button>
                        </div>
                    </div>
                </form>
            </div><!-- End Contact Form -->
            <div class="col-lg-4">
                <!-- Zone d'affichage du secteur détecté 
                <div id="secteur-info" class="mb-4">
                    
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Détection de votre secteur...</span>
                        </div>
                    </div>
                </div>-->
                <!-- Informations générales -->
                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                    <i class="bi bi-geo-alt flex-shrink-0"></i>
                    <div id="secteur-adresse">
                        <h3>Siège social</h3>
                        <p>3 place de l'Eglise 45740 Lailly-en-Val</p>
                        <p>RCS Orléans 80291044800033</p>
                        <p>N° agrément simple SAP802910448</p>
                        <p>02.38.45.15.78</p>
                        <p><a href="mailto:contact@ejeri.fr">contact@ejeri.fr</a></p>
                    </div>
                </div><!-- End Info Item -->

                <!-- Bouton pour forcer l'affichage de tous les secteurs 
                <div class=" info-item d-flex" data-aos="fade-up" data-aos-delay="500">
                                <i class="bi bi-buildings flex-shrink-0"></i>
                                <div>
                                    <h3>Tous nos secteurs</h3>
                                    <button id="btn-show-all-secteurs" class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#all-secteurs-contact">
                                        Voir tous les secteurs
                                    </button>
                                    <div class="collapse mt-3" id="all-secteurs-contact">
                                        <div class="list-group list-group-flush">
                                            <div class="list-group-item px-0">
                                                <strong>Nantes</strong><br>
                                                <small>02 40 89 15 42 - nantes@ejeri.fr</small>
                                            </div>
                                            <div class="list-group-item px-0">
                                                <strong>Cholet</strong><br>
                                                <small>02 41 75 28 93 - cholet@ejeri.fr</small>
                                            </div>
                                            <div class="list-group-item px-0">
                                                <strong>La Roche-sur-Yon</strong><br>
                                                <small>02 51 62 84 37 - larochesuryon@ejeri.fr</small>
                                            </div>
                                            <div class="list-group-item px-0">
                                                <strong>Orléans</strong><br>
                                                <small>02 38 94 51 76 - orleans@ejeri.fr</small>
                                            </div>
                                            <div class="list-group-item px-0">
                                                <strong>Lamotte-Beuvron</strong><br>
                                                <small>02 54 73 69 28 - lamottebeuvron@ejeri.fr</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
                    </div><!-- End Info Item -->
                </div>
            </div>
        </div>
</section>