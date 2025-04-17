<?php

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

$action = $_GET['action'];
$nbrid = $_GET['nbrid'];
$now = time();

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM membres_profil WHERE Confirmer_mail_code_securite=?");
$req_select->execute(array($nbrid));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idccontrolec = $ligne_select['id'];

/////////////////Confirmation
if($action == "mail" && !empty($nbrid) && !empty($idccontrolec) ){

///////////////////////////////UPDATE
$sql_update = $bdd->prepare("UPDATE membres_profils SET 
	Confirmer_mail=?
	WHERE pseudo=?");
$sql_update->execute(array(
	'oui',
	$user));                     
$sql_update->closeCursor();

$de_nom = "$nomsiteweb"; //Nom de l'envoyeur
$de_mail = "$emaildefault"; //Email de l'envoyeur
$vers_nom = "$user"; //Nom du receveur
$vers_mail = "$mail_oo"; //Email du receveur
$sujet = mail_bi($type_scan="sujet",$id_mail_requete=7);

$message_principalone = mail_bi($type_scan="",$id_mail_requete=7);

$_SESSION['retour_confirmation_mail'] = "oui";
header("location: /Modifier-profil.html");

}else{

$_SESSION['retour_confirmation_mail'] = "non";
header("location: /Modifier-profil.html");
}
/////////////////Confirmation

?>
