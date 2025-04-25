<?php
ob_start();
require_once ('../../Configurations_bdd.php');
require_once ('../../Configurations.php');
require_once ('../../Configurations_modules.php');

$data = json_decode(file_get_contents('php://input'), true);
$_SESSION['lat'] = $data['lat'];
$_SESSION['lng'] = $data['lng'];

echo json_encode(['res' => 'ok', 'session' => $_SESSION]);

ob_end_flush();
?>