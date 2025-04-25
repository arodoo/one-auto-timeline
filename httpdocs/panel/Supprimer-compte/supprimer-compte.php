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

if(!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user) ){

	//unset($_SESSION['demande_suppression_de_compte_confirmer']);

	$mail_compte_concerne = $mail_oo;
	$module_log = "SUPPRESSION";
	$action_sujet_log = "Notification de suppression de votre compte";
	$action_libelle_log = "Notification de votre compte <b>$mail_compte_concerne</b> sur $nomsiteweb. Vous avez demandé à supprimer votre compte, si vous n'êtes pas à l'origine de cette action,
	veuillez sans attendre contacter un administrateur sur la page
	<a href='".$http."".$nomsiteweb."/Contact' target='blank_' style='text-decoration: underline;' >Contact</a>";
	$action_log = "DEMANDE";
	$niveau_log = "1";
	$compte_bloque = "";
	log_h($mail_compte_concerne,$module_log,$action_sujet_log,$action_libelle_log,$action_log,$niveau_log,$compte_bloque);

	///////////////////////////////UPDATE
	$sql_update = $bdd->prepare("UPDATE membres SET 
		demande_de_suppression=?,
		demande_de_suppression_date=?
		WHERE pseudo=?");
	$sql_update->execute(array(
		'oui',
		time(),
		$user));                     
	$sql_update->closeCursor();

	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT * FROM membres WHERE pseudo=?");
	$req_select->execute(array($user));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
	$idd2dddf = $ligne_select['id']; 
	$pseudo2 = $ligne_select['pseudo'];
	$mail = $ligne_select['mail'];
	$nom = $ligne_select['nom'];
	$prenom = $ligne_select['prenom'];

	///////////////////////Mail client
	$de_nom = "$nomsiteweb"; //Nom de l'envoyeur
	$de_mail = "$emaildefault"; //Email de l'envoyeur
	$vers_nom = "$pseudo2"; //Nom du receveur
	$vers_mail = "$mail"; //Email du receveur
	$sujet = "Suppression de votre compte $sur $nomsiteweb";

	$message_principalone = "<b>Objet :</b> $sujet<br /><br />
	<b>Bonjour, </b><br /><br />  
	Votre demande va être prise en compte par un membre de l'équipe, très rapidement. <br /><br />
	Nous restons à votre diposition. <br /><br />
	Cordialement l'équipe";
	mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);
	///////////////////////Mail client

	///////////////////////Mail support
	$de_nom = "$pseudo2"; //Nom de l'envoyeur
	$de_mail = "$mail"; //Email de l'envoyeur
	$vers_nom = "$nomsiteweb"; //Nom du receveur
	$vers_mail = "$emaildefault"; //Email du receveur
	$sujet = "Suppression du compte $pseudo2 sur $nomsiteweb";

	$message_principalone = "<b>Objet:</b> $sujet<br /><br />
	<b>Bonjour, </b><br /><br />  

	<b><u>Récapitulatif:</u></b><br /><br />
	<b>Pseudo du compte à supprimer:</b><span style='color: green;'> $pseudo2 </span><br />
	<b>L'email associé :</b> $mail<br /><br />
	<b>Nom d'usage :</b> $nom<br />
	<b>Prénom :</b> $prenom<br />
	<br />
	Veuillez vous connecter à votre back office, pour effectuer le droit d'exercice de l'utilisateur demandeur, dans le module associé
	à la gestion des membres.<br />
	<br />
	Cordialement,
	";

	mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);
	///////////////////////Mail support

$result = array("Texte_rapport"=>"Votre demande à été envoyée !","retour_validation"=>"ok","retour_lien"=>"");

$result = json_encode($result);
echo $result;

}else{
header('location:index.html');
}

ob_end_flush();
?>