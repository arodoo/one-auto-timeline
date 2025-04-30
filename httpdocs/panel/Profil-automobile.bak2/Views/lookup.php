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
?>

<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">Rechercher un véhicule par immatriculation</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <form id="vehicle-lookup-form">
                    <div class="form-group">
                        <label for="immatriculation">Numéro d'immatriculation</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="immatriculation" name="immatriculation" 
                                placeholder="AB-123-CD ou AB123CD" required>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" id="search-button">
                                    <i class="fas fa-search"></i> Rechercher
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Entrez le numéro d'immatriculation du véhicule (format FNI ou SIV)</small>
                    </div>
                </form>

                <div id="lookup-spinner" class="text-center mt-4" style="display:none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Recherche en cours...</span>
                    </div>
                    <p class="mt-2">Recherche en cours auprès du service d'immatriculation...</p>
                </div>

                <div id="vehicle-not-found" class="alert alert-warning mt-4" style="display:none;">
                    <h5><i class="fas fa-exclamation-triangle"></i> Véhicule non trouvé</h5>
                    <p>Nous n'avons pas pu trouver les informations pour cette immatriculation.</p>
                    <div class="mt-3">
                        <button class="btn btn-secondary" type="button" onclick="loadTabContent('manual')">
                            <i class="fas fa-edit"></i> Ajouter manuellement
                        </button>
                        <button class="btn btn-outline-secondary" type="button" onclick="resetLookupForm()">
                            <i class="fas fa-redo"></i> Nouvelle recherche
                        </button>
                    </div>
                </div>

                <div id="vehicle-found" class="mt-4" style="display:none;">
                    <div class="alert alert-success">
                        <h5><i class="fas fa-check-circle"></i> Véhicule trouvé</h5>
                        <p>Les informations suivantes ont été récupérées pour cette immatriculation.</p>
                    </div>

                    <form id="vehicle-confirm-form">
                        <input type="hidden" name="source" value="api">
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody id="vehicle-info">
                                    <!-- Vehicle data will be inserted here by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="form-group mt-3">
                            <label for="date_dernier_control_tecnique">Date du dernier contrôle technique</label>
                            <input type="date" class="form-control" id="date_dernier_control_tecnique" name="date_dernier_control_tecnique">
                            <small class="form-text text-muted">Cette information n'est pas disponible via l'API et doit être renseignée manuellement</small>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Enregistrer ce véhicule
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="resetLookupForm()">
                                <i class="fas fa-redo"></i> Nouvelle recherche
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Form submission for vehicle lookup
    $('#vehicle-lookup-form').on('submit', function(e) {
        e.preventDefault();
        
        const immat = $('#immatriculation').val().trim();
        if (!immat) {
            popup_alert("Veuillez saisir une immatriculation", "red filledlight", "#ff0000", "uk-icon-close");
            return;
        }
        
        // Hide existing results
        $('#vehicle-found, #vehicle-not-found').hide();
        
        // Show spinner
        $('#lookup-spinner').show();
        
        // Call API via backend
        $.ajax({
            url: '?action=lookupVehicle',
            type: 'POST',
            data: { immatriculation: immat },
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    // Vehicle found - populate and show vehicle data
                    populateVehicleData(response.data);
                    $('#vehicle-found').show();
                } else {
                    // Vehicle not found
                    $('#vehicle-not-found').show();
                }
            },
            error: function() {
                popup_alert("Erreur lors de la recherche du véhicule", "red filledlight", "#ff0000", "uk-icon-close");
                $('#vehicle-not-found').show();
            },
            complete: function() {
                $('#lookup-spinner').hide();
            }
        });
    });
    
    // Form submission for saving vehicle 
    $('#vehicle-confirm-form').on('submit', function(e) {
        e.preventDefault();
        
        // Hide form and show saving spinner
        $(this).hide();
        $('#lookup-spinner').show().find('p').text('Enregistrement en cours...');
        
        // Get all form data
        const formData = $(this).serialize();
        
        // Submit via AJAX
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
                    $('#vehicle-confirm-form').show();
                }
            },
            error: function() {
                popup_alert("Erreur lors de l'enregistrement du véhicule", "red filledlight", "#ff0000", "uk-icon-close");
                $('#vehicle-confirm-form').show();
            },
            complete: function() {
                $('#lookup-spinner').hide();
            }
        });
    });
});

/**
 * Populate vehicle data from API response
 */
function populateVehicleData(vehicle) {
    // Clear existing data
    $('#vehicle-info').empty();
    
    // Add all hidden fields for form submission
    const fields = ['immat', 'vin', 'marque', 'modele', 'couleur', 'date1erCir_fr', 'date1erCir', 
        'energieNGC', 'puisFisc', 'puisKW', 'boite_vitesse', 'nb_portes', 'nr_passagers'];
    
    fields.forEach(field => {
        if (vehicle[field]) {
            $('<input>').attr({
                type: 'hidden',
                name: field,
                value: vehicle[field]
            }).appendTo('#vehicle-confirm-form');
        }
    });
    
    // Display vehicle information in the table
    const displayFields = [
        { key: 'immat', label: 'Immatriculation' },
        { key: 'marque', label: 'Marque' },
        { key: 'modele', label: 'Modèle' },
        { key: 'date1erCir_fr', label: 'Date 1ère mise en circulation' },
        { key: 'puisFisc', label: 'Puissance fiscale (CV)' },
        { key: 'energieNGC', label: 'Énergie' },
        { key: 'couleur', label: 'Couleur' },
        { key: 'nb_portes', label: 'Nombre de portes' },
        { key: 'nr_passagers', label: 'Nombre de places' },
        { key: 'boite_vitesse', label: 'Boîte de vitesses' },
    ];
    
    displayFields.forEach(field => {
        if (vehicle[field.key]) {
            $('<tr>')
                .append($('<th>').text(field.label))
                .append($('<td>').text(vehicle[field.key]))
                .appendTo('#vehicle-info');
        }
    });
}

/**
 * Reset the lookup form and hide results
 */
function resetLookupForm() {
    $('#immatriculation').val('');
    $('#vehicle-found, #vehicle-not-found').hide();
    $('#vehicle-info').empty();
    $('#date_dernier_control_tecnique').val('');
}
</script>