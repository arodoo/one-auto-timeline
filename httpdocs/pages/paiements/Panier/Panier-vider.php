<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

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

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM membres_panier WHERE pseudo=?");
$req_select->execute(array($user));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$id_panier = $ligne_select['id'];

if(!empty($user) && !empty($id_panier) ){

///////////////////////////////DELETE
$sql_delete = $bdd->prepare("DELETE FROM membres_panier WHERE pseudo=?");
$sql_delete->execute(array($user));                     
$sql_delete->closeCursor();

///////////////////////////////DELETE
$sql_delete = $bdd->prepare("DELETE FROM membres_panier_details WHERE pseudo=?");
$sql_delete->execute(array($user));                     
$sql_delete->closeCursor();

unset($_SESSION['option_impression_oui']);

$result = array("Texte_rapport"=>"Panier supprimé avec succès !","retour_validation"=>"ok","retour_lien"=>"");

}

$result = json_encode($result);
echo $result;

ob_end_flush();
?>