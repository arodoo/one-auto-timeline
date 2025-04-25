<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../";
include('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

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

unset($_SESSION['nbr_test_mot_de_passe']);

$action_password = $_GET['action_password'];
$erreur = $_GET['erreur'];
$dateverif = (time()-7200);
$nowtime = time();

////////////////////////////////////////////////////////////////////////////////SI FORMULAIRE DEMANDE DE REDEFINITION
if (!empty($_POST['mail_password_redefinition']) && empty($action_password) ){

	$mail = $_POST['mail_password_redefinition'];
	$idverif = create_password();

	////////////////////////////////////////////////////////////////////////////////SI 3 TENTATIVES POUR DEFINIR UN MOT DE PASSE
	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT * FROM membres WHERE mail=? and compte_bloque=? ");
	$req_select->execute(array(htmlspecialchars($mail),'oui'));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
  	$id_controle_compte_bloque = $ligne_select['id'];

	////////////////////////////////////////////////////////////////////////////////SI L'ADRESSE MAIL EXISTE
	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT * FROM membres WHERE mail=?");
	$req_select->execute(array(htmlspecialchars($mail)));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
	$id_password = $ligne_select['id'];
	$nom_password = $ligne_select['nom'];
	$prenom_password = $ligne_select['prenom'];
	$mail_password = $ligne_select['mail'];

	if (empty($id_password)){
		$hack = 'oui';

		///////////////////////////////DELETE
		$sql_delete = $bdd->prepare("DELETE FROM membres_password_perdu WHERE mail=?");
		$sql_delete->execute(array(htmlspecialchars($mail)));                     
		$sql_delete->closeCursor();

		if(!empty($mail_password)){
		$mail_compte_concerne = $mail_password;
		$module_log = "NOUVEAU MOT DE PASSE";
		$action_sujet_log = "Notification de votre compte ".$mail_compte_concerne." pour définir un mot de passe";
		$action_libelle_log = "Notification de votre compte <b>$mail_compte_concerne</b> sur $nomsiteweb. La tentative pour définir un nouveau mot de passe à échoué. 
		Si vous n'êtes pas à l'origine de cette action, veuillez sans attendre contacter un administrateur sur la page
		<a href='".$http."".$nomsiteweb."/Contact' target='blank_' style='text-decoration: underline;' >Contact</a>";
		$action_log = "DEMANDE";
		$niveau_log = "2";
		$compte_bloque = "";
		log_h($mail_compte_concerne,$module_log,$action_sujet_log,$action_libelle_log,$action_log,$niveau_log,$compte_bloque);
		}
		$result = array("Texte_rapport"=>"<div class='alert alert-danger' role='alert' style='text-align: left;' ><span class='uk-icon-warning'></span> L'adresse mail, n'existe pas.</div>","retour_validation"=>"","retour_lien"=>"");

	}else
	////////////////////////////////////////////////////////////////////////////////SI L'ADRESSE MAIL EXISTE

	////////////////////////////////////////////////////////////////////////////////SI 3 TENTATIVES POUR DEFINIR UN MOT DE PASSE
	if(!empty($id_controle_compte_bloque)){

		$hack = 'oui';

		$result = array("Texte_rapport"=>"<div class='alert alert-warning' role='alert' style='text-align: left;' ><span class='uk-icon-warning'></span> Le compte à été bloqué, il y a eu 3 tentatives pour définir un nouveau mot de passe. 
		Et le compte à été bloqué, contactez un administrateur sur la page <a href='".$http."".$nomsiteweb."/Contact' target='blank_' style='text-decoration: underline;' >Contact</a></div>","retour_validation"=>"","retour_lien"=>"");

	}else
	////////////////////////////////////////////////////////////////////////////////SI 3 TENTATIVES POUR DEFINIR UN MOT DE PASSE

	////////////////////////////////////////////////////////////////////////////////SI 3 TENTATIVES POUR DEFINIR UN MOT DE PASSE
	if( 3 == $_SESSION['nbr_test_mot_de_passe']){

		$hack = 'oui';

		$result = array("Texte_rapport"=>"<div class='rapport_red' ><span class='uk-icon-warning' ></span> Le compte vient d'être bloqué, contactez un administrateur ! </div>","Texte_rapport_panier"=>"Le compte vient d'être bloqué, contactez un administrateur !","retour_validation"=>"","retour_lien"=>"");
		$mail_compte_concerne = $mail_password;
		$module_log = "NOUVEAU MOT DE PASSE";
		$action_sujet_log = "Compte bloqué - Notification de tentative pour définir un nouveau mot de passe";
		$action_libelle_log = "Une personne ou vous même avez tenté de définir 3 fois un nouveau mot de passe <b></b> sur $nomsiteweb. 3 demandes pour définir un nouveau mot de passe ont été réalisées, en conséquence le
		compte est bloqué. Pour débloquer le compte, veuillez prendre contact avec un administrateur. Si vous n'êtes pas à l'origine de cette action, veuillez sans attendre contacter un administrateur sur la page
		<a href='".$http."".$nomsiteweb."/Contact' target='blank_' style='text-decoration: underline;' >Contact</a>";
		$action_log = "DEMANDE";
		$niveau_log = "1";
		$compte_bloque = "oui";
		log_h($mail_compte_concerne,$module_log,$action_sujet_log,$action_libelle_log,$action_log,$niveau_log,$compte_bloque);

		$result = array("Texte_rapport"=>"<div class='alert alert-danger' role='alert' style='text-align: left;' ><span class='uk-icon-warning'></span> Le compte est bloqué, il y a eu 3 tentatives pour définir un nouveau mot de passe. Veuillez contacter 
		un administrateur sur la page <a href='".$http."".$nomsiteweb."/Contact' target='blank_' style='text-decoration: underline;' >Contact</a></div>","retour_validation"=>"","retour_lien"=>"");

	////////////////////////////////////////////////////////////////////////////////SI 3 TENTATIVES POUR DEFINIR UN MOT DE PASSE
	}

	////////////////////////////////////////////////////////////////////////////////ON EXECUTE L'ACTION SI AUCUNE ERREUR 
	if($hack != 'oui'){

		$idverif = create_password();
		$idverif = "".hash('sha256',$idverif)."".time()."";

		///////////////////////////////INSERT
		$sql_insert = $bdd->prepare("INSERT INTO membres_password_perdu
			(mail,
			pseudo_id,
			idverif,
			date)
			VALUES (?,?,?,?)");
		$sql_insert->execute(array(
			htmlspecialchars($mail),
			htmlspecialchars($ligne_select['id']),
			htmlspecialchars($idverif),
			$nowtime));                     
		$sql_insert->closeCursor();

       	$lien_recuperation = "<a href='".$http."".$nomsiteweb."/mot-de-passe-oublie/".$idverif."/".$mail."' style='text-decoration: underline;' >Récupérer le mot de passe</a>";

		//Envoi de l'e-mail test
		$de_nom = "$nomsiteweb";//Nom de l'envoyeur
		$de_mail = "$emaildefault"; //Email de l'envoyeur
		$vers_nom = "$prenom_password"; //Nom du receveur
		$vers_mail = "$mail"; //Email du receveur
		$sujet = "Redéfinition de votre mot de passe"; //Sujet du mail

    		$message_principalone = "<b>Objet :</b> $sujet<br /><br />
    		<b>Bonjour, </b><br /><br /> 
		Vou avez demandé une rédéfinition de votre mot de passe.
		Vous devez cliquer sur le lien suivant $lien_recuperation<br /> 
    		<br />
    		Cordialement,
    		<br />";
    		mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);

		/*
		$mail_compte_concerne = $mail;
		$module_log = "NOUVEAU MOT DE PASSE";
		$action_sujet_log = "Notification de votre compte ".$mail_compte_concerne." pour définir un mot de passe";
		$action_libelle_log = "Notification de votre compte <b>$mail_compte_concerne</b> sur $nomsiteweb. Un email de confirmation a été généré pour définir un nouveau mot de passe. 
		Si vous n'êtes pas à l'origine de cette action, veuillez sans attendre contacter un administrateur sur la page
		<a href='".$http."".$nomsiteweb."/Contact' target='blank_' style='text-decoration: underline;' >Contact</a>";
		$action_log = "CONFIRMATION";
		$niveau_log = "1";
		$compte_bloque = "";
		log_h($mail_compte_concerne,$module_log,$action_sujet_log,$action_libelle_log,$action_log,$niveau_log,$compte_bloque);
		*/

		$_SESSION['nbr_test_mot_de_passe'] = (1+$_SESSION['nbr_test_mot_de_passe']);
		$_SESSION['nbr_test_mot_de_passe_reste'] = (3-$_SESSION['nbr_test_mot_de_passe']);

		$result = array("Texte_rapport"=>"<div class='alert alert-success' role='alert' style='text-align: left;' ><span class='uk-icon-warning'></span> Un e-mail de confirmation vous a été envoyé sur <b>".$mail."</b>. Vous devrez cliquer sur un lien présent dans le mail, pour accéder à l'étape suivante.</div>","retour_validation"=>"ok","retour_lien"=>"");

	}
	////////////////////////////////////////////////////////////////////////////////ON EXECUTE L'ACTION SI AUCUNE ERREUR 

////////////////////////////////////////////////////////////////////////////////SI FORMULAIRE DEMANDE DE REDEFINITION

////////////////////////////////////////////////////////////////////////////////SI FORMULAIRE PAS D'ADRESSE MAIL RENSEIGNEE
}elseif (empty($_POST['mail_password_redefinition']) && empty($action_password) ){

	$result = array("Texte_rapport"=>"<div class='alert alert-danger' role='alert' style='text-align: left;' ><span class='uk-icon-warning'></span> Vous devez indiquer une adresse mail.</div>","retour_validation"=>"","retour_lien"=>"");

}
////////////////////////////////////////////////////////////////////////////////SI FORMULAIRE PAS D'ADRESSE MAIL RENSEIGNEE

$result = json_encode($result);
echo $result;

ob_end_flush();
?>