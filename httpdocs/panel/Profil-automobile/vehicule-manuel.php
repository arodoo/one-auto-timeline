<?php
if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    global $id_oo;
    
    // Check if we're editing an existing vehicle
    $vehicle_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $vehicle = null;
    
    if ($vehicle_id) {
        try {
            $sql = "SELECT * FROM membres_profil_auto WHERE id = :id AND id_membre = :id_membre";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([':id' => $vehicle_id, ':id_membre' => $id_oo]);
            $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$vehicle) {
                header("Location: /Mes-vehicules");
                exit;
            }
        } catch (PDOException $e) {
            // Handle error
        }
    }
    
    // Get car brands for the dropdown
    $brands = [];
    try {
        $sql = "SELECT DISTINCT marque FROM configurations_modeles ORDER BY marque ASC";
        $stmt = $bdd->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $brands[] = $row['marque'];
        }
    } catch (PDOException $e) {
        // Handle error
    }
    
    $page_title = $vehicle ? "Modifier un véhicule" : "Ajouter un véhicule manuellement";
?>
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2><?php echo htmlspecialchars($page_title); ?></h2>
            
            <!-- Include vehicle management menu -->
            <?php include('panel/Profil-automobile/includes/menu-vehicle.php'); ?>
            
            <div class="card">
                <div class="card-header">
                    <h4>Informations du véhicule</h4>
                </div>
                <div class="card-body">
                    <form id="manualVehicleForm">
                        <?php if ($vehicle): ?>
                            <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['id']; ?>">
                        <?php endif; ?>
                        <input type="hidden" name="source" value="manual">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="immat">Immatriculation*</label>
                                    <input type="text" class="form-control" id="immat" name="immat" 
                                           value="<?php echo $vehicle ? htmlspecialchars($vehicle['immat']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="marque">Marque*</label>
                                    <input type="text" class="form-control" id="marque" name="marque" 
                                           value="<?php echo $vehicle ? htmlspecialchars($vehicle['marque']) : ''; ?>" 
                                           list="marques-list" required>
                                    <datalist id="marques-list">
                                        <?php foreach($brands as $brand): ?>
                                            <option value="<?php echo htmlspecialchars($brand); ?>">
                                        <?php endforeach; ?>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="modele">Modèle*</label>
                                    <input type="text" class="form-control" id="modele" name="modele" 
                                           value="<?php echo $vehicle ? htmlspecialchars($vehicle['modele']) : ''; ?>"
                                           list="modeles-list" required>
                                    <datalist id="modeles-list"></datalist>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date1erCir_fr">Date de première mise en circulation*</label>
                                    <input type="date" class="form-control" id="date1erCir_fr" name="date1erCir_fr" 
                                           value="<?php echo $vehicle ? htmlspecialchars(date('Y-m-d', strtotime(str_replace('/', '-', $vehicle['date1erCir_fr'])))) : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="couleur">Couleur*</label>
                                    <input type="text" class="form-control" id="couleur" name="couleur" 
                                           value="<?php echo $vehicle ? htmlspecialchars($vehicle['couleur']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="energieNGC">Carburant*</label>
                                    <select class="form-control" id="energieNGC" name="energieNGC" required>
                                        <option value="">Sélectionner</option>
                                        <option value="ESSENCE" <?php echo ($vehicle && $vehicle['energieNGC'] == 'ESSENCE') ? 'selected' : ''; ?>>Essence</option>
                                        <option value="DIESEL" <?php echo ($vehicle && $vehicle['energieNGC'] == 'DIESEL') ? 'selected' : ''; ?>>Diesel</option>
                                        <option value="ELECTRIQUE" <?php echo ($vehicle && $vehicle['energieNGC'] == 'ELECTRIQUE') ? 'selected' : ''; ?>>Électrique</option>
                                        <option value="HYBRIDE" <?php echo ($vehicle && $vehicle['energieNGC'] == 'HYBRIDE') ? 'selected' : ''; ?>>Hybride</option>
                                        <option value="GPL" <?php echo ($vehicle && $vehicle['energieNGC'] == 'GPL') ? 'selected' : ''; ?>>GPL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="boite_vitesse">Boîte de vitesse*</label>
                                    <select class="form-control" id="boite_vitesse" name="boite_vitesse" required>
                                        <option value="">Sélectionner</option>
                                        <option value="M" <?php echo ($vehicle && $vehicle['boite_vitesse'] == 'M') ? 'selected' : ''; ?>>Manuelle</option>
                                        <option value="A" <?php echo ($vehicle && $vehicle['boite_vitesse'] == 'A') ? 'selected' : ''; ?>>Automatique</option>
                                        <option value="S" <?php echo ($vehicle && $vehicle['boite_vitesse'] == 'S') ? 'selected' : ''; ?>>Semi-automatique</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="puisFisc">Puissance fiscale*</label>
                                    <input type="text" class="form-control" id="puisFisc" name="puisFisc" 
                                           value="<?php echo $vehicle ? htmlspecialchars($vehicle['puisFisc']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="puisFiscReel">Puissance réelle (ch/kW)</label>
                                    <input type="text" class="form-control" id="puisFiscReel" name="puisFiscReel" 
                                           value="<?php echo $vehicle ? htmlspecialchars($vehicle['puisFiscReel']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="vin">Numéro de série (VIN)</label>
                                    <input type="text" class="form-control" id="vin" name="vin" 
                                           value="<?php echo $vehicle ? htmlspecialchars($vehicle['vin']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nb_portes">Nombre de portes</label>
                                    <input type="number" class="form-control" id="nb_portes" name="nb_portes" min="1" max="9" 
                                           value="<?php echo $vehicle ? htmlspecialchars($vehicle['nb_portes']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nr_passagers">Nombre de places</label>
                                    <input type="number" class="form-control" id="nr_passagers" name="nr_passagers" min="1" max="9" 
                                           value="<?php echo $vehicle ? htmlspecialchars($vehicle['nr_passagers']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_dernier_control_tecnique">Date dernier contrôle technique</label>
                                    <input type="date" class="form-control" id="date_dernier_control_tecnique" name="date_dernier_control_tecnique" 
                                           value="<?php echo $vehicle && $vehicle['date_dernier_control_tecnique'] ? date('Y-m-d', (int)$vehicle['date_dernier_control_tecnique']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <small><em>* Champs obligatoires. Les champs optionnels peuvent être complétés plus tard.</em></small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary" id="saveVehicleBtn">
                                <i class="fas fa-save"></i> <?php echo $vehicle ? 'Mettre à jour' : 'Enregistrer'; ?> le véhicule
                            </button>
                            <a href="/Mes-vehicules" class="btn btn-secondary ml-2">
                                <i class="fas fa-arrow-left"></i> Retour à la liste
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Set US date format based on FR date input
        function updateUSDate() {
            const frDate = $('#date1erCir_fr').val();
            if (frDate) {
                const dateParts = frDate.split('-');
                if (dateParts.length === 3) {
                    $('#date1erCir_us').val(frDate);
                }
            }
        }
        
        // Add hidden date field for US format
        $('#manualVehicleForm').append('<input type="hidden" id="date1erCir_us" name="date1erCir_us">');
        
        // Update US date when FR date changes
        $('#date1erCir_fr').on('change', updateUSDate);
        updateUSDate(); // Set initial value
        
        // Fetch models when brand changes
        $('#marque').on('change', function() {
            const marque = $(this).val();
            if (marque) {
                $.ajax({
                    url: '/panel/Profil-automobile/modeles.php',
                    type: 'POST',
                    data: { marque: marque },
                    success: function(data) {
                        $('#modeles-list').html(data);
                    }
                });
            }
        });
        
        // Trigger change if brand is already selected (editing mode)
        if ($('#marque').val()) {
            $('#marque').trigger('change');
        }
        
        // Form submission
        $('#manualVehicleForm').on('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = $(this).serializeArray();
            const jsonData = {};
            
            $.each(formData, function(idx, field) {
                jsonData[field.name] = field.value;
            });
            
            // Submit the data
            $.ajax({
                url: '/panel/Profil-automobile/Profil-automobile-ajouter-modifier-ajax.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(jsonData),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 200) {
                        popup_alert(response.message, "green filledlight", "#009900", "uk-icon-check");
                        setTimeout(function() {
                            window.location.href = '/Mes-vehicules';
                        }, 1500);
                    } else {
                        let errorMsg = response.message;
                        if (response.missingFields) {
                            errorMsg += ': ' + response.missingFields.join(', ');
                        }
                        popup_alert(errorMsg, "red filledlight", "#ff0000", "uk-icon-close");
                    }
                },
                error: function() {
                    popup_alert('Erreur lors de l\'enregistrement des données', "red filledlight", "#ff0000", "uk-icon-close");
                }
            });
        });
    });
</script>

<?php
} else {
    header("location: /");
}
?>