<?php
// For AJAX requests, we don't need the security check as it's already done in the controller
$is_ajax_request = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if (!$is_ajax_request) {
    // Security check for direct access
    if (empty($_SESSION['4M8e7M5b1R2e8s']) || empty($user)) {
        header("location: /");
        exit;
    }
}

// Determine if we're in edit mode
$isEdit = !empty($vehicle);
$formTitle = $isEdit ? 'Modifier un véhicule' : 'Ajouter un véhicule manuellement';
?>

<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0"><?php echo $formTitle; ?></h5>
    </div>
    <div class="card-body">
        <form id="manual-vehicle-form">
            <?php if ($isEdit): ?>
            <input type="hidden" name="vehicle_id" value="<?php echo htmlspecialchars($vehicle['id']); ?>">
            <?php endif; ?>
            
            <input type="hidden" name="source" value="manual">
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="immat">Immatriculation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="immat" name="immat" required
                            placeholder="AB-123-CD" 
                            value="<?php echo $isEdit ? htmlspecialchars($vehicle['immat']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date1erCir_fr">Date de 1ère mise en circulation <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date1erCir_fr" name="date1erCir_fr" required
                            value="<?php echo $isEdit ? htmlspecialchars($vehicle['date1erCir_fr']) : ''; ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="marque">Marque <span class="text-danger">*</span></label>
                        <select class="form-control" id="marque" name="marque" required>
                            <option value="">-- Sélectionnez --</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?php echo htmlspecialchars($brand); ?>" 
                                    <?php echo $isEdit && $vehicle['marque'] == $brand ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($brand); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="modele">Modèle <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modele" name="modele" required
                            placeholder="Modèle du véhicule" list="modelsList"
                            value="<?php echo $isEdit ? htmlspecialchars($vehicle['modele']) : ''; ?>">
                        <datalist id="modelsList"></datalist>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="energieNGC">Énergie</label>
                        <select class="form-control" id="energieNGC" name="energieNGC">
                            <option value="">-- Sélectionnez --</option>
                            <option value="Essence" <?php echo $isEdit && $vehicle['energieNGC'] == 'Essence' ? 'selected' : ''; ?>>Essence</option>
                            <option value="Diesel" <?php echo $isEdit && $vehicle['energieNGC'] == 'Diesel' ? 'selected' : ''; ?>>Diesel</option>
                            <option value="Électrique" <?php echo $isEdit && $vehicle['energieNGC'] == 'Électrique' ? 'selected' : ''; ?>>Électrique</option>
                            <option value="Hybride" <?php echo $isEdit && $vehicle['energieNGC'] == 'Hybride' ? 'selected' : ''; ?>>Hybride</option>
                            <option value="GPL" <?php echo $isEdit && $vehicle['energieNGC'] == 'GPL' ? 'selected' : ''; ?>>GPL</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="couleur">Couleur</label>
                        <input type="text" class="form-control" id="couleur" name="couleur" 
                            placeholder="Couleur du véhicule"
                            value="<?php echo $isEdit ? htmlspecialchars($vehicle['couleur']) : ''; ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="puisFisc">Puissance fiscale</label>
                        <input type="number" class="form-control" id="puisFisc" name="puisFisc" 
                            placeholder="CV"
                            value="<?php echo $isEdit ? htmlspecialchars($vehicle['puisFisc']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="boite_vitesse">Boîte de vitesses</label>
                        <select class="form-control" id="boite_vitesse" name="boite_vitesse">
                            <option value="">-- Sélectionnez --</option>
                            <option value="Manuelle" <?php echo $isEdit && $vehicle['boite_vitesse'] == 'Manuelle' ? 'selected' : ''; ?>>Manuelle</option>
                            <option value="Automatique" <?php echo $isEdit && $vehicle['boite_vitesse'] == 'Automatique' ? 'selected' : ''; ?>>Automatique</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date_dernier_control_tecnique">Date dernier CT</label>
                        <input type="date" class="form-control" id="date_dernier_control_tecnique" name="date_dernier_control_tecnique"
                            value="<?php echo $isEdit && !empty($vehicle['date_dernier_control_tecnique']) ? htmlspecialchars($vehicle['date_dernier_control_tecnique']) : ''; ?>">
                        <small class="form-text text-muted">Date du dernier contrôle technique</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nb_portes">Nombre de portes</label>
                        <input type="number" class="form-control" id="nb_portes" name="nb_portes" 
                            min="2" max="10"
                            value="<?php echo $isEdit && !empty($vehicle['nb_portes']) ? htmlspecialchars($vehicle['nb_portes']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nr_passagers">Nombre de places</label>
                        <input type="number" class="form-control" id="nr_passagers" name="nr_passagers" 
                            min="1" max="9"
                            value="<?php echo $isEdit && !empty($vehicle['nr_passagers']) ? htmlspecialchars($vehicle['nr_passagers']) : ''; ?>">
                    </div>
                </div>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> <?php echo $isEdit ? 'Mettre à jour' : 'Enregistrer'; ?>
                </button>
                <button type="button" class="btn btn-secondary" id="cancel-btn" onclick="loadTabContent('list')">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </div>
        </form>
        
        <div id="form-saving-spinner" style="display:none;" class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Enregistrement en cours...</span>
            </div>
            <p class="mt-2">Enregistrement en cours...</p>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Dynamic model loading when brand changes
    $('#marque').on('change', function() {
        const brand = $(this).val();
        if (brand) {
            $.post('?action=getModels', { marque: brand }, function(data) {
                $('#modelsList').html(data);
            });
        } else {
            $('#modelsList').empty();
        }
    });
    
    // Form submission handler
    $('#manual-vehicle-form').on('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        if (!$('#immat').val() || !$('#marque').val() || !$('#modele').val() || !$('#date1erCir_fr').val()) {
            popup_alert("Veuillez remplir tous les champs obligatoires", "red filledlight", "#ff0000", "uk-icon-close");
            return;
        }
        
        // Hide form and show saving spinner
        $(this).hide();
        $('#form-saving-spinner').show();
        
        // Prepare form data
        const formData = $(this).serialize();
        
        // Submit the form via AJAX
        $.ajax({
            url: '?action=save',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    // Success
                    popup_alert(response.message, "green filledlight", "#009900", "uk-icon-check");
                    
                    // Redirect to vehicle list
                    setTimeout(function() {
                        loadTabContent('list');
                        $('#vehicleTabs a[href="#list"]').tab('show');
                    }, 1000);
                } else {
                    // Error
                    popup_alert(response.message, "red filledlight", "#ff0000", "uk-icon-close");
                    $('#manual-vehicle-form').show();
                }
            },
            error: function() {
                popup_alert("Erreur lors de l'enregistrement du véhicule", "red filledlight", "#ff0000", "uk-icon-close");
                $('#manual-vehicle-form').show();
            },
            complete: function() {
                $('#form-saving-spinner').hide();
            }
        });
    });
});
</script>