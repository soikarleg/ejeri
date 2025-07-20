<section id="team" class="team section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Votre contact local</h2>
    <p><span><img src="favicon.png" alt="Entretien de jardins - Nantes Orléans - Cugand Lailly-en-Val - Lamotte-Beuvron" height="35px" style="margin-top:-5px;margin-right:10px"></span>L'équipe de votre secteur</p>
  </div>
  <div class="container">
    <!-- Saisie du code postal 
    <div class="row mb-4">
      <div class="col-md-6 mx-auto">
        <div class="card border-0 myshadow">
          <div class="card-body" style="border-radius: var(--border-radius);">
            <label for="code-postal" class="form-label">
              <i class="bi bi-geo-alt me-2"></i>Indiquez votre code postal
            </label>
            <div class="input-group">
              <input type="text"
                id="code-postal"
                class="form-control"
                placeholder="Ex: 75001"
                maxlength="5"
                pattern="[0-9]{5}">
              <button class="devis-btn" type="button" id="btn-rechercher">
                <i class="bi bi-search"></i> Rechercher
              </button>
            </div>
            <small class="text-muted">
              Saisissez votre code postal pour connaître votre équipe locale
            </small>
          </div>
        </div>
      </div>
    </div>-->

    <!-- Zone d'affichage de l'équipe du secteur -->
    <div class="row gy-4" id="team-secteur">
      <!-- Le contenu sera chargé via JavaScript -->
      <div class="col-md-6 col-sm-12 mx-auto text-center">
        <div class="card border-0 myshadow">
          <div class="card-body py-5">
            <i class="bi bi-geo-alt-fill text-muted mb-3" style="font-size: 3rem;"></i>
            <h5 class="text-muted">Saisissez votre code postal</h5>
            <div id="cpform" class="input-group" style="margin-bottom:5px;margin-left:auto;margin-right:auto">
              <input style="max-height:36px;border:1px solid #c8c8c8 !important;width:50% !important" type="text"
                id="code-postal-bas"
                class="form-control"
                placeholder="Votre code postal"
                maxlength="5"
                pattern="[0-9]{5}">
              <button class="devis-btn" type="button" id="btn-rechercher">
                <i class="bi bi-search"></i>

              </button>
            </div>
            <p class="text-muted">pour découvrir l'équipe qui intervient dans votre secteur</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <img src="assets/img/svg/grass_noir_1.svg" alt="Fond - Entretien de jardins" srcset="" style="width:100%;margin: -20px 0px -1px auto">
</section>