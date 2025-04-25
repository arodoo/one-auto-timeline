<?php

/*****************************************************\
* Adresse e-mail => direction@codi-one.fr             *
* La conception est assujettie à une autorisation     *
* spéciale de codi-one.com. Si vous ne disposez pas de*
* cette autorisation, vous êtes dans l'illégalité.    *
* L'auteur de la conception est et restera            *
* codi-one.fr                                         *
* Codage, script & images (all contenu) sont réalisés * 
* par codi-one.fr                                     *
* La conception est à usage unique et privé.          *
* La tierce personne qui utilise le script se porte   *
* garante de disposer des autorisations nécessaires   *
*                                                     *
* Copyright ... Tous droits réservés auteur (Fabien B)*
\*****************************************************/

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {

    $action = $_GET['action'];
    $idaction = $_GET['idaction'];

    ?>
    <link rel="stylesheet" href="/css/carte-grise.css">
    <div id="main-content" style='padding: 5px;' align="center">
        <div class="container mt-5"> 
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item"> <a class="nav-link active" id="tab1-tab" data-target="#tab1" role="tab"
                        aria-controls="tab1" aria-selected="true">Changement de titulaire</a> </li>
                <li class="nav-item"> <a class="nav-link" id="tab2-tab" data-target="#tab2" role="tab" aria-controls="tab2"
                        aria-selected="false">Changement de domicile</a> </li>
                <li class="nav-item"> <a class="nav-link" id="tab3-tab" data-target="#tab3" role="tab" aria-controls="tab3"
                        aria-selected="false">Déclaration de session</a> </li>
            </ul> 

            <div class="tab-content" id="myTabContent"> 

                <?php include 'changement-de-titulaire/changement-de-titulaire-vehicule-select.php'; ?>

                <?php include 'changement-de-domicile/changement-de-domicile.php'; ?>

                <?php include 'declaration-de-session/declaration-de-session.php'; ?>

            </div>
        </div>
        <button id="reset-form-button" class="btn ct-fixed-button" style="display: none;">Réinitialiser le
            formulaire</button>
        <!-- <button id="dev-show-panier" class="btn ct-fixed-button" style="display: block; background-color: #ff7f50; left: 10px;">Mode Dev: Afficher Panier</button> -->
    </div>

    <div id="panier-recap-content" style="display: none;">
        <?php include 'panier-recap/panier-recap.php'; ?>
    </div>


    <?php include '/var/www/vhosts/mon-espace-auto.com/httpdocs/panel/Carte-grise/payer-loading.php'; ?>

    <script type="module">
        import formProcess from '/panel/Carte-grise/js/formProcessSingleton.js';
        import { showPanierRecap } from '/panel/Carte-grise/js/payer-loading.js';

        document.addEventListener("DOMContentLoaded", function () {

            resetFormState();

            
            var tabs = document.querySelectorAll("#myTab .nav-link");
            var tabContents = document.querySelectorAll(".tab-pane");

            const processMap = {
                "tab1-tab": "changement de titulaire",
                "tab2-tab": "changement de domicile",
                "tab3-tab": "declaration de session"
            };

            tabs.forEach(function (tab) {
                tab.addEventListener("click", function (event) {
                    
                    event.preventDefault();

                    tabs.forEach(function (t) {
                        t.classList.remove("active");
                    });
                    tabContents.forEach(function (tc) {
                        tc.classList.remove("show", "active");
                        tc.style.display = "none";
                    });

                    var target = document.querySelector(tab.getAttribute("data-target"));
                    if (target) {
                        tab.classList.add("active");
                        target.classList.add("show", "active");
                        target.style.display = "block";

                        var process = processMap[tab.id];
                        formProcess.setProcess(process);

                        resetFormState();
                    }
                });
            });

            // Adjust the logic to disable only the three specific tabs
            formProcess.addObserver(() => {
                if (formProcess.getEtatVehicule() || formProcess.getImmatriculation()) {
                    const specificTabs = ["tab1-tab", "tab2-tab", "tab3-tab"];
                    tabs.forEach(tab => {
                        if (specificTabs.includes(tab.id)) {
                            tab.classList.add("disabled");
                            tab.style.pointerEvents = "none";
                        }
                    });

                    const resetButton = document.getElementById("reset-form-button");
                    resetButton.style.display = "block";
                    resetButton.addEventListener("click", () => {
                        location.reload();
                    });
                }
            });

            function resetFormState() {
                //console.log('Resetting form state');
                const formData = formProcess.getEntireState();
                if (localStorage.getItem('formProcessState')) {
                    localStorage.removeItem('formProcessState');
                }
                localStorage.setItem('formProcessState', JSON.stringify(formData));
            }
            
            // Add event listener for dev button
            const devShowPanierBtn = document.getElementById('dev-show-panier');
            if (devShowPanierBtn) {
                devShowPanierBtn.addEventListener('click', function() {
                    // Set some test values in formProcess for development purposes
                    formProcess.setEmail('test@example.com');
                    formProcess.setCodePostal('75001');
                    formProcess.setMontantCommandeSansFrais(100);
                    formProcess.setMontantCommandeFrais(120);
                    formProcess.setCommission(10);
                    
                    // Show panier recap
                    showPanierRecap();
                });
            }
        });

    </script>

    <?php
} else {
    header("location: /");
}

?>