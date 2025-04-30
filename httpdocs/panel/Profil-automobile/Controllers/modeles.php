<?php
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

$dir_fonction = "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

require_once __DIR__ . '/../Models/BrandModel.php';

// Security check
if (empty($_SESSION['4M8e7M5b1R2e8s']) || empty($user)) {
    exit;
}

$brandModel = new BrandModel($bdd);
$brand = isset($_POST['marque']) ? $_POST['marque'] : '';

if (empty($brand)) {
    echo '';
    exit;
}

$models = $brandModel->getModelsByBrand($brand);
foreach ($models as $model) {
    echo '<option value="' . htmlspecialchars($model) . '">';
}
?>