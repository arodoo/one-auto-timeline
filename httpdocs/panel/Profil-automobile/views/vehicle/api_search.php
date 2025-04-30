<?php
// filepath: panel/Profil-automobile/views/vehicle/api_search.php
global $path_cms_general;
?>
<div class="card mb-4">
    <div class="card-header">
        <h4>Recherche par immatriculation</h4>
    </div>
    <div class="card-body">
        <p class="mb-3">
            Entrez l'immatriculation de votre véhicule pour obtenir automatiquement ses caractéristiques.
        </p>

        <form id="api-search-form" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="voir_immatriculation" class="required">Immatriculation</label>
                        <input type="text" class="form-control" id="voir_immatriculation" name="voir_immatriculation" 
                               placeholder="AB-123-CD" required>
                        <small class="form-text text-muted">Format: AA-123-BB ou AA123BB</small>
                    </div>
                </div>
                <div class="col-md-6 align-self-end">
                    <button type="submit" class="btn btn-primary" id="api-search-btn">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </div>
            </div>
        </form>

        <!-- Loading indicator -->
        <div id="loading-screen" style="display: none;">
            <div class="d-flex justify-content-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
            </div>
            <p class="text-center mt-2">Recherche en cours, veuillez patienter...</p>
        </div>

        <!-- API results will be shown here -->
        <div id="vehicle-form-container" style="display: none;">
            <hr>
            <h5>Informations du véhicule</h5>
            
            <form id="vehicle-api-form">
                <input type="hidden" id="aim_source" name="source" value="api">
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="aim_immat" class="required">Immatriculation</label>
                            <input type="text" class="form-control" id="aim_immat" name="immat" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="aim_marque" class="required">Marque</label>
                            <input type="text" class="form-control" id="aim_marque" name="marque">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="aim_modele" class="required">Modèle</label>
                            <input type="text" class="form-control" id="aim_modele" name="modele">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aim_date1erCir_fr" class="required">Date 1ère circulation (FR)</label>
                            <input type="text" class="form-control" id="aim_date1erCir_fr" name="date1erCir_fr" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aim_date1erCir_us" class="required">Date 1ère circulation (US)</label>
                            <input type="hidden" id="aim_date1erCir_us" name="date1erCir_us">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aim_energieNGC" class="required">Énergie</label>
                            <input type="text" class="form-control" id="aim_energieNGC" name="energieNGC">
                            <input type="hidden" id="aim_energie" name="energie">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aim_couleur" class="required">Couleur</label>
                            <input type="text" class="form-control" id="aim_couleur" name="couleur">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aim_puisFisc" class="required">Puissance fiscale</label>
                            <input type="text" class="form-control" id="aim_puisFisc" name="puisFisc">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aim_boite_vitesse" class="required">Boîte de vitesse</label>
                            <select class="form-control" id="aim_boite_vitesse" name="boite_vitesse">
                                <option value="M">Manuelle</option>
                                <option value="A">Automatique</option>
                                <option value="SA">Semi-automatique</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aim_nb_portes">Nombre de portes</label>
                            <input type="number" class="form-control" id="aim_nb_portes" name="nb_portes" min="1">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aim_nr_passagers">Nombre de places</label>
                            <input type="number" class="form-control" id="aim_nr_passagers" name="nr_passagers" min="1">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aim_co2">CO2 (g/km)</label>
                            <input type="text" class="form-control" id="aim_co2" name="co2">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aim_cylindres">Cylindrée (cm³)</label>
                            <input type="text" class="form-control" id="aim_cylindres" name="cylindres">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aim_puisFiscReel">Puissance réelle (ch)</label>
                            <input type="text" class="form-control" id="aim_puisFiscReel" name="puisFiscReel">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aim_poids">Poids (kg)</label>
                            <input type="text" class="form-control" id="aim_poids" name="poids">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="aim_carrosserieCG">Type de carrosserie</label>
                            <input type="text" class="form-control" id="aim_carrosserieCG" name="carrosserieCG">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="aim_genreVCGNGC">Genre</label>
                            <input type="text" class="form-control" id="aim_genreVCGNGC" name="genreVCGNGC">
                            <input type="hidden" id="aim_genreVCG" name="genreVCG">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="aim_vin">Numéro de série (VIN)</label>
                            <input type="text" class="form-control" id="aim_vin" name="vin">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="aim_date_dernier_control_tecnique">Date du dernier contrôle technique</label>
                            <input type="date" class="form-control" id="aim_date_dernier_control_tecnique" name="date_dernier_control_tecnique">
                        </div>
                    </div>
                </div>

                <!-- Hidden fields for other data -->
                <input type="hidden" id="aim_type_mine" name="type_mine">
                <input type="hidden" id="aim_collection" name="collection">
                <input type="hidden" id="aim_date30" name="date30">
                <input type="hidden" id="aim_code_moteur" name="code_moteur">
                <input type="hidden" id="aim_k_type" name="k_type">
                <input type="hidden" id="aim_db_c" name="db_c">
                <input type="hidden" id="aim_sra_id" name="sra_id">
                <input type="hidden" id="aim_sra_group" name="sra_group">
                <input type="hidden" id="aim_sra_commercial" name="sra_commercial">
                <input type="hidden" id="aim_logo_marque" name="logo_marque">

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Enregistrer ce véhicule
                    </button>
                    <a href="<?php echo $path_cms_general; ?>Profil-automobile" class="btn btn-outline-secondary ml-2">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4>Vous ne trouvez pas votre véhicule ?</h4>
    </div>
    <div class="card-body">
        <p>Si la recherche par immatriculation ne donne pas de résultat ou si les informations sont incorrectes, vous pouvez saisir manuellement les caractéristiques de votre véhicule.</p>
        <a href="<?php echo $path_cms_general; ?>Profil-automobile?action=manual_form" class="btn btn-primary">
            <i class="fas fa-pencil-alt"></i> Saisie manuelle
        </a>
    </div>
</div>