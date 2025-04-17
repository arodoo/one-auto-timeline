<?php
require_once '../dataHandler/FormSingleton.php';
$form = FormSingleton::getInstance();
require_once '../Components/Canvas.php';
?>
<div class="text-center">
    <?php
    $canvas = new Canvas(
        'sc6-canvas',
        'Signature de conducteur A',
        'Veuillez placer votre signature électronique ici. Assurez-vous que la signature soit claire et lisible. Utilisez la souris ou le doigt sur les appareils tactiles pour dessiner votre signature dans la zone désignée. Si vous avez besoin de recommencer, cliquez sur le bouton d\'effacement.',
        $form->getNextCounter('P1-'),
        '',  // color parameter
        's6_signature_a'  // database field name
    );
    echo $canvas->render();
    ?>
</div>

<?php
$canvas->initializeCanvas();
$canvas->loadCanvasData();
?>
