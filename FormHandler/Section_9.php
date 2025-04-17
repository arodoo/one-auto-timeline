<?php
require_once '../dataHandler/FormSingleton.php';
$form = FormSingleton::getInstance();
require_once '../Components/Canvas.php';
?>
<div class="text-center">
    <?php
    $canvas = new Canvas(
        'sc9-canvas',
        'CROQUIS',
        'Désigner les véhicules A et B conformément au recto. Préciser : 1. Le tracé des voies - 2. La direction (par des flèches) des véhicules a, B - 3. Leur position au moment du choc - 4. Les signaux routiers - 5. Le nom des rues (ou routes).',
        $form->getNextCounter('P2-'),
        '',  // color parameter
        's9_final_sketch'  // database field name
    );
    echo $canvas->render();
    ?>
</div>

<?php
$canvas->initializeCanvas();
$canvas->loadCanvasData();
?>