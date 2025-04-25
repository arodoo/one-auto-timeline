<?php
header('Content-Type: application/json');
session_start();
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

$dir_fonction = "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');





$query = $bdd->prepare("
    SELECT * FROM `membres_panier_details` WHERE id_membre = ?
");
$query->execute([$id_oo]);
$cart_items = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($cart_items);
?>
