<?php
// Section 10: Form Handling Logic
include_once '../Components/FormHeaderGenerator.php';
?>
<div class="container">
    <?php
    echo FormHeaderGenerator::generateHeader(
        'Déclaration','','','','',''
    );
    ?>
    <h4>4 Procès-verbal, Rapport de police, Main-courante</h4>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">A-t-il été établi un procès-verbal de gendarmerie</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc10-radio1" id="sc10-input1" data-db-name="s10_has_police_report" value="yes">
                <label class="form-check-label" for="sc10-input1">Oui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc10-radio1" id="sc10-input2" data-db-name="s10_has_police_report" value="no">
                <label class="form-check-label" for="sc10-input2">Non</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Un rapport de police</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc10-radio2" id="sc10-input3" data-db-name="s10_has_police_statement" value="yes">
                <label class="form-check-label" for="sc10-input3">Oui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc10-radio2" id="sc10-input4" data-db-name="s10_has_police_statement" value="no">
                <label class="form-check-label" for="sc10-input4">Non</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Une main-courante</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc10-radio3" id="sc10-input5" data-db-name="s10_has_incident_report" value="yes">
                <label class="form-check-label" for="sc10-input5">Oui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc10-radio3" id="sc10-input6" data-db-name="s10_has_incident_report" value="no">
                <label class="form-check-label" for="sc10-input6">Non</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="sc10-input7" class="form-label">Si oui : Brigade ou Commisariat de</label>
            <input type="text" class="form-control" id="sc10-input7" data-db-name="s10_police_station" placeholder="Si oui : Brigade ou Commisariat de" data-maxlength="50">
        </div>
    </div>
    <h4>5 Véhicule assuré</h4>
    <div class="row tall-row">
        <div class="col-md-4 mb-3">
            <label for="sc10-input9" class="form-label">Lieu habituel de garage</label>
            <input type="text" class="form-control" id="sc10-input9" data-db-name="s10_vehicle_garage" placeholder="Lieu habituel de garage" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc10-input10" class="form-label"><strong>EXPERTISE des DÉGÂTS :</strong> Réparateur chez qui le véhicule sera visible</label>
            <input type="text" class="form-control" id="sc10-input10" data-db-name="s10_repair_shop" placeholder="Réparateur chez qui le véhicule sera visible" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc10-input11" class="form-label">Tel</label>
            <input type="text" class="form-control" id="sc10-input11" data-db-name="s10_repair_phone" placeholder="Tel" data-maxlength="15">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc10-input12" class="form-label">Fax</label>
            <input type="text" class="form-control" id="sc10-input12" data-db-name="s10_repair_fax" placeholder="Fax" data-maxlength="15">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc10-input13" class="form-label">E-mail</label>
            <input type="text" class="form-control" id="sc10-input13" data-db-name="s10_repair_email" placeholder="E-mail" data-maxlength="50">
        </div>

    </div>
    <div class="row">
        <div class="col-md-8 mb-3">
            <label for="sc10-input8" class="form-label">Quand ?</label>
            <input type="text" class="form-control" id="sc10-input8" data-db-name="s10_police_date" placeholder="Quand ?" data-maxlength="30">
            <label for="sc10-input20" class="form-label">- A été volé, indiquer son numéro dans la série du type (voir carte grise)</label>
            <label class="form-label">- Est gagé ou fait l’objet d’un contrat de location (ou crédit-bail) : nom et adresse de l’organisme
                concerné</label>
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc10-input14" class="form-label">Eventuellement téléphoner à</label>
            <input type="text" class="form-control" id="sc10-input14" data-db-name="s10_contact_phone" placeholder="Eventuellement téléphoner à">
        </div>
    </div>
    <h5>Si le véhicule</h5>
    <div class="row tall-row">
        <div class="col-md-4 mb-3">
            <label for="sc10-input15" class="form-label">Est un poids lourd : poids total en charge</label>
            <input type="text" class="form-control" id="sc10-input15" data-db-name="s10_truck_weight" placeholder="est un poids lourd : poids total en charge" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc10-input16" class="form-label">Était attelé à un autre véhicule (tractant ou remorqué) au moment de l’accident,
                indiquer le poids total en charge :</label>
            <input type="text" class="form-control" id="sc10-input16" data-db-name="s10_trailer_weight" placeholder="total en charge" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc10-input17" class="form-label">Nom de la Société qui l’assure</label>
            <input type="text" class="form-control" id="sc10-input17" data-db-name="s10_trailer_insurance" placeholder="Nom de la Société qui l’assure" data-maxlength="50">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc10-input18" class="form-label">N° de contrat dans la société</label>
            <input type="text" class="form-control" id="sc10-input18" data-db-name="s10_trailer_contract" placeholder="n° de contrat dans la société" data-maxlength="50">
        </div>
    </div>
    <h4>6 Dégâts matériels autres</h4>
    <div class="row">
        <div class="col-md-12 mb-3">
            <label for="sc10-input19" class="form-label">Qu’aux véhicules A et B (nature et importance : nom et adresse du propriétaire)</label>
            <input type="text" class="form-control" id="sc10-input19" data-db-name="s10_other_damage" placeholder="dégâts matériels autres qu’aux véhicules A et B (nature et importance : nom et adresse du propriétaire)" data-maxlength="50">
        </div>
    </div>
</div>