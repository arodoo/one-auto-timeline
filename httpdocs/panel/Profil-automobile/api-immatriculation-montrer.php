<div id="aimm-container" class="card-body" style="display: none;">
    <h1>Vérifier les informations du véhicule</h1>
    <form>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="aim_energie">Énergie:</label>
                    <input type="text" class="form-control" id="aim_energie">
                </div>
                <div class="form-group">
                    <label for="aim_genreVCGNGC">Genre du Véhicule (NGC):</label>
                    <input type="text" class="form-control" id="aim_genreVCGNGC">
                </div>
                <div class="form-group">
                    <label for="aim_marque">Marque:</label>
                    <input type="text" class="form-control" id="aim_marque">
                </div>
                <div class="form-group">
                    <label for="aim_date1erCir_fr">Date de Première Circulation (FR):</label>
                    <input type="text" class="form-control" id="aim_date1erCir_fr">
                </div>
                <div class="form-group">
                    <label for="aim_vin">Numéro d'Identification du Véhicule (VIN):</label>
                    <input type="text" class="form-control" id="aim_vin">
                </div>
                <div class="form-group">
                    <label for="aim_nr_passagers">Nombre de Passagers:</label>
                    <input type="text" class="form-control" id="aim_nr_passagers">
                </div>
                <div class="form-group">
                    <label for="aim_couleur">Couleur:</label>
                    <input type="text" class="form-control" id="aim_couleur">
                </div>
                <div class="form-group">
                    <label for="aim_sra_id">ID SRA:</label>
                    <input type="text" class="form-control" id="aim_sra_id">
                </div>
                <div class="form-group">
                    <label for="aim_code_moteur">Code Moteur:</label>
                    <input type="text" class="form-control" id="aim_code_moteur">
                </div>
                <div class="form-group">
                    <label for="aim_db_c">DB C:</label>
                    <input type="text" class="form-control" id="aim_db_c">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="aim_immat">Immatriculation:</label>
                    <input type="text" class="form-control" id="aim_immat">
                </div>
                <div class="form-group">
                    <label for="aim_energieNGC">Énergie (NGC):</label>
                    <input type="text" class="form-control" id="aim_energieNGC">
                </div>
                <div class="form-group">
                    <label for="aim_puisFisc">Puissance Fiscale:</label>
                    <input type="text" class="form-control" id="aim_puisFisc">
                </div>
                <div class="form-group">
                    <label for="aim_modele">Modèle:</label>
                    <input type="text" class="form-control" id="aim_modele">
                </div>
                <div class="form-group">
                    <label for="aim_collection">Collection:</label>
                    <input type="text" class="form-control" id="aim_collection">
                </div>
                <div class="form-group">
                    <label for="aim_boite_vitesse">Boîte de Vitesse:</label>
                    <input type="text" class="form-control" id="aim_boite_vitesse">
                </div>
                <div class="form-group">
                    <label for="aim_nb_portes">Nombre de Portes:</label>
                    <input type="text" class="form-control" id="aim_nb_portes">
                </div>
                <div class="form-group">
                    <label for="aim_poids">Poids:</label>
                    <input type="text" class="form-control" id="aim_poids">
                </div>
                <div class="form-group">
                    <label for="aim_sra_group">Groupe SRA:</label>
                    <input type="text" class="form-control" id="aim_sra_group">
                </div>
                <div class="form-group">
                    <label for="aim_k_type">Type K:</label>
                    <input type="text" class="form-control" id="aim_k_type">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="aim_co2">Émissions de CO2:</label>
                    <input type="text" class="form-control" id="aim_co2">
                </div>
                <div class="form-group">
                    <label for="aim_genreVCG">Genre du Véhicule:</label>
                    <input type="text" class="form-control" id="aim_genreVCG">
                </div>
                <div class="form-group">
                    <label for="aim_carrosserieCG">Type de Carrosserie:</label>
                    <input type="text" class="form-control" id="aim_carrosserieCG">
                </div>
                <div class="form-group">
                    <label for="aim_date1erCir_us">Date de Première Circulation (US):</label>
                    <input type="text" class="form-control" id="aim_date1erCir_us">
                </div>
                <div class="form-group">
                    <label for="aim_date30">Date 30:</label>
                    <input type="text" class="form-control" id="aim_date30">
                </div>
                <div class="form-group">
                    <label for="aim_puisFiscReel">Puissance Fiscale Réelle:</label>
                    <input type="text" class="form-control" id="aim_puisFiscReel">
                </div>
                <div class="form-group">
                    <label for="aim_type_mine">Type Mine:</label>
                    <input type="text" class="form-control" id="aim_type_mine">
                </div>
                <div class="form-group">
                    <label for="aim_cylindres">Nombre de Cylindres:</label>
                    <input type="text" class="form-control" id="aim_cylindres">
                </div>
                <div class="form-group">
                    <label for="aim_sra_commercial">Nom Commercial (SRA):</label>
                    <input type="text" class="form-control" id="aim_sra_commercial">
                </div>
                <div class="form-group">
                    <label for="aim_date_dernier_control_tecnique">Date du Dernier Contrôle Technique:</label>
                    <input class="form-control" id="aim_date_dernier_control_tecnique" type="date">
                </div>
            </div>
        </div>
        <!-- Hidden fields for additional keys -->
        <input type="hidden" id="aim_erreur">
        <input type="hidden" id="aim_nbr_req_restants">
        <input type="hidden" id="aim_logo_marque">
    </form>
    <button class="btn" id="aimm-btn">Enregistrer</button>
    <button class="btn" id="modifier-btn" style="display: none; background-color:#d7d54b !important;">Modifier</button>
</div>