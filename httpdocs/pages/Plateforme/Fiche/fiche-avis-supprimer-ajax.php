<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once ('../../../Configurations_bdd.php');
require_once ('../../../Configurations.php');
require_once ('../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../../";
require_once ('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

if($admin_oo > 0 ){

///////////////On delete un avis
if(!empty($_POST['idaction']) ){

	$sql_delete = $bdd->prepare("DELETE FROM membres_etablissements_avis WHERE id=?");
	$sql_delete->execute(array($_POST['idaction']));                     
	$sql_delete->closeCursor();

	$sql_avis = $bdd->prepare("SELECT * FROM membres_etablissements_avis WHERE id_etablissement=?");
	$sql_avis->execute([ $_POST['id_etablissement'] ]); 
	$avis = $sql_avis->fetchAll();                    
	$sql_avis->closeCursor();

	$totalAvis = 0;
	$nombreAvis = 0;

	foreach ($avis as $av) {
  		$totalAvis = $totalAvis + $av['note'];
  		$nombreAvis++;
	}
	if($nombreAvis != 0){
		$moyenne = round(($totalAvis / $nombreAvis), 0);
	}
	
	$sql_avis = $bdd->prepare("UPDATE membres_etablissements SET avis=? WHERE id=?");
	$sql_avis->execute( [ $moyenne, $_POST['id_etablissement'] ]);                     
	$sql_avis->closeCursor();

	$result = array("Texte_rapport"=>"Avis supprimé avec succès !","retour_validation"=>"ok","retour_lien"=>"");

}

$result = json_encode($result);
echo $result;

}else{
header("HTTP/1.0 410 Gone");
}

ob_end_flush();
?>