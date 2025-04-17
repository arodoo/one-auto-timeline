<?php
require_once '../dataHandler/FormSingleton.php';
$form = FormSingleton::getInstance();
require_once '../Components/Canvas.php';

// Ensure the session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if we're in jumelage mode for user B
$isJumelageModeUserB = isset($_SESSION['jumelage_mode']) && $_SESSION['jumelage_mode'] === true;
?>
<div class="text-center">
    <div class="alert alert-info mt-3 jumelage-info-message" style="display: none;">
        <i class="fas fa-info-circle"></i> La signature du conducteur B sera remplie par <span class="jumelage-email"></span>
    </div>
    <?php
    $canvas = new Canvas(
        'sc7-canvas',
        'Signature de conducteur B',
        'Veuillez placer votre signature électronique ici. Assurez-vous que la signature soit claire et lisible. Utilisez la souris ou le doigt sur les appareils tactiles pour dessiner votre signature dans la zone désignée. Si vous avez besoin de recommencer, cliquez sur le bouton d\'effacement.',
        $form->getNextCounter('P1-'),
        'color: #cfcfff;', // Optional color parameter
        's7_signature_b'  // database field name
    );
    echo $canvas->render();
    ?>
</div>

<?php
$canvas->initializeCanvas();
$canvas->loadCanvasData();
?>

<?php if ($isJumelageModeUserB): ?>
    <div class="row mt-4 mt-8">
        <div class="col-12 text-center">
            <button class="btn btn-success" onclick="console.log('Submit button clicked'); window.saveConstat('update')">Soumettre le Constat Partagé</button>
        </div>
    </div>
<?php endif; ?>

<script src="/panel/Constats/constant-form/JS/Section7Jumelage.js"></script>
<script>
    // Initialize immediately since this script is lazy-loaded
    handleCanvasJumelageState();
</script>