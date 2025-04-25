<div id="personal-info-section" class="card-body">
    <h2>Informations Personnelles</h2>
    <p>Ces informations seront utilisées pour pré-remplir vos formulaires lors de vos démarches.</p>
    
    <form id="personal-info-form">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="pi_civilite">Civilité:</label>
                    <select class="form-control" id="pi_civilite">
                        <option value="Monsieur">Monsieur</option>
                        <option value="Madame">Madame</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pi_nom">Nom:</label>
                    <input type="text" class="form-control" id="pi_nom">
                </div>
                <div class="form-group">
                    <label for="pi_prenom">Prénom:</label>
                    <input type="text" class="form-control" id="pi_prenom">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="pi_nom_usage">Nom d'usage (facultatif):</label>
                    <input type="text" class="form-control" id="pi_nom_usage">
                </div>
                <div class="form-group">
                    <label for="pi_complement_adresse">Complément d'adresse:</label>
                    <input type="text" class="form-control" id="pi_complement_adresse">
                </div>
                <div class="form-group">
                    <label for="pi_code_postal">Code postal:</label>
                    <input type="text" class="form-control" id="pi_code_postal">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="pi_ville">Ville:</label>
                    <input type="text" class="form-control" id="pi_ville">
                </div>
                <div class="form-group">
                    <label for="pi_pays">Pays:</label>
                    <select class="form-control" id="pi_pays" data-live-search="true">
                        <option>France métropolitaine</option>
                        <option>Guadeloupe</option>
                        <option>Martinique</option>
                        <option>Guyane</option>
                        <option>La Réunion</option>
                        <option>Mayotte</option>
                        <!-- Additional countries can be added here -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="pi_telephone">Téléphone:</label>
                    <input type="tel" class="form-control" id="pi_telephone">
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <button type="button" id="save-personal-info" class="btn btn-primary">Enregistrer</button>
                <div id="personal-info-status" class="mt-2" style="display: none;"></div>
            </div>
        </div>
    </form>
</div>
