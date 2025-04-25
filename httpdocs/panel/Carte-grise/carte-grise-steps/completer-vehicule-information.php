<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="/css/carte-grise.css">

<div id="completer-container" class="content">
    <div class="row">
        <div class="inputsContainer">
            <div class="title">
                Veuillez compléter les informations de votre véhicule
            </div>
            <div class="completer-item">
                <div class="completer-infoText">Repère J.1 sur la carte grise</div>
                <div class="completer-selectContainer">
                    <div class="completer-selLabel">
                        <div>Genre du véhicule (J.1)</div>
                    </div>
                    <div class="completer-elemContainer">
                        <select class="form-control" id="genre" name="genre">
                            <option value="Véhicule utilitaire - CTTE, DERIV-VP">Véhicule utilitaire - CTTE, DERIV-VP
                            </option>
                            <option value="Véhicule particulier - VP, VT, M1">Véhicule particulier - VP, VT, M1</option>
                            <option value="Motocyclette < 125 cm3 - MTL, L3e, L4e">Motocyclette &lt; 125 cm3 - MTL, L3e,
                                L4e
                            </option>
                            <option value="Moto > 125 cm3 - MTT1, MTT2, L3e, L4e">Moto &gt; 125 cm3 - MTT1, MTT2, L3e,
                                L4e
                            </option>
                            <option value="Cyclomoteur - CL, L1e, L2e">Cyclomoteur - CL, L1e, L2e</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="completer-item" id="carburant-container" style="display: none;">
                <div class="completer-infoText">Repère 25 sur le COC</div>
                <div class="completer-selectContainer">
                    <div class="completer-selLabel">
                        <div>Carburant utilisé par le véhicule (P.3)</div>
                    </div>
                    <div class="completer-elemContainer">
                        <select class="form-control" id="carburant" name="carburant">
                            <option value="Essence - ES">Essence - ES</option>
                            <option value="Diesel - GO">Diesel - GO</option>
                            <option value="Hybride rechargeable - EE, GL">Hybride rechargeable - EE, GL</option>
                            <option value="Hybride non rechargeable - EH, GH">Hybride non rechargeable - EH, GH</option>
                            <option value="Electricité / Hydrogène - EL, H2, HE, HH">Electricité / Hydrogène - EL, H2,
                                HE,
                                HH</option>
                            <option value="Superéthanol E85 - FE">Superéthanol E85 - FE</option>
                            <option value="Superéthanol hybrides - FG, FN, FL, FH">Superéthanol hybrides - FG, FN, FL,
                                FH
                            </option>
                            <option value="GPL - GP, EG, ER, EQ, G2, PE, PH">GPL - GP, EG, ER, EQ, G2, PE, PH</option>
                            <option value="Gaz naturel - GN, EN, GF, 1A, EM, EP, GM, GQ, NE, NH">Gaz naturel - GN, EN,
                                GF,
                                1A, EM, EP, GM, GQ, NE, NH</option>
                            <option value="Autre - ET, GA, GE, GZ, GG, PL, AC">Autre - ET, GA, GE, GZ, GG, PL, AC
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="completer-item" id="dateCirculation-container" style="display: none;">
                <div class="input-date">
                    <div class="completer-infoText">Repère B sur la carte grise</div>
                    <div class="completer-inputContainer">
                        <span class="completer-inputLabel"><label for="dateCirculation">Date de mise en circulation
                                (B)</label></span>
                        <div class="completer-inputContent">
                            <input class="completer-elemInp" id="dateCirculation" maxlength="10"
                                 type="date">
                        </div>
                    </div>
                </div>
            </div>

            <div class="completer-item" id="chevauxFiscaux-container" style="display: none;">
                <div class="input-chevaux">
                    <div class="completer-infoText">Repère P.6 sur la carte grise</div>
                    <div class="completer-inputContainer">
                        <span class="completer-inputLabel"><label for="chevauxFiscaux">Chevaux fiscaux
                                (P.6)</label></span>
                        <div class="completer-inputContent">
                            <input spellcheck="false" autocorrect="off" autocomplete="off" class="completer-elemInp"
                                type="tel" id="chevauxFiscaux" placeholder="Entrer un nombre entre 1 et 36"
                                maxlength="2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="completer-stepCtaContainer completer-alignColumnCenterX" id="cta-container" style="display: none;">
            <button class="completer-cta1">Suivant</button>
        </div>
    </div>
</div>
<div id="cvitcsd"></div>

<script type="module">
    import formProcess from '/panel/Carte-grise/js/formProcessSingleton.js';

    document.getElementById('genre').addEventListener('change', function () {
        const genre = this.value;
        const isCyclomoteur = genre === 'Cyclomoteur - CL, L1e, L2e';

        //reset values to empty
        document.getElementById('carburant').value = '';
        document.getElementById('dateCirculation').value = '';
        document.getElementById('chevauxFiscaux').value = '';

        if (formProcess.getEntireState().etatVehicule !== 'neuf') {
            document.getElementById('dateCirculation-container').style.display = 'block';
        } else {
            document.getElementById('dateCirculation-container').style.display = 'none';
        }

        document.getElementById('cta-container').style.display = 'block';

        if (isCyclomoteur) {
            document.getElementById('carburant-container').style.display = 'none';
            document.getElementById('chevauxFiscaux-container').style.display = 'none';
        } else {
            document.getElementById('carburant-container').style.display = 'block';
            document.getElementById('chevauxFiscaux-container').style.display = 'block';
        }
    });

    function nextStep() {
        const genre = document.getElementById('genre').value;
        const carburant = document.getElementById('carburant').value;
        const dateCirculation = document.getElementById('dateCirculation').value;
        const chevauxFiscaux = document.getElementById('chevauxFiscaux').value;

        const currentDate = new Date();
        const minDate = new Date('1900-01-01');
        const inputDate = new Date(dateCirculation.split('/').reverse().join('-'));

        if (formProcess.getEntireState().etatVehicule !== 'neuf') {
            if (inputDate > currentDate || inputDate < minDate || isNaN(inputDate)) {
                popup_alert('Veuillez entrer une date valide', "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                return;
            }
        }

        // If not cyclomoteur, validate other fields
        if (genre !== 'Cyclomoteur - CL, L1e, L2e') {
            if (chevauxFiscaux === '' || isNaN(chevauxFiscaux) || parseInt(chevauxFiscaux, 10) < 1 || parseInt(chevauxFiscaux, 10) > 36) {
                popup_alert('Veuillez entrer un nombre de chevaux fiscaux valide entre 1 et 36', "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                return;
            }

            if (!carburant) {
                popup_alert('Veuillez entrer un carburant', "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                return;
            }

        }

        formProcess.setGenre(genre);
        formProcess.setCarburant(carburant);
        formProcess.setDateCirculation(dateCirculation);
        formProcess.setChevauxFiscaux(chevauxFiscaux);

        // Load the next form dynamically
        $('#cvitcsd').load('/panel/Carte-grise/carte-grise-steps/concerne-situations-dessous.php');
    }
    document.querySelector('.completer-cta1').addEventListener('click', nextStep);

    //only numbers from 1 to 36
    document.getElementById('chevauxFiscaux').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value !== '') {
            value = Math.min(Math.max(parseInt(value, 10), 1), 36);
        }
        e.target.value = value;
    });
</script>