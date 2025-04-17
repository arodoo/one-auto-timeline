<?php
// Section 11: Form Handling Logic
include_once '../Components/FormHeaderGenerator.php';
?>
<div class="container">
    <?php
    echo FormHeaderGenerator::generateHeader(
        'dégâts matériels autres',
        'qu’aux véhicules A et B (nature et importance : nom et adresse du propriétaire) :',
        'dégâts matériels autres qu’aux véhicules A et B (nature et importance : nom et adresse du propriétaire) :',
        '',
        '',
        ''
    );
    ?>
    <h4>BLESSÉ 1</h4>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc11-input1" class="form-label">Nom</label>
            <input type="text" class="form-control" id="sc11-input1" data-db-name="s11_injured1_name" placeholder="Nom" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc11-input2" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="sc11-input2" data-db-name="s11_injured1_firstname" placeholder="Prénom" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc11-input3" class="form-label">Age</label>
            <input type="text" class="form-control" id="sc11-input3" data-db-name="s11_injured1_age" placeholder="Age" data-maxlength="3">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc11-input4" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="sc11-input4" data-db-name="s11_injured1_address" placeholder="Adresse" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc11-input5" class="form-label">Tél</label>
            <input type="text" class="form-control" id="sc11-input5" data-db-name="s11_injured1_phone" placeholder="Tél" data-maxlength="15">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc11-input6" class="form-label">Profession</label>
            <input type="text" class="form-control" id="sc11-input6" data-db-name="s11_injured1_profession" placeholder="Profession" data-maxlength="30">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc11-input7" class="form-label">Situation au moment de l'accident</label>
            <input type="text" class="form-control" id="sc11-input7" data-db-name="s11_injured1_situation" placeholder="Situation au moment de l'accident" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">1ers soins ou hospitalisation à</label>
            <input type="text" class="form-control" id="sc11-input8" data-db-name="s11_injured1_hospital" placeholder="1ers soins ou hospitalisation à" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Portait-il casque ou ceinture ?</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc11-radio1" id="sc11-input9" data-db-name="s11_injured1_wore_protection" value="yes">
                <label class="form-check-label" for="sc11-input9">Oui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc11-radio1" id="sc11-input10" data-db-name="s11_injured1_wore_protection" value="no">
                <label class="form-check-label" for="sc11-input10">Non</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Nature et gravité des blessures</label>
            <input type="text" class="form-control" id="sc11-input11" data-db-name="s11_injured1_injuries" placeholder="Nature et gravité des blessures" data-maxlength="50">
        </div>
    </div>
    <h4 style="color: #cfcfff;">BLESSÉ 2</h4>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc11-input12" class="form-label">Nom</label>
            <input type="text" class="form-control" id="sc11-input12" data-db-name="s11_injured2_name" placeholder="Nom" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc11-input13" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="sc11-input13" data-db-name="s11_injured2_firstname" placeholder="Prénom" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc11-input14" class="form-label">Age</label>
            <input type="text" class="form-control" id="sc11-input14" data-db-name="s11_injured2_age" placeholder="Age" data-maxlength="3">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc11-input15" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="sc11-input15" data-db-name="s11_injured2_address" placeholder="Adresse" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc11-input16" class="form-label">Tél</label>
            <input type="text" class="form-control" id="sc11-input16" data-db-name="s11_injured2_phone" placeholder="Tél" data-maxlength="15">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc11-input17" class="form-label">Profession</label>
            <input type="text" class="form-control" id="sc11-input17" data-db-name="s11_injured2_profession" placeholder="Profession" data-maxlength="30">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc11-input18" class="form-label">Situation au moment de l'accident</label>
            <input type="text" class="form-control" id="sc11-input18" data-db-name="s11_injured2_situation" placeholder="Situation au moment de l'accident" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">1ers soins ou hospitalisation à</label>
            <input type="text" class="form-control" id="sc11-input19" data-db-name="s11_injured2_hospital" placeholder="1ers soins ou hospitalisation à" data-maxlength="50">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Portait-il casque ou ceinture ?</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc11-radio2" id="sc11-input20" data-db-name="s11_injured2_wore_protection" value="yes">
                <label class="form-check-label" for="sc11-input20">Oui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc11-radio2" id="sc11-input21" data-db-name="s11_injured2_wore_protection" value="no">
                <label class="form-check-label" for="sc11-input21">Non</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Nature et gravité des blessures</label>
            <input type="text" class="form-control" id="sc11-input22" data-db-name="s11_injured2_injuries" placeholder="Nature et gravité des blessures" data-maxlength="50">
        </div>
    </div>
    <!-- Hidden date fields -->
    <input type="hidden" id="sc11-input23" data-db-name="s11_current_day">
    <input type="hidden" id="sc11-input24" data-db-name="s11_current_month">
    <input type="hidden" id="sc11-input25" data-db-name="s11_current_year">

    <div class="row mt-4">
        <div class="col-12 text-center">
            <button class="btn btn-success" onclick="window.saveConstat()">Enregistrer le Constat</button>
        </div>
    </div>
</div>

<!-- Fix script paths to use absolute paths -->
<script src="/panel/Constats/constant-form/JS/clearLocalStorage.js"></script>

<script>
(function() {
    setTimeout(() => {
        const currentDate = new Date();
        const day = String(currentDate.getDate()).padStart(2, '0');
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const year = String(currentDate.getFullYear()).slice(-2);
        
        const dayInput = document.getElementById('sc11-input23');
        const monthInput = document.getElementById('sc11-input24');
        const yearInput = document.getElementById('sc11-input25');
        
        if (dayInput && monthInput && yearInput) {
            // Set values to inputs
            dayInput.value = day;
            monthInput.value = month;
            yearInput.value = year;

            // Store in localStorage with proper structure
            localStorage.setItem('sc11-input23', JSON.stringify({
                table: 'constats_main',
                dbName: 's11_current_day',
                value: day
            }));

            localStorage.setItem('sc11-input24', JSON.stringify({
                table: 'constats_main',
                dbName: 's11_current_month',
                value: month
            }));

            localStorage.setItem('sc11-input25', JSON.stringify({
                table: 'constats_main',
                dbName: 's11_current_year',
                value: year
            }));
        }
    }, 100);
})();
</script>