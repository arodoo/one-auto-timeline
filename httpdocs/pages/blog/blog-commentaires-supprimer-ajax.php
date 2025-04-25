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

if(isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)){

/////////////////////////////ACTION - SUPPRIMER LES COMMENTAIRES
if(!empty($_POST['supprimer_commentaire'])){

foreach($_POST['supprimer_commentaire'] as $idcommentaire){
///////////////////////////////DELETE
$sql_delete = $bdd->prepare("DELETE FROM codi_one_blog_commentaires WHERE id=?");
$sql_delete->execute(array($idcommentaire));                     
$sql_delete->closeCursor();

}
$result = array("Texte_rapport"=>"Sélection supprimée ! avec succès !","retour_validation"=>"ok","retour_lien"=>"");

}elseif(empty($_POST['supprimer_commentaire']) && isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && isset($user)){
$result = array("Texte_rapport"=>"Vous devez sélectionner un commentaire !","retour_validation"=>"","retour_lien"=>"");

}
/////////////////////////////ACTION - SUPPRIMER LES COMMENTAIRES

$result = json_encode($result);
echo $result;

}else{
header("HTTP/1.0 410 Gone");
}

ob_end_flush();
?>