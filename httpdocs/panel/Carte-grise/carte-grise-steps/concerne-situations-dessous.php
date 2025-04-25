<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="/css/carte-grise.css">

<div id="concerne-container" class="content">
    <div class="row">
        <div class="inputsContainer">
            <div class="title">
                Veuillez indiquer les informations supplémentaires concernant votre véhicule
            </div>
            <div class="concerne-item" id="csdLeasing">
                <div class="concerne-infoText">Le véhicule est-il pris en leasing ?</div>
                <div class="concerne-radioContainer">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="leasing" id="leasingOui" value="oui">
                        <label class="form-check-label concerne-textValue" for="leasingOui">
                            Oui
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="leasing" id="leasingNon" value="non">
                        <label class="form-check-label concerne-textValue" for="leasingNon">
                            Non
                        </label>
                    </div>
                </div>
            </div>

            <div class="concerne-item" id="csdHandicap">
                <div class="concerne-infoText">Etes-vous concerné par l'exonération pour les personnes en situation de
                    handicap ?</div>
                <div class="concerne-radioContainer">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="handicap" id="handicapOui" value="oui">
                        <label class="form-check-label concerne-textValue" for="handicapOui">
                            Oui
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="handicap" id="handicapNon" value="non">
                        <label class="form-check-label concerne-textValue" for="handicapNon">
                            Non
                        </label>
                    </div>
                </div>
            </div>

            <div class="concerne-item" id="csdSuccession">
                <div class="concerne-infoText">Le véhicule a-t-il été obtenu par succession ?</div>
                <div class="concerne-radioContainer">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="succession" id="successionOui" value="oui">
                        <label class="form-check-label concerne-textValue" for="successionOui">
                            Oui
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="succession" id="successionNon" value="non">
                        <label class="form-check-label concerne-textValue" for="successionNon">
                            Non
                        </label>
                    </div>
                </div>
            </div>

            <div class="concerne-item" id="csdTechChange">
                <div class="concerne-infoText">Souhaitez-vous également déclarer un changement de caractéristiques
                    techniques ?</div>
                <div class="concerne-radioContainer">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="techChange" id="techChangeOui" value="oui">
                        <label class="form-check-label concerne-textValue" for="techChangeOui">
                            Oui
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="techChange" id="techChangeNon" value="non">
                        <label class="form-check-label concerne-textValue" for="techChangeNon">
                            Non
                        </label>
                    </div>
                </div>
            </div>

            <div class="concerne-item" id="csdImmatriculationDeType123ABC01">
                <div class="concerne-infoText">Le véhicule possède-t-il une ancienne immatriculation de type 123 ABC 01
                    ?</div>
                <div class="concerne-radioContainer">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="immatriculationDeType123ABC01"
                            id="immatriculationDeType123ABC01Oui" value="oui">
                        <label class="form-check-label concerne-textValue" for="immatriculationDeType123ABC01Oui">
                            Oui
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="immatriculationDeType123ABC01"
                            id="immatriculationDeType123ABC01Non" value="non">
                        <label class="form-check-label concerne-textValue" for="immatriculationDeType123ABC01Non">
                            Non
                        </label>
                    </div>
                </div>
            </div>

            <div class="concerne-item" id="csdVenduEtranger">
                <div class="concerne-infoText">Le véhicule est-il vendu à l'étranger ?</div>
                <div class="concerne-radioContainer">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="venduEtranger" id="venduEtrangerOui"
                            value="oui">
                        <label class="form-check-label concerne-textValue" for="venduEtrangerOui">
                            Oui
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="venduEtranger" id="venduEtrangerNon"
                            value="non">
                        <label class="form-check-label concerne-textValue" for="venduEtrangerNon">
                            Non
                        </label>
                    </div>
                </div>
            </div>

            <div class="concerne-stepCtaContainer alignColumnCenterX">
                <button class="concerne-cta1">Suivant</button>
            </div>
        </div>
    </div>
</div>
<div id="csdtic"></div>

<style>
    .hidden {
        display: none;
    }
</style>

<script type="module">
    import formProcess from '/panel/Carte-grise/js/formProcessSingleton.js';

    function initialize() {
        const process = formProcess.getProcess();
        const elements = {
            leasing: document.getElementById('csdLeasing'),
            handicap: document.getElementById('csdHandicap'),
            succession: document.getElementById('csdSuccession'),
            techChange: document.getElementById('csdTechChange'),
            immatriculationDeType123ABC01: document.getElementById('csdImmatriculationDeType123ABC01'),
            venduEtranger: document.getElementById('csdVenduEtranger')
        };

        if (process === 'changement de domicile') {
            Object.values(elements).forEach(element => element.classList.add('hidden'));
            elements.leasing.classList.remove('hidden');
            elements.immatriculationDeType123ABC01.classList.remove('hidden');
        } else if (process === 'declaration de session') {
            Object.values(elements).forEach(element => element.classList.add('hidden'));
            elements.venduEtranger.classList.remove('hidden');
        } else {
            elements.immatriculationDeType123ABC01.classList.add('hidden');
            elements.venduEtranger.classList.add('hidden');
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener("DOMContentLoaded", initialize);
    } else {
        initialize();
    }

    function nextStep() {
        const elements = {
            leasing: 'csdLeasing',
            handicap: 'csdHandicap',
            succession: 'csdSuccession',
            techChange: 'csdTechChange',
            immatriculationDeType123ABC01: 'csdImmatriculationDeType123ABC01',
            venduEtranger: 'csdVenduEtranger'
        };

        Object.keys(elements).forEach(key => {
            const element = document.getElementById(elements[key]);
            if (!element.classList.contains('hidden')) {
                const value = document.querySelector(`input[name="${key}"]:checked`)?.value || '';
                formProcess[`set${key.charAt(0).toUpperCase() + key.slice(1)}`](value);
            }
        });
        //console.log(formProcess.getEntireState());
        $('#csdtic').load('/panel/Carte-grise/carte-grise-steps/indiquer-coordonnees.php');
    }

    document.querySelector('.concerne-cta1').addEventListener('click', nextStep);
</script>