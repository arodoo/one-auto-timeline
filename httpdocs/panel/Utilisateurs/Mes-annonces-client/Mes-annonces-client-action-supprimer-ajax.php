<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
$action = $_POST['action'];
$idaction = $_POST['idaction'];

// Supprimer les entrées des images de la base de données
$sql_delete_images = $bdd->prepare("DELETE FROM membres_devis WHERE id_annonce_client = ? AND id_membre_utilisateur=?");
$sql_delete_images->execute(array($idaction,$id_oo));
$sql_delete_images->closeCursor();

// Supprimer le produit
$sql_delete = $bdd->prepare("DELETE FROM membres_annonces_clients WHERE id = ? AND id_membre = ?");
$sql_delete->execute(array($idaction, $id_oo));
$sql_delete->closeCursor();

$result = array("Texte_rapport" => "Annonce supprimée.", "retour_validation" => "ok", "retour_lien" => "");

echo json_encode($result);
} else {
header('location: /');
}

ob_end_flush();
?>