<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="/css/carte-grise.css">

<div id="coordonnees-container" class="content">
    <div class="row">
        <div class="inputsContainer">
            <div class="title">
                Veuillez indiquer vos coordonnées pour connaître le prix et finaliser votre demande
            </div>
            <div class="coordonnees-item">
                <div class="coordonnees-inputContainer">
                    <label class="coordonnees-inputLabel" for="email">Email</label>
                    <div class="coordonnees-inputContent">
                        <input spellcheck="false" autocorrect="off" autocomplete="off" class="coordonnees-elemInp"
                            type="text" id="email" placeholder="exemple@domaine.fr" maxlength="50">
                    </div>
                </div>
            </div>

            <div class="coordonnees-item">
                <div class="coordonnees-inputContainer">
                    <label class="coordonnees-inputLabel" for="codePostal">Code postal</label>
                    <div class="coordonnees-inputContent">
                        <input spellcheck="false" autocorrect="off" autocomplete="off" class="coordonnees-elemInp"
                            type="tel" id="codePostal" placeholder="75001" maxlength="5">
                    </div>
                </div>
            </div>

            <div class="coordonnees-item">
                <div class="coordonnees-checkboxContainer">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="acceptPolicy" value="accepted">
                        <label class="form-check-label" for="acceptPolicy">
                            Acceptez la
                            <u><a href="https://mon-espace-auto.com/Traitements-de-mes-donnees"
                                    target="_blank">politique de confidentialité</a></u>,
                            les
                            <u><a href="https://mon-espace-auto.com/CGV" target="_blank">CGV</a></u>
                            et les
                            <u><a href="https://mon-espace-auto.com/CGU" target="_blank">CGU</a></u>.
                        </label>
                    </div>
                </div>
            </div>

            <div class="coordonnees-stepCtaContainer alignColumnCenterX">
                <button class="coordonnees-cta1">Suivant</button>
            </div>
        </div>
    </div>
</div>



<script type="module">
    import formProcess from '/panel/Carte-grise/js/formProcessSingleton.js';
    import { showPanierRecap } from '/panel/Carte-grise/js/payer-loading.js';

    const price = 100;

    <?php
    require_once('../../../Configurations_bdd.php');
    $req_commission = $bdd->prepare("SELECT prix_commission FROM configurations_preferences_generales LIMIT 1");
    $req_commission->execute();
    $commission = $req_commission->fetchColumn();
    $req_commission->closeCursor();
    ?>
    const commission = <?php echo $commission; ?>;


    function setPriceWithNoFees() {
        const fixedPrice = price + (price * (commission / 100));
        const priceWithNoFees = fixedPrice;
        formProcess.setMontantCommandeSansFrais(priceWithNoFees);
        return fixedPrice;
    }

    function setPriceWithFees() {
        const fixedPrice = setPriceWithNoFees();
        const priceWithFees = fixedPrice * 1.2;
        formProcess.setMontantCommandeFrais(priceWithFees);
        return priceWithFees;
    }

    function setRegisterCommission() {
        const registerCommission = (price * commission) / 100;
        formProcess.setCommission(registerCommission);
        console.log(formProcess.getEntireState());
        return registerCommission;
    }

    function nextStep() {
        const email = document.getElementById('email').value;
        const codePostal = document.getElementById('codePostal').value;
        const acceptPolicy = document.getElementById('acceptPolicy').checked;

        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            popup_alert('Veuillez entrer une adresse email valide.', "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            return;
        }

        // Validate code postal
        const codePostalRegex = /^(0[1-9]|[1-9]\d)\d{3}$/;
        if (!codePostalRegex.test(codePostal)) {
            popup_alert('Veuillez entrer un code postal valide.', "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            return;
        }

        // Validate accept policy
        if (!acceptPolicy) {
            popup_alert('Vous devez accepter la politique de confidentialité.', "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            return;
        }

        formProcess.setEmail(email);
        formProcess.setCodePostal(codePostal);
        formProcess.setAcceptPolicy(acceptPolicy);

        const formData = formProcess.getEntireState();

        // AJAX
        $.ajax({
            type: 'POST',
            url: '/panel/Carte-grise/changement-de-titulaire/changement-de-titulaire-ajax.php',
            data: JSON.stringify(formData),
            success: function (data) {

                //PRICE SET FOR TEST PORPUSES ONLY
                setPriceWithNoFees();
                setPriceWithFees();
                setRegisterCommission();
                //PRICE SET FOR TEST PORPUSES ONLY

                showPanierRecap();
            },
            error: function (error) {
                popup_alert('Une erreur inconnue est survenue, veuillez réessayer plus tard ou nous contacter pour résoudre le problème.', "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            }
        });
    }

    document.querySelector('.coordonnees-cta1').addEventListener('click', nextStep);
</script>