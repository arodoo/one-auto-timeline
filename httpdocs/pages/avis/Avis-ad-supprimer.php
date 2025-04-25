<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

if(!empty($_SESSION['7A5d8M9i4N9']) && !empty($_SESSION['4M8e7M5b1R2e8s'])){

///////////////On delete un avis
if(!empty($_POST['supprimeaction']) && $_POST['action'] == "update" && isset($user) && !empty($_SESSION['7A5d8M9i4N9']) && !empty($_SESSION['4M8e7M5b1R2e8s']) ){

foreach($_POST['supprimeaction'] as $newidoneldor){
///////////////////////////////DELETE
$sql_delete = $bdd->prepare("DELETE FROM avis WHERE id=?");
$sql_delete->execute(array($newidoneldor));                     
$sql_delete->closeCursor();
}

$result = array("Texte_rapport"=>"Suppression effectuée avec succès !","retour_validation"=>"ok","retour_lien"=>$lasturl);

}elseif(empty($_POST['supprimeaction']) && $_POST['action'] == "update" && isset($user) && !empty($_SESSION['7A5d8M9i4N9']) && !empty($_SESSION['4M8e7M5b1R2e8s']) ){
$result = array("Texte_rapport"=>"Vous sélectionnez un élément !","retour_validation"=>"","retour_lien"=>$lasturl);

}

///////////////On delete un avis

$result = json_encode($result);
echo $result;

}else{
header("HTTP/1.0 410 Gone");
}

ob_end_flush();
?>