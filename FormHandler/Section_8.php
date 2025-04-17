<?php
// Section 8: Form Handling Logic
include_once '../Components/FormHeaderGenerator.php';
?>
<div class="container">
    <?php
    echo FormHeaderGenerator::generateHeader(
        'DÉCLARATION',
        'à remplir et à transmettre dans les cinq jours à votre assureur',
        'Cette déclaration complémentaire vous permet de mieux expliquer les circonstances de l’accident ; toutefois les
        éléments qui sont contraires à ceux mentionnés au recto signé de votre adversaire ne peuvent lui être opposés.',
        '',
        '',
        ''
    );
    ?>
    <h4>L’assuré</h4>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc8-input1" class="form-label">Nom de l’assuré</label>
            <input type="text" class="form-control" id="sc8-input1" data-db-name="s8_insured_name" placeholder="Nom de l’assuré" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc8-input2" class="form-label">Profession</label>
            <input type="text" class="form-control" id="sc8-input2" data-db-name="s8_insured_profession" placeholder="Profession" data-maxlength="30">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc8-input3" class="form-label">N° tél.</label>
            <input type="text" class="form-control" id="sc8-input3" data-db-name="s8_insured_phone" placeholder="N° tél." data-maxlength="15">
        </div>

    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc8-input4" class="form-label">E-mail</label>
            <input type="text" class="form-control" id="sc8-input4" data-db-name="s8_insured_email" placeholder="E-mail" data-maxlength="50">
        </div>
    </div>
    <h4>Conducteur du véhicule</h4>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc8-input5" class="form-label">Profession</label>
            <input type="text" class="form-control" id="sc8-input5" data-db-name="s8_driver_profession" placeholder="Profession" data-maxlength="30">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Est-il</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc8-radio1" id="sc8-input6" data-db-name="s8_driver_marital_status" value="single">
                <label class="form-check-label" for="sc8-input6">Célibataire</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc8-radio1" id="sc8-input7" data-db-name="s8_driver_marital_status" value="married">
                <label class="form-check-label" for="sc8-input7">Marié</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc8-radio1" id="sc8-input8" data-db-name="s8_driver_marital_status" value="other">
                <label class="form-check-label" for="sc8-input8">Autre</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Est-il conducteur habituel du véhicule ?</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc8-radio2" id="sc8-input9" data-db-name="s8_is_regular_driver" value="yes">
                <label class="form-check-label" for="sc8-input9">Oui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc8-radio2" id="sc8-input10" data-db-name="s8_is_regular_driver" value="no">
                <label class="form-check-label" for="sc8-input10">Non</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Réside-t-il habituellement chez l’assuré</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc8-radio3" id="sc8-input11" data-db-name="s8_lives_with_insured" value="yes">
                <label class="form-check-label" for="sc8-input11">Oui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc8-radio3" id="sc8-input12" data-db-name="s8_lives_with_insured" value="no">
                <label class="form-check-label" for="sc8-input12">Non</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Est-il salarié de l’assuré ?</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc8-radio4" id="sc8-input13" data-db-name="s8_is_employee" value="yes">
                <label class="form-check-label" for="sc8-input13">Oui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc8-radio4" id="sc8-input14" data-db-name="s8_is_employee" value="no">
                <label class="form-check-label" for="sc8-input14">Non</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="sc8-input17" class="form-label">Sinon à quel titre conduisait-il ?</label>
                <input type="text" class="form-control" id="sc8-input17" data-db-name="s8_driving_reason" placeholder="Sinon à quel titre conduisait-il ?" data-maxlength="50">
            </div>
        </div>
    </div>
    <h4>Circonstances de l’accident</h4>
    <div class="row">
        <div class="col-md-12 mb-3"></div>
        <label for="sc8-input18" class="form-label">À préciser dans tous les cas même si un procès-verbal de gendarmerie ou un rapport de police a été établi</label>
        <textarea class="form-control" id="sc8-input18" data-db-name="s8_accident_details" placeholder="À préciser dans tous les cas..." data-maxlength="500"></textarea>
    </div>
</div>