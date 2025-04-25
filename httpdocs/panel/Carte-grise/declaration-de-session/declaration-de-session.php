<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="/css/carte-grise.css">

<div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
    <div class="card-body">
        <div id="dds-timeline-wizard-container">
            <div id="dds-timeline"></div>
        </div>
        <div class="row">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/panel/Carte-grise/carte-grise-steps/indiquer-immatriculation-case-dds.php'; ?>
        </div>
        <div id="dds-timeline-container"></div>
    </div>
</div>

<script type="module">
    import formProcess from '/panel/Carte-grise/js/formProcessSingleton.js';
</script>
<script type="module" src="/panel/Carte-grise/declaration-de-session/wizard-animations.js"></script>