<div class="container" style="">
  <div class="row mt-3">
    <div class="col">
      <div class="form-check">
        <input class="form-check-input" type="radio" name="typeContactCotitulaire{{uniqueId}}"
          id="particulierCotitulaire{{uniqueId}}" checked>
        <label class="form-check-label" for="particulierCotitulaire{{uniqueId}}">Particulier</label>
      </div>
    </div>
    <div class="col">
      <div class="form-check">
        <input class="form-check-input" type="radio" name="typeContactCotitulaire{{uniqueId}}"
          id="entrepriseCotitulaire{{uniqueId}}">
        <label class="form-check-label" for="entrepriseCotitulaire{{uniqueId}}">Entreprise</label>
      </div>
    </div>
  </div>

  <div id="formParticulierCotitulaire{{uniqueId}}">
    <div class="row mt-3">
      <div class="col">
        <label for="civiliteCotitulaire{{uniqueId}}">Civilité</label>
        <select class="form-control" id="civiliteCotitulaire{{uniqueId}}">
          <option>Monsieur</option>
          <option>Madame</option>
        </select>
      </div>
      <div class="col">
        <label for="nomCotitulaire{{uniqueId}}">Nom</label>
        <input type="text" class="form-control" id="nomCotitulaire{{uniqueId}}">
      </div>
      <div class="col">
        <label for="prenomCotitulaire{{uniqueId}}">Prénom</label>
        <input type="text" class="form-control" id="prenomCotitulaire{{uniqueId}}">
      </div>
    </div>
    <div class="row mt-3">
      <div class="col">
        <label for="nomUsageCotitulaire{{uniqueId}}">Nom d'usage (facultatif)</label>
        <input type="text" class="form-control" id="nomUsageCotitulaire{{uniqueId}}">
      </div>
    </div>
  </div>

  <div id="formEntrepriseCotitulaire{{uniqueId}}" style="display: none;">
    <div class="row mt-3">
      <div class="col">
        <label for="raisonSocialeCotitulaire{{uniqueId}}">Raison sociale</label>
        <input type="text" class="form-control" id="raisonSocialeCotitulaire{{uniqueId}}" maxlength="50">
      </div>
      <div class="col">
        <label for="siretCotitulaire{{uniqueId}}">N° SIRET</label>
        <input type="tel" class="form-control" id="siretCotitulaire{{uniqueId}}" maxlength="17">
      </div>
    </div>
  </div>
</div>