<?php
require_once '../dataHandler/FormSingleton.php';
$form = FormSingleton::getInstance();
require_once '../Components/Canvas.php';
?>
<div class="text-center">
    <?php
    $canvas = new Canvas(
        'sc5-canvas',
        'Croquis de l’accident au moment du choc',
        'Préciser : 1. le tracé des voies - 2. La direction (par des flèches) des véhicules A, B - 3. leur position au moment du choc - 4. les signaux routiers - 5. le nom des rues (ou routes).',
        $form->getNextCounter('P1-'),
        '',  // color parameter
        's5_accident_sketch'  // database field name
    );
    echo $canvas->render();
    ?>
</div>

<?php
$canvas->initializeCanvas();
$canvas->loadCanvasData();
?>