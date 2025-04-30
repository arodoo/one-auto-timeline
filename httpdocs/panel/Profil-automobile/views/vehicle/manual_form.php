<?php
// filepath: panel/Profil-automobile/views/vehicle/manual_form.php
global $path_cms_general;

// Check if we are in edit mode
$isEditMode = isset($vehicle) && !empty($vehicle);
$pageTitle = $isEditMode ? 'Modifier un véhicule' : 'Ajouter un véhicule manuellement';
?>

<div class="card">
    <div class="card-header">
        <h4><?php echo $pageTitle; ?></h4>
    </div>
    <div class="card-body">
        <form id="manual-vehicle-form">
            <?php if ($isEditMode): ?>
                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['id']; ?>">
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="immat" class="required">Immatriculation</label>
                        <input type="text" class="form-control" id="immat" name="immat" 
                               value="<?php echo $isEditMode ? htmlspecialchars($vehicle['immat']) : ''; ?>" 
                               required <?php echo $isEditMode ? 'readonly' : ''; ?>>
                        <small class="form-text text-muted">Format: AB-123-CD ou AB123CD</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="marque" class="required">Marque</label>
                        <input type="text" class="form-control" id="marque" name="marque" 
                               value="<?php echo $isEditMode ? htmlspecialchars($vehicle['marque']) : ''; ?>" 
                               list="marques-list" required>
                        <datalist id="marques-list">
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?php echo htmlspecialchars($brand); ?>"><?php echo htmlspecialchars($brand); ?></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="modele" class="required">Modèle</label>
                        <input type="text" class="form-control" id="modele" name="modele" 
                               value="<?php echo $isEditMode ? htmlspecialchars($vehicle['modele']) : ''; ?>" 
                               list="modeles-list" required>
                        <datalist id="modeles-list">
                            <!-- Will be populated via AJAX when brand changes -->
                        </datalist>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date1erCir_fr" class="required">Date 1ère circulation</label>
                        <input type="date" class="form-control" id="date1erCir_fr" name="date1erCir_fr" 
                               value="<?php echo $isEditMode ? date('Y-m-d', strtotime($vehicle['date1erCir_fr'])) : ''; ?>" 
                               required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="energieNGC" class="required">Énergie</label>
                        <select class="form-control" id="energieNGC" name="energieNGC" required>
                            <option value="">Sélectionner</option>
                            <option value="ESSENCE" <?php echo $isEditMode && $vehicle['energieNGC'] == 'ESSENCE' ? 'selected' : ''; ?>>Essence</option>
                            <option value="DIESEL" <?php echo $isEditMode && $vehicle['energieNGC'] == 'DIESEL' ? 'selected' : ''; ?>>Diesel</option>
                            <option value="ELECTRIQUE" <?php echo $isEditMode && $vehicle['energieNGC'] == 'ELECTRIQUE' ? 'selected' : ''; ?>>Électrique</option>
                            <option value="HYBRIDE" <?php echo $isEditMode && $vehicle['energieNGC'] == 'HYBRIDE' ? 'selected' : ''; ?>>Hybride</option>
                            <option value="GPL" <?php echo $isEditMode && $vehicle['energieNGC'] == 'GPL' ? 'selected' : ''; ?>>GPL</option>
                            <option value="GNV" <?php echo $isEditMode && $vehicle['energieNGC'] == 'GNV' ? 'selected' : ''; ?>>GNV</option>
                        </select>
                        <input type="hidden" id="energie" name="energie" value="<?php echo $isEditMode ? htmlspecialchars($vehicle['energie']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="couleur" class="required">Couleur</label>
                        <input type="text" class="form-control" id="couleur" name="couleur" 
                               value="<?php echo $isEditMode ? htmlspecialchars($vehicle['couleur']) : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="puisFisc" class="required">Puissance fiscale</label>
                        <input type="text" class="form-control" id="puisFisc" name="puisFisc" 
                               value="<?php echo $isEditMode ? htmlspecialchars($vehicle['puisFisc']) : ''; ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="boite_vitesse" class="required">Boîte de vitesse</label>
                        <select class="form-control" id="boite_vitesse" name="boite_vitesse" required>
                            <option value="M" <?php echo $isEditMode && $vehicle['boite_vitesse'] == 'M' ? 'selected' : ''; ?>>Manuelle</option>
                            <option value="A" <?php echo $isEditMode && $vehicle['boite_vitesse'] == 'A' ? 'selected' : ''; ?>>Automatique</option>
                            <option value="SA" <?php echo $isEditMode && $vehicle['boite_vitesse'] == 'SA' ? 'selected' : ''; ?>>Semi-automatique</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nb_portes">Nombre de portes</label>
                        <input type="number" class="form-control" id="nb_portes" name="nb_portes" 
                               value="<?php echo $isEditMode ? htmlspecialchars($vehicle['nb_portes']) : ''; ?>" min="1">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nr_passagers">Nombre de places</label>
                        <input type="number" class="form-control" id="nr_passagers" name="nr_passagers" 
                               value="<?php echo $isEditMode ? htmlspecialchars($vehicle['nr_passagers']) : ''; ?>" min="1">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="co2">CO2 (g/km)</label>
                        <input type="text" class="form-control" id="co2" name="co2" 
                               value="<?php echo $isEditMode ? htmlspecialchars($vehicle['co2']) : ''; ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cylindres">Cylindrée (cm³)</label>
                        <input type="text" class="form-control" id="cylindres" name="cylindres" 
                               value="<?php echo $isEditMode ? htmlspecialchars($vehicle['cylindres']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="puisFiscReel">Puissance réelle (ch)</label>
                        <input type="text" class="form-control" id="puisFiscReel" name="puisFiscReel" 
                               value="<?php echo $isEditMode ? htmlspecialchars($vehicle['puisFiscReel']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="vin">VIN (Numéro de série)</label>
                        <input type="text" class="form-control" id="vin" name="vin" 
                               value="<?php echo $isEditMode ? htmlspecialchars($vehicle['vin']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date_dernier_control_tecnique">Dernier contrôle technique</label>
                        <input type="date" class="form-control" id="date_dernier_control_tecnique" 
                               name="date_dernier_control_tecnique" 
                               value="<?php 
                                    echo $isEditMode && !empty($vehicle['date_dernier_control_tecnique']) 
                                        ? date('Y-m-d', $vehicle['date_dernier_control_tecnique'])
                                        : ''; 
                                ?>">
                    </div>
                </div>
            </div>

            <!-- Hidden fields for compatibility with API form -->
            <input type="hidden" name="source" value="manual">
            <input type="hidden" id="date1erCir_us" name="date1erCir_us" 
                   value="<?php echo $isEditMode ? htmlspecialchars($vehicle['date1erCir_us']) : ''; ?>">
            <input type="hidden" id="carrosserieCG" name="carrosserieCG" 
                   value="<?php echo $isEditMode ? htmlspecialchars($vehicle['carrosserieCG']) : ''; ?>">
            <input type="hidden" id="genreVCGNGC" name="genreVCGNGC" 
                   value="<?php echo $isEditMode ? htmlspecialchars($vehicle['genreVCGNGC']) : ''; ?>">
            <input type="hidden" id="genreVCG" name="genreVCG" 
                   value="<?php echo $isEditMode ? htmlspecialchars($vehicle['genreVCG']) : ''; ?>">
            <input type="hidden" id="type_mine" name="type_mine" 
                   value="<?php echo $isEditMode ? htmlspecialchars($vehicle['type_mine']) : ''; ?>">
            <input type="hidden" id="poids" name="poids" 
                   value="<?php echo $isEditMode ? htmlspecialchars($vehicle['poids']) : ''; ?>">
            <input type="hidden" id="collection" name="collection" 
                   value="<?php echo $isEditMode ? htmlspecialchars($vehicle['collection']) : ''; ?>">
            <input type="hidden" id="date30" name="date30" 
                   value="<?php echo $isEditMode ? htmlspecialchars($vehicle['date30']) : ''; ?>">
            <input type="hidden" id="code_moteur" name="code_moteur" 
                   value="<?php echo $isEditMode ? htmlspecialchars($vehicle['code_moteur']) : ''; ?>">
            <input type="hidden" id="k_type" name="k_type" 
                   value="<?php echo $isEditMode ? htmlspecialchars($vehicle['k_type']) : ''; ?>">
            <input type="hidden" id="db_c" name="db_c" 
                   value="<?php echo $isEditMode ? htmlspecialchars($vehicle['db_c']) : ''; ?>">

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> <?php echo $isEditMode ? 'Mettre à jour' : 'Enregistrer'; ?> le véhicule
                </button>
                <a href="<?php echo $path_cms_general; ?>Profil-automobile" class="btn btn-outline-secondary ml-2">Annuler</a>
            </div>
        </form>
    </div>
</div>