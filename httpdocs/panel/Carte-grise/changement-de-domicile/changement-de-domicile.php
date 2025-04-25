<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="/css/carte-grise.css">

<div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
    <div class="card-body">
        <div id="cdd-timeline-wizard-container">
            <div id="cdd-timeline"></div>
        </div>
        <div class="row">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/panel/Carte-grise/carte-grise-steps/indiquer-immatriculation.php'; ?>
        </div>
        <div id="cdd-timeline-container"></div>
    </div>
</div>

<script type="module">
    import formProcess from '/panel/Carte-grise/js/formProcessSingleton.js';
</script>
<script type="module" src="/panel/Carte-grise/changement-de-domicile/wizard-animations.js"></script>