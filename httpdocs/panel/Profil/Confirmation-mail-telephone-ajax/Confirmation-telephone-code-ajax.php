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

  /*****************************************************\
  * Adresse e-mail => direction@codi-one.fr             *
  * La conception est assujettie à une autorisation     *
  * spéciale de codi-one.com. Si vous ne disposez pas de*
  * cette autorisation, vous êtes dans l'illégalité.    *
  * L'auteur de la conception est et restera            *
  * codi-one.fr                                         *
  * Codage, script & images (all contenu) sont réalisés * 
  * par codi-one.fr                                     *
  * La conception est à usage unique et privé.          *
  * La tierce personne qui utilise le script se porte   *
  * garante de disposer des autorisations nécessaires   *
  *                                                     *
  * Copyright ... Tous droits réservés auteur (Fabien B)*
  \*****************************************************/

if(!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)){

$Confirmer_telephone_code = htmlspecialchars($_POST['Confirmer_telephone_code']);

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM membres_profil WHERE Confirmer_telephone_code=?");
$req_select->execute(array($Confirmer_telephone_code));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$id_Confirmer_telephone_code_controle = $ligne_select['id'];

////////////////////////////////////////////////////////////////////////Update du profil
if(!empty($_POST['Confirmer_telephone_code']) && !empty($id_Confirmer_telephone_code_controle)){

/////////////////////////////////////////////////SI MODULE URL ACTIVE
///////////////////////////////UPDATE
$sql_update = $bdd->prepare("UPDATE membres_profil SET 
	Confirmer_telephone=?
	WHERE pseudo=?");
$sql_update->execute(array(
	'oui',
	$user));                     
$sql_update->closeCursor();
/////////////////////////////////////////////////SI MODULE URL ACTIVE

$result2 = array("Texte_rapport"=>"Code validé avec succès !","retour_validation"=>"ok","retour_lien"=>"");

}elseif(empty($_POST['Confirmer_telephone_code'])){
$result2 = array("Texte_rapport"=>"Vous devez indiquez un code !","retour_validation"=>"","retour_lien"=>"");

}elseif(empty($id_Confirmer_telephone_code_controle)){
$result2 = array("Texte_rapport"=>"Le code ne correspond pas à celui reçu !","retour_validation"=>"","retour_lien"=>"");
}
////////////////////////////////////////////////////////////////////////Update du profil

$result2 = json_encode($result2);
echo $result2;

}else{
header('location: /index.html');
}

ob_end_flush();
?>