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

///////////////On desactive un avis
if($_POST['action'] == "desactiver" && !empty($_SESSION['7A5d8M9i4N9']) && !empty($_SESSION['4M8e7M5b1R2e8s'])){

///////////////////////////////UPDATE
$sql_update = $bdd->prepare("UPDATE avis SET plus1=? WHERE id=?");
$sql_update->execute(array('',$_POST['idaction']));                     
$sql_update->closeCursor();

$result = array("Texte_rapport"=>"Avis désactivé avec succès !","retour_validation"=>"ok","retour_lien"=>"");

}
///////////////On desactive un avis

$result = json_encode($result);
echo $result;

}else{
header("HTTP/1.0 410 Gone");
}

ob_end_flush();
?>
