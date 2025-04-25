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

if(isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && isset($user) ){

///////ON SUPPRIME L'IMAGE D'UN MESSAGE
if(!empty($_POST['table'])){

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM ".$_POST['table']." WHERE id=?");
$req_select->execute(array($_POST['idaction']));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$id_message = $ligne_select['id'];
$pseudo_message = $ligne_select['pseudo'];
$pseudo_destinataire = $ligne_select['pseudo_destinataire'];
$fichier = $ligne_select['fichier'];

///////////////////////////////UPDATE
$sql_update = $bdd->prepare("UPDATE ".$_POST['table']." SET fichier=? WHERE id=?");
$sql_update->execute(array('',htmlspecialchars($_POST['idaction']) ));                     
$sql_update->closeCursor();

if(file_exists("/images/membres/".$pseudo_message."/$fichier") || file_exists("/images/membres/".$pseudo_destinataire."/$fichier") ){

if(file_exists("/images/membres/".$pseudo_message."/$fichier")){
$repertoire_fichier = "/images/membres/".$pseudo_message."/$fichier";
}elseif(file_exists("/images/membres/".$pseudo_destinataire."/$fichier") ){
$repertoire_fichier = "/images/membres/".$pseudo_destinataire."/$fichier";
}
if(!empty($repertoire_fichier)){
unlink("$repertoire_fichier");
}
}

$result = array("Texte_rapport"=>"Fichier supprimé avec succès !","retour_validation"=>"ok","retour_lien"=>$_POST['retour_lien']);
}
///////ON SUPPRIME L'IMAGE D'UN MESSAGE


$result = json_encode($result);
echo $result;

}else{
header('location: /index.html');
}

ob_end_flush();
?>