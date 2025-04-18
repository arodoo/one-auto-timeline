<?php
include_once '../Components/FormHeaderGenerator.php';
require_once '../Components/Section3DataLoader.php';

// Ensure the session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if we're in jumelage mode
$isJumelageMode = isset($_SESSION['jumelage_mode']) && $_SESSION['jumelage_mode'] === true;

// Load form data only when in jumelage mode - the DataLoader will check if user is B
$formData = [];
try {
    if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
        $dataLoader = new Section3DataLoader($bdd, $id_oo, $isJumelageMode);
        $formData = $dataLoader->loadUserData();
        error_log("Section 3 data loading attempted. In jumelage mode: " . ($isJumelageMode ? "Yes" : "No"));
        
        // Make the form data available to JavaScript
        echo '<script>';
        echo 'window.section3FormData = ' . json_encode($formData) . ';';
        echo 'console.log("Section 3 form data loaded:", window.section3FormData);';
        echo '</script>';
    }
} catch (Exception $e) {
    error_log("Error in Section 3 data loading: " . $e->getMessage());
}

// Better debugging output
error_log("Section_3.php - Session data: " . json_encode($_SESSION));
error_log("Section_3.php - isJumelageMode: " . ($isJumelageMode ? "true" : "false"));
?>

<script>
    // Only set data if it doesn't already exist
    if (!window.section3FormData || Object.keys(window.section3FormData).length === 0) {
        window.section3FormData = <?php echo json_encode($formData); ?>;
        console.log("Section 3 data set by secondary script");
    } else {
        console.log("Preserving existing section3FormData");
    }
    window.isJumelageMode = <?php echo $isJumelageMode ? 'true' : 'false'; ?>;
</script>

<div class="container">
    <div class="alert alert-info mt-3 jumelage-info-message" style="display: none;">
        <i class="fas fa-info-circle"></i> Les informations du conducteur B seront remplies par <span
            class="jumelage-email"></span>
    </div>

    <!-- Jumelage Controls - Only visible for User A (not in jumelage mode) -->
    <div id="div-jumelage-controls" class="row my-3" <?php echo $isJumelageMode ? 'style="display:none;"' : ''; ?>>
        <div class="col-12">
            <!-- Jumelage Radio Options -->
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="isJumelage" id="noJumelageRadio" value="no" checked>
                <label class="form-check-label" for="noJumelageRadio">Constat Standard</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="isJumelage" id="jumelageRadio" value="yes">
                <label class="form-check-label" for="jumelageRadio">Constat Partagé</label>
            </div>

            <!-- Email Input Group -->
            <div class="input-group" id="jumelageEmailGroup" style="opacity: 0.5;">
                <input type="email" class="form-control" id="jumelageEmail" placeholder="Email de l'autre conducteur"
                    disabled>
                <button class="btn btn-primary" type="button" id="checkEmailBtn" disabled>Vérifier</button>
            </div>

            <!-- Hidden Inputs -->
            <input type="hidden" id="sc3-input37" data-db-name="is_shared">
            <input type="hidden" id="sc3-input38" data-db-name="share_token">
            <input type="hidden" id="sc3-input39" data-db-name="shared_with_user_id">
        </div>
    </div>

    <?php if (!$isJumelageMode): ?>
    <script src="/panel/Constats/constant-form/JS/Sectio3Jumelage.js"></script>
    <?php endif; ?>

    <?php
    echo FormHeaderGenerator::generateHeader('VÉHICULE B', '', '', '#cfcfff', '', '');
    ?>
    <h4>Preneur d’assurance / assuré (voir attestation d’assurance)</h4>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc3-input1" class="form-label">Nom</label>
            <input type="text" class="form-control" id="sc3-input1" data-db-name="s3_insured_name" placeholder="Nom"
                data-maxlength="14">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input2" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="sc3-input2" data-db-name="s3_insured_firstname"
                placeholder="Prénom" data-maxlength="16">
        </div>

        <div class="col-md-4 mb-3">
            <label for="sc3-input3" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="sc3-input3" data-db-name="s3_insured_address"
                placeholder="Adresse" data-maxlength="43">
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc3-input4" class="form-label">Code postal</label>
            <input type="text" class="form-control" id="sc3-input4" data-db-name="s3_insured_postal"
                placeholder="Code postal" data-maxlength="15">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input5" class="form-label">Tél. ou email</label>
            <input type="text" class="form-control" id="sc3-input5" data-db-name="s3_insured_contact"
                placeholder="Tél. ou email" data-maxlength="24">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input6" class="form-label">Pays</label>
            <input type="text" class="form-control" id="sc3-input6" data-db-name="s3_insured_country" placeholder="Pays"
                data-maxlength="16">
        </div>
    </div>
    <h4>Vehicle</h4>
    <div class="row mb-3">
        <div class="col-12">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="vehicleTypeB" id="motorizedRadioB" value="moteur"
                    checked>
                <label class="form-check-label" for="motorizedRadioB">A MOTEUR</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="vehicleTypeB" id="trailerRadioB" value="remorque">
                <label class="form-check-label" for="trailerRadioB">REMORQUE</label>
            </div>
            <small class="text-danger">* Ne sélectionner qu'un seul type de véhicule</small>
        </div>
    </div>

    <div id="moteurSectionB">
        <h5><strong>A MOTEUR</strong></h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="sc3-input7" class="form-label">Marque, type</label>
                <input type="text" class="form-control" id="sc3-input7" data-db-name="s3_vehicle_brand"
                    placeholder="Marque, type" data-maxlength="17">
            </div>
            <div class="col-md-4 mb-3">
                <label for="sc3-input8" class="form-label">N° d’immatriculation</label>
                <input type="text" class="form-control" id="sc3-input8" data-db-name="s3_vehicle_plate"
                    placeholder="N° d’immatriculation" data-maxlength="18">
            </div>
            <div class="col-md-4 mb-3">
                <label for="sc3-input9" class="form-label">Pays d’immatriculation</label>
                <input type="text" class="form-control" id="sc3-input9" data-db-name="s3_vehicle_country"
                    placeholder="Pays d’immatriculation" data-maxlength="16">
            </div>
        </div>
    </div>

    <div id="remorqueSectionB">
        <h5>REMORQUE</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="sc3-input10" class="form-label">N° d’immatriculation</label>
                <input type="text" class="form-control" id="sc3-input10" data-db-name="s3_trailer_plate"
                    placeholder="N° d’immatriculation" data-maxlength="18">
            </div>
            <div class="col-md-4 mb-3">
                <label for="sc3-input12" class="form-label">Pays d’immatriculation</label>
                <input type="text" class="form-control" id="sc3-input12" data-db-name="s3_trailer_country"
                    placeholder="Pays d’immatriculation" data-maxlength="16">
            </div>
        </div>
    </div>

    <h4>Société d’assurance (voir attestation d’assurance)</h4>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc3-input13" class="form-label">Nom</label>
            <input type="text" class="form-control" id="sc3-input13" data-db-name="s3_insurance_name" placeholder="Nom"
                data-maxlength="22">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input14" class="form-label">N° de contrat</label>
            <input type="text" class="form-control" id="sc3-input14" data-db-name="s3_insurance_contract"
                placeholder="N° de contrat" data-maxlength="19">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input15" class="form-label">N° de carte verte</label>
            <input type="text" class="form-control" id="sc3-input15" data-db-name="s3_insurance_green_card"
                placeholder="N° de carte verte" data-maxlength="19">
        </div>
    </div>
    <h5>Attestation d’assurance ou carte verte valable</h5>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc3-input16" class="form-label">Du</label>
            <input type="date" class="form-control" id="sc3-input16" data-db-name="s3_insurance_valid_from">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input17" class="form-label">Au</label>
            <input type="date" class="form-control" id="sc3-input17" data-db-name="s3_insurance_valid_to">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input18" class="form-label">Agence (ou bureau, ou courtier)</label>
            <input type="text" class="form-control" id="sc3-input18" data-db-name="s3_insurance_agency"
                placeholder="Agence" data-maxlength="16">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc3-input19" class="form-label">Nom de l'agence</label>
            <input type="text" class="form-control" id="sc3-input19" data-db-name="s3_agency_name"
                placeholder="Nom de l'agence" data-maxlength="24">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input20" class="form-label">Adresse de l'agence</label>
            <input type="text" class="form-control" id="sc3-input20" data-db-name="s3_agency_address"
                placeholder="Adresse de l'agence" data-maxlength="43">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input21" class="form-label">Pays de l'agence</label>
            <input type="text" class="form-control" id="sc3-input21" data-db-name="s3_agency_country"
                placeholder="Pays de l'agence" data-maxlength="16">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc3-input22" class="form-label">Email de l'agence <?php echo $isJumelageMode ? '<span class="text-danger fw-light">(Obligatoire)</span>' : ''; ?></label>
            <input type="text" class="form-control" id="sc3-input22" data-db-name="s3_agency_phone"
                placeholder="Email de l'agence" data-maxlength="24">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Les dégâts matériels au véhicule sont-ils assurés par le contrat ?</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc3-radio1" id="sc3-input23"
                    data-db-name="s3_has_damage_coverage" value="yes">
                <label class="form-check-label" for="sc3-input23">Oui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc3-radio1" id="sc3-input24"
                    data-db-name="s3_has_damage_coverage" value="no">
                <label class="form-check-label" for="sc3-input24">Non</label>
            </div>
        </div>
    </div>
    <h4>Conducteur (voir permis de conduire)</h4>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc3-input25" class="form-label">Nom</label>
            <input type="text" class="form-control" id="sc3-input25" data-db-name="s3_driver_name" placeholder="Nom"
                data-maxlength="14">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input26" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="sc3-input26" data-db-name="s3_driver_firstname"
                placeholder="Prénom" data-maxlength="16">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input27" class="form-label">Date de naissance</label>
            <input type="date" class="form-control" id="sc3-input27" data-db-name="s3_driver_birthdate">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc3-input28" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="sc3-input28" data-db-name="s3_driver_address"
                placeholder="Adresse" data-maxlength="43">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input29" class="form-label">Pays</label>
            <input type="text" class="form-control" id="sc3-input29" data-db-name="s3_driver_country" placeholder="Pays"
                data-maxlength="16">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input30" class="form-label">Tél ou email</label>
            <input type="text" class="form-control" id="sc3-input30" data-db-name="s3_driver_contact"
                placeholder="Tél ou email" data-maxlength="24">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc3-input31" class="form-label">Permis de conduire n°</label>
            <input type="text" class="form-control" id="sc3-input31" data-db-name="s3_license_number"
                placeholder="Permis de conduire n°" data-maxlength="19">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input32" class="form-label">Catégorie (A, B..)</label>
            <input type="text" class="form-control" id="sc3-input32" data-db-name="s3_license_category"
                placeholder="Catégorie (A, B..)" data-maxlength="11">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc3-input33" class="form-label">Permis valable jusqu’au</label>
            <input type="date" class="form-control" id="sc3-input33" data-db-name="s3_license_valid_until">
        </div>
    </div>
    <h4>Indiquer le point de choc initial au véhicule B</h4>
    <div class="row">
        <div class="col-md-12 mb-3">
            <?php
            require_once '../Components/CanvasDamage.php';
            $canvas = new CanvasDamage('sc3-input34', 'B');
            echo $canvas->render();
            ?>
        </div>
    </div>
    <h4>Dégâts apparents au véhicule B</h4>
    <div class="row">
        <div class="col-md-12 mb-3">
            <label for="sc3-input35" class="form-label">Description 1</label>
            <textarea class="form-control" id="sc3-input35" data-db-name="s3_damage_description"
                placeholder="Description des dégâts" data-maxlength="70"></textarea>
        </div>
    </div>
    <h4>Mes observations (14)</h4>
    <div class="row">
        <div class="col-md-12 mb-3">
            <label for="sc3-input36" class="form-label">Description 2</label>
            <textarea class="form-control" id="sc3-input36" data-db-name="s3_observations" placeholder="Observations"
                data-maxlength="134"></textarea>
        </div>
    </div>
</div>
<script src="/panel/Constats/constant-form/JS/Section_3.js"></script>