<?php
require_once __DIR__ . '/../dataHandler/Section1Data.php';
?>
<div class="container">
    <h1>CONSTAT AMIABLE D’ACCIDENT AUTOMOBILE</h1>
    <!-- <h4>1-3</h4> -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sc1-input1" class="form-label">Date de l’accident</label>
            <input type="date" class="form-control" id="sc1-input1" placeholder="Date de l’accident" data-db-name="s1_accident_date">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc1-input2" class="form-label">Heure</label>
            <input type="time" class="form-control" id="sc1-input2" placeholder="Heure" data-db-name="s1_accident_time">
        </div>
        <div class="col-md-4 mb-3">
            <label for="sc1-input4" class="form-label">Adresse de l'accident</label>
            <input type="text" class="form-control" id="sc1-input4" placeholder="Lieu" data-maxlength="84" data-db-name="s1_accident_place">
        </div>
    </div>
    <div class="row">
        
        <div class="col-md-4 mb-3">
            <label class="form-label">Blessé(s) même léger(s)</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc1-radio1" id="sc1-input5" value="yes" data-db-name="s1_has_injuries">
                <label class="form-check-label" for="sc1-input5">Oui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc1-radio1" id="sc1-input6" value="no" data-db-name="s1_has_injuries">
                <label class="form-check-label" for="sc1-input6">Non</label>
            </div>
        </div>
    </div>
    <div class="row">
        <h4>4 Dégâts matériel à des</h4>
        <div class="col-md-6 mb-3">
            <label class="form-label">véhicules autres que A et B</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc1-radio2" id="sc1-input7" value="yes" data-db-name="s1_has_vehicle_damage">
                <label class="form-check-label" for="sc1-input7">Oui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc1-radio2" id="sc1-input8" value="no" data-db-name="s1_has_vehicle_damage">
                <label class="form-check-label" for="sc1-input8">Non</label>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">objets autres que des véhicules</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc1-radio3" id="sc1-input9" value="yes" data-db-name="s1_has_object_damage">
                <label class="form-check-label" for="sc1-input9">Oui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sc1-radio3" id="sc1-input10" value="no" data-db-name="s1_has_object_damage">
                <label class="form-check-label" for="sc1-input10">Non</label>
            </div>
        </div>
    </div>
    <h4>Témoins : noms, adresses et tél</h4>
    <div class="row">
        <div class="col-12 mb-3">
            <label for="sc1-input11" class="form-label">Témoins</label>
            <textarea class="form-control" id="sc1-input11" 
                      placeholder="Noms, adresses et tél des témoins" 
                      data-maxlength="160" 
                      data-db-name="s1_witnesses_info"
                      rows="3"></textarea>
        </div>
    </div>
    <div class="col-md-4 mb-3">
            <!-- <label for="sc1-input3" class="form-label">Localisation</label> -->
            <input type="hidden" class="form-control" id="sc1-input3" data-db-name="s1_accident_location">
        </div>
</div>

<script>
(function() {
    setTimeout(() => {
        const locationInput = document.getElementById('sc1-input3');
        
        if (locationInput) {
            // Set default value
            locationInput.value = 'France';

            // Store in localStorage with proper structure
            localStorage.setItem('sc1-input3', JSON.stringify({
                table: 'constats_main',
                dbName: 's1_accident_location',
                value: 'France'
            }));
        }
    }, 100);
})();
</script>