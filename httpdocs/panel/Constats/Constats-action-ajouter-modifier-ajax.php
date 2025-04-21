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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)){

$action = $_POST['action'];
$idaction = $_POST['idaction'];

////////////////////////////MODIFIER
if($action == "modifier-action"){

$result = array("Texte_rapport"=>"Constat modifié !","retour_validation"=>"ok","retour_lien"=>"");

}
////////////////////////////MODIFIER

////////////////////////////AJOUTER
if($action == "ajouter-action"){

$result = array("Texte_rapport"=>"Constat ajouté !","retour_validation"=>"ok","retour_lien"=>"");

}
////////////////////////////AJOUTER

$result = json_encode($result);
echo $result;

}else{
header('location: /');
}

ob_end_flush();
?>
