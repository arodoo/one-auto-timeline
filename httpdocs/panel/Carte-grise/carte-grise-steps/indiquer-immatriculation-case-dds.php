<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="/css/carte-grise.css">

<div id="dds-matriculation-container" class="content step">
    <div class="row">
        <div class="inputsContainer">
            <div class="title">
                Veuillez indiquer l'immatriculation du véhicule
            </div>
            <div class="immatriculation-container">
                <div class="immatriculation-plaqueVerifContainer">
                    <div class="immatriculation-numeroMain">
                        <div class="immatriculation-middle">
                            <app-inp>
                                <div id="input">
                                    <div class="immatriculation-inputContainer">
                                        <div class="immatriculation-inputContent">
                                            <input spellcheck="false" autocorrect="off" autocomplete="off"
                                                class="immatriculation-elemInp" type="text" id="dds-w0qu4tsdwg"
                                                placeholder="AA-123-AA" maxlength="12" value="">
                                        </div>
                                    </div>
                                </div>
                            </app-inp>
                        </div>
                    </div>
                    <div class="immatriculation-step-cta-container">
                        <button class="btn cta1" id="dds-nextStepButton">Suivant</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="dds-iimtns"></div>

<script type="module">
    import formProcess from '/panel/Carte-grise/js/formProcessSingleton.js';

    document.getElementById('dds-w0qu4tsdwg').addEventListener('input', function (e) {
        let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (value.length > 2) {
            value = value.slice(0, 2) + '-' + value.slice(2);
        }
        if (value.length > 6) {
            value = value.slice(0, 6) + '-' + value.slice(6);
        }
        e.target.value = value;
    })

    document.getElementById('dds-nextStepButton').addEventListener('click', function () {
        var immatriculation = document.getElementById('dds-w0qu4tsdwg').value;
        const regex = /^[A-Z]{2}-[0-9]{3}-[A-Z]{2}$/;
        if (!regex.test(immatriculation)) {
            popup_alert('Veuillez entrer une immatriculation valide', "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            return;
        }
        formProcess.setImmatriculation(immatriculation);
        //console.log(formProcess.getEntireState());
        // Load the next form dynamically based on the process
        let currentProcess = formProcess.getProcess();
        if (currentProcess === 'declaration de session') {
            $('#dds-iimtns').load('/panel/Carte-grise/carte-grise-steps/concerne-situations-dessous.php');
        } 
    });

    $(document).on('DOMNodeRemoved', '.dds-immatriculation-container', function () {
        $('#dds-iimtns').empty();
    });

</script>