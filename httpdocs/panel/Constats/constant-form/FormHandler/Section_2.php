<?php
require_once('/var/www/vhosts/mon-espace-auto.com/httpdocs/Configurations_bdd.php');
require_once('/var/www/vhosts/mon-espace-auto.com/httpdocs/Configurations.php');
require_once('/var/www/vhosts/mon-espace-auto.com/httpdocs/Configurations_modules.php');
include_once '../Components/FormHeaderGenerator.php';
require_once '../Components/Section2DataLoader.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$formData = [];
try {
    if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
        $dataLoader = new Section2DataLoader($bdd, $id_oo);
        $formData = $dataLoader->loadUserData();
        error_log("Section 2 data loaded successfully for user ID: " . $id_oo);
    }
} catch (Exception $e) {
    error_log("Error in Section 2: " . $e->getMessage());
}
?>

<script>
    window.section2FormData = <?php echo json_encode($formData); ?>;
</script>
<script src="/panel/Constats/constant-form/JS/Section_2.js?v=<?php echo time(); ?>"></script>

<div class="container">
    <?php echo FormHeaderGenerator::generateHeader('VÉHICULE A', '', '', '', '', ''); ?>
    <h4>Preneur d’assurance / assuré (voir attestation d’assurance)</h4>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc2-input1" class="form-label">Nom</label>
            <input type="text" class="form-control" id="sc2-input1" data-db-name="s2_insured_name" placeholder="Nom" data-maxlength="14">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input2" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="sc2-input2" data-db-name="s2_insured_firstname" placeholder="Prénom" data-maxlength="16">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input3" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="sc2-input3" data-db-name="s2_insured_address" placeholder="Adresse" data-maxlength="43">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc2-input4" class="form-label">Code postal</label>
            <input type="text" class="form-control" id="sc2-input4" data-db-name="s2_insured_postal" placeholder="Code postal" data-maxlength="15">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input5" class="form-label">Tél. ou email</label>
            <input type="text" class="form-control" id="sc2-input5" data-db-name="s2_insured_contact" placeholder="Tél. ou email" data-maxlength="24">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input6" class="form-label">Pays</label>
            <input type="text" class="form-control" id="sc2-input6" data-db-name="s2_insured_country" placeholder="Pays" data-maxlength="16">
        </div>
    </div>
    <h4>Vehicle</h4>
    <div class="row mb-3">
        <div class="col-12">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="vehicleType" id="motorizedRadio" value="moteur" checked>
                <label class="form-check-label" for="motorizedRadio">A MOTEUR</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="vehicleType" id="trailerRadio" value="remorque">
                <label class="form-check-label" for="trailerRadio">REMORQUE</label>
            </div>
            <small class="text-danger">* Ne sélectionner qu'un seul type de véhicule</small>
        </div>
    </div>

    <div id="moteurSection">
        <h5><strong>A MOTEUR</strong></h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="sc2-input8" class="form-label">Marque, type</label>
                <input type="text" class="form-control" id="sc2-input8" data-db-name="s2_vehicle_brand" placeholder="Marque, type" data-maxlength="17">
            </div>
            <div class="col-md-4 mb-3">
                <label for="sc2-input9" class="form-label">N° d’immatriculation</label>
                <input type="text" class="form-control" id="sc2-input9" data-db-name="s2_vehicle_plate" placeholder="N° d’immatriculation" data-maxlength="18">
            </div>
            <div class="col-md-4 mb-3">
                <label for="sc2-input11" class="form-label">Pays d’immatriculation</label>
                <input type="text" class="form-control" id="sc2-input11" data-db-name="s2_vehicle_country" placeholder="Pays d’immatriculation" data-maxlength="16">
            </div>
        </div>
    </div>

    <div id="remorqueSection">
        <h5>REMORQUE</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="sc2-input10" class="form-label">N° d’immatriculation</label>
                <input type="text" class="form-control" id="sc2-input10" data-db-name="s2_trailer_plate" placeholder="N° d’immatriculation" data-maxlength="18">
            </div>
            <div class="col-md-4 mb-3">
                <label for="sc2-input12" class="form-label">Pays d’immatriculation</label>
                <input type="text" class="form-control" id="sc2-input12" data-db-name="s2_trailer_country" placeholder="Pays d’immatriculation" data-maxlength="16">
            </div>
        </div>
    </div>

    <h4>Société d’assurance (voir attestation d’assurance)</h4>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc2-input13" class="form-label">Nom</label>
            <input type="text" class="form-control" id="sc2-input13" data-db-name="s2_insurance_name" placeholder="Nom" data-maxlength="22">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input14" class="form-label">N° de contrat</label>
            <input type="text" class="form-control" id="sc2-input14" data-db-name="s2_insurance_contract" placeholder="N° de contrat" data-maxlength="19">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input15" class="form-label">N° de carte verte</label>
            <input type="text" class="form-control" id="sc2-input15" data-db-name="s2_insurance_green_card" placeholder="N° de carte verte" data-maxlength="19">
        </div>
    </div>
    <h5>Attestation d’assurance ou carte verte valable</h5>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc2-input16" class="form-label">Du</label>
            <input type="date" class="form-control" id="sc2-input16" data-db-name="s2_insurance_valid_from">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input17" class="form-label">Au</label>
            <input type="date" class="form-control" id="sc2-input17" data-db-name="s2_insurance_valid_to">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input18" class="form-label">Agence (ou bureau, ou courtier)</label>
            <input type="text" class="form-control" id="sc2-input18" data-db-name="s2_insurance_agency" placeholder="Agence" data-maxlength="16">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc2-input19" class="form-label">Nom de l'agence</label>
            <input type="text" class="form-control" id="sc2-input19" data-db-name="s2_agency_name" placeholder="Nom de l'agence" data-maxlength="24">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input20" class="form-label">Adresse de l'agence</label>
            <input type="text" class="form-control" id="sc2-input20" data-db-name="s2_agency_address" placeholder="Adresse de l'agence" data-maxlength="43">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input21" class="form-label">Pays de l'agence</label>
            <input type="text" class="form-control" id="sc2-input21" data-db-name="s2_agency_country" placeholder="Pays de l'agence" data-maxlength="16">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc2-input22" class="form-label">Email de l'agence <span class="text-danger fw-light">(Obligatoire)</span></label>
            <input type="text" class="form-control" id="sc2-input22" data-db-name="s2_agency_phone" placeholder="Tél ou email de l'agence" data-maxlength="24">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Les dégâts matériels au véhicule sont-ils assurés par le contrat ?</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc2-radio1" id="sc2-input23" data-db-name="s2_has_damage_coverage" value="yes">
                <label class="form-check-label" for="sc2-input23">Oui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc2-radio1" id="sc2-input24" data-db-name="s2_has_damage_coverage" value="no">
                <label class="form-check-label" for="sc2-input24">Non</label>
            </div>
        </div>
    </div>

    <h4>Conducteur (voir permis de conduire)</h4>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc2-input25" class="form-label">Nom</label>
            <input type="text" class="form-control" id="sc2-input25" data-db-name="s2_driver_name" placeholder="Nom" data-maxlength="14">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input26" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="sc2-input26" data-db-name="s2_driver_firstname" placeholder="Prénom" data-maxlength="16">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input27" class="form-label">Date de naissance</label>
            <input type="date" class="form-control" id="sc2-input27" data-db-name="s2_driver_birthdate">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc2-input28" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="sc2-input28" data-db-name="s2_driver_address" placeholder="Adresse" data-maxlength="43">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input29" class="form-label">Pays</label>
            <input type="text" class="form-control" id="sc2-input29" data-db-name="s2_driver_country" placeholder="Pays" data-maxlength="16">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input30" class="form-label">Tél ou email</label>
            <input type="text" class="form-control" id="sc2-input30" data-db-name="s2_driver_contact" placeholder="Tél ou email" data-maxlength="24">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc2-input31" class="form-label">Permis de conduire n°</label>
            <input type="text" class="form-control" id="sc2-input31" data-db-name="s2_license_number" placeholder="Permis de conduire n°" data-maxlength="19">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input32" class="form-label">Catégorie (A, B..)</label>
            <input type="text" class="form-control" id="sc2-input32" data-db-name="s2_license_category" placeholder="Catégorie (A, B..)" data-maxlength="11">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc2-input33" class="form-label">Permis valable jusqu’au</label>
            <input type="date" class="form-control" id="sc2-input33" data-db-name="s2_license_valid_until">
        </div>
    </div>
    <h4>Indiquer le point de choc initial au véhicule A</h4>
    <div class="row">
        <div class="col-md-12 mb-3">
            <?php
            require_once '../Components/CanvasDamage.php';
            $canvas = new CanvasDamage('sc2-input34', 'A');
            echo $canvas->render();
            ?>
        </div>
    </div>
    <h4>Dégâts apparents au véhicule A</h4>
    <div class="row">
        <div class="col-md-12 mb-3">
            <label for="sc2-input35" class="form-label">Description 1</label>
            <textarea class="form-control" id="sc2-input35" data-db-name="s2_damage_description" placeholder="Description des dégâts" data-maxlength="70"></textarea>
        </div>
    </div>
    <h4>Mes observations</h4>
    <div class="row">
        <div class="col-md-12 mb-3">
            <label for="sc2-input36" class="form-label">Description 2</label>
            <textarea class="form-control" id="sc2-input36" data-db-name="s2_observations" placeholder="Observations" data-maxlength="134"></textarea>
        </div>
    </div>
</div>