<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="/css/carte-grise.css">

<style>
    .titulaire-item.selected {
        border: 5px solid #e3e151;
    }
</style>

<div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
    <div class="card-body">
        <div id="timeline-wizard-container">
            <div id="timeline"></div>
        </div>
        <div class="row">
            <div class="titulaire-container">
                <div class="title">
                    De quel véhicule s'agit-il ?
                </div>
                <app-rad class="full">
                    <div id="radio">
                        <div class="etatVehicule titulaire-radio-container">
                            <div class="titulaire-item" data-value="occasion">
                                <div class="left">
                                    <p class="titulaire-choice-name">Occasion</p>
                                    <p class="titulaire-text">Véhicule d'occasion acheté en France</p>
                                </div>
                                <p class="titulaire-icons"><i class="fas fa-arrow-right"></i></p>
                            </div>
                            <div class="titulaire-item" data-value="neuf">
                                <div class="left">
                                    <p class="titulaire-choice-name">Neuf</p>
                                    <p class="titulaire-text">Véhicule neuf acheté en France</p>
                                </div>
                                <p class="titulaire-icons"><i class="fas fa-arrow-right"></i></p>
                            </div>
                            <div class="titulaire-item" data-value="etranger">
                                <div class="left">
                                    <p class="titulaire-choice-name">Étranger</p>
                                    <p class="titulaire-text">Véhicule neuf ou d'occasion acheté à l'étranger</p>
                                </div>
                                <p class="titulaire-icons"><i class="fas fa-arrow-right"></i></p>
                            </div>
                        </div>
                    </div>
                </app-rad>
                <div class="titulaire-step-cta-container alignColumnCenterX">
                    <div class="titulaire-retour-container alignCenter">
                    </div>
                </div>
            </div>
        </div>
        <div id="timeline-container"></div>
    </div>
</div>

<script type="module">
    import formProcess from '/panel/Carte-grise/js/formProcessSingleton.js';

    function bindClickEvents() {
        $(document).off('click', '.titulaire-item');
        $(document).on('click', '.titulaire-item', function () {
            var selectedValue = $(this).data('value');

            // Highlight the selected item
            highlightSelectedItem($(this));

            //Reset form state 
            formProcess.resetFormState();
            formProcess.setProcess('changement de titulaire');

            // Set the etatVehicule value
            formProcess.setEtatVehicule(selectedValue);
            //console.log(formProcess.getEntireState());

            // Clear the timeline container before showing the next form
            $('#timeline-container').empty();
            
            // Show the next form in the timeline container
            if (selectedValue === 'occasion') {
                $('#timeline-container').append('<div id="occasion-content" ></div>');
                $('#occasion-content').load('/panel/Carte-grise/carte-grise-steps/indiquer-immatriculation.php');
            } else if (selectedValue === 'neuf') {
                $('#timeline-container').append('<div id="neuf-content" ></div>');
                $('#neuf-content').load('/panel/Carte-grise/carte-grise-steps/completer-vehicule-information.php');
            } else if (selectedValue === 'etranger') {
                $('#timeline-container').append('<div id="etranger-content" ></div>');
                $('#etranger-content').load('/panel/Carte-grise/carte-grise-steps/completer-vehicule-information.php');
            }
        });
    }

    function highlightSelectedItem(item){
        $('.titulaire-item').removeClass('selected');
        item.addClass('selected');
    }

    $(document).ready(function () {
        bindClickEvents();
    });
</script>
<script type="module" src="/panel/Carte-grise/changement-de-titulaire/wizard-animations.js"></script>