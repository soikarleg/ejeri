<section id="contact" class="contact section dark-background" style="padding-top:200px;padding-bottom:200px">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Nous contacter</h2>
        <p><span><img src="assets/img/png/enooki_ico_noir.png" alt="" height="35px" style="margin-top:-5px;margin-right:10px"></span>Demande de devis ou de renseignements</p>
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
                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                    <i class="bi bi-geo-alt flex-shrink-0"></i>
                    <div>
                        <h3>Par la voie postale</h3>
                        <p>3 place de l'Eglise 45740 Lailly-en-Val</p>
                        <p>RCS Orléans 000 000 000</p>
                        <p>N° agrément simple SAP000 000 000 du 01/02/2025</p>
                    </div>
                </div><!-- End Info Item -->
                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                    <i class="bi bi-telephone flex-shrink-0"></i>
                    <div>
                        <h3>Pour nous appeller</h3>
                        <p>+33 02 38 44 00 00</p>
                    </div>
                </div><!-- End Info Item -->
                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                    <i class="bi bi-envelope flex-shrink-0"></i>
                    <div>
                        <h3>Par email directement</h3>
                        <p>contact@enooki.fr</p>
                    </div>
                </div><!-- End Info Item -->
            </div>
        </div>
    </div>
</section>