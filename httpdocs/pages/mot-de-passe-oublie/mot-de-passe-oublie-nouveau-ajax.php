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

$nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];
$nouveau_mot_de_passe_controle = $_POST['nouveau_mot_de_passe_controle'];

if(!empty($_SESSION['definition_mot_de_passe_autorise_id']) && !empty($_SESSION['definition_mot_de_passe_autorise']) && !empty($_SESSION['definition_mot_de_passe_autorise_idverif']) && !empty($_SESSION['definition_mot_de_passe_autorise_mail']) && $_SESSION['definition_mot_de_passe_autorise'] == "oui"){

$dateverif = (time()-7200);

////////////////////////////////////PASSWORD 

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM membres_password_perdu 
	WHERE mail=? 
	and idverif=? 
	and date >?");
$req_select->execute(array(
	htmlspecialchars($_SESSION['definition_mot_de_passe_autorise_mail']), 
	htmlspecialchars($_SESSION['definition_mot_de_passe_autorise_idverif']),
	$dateverif));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$id_controle_modification_mail = $ligne_select['id'];

if(empty($id_controle_modification_mail)){
	$erreur_nouveau_passe = "oui";
	$erreur_password = "<span>La demande de redéfinition de mot de passe est introuvable.</span>";
	$result = array("Texte_rapport"=>"<div class='alert alert-danger' ><span class='uk-icon-warning' ></span> $erreur_password </div>","retour_validation"=>"","retour_lien"=>"");
	$result2 = array("Texte_rapport"=>"$erreur_password","retour_validation"=>"","retour_lien"=>"");

}elseif($_POST['nouveau_mot_de_passe'] != $_POST['nouveau_mot_de_passe_controle'] && !empty($_POST['nouveau_mot_de_passe_controle']) && !empty($_POST['nouveau_mot_de_passe']) ){
	$erreur_nouveau_passe = "oui";
	$erreur_password = "<span>Les <b>mots de passe</b> ne correspondent pas !</span>";
	$result = array("Texte_rapport"=>"<div class='alert alert-danger' ><span class='uk-icon-warning' ></span> $erreur_password </div>","retour_validation"=>"","retour_lien"=>"");
	$result2 = array("Texte_rapport"=>"$erreur_password","retour_validation"=>"","retour_lien"=>"");

}elseif(strlen($nouveau_mot_de_passe) < 8 ){
	$erreur_nouveau_passe = "oui";
	$erreur_password = "<span>Le mot de passe doit être constitué de <b>8 caractères minimum</b> !</span>";
	$erreur_password2 = "<span>Mot de passe à <b>8 caractères minimum</b> !</span>";
	$result = array("Texte_rapport"=>"<div class='alert alert-danger' ><span class='uk-icon-warning' ></span> $erreur_password </div>","retour_validation"=>"","retour_lien"=>"");
	$result2 = array("Texte_rapport"=>"$erreur_password2","retour_validation"=>"","retour_lien"=>"");

}elseif((!preg_match("#[a-z]#", $nouveau_mot_de_passe) || !preg_match("#[0-9]#", $nouveau_mot_de_passe)) ){
	$erreur_nouveau_passe = "oui";
	$erreur_password = "<span>Le mot de passe doit être constitué de <b>lettre et de chiffre</b> !</span>";
	$result = array("Texte_rapport"=>"<div class='alert alert-danger' ><span class='uk-icon-warning' ></span> $erreur_password </div>","retour_validation"=>"","retour_lien"=>"");
	$result2 = array("Texte_rapport"=>"$erreur_password","retour_validation"=>"","retour_lien"=>"");

}elseif(ctype_upper($nouveau_mot_de_passe) == true ){
	$erreur_nouveau_passe = "oui";
	$erreur_password = "<span>Le mot de passe doit être constitué d'<b>une minuscule au minimum</b> !</span>";
	$result = array("Texte_rapport"=>"<div class='alert alert-danger' ><span class='uk-icon-warning' ></span> $erreur_password </div>","retour_validation"=>"","retour_lien"=>"");
	$result2 = array("Texte_rapport"=>"$erreur_password","retour_validation"=>"","retour_lien"=>"");

}elseif(ctype_upper($nouveau_mot_de_passe) ){
	$erreur_nouveau_passe = "oui";
	$erreur_password = "<span>Le mot de passe doit être constitué d'<b>une majuscule au minimum</b> !</span>";
	$result = array("Texte_rapport"=>"<div class='alert alert-danger' ><span class='uk-icon-warning' ></span> $erreur_password </div>","retour_validation"=>"","retour_lien"=>"");
	$result2 = array("Texte_rapport"=>"$erreur_password","retour_validation"=>"","retour_lien"=>"");

}elseif(empty($_POST['nouveau_mot_de_passe'])){
	$erreur_nouveau_passe = "oui";
	$erreur_password = "<span>Vous devez indiquer <b>un mot de passe</b> !</span>";
	$result = array("Texte_rapport"=>"<div class='alert alert-danger' ><span class='uk-icon-warning' ></span> $erreur_password </div>","retour_validation"=>"","retour_lien"=>"");
	$result2 = array("Texte_rapport"=>"$erreur_password","retour_validation"=>"","retour_lien"=>"");

}elseif(empty($_POST['nouveau_mot_de_passe_controle']) && !empty($_POST['nouveau_mot_de_passe'])){
	$erreur_nouveau_passe = "oui";
	$erreur_password = "<span>Vous devez <b>confirmer le mot de passe</b> !</span>";
	$result = array("Texte_rapport"=>"<div class='alert alert-danger' ><span class='uk-icon-warning' ></span> $erreur_password </div>","retour_validation"=>"","retour_lien"=>"");
	$result2 = array("Texte_rapport"=>"$erreur_password","retour_validation"=>"","retour_lien"=>"");

}


///////////////////////////SI MODIFICATION ACCEPTEE
if(empty($erreur_nouveau_passe)){
/*
	$mail_compte_concerne = $_SESSION['definition_mot_de_passe_autorise_mail'];
	$module_log = "NOUVEAU MOT DE PASSE";
	$action_sujet_log = "Notification pour le changement de mot de passe associé à votre compte ".$_SESSION['definition_mot_de_passe_autorise_mail']." ";
	$action_libelle_log = "Notification associée au changement d'un nouveau mot de passe sur votre compte ".$_SESSION['definition_mot_de_passe_autorise_mail'].". Si vous n'êtes pas à l'origine de cette action, veuillez sans attendre contacter un administrateur sur la page
	<a href='".$http."".$nomsiteweb."/Contact' target='blank_' style='text-decoration: underline;' >Contact</a>";
	$action_log = "CONFIRMATION";
	$niveau_log = "1";
	$compte_bloque = "";
	log_h($mail_compte_concerne,$module_log,$action_sujet_log,$action_libelle_log,$action_log,$niveau_log,$compte_bloque);
*/
	///////////////////////////////DELETE
	$sql_delete = $bdd->prepare("DELETE FROM membres_password_perdu WHERE mail=?");
	$sql_delete->execute(array(htmlspecialchars($_SESSION['definition_mot_de_passe_autorise_mail'])));                     
	$sql_delete->closeCursor();

	///////////////////////////////UPDATE
	$sql_update = $bdd->prepare("UPDATE membres SET 
		pass=? 
		WHERE mail=?");
	$sql_update->execute(array(
		hash("sha256",htmlspecialchars($_POST['nouveau_mot_de_passe_controle'])),
		htmlspecialchars($_SESSION['definition_mot_de_passe_autorise_mail'])));                     
	$sql_update->closeCursor();

	$result = array("Texte_rapport"=>"<div class='alert alert-success' ><span class='uk-icon-warning' ></span> Votre nouveau mot de passe à été mis à jour. Vous pouvez maintenant vous connecter sur votre compte. </div>","retour_validation"=>"ok","retour_lien"=>"");

	unset($_SESSION['definition_mot_de_passe_autorise_id']);
	unset($_SESSION['definition_mot_de_passe_autorise']);
	unset($_SESSION['definition_mot_de_passe_autorise_idverif']);
	unset($_SESSION['definition_mot_de_passe_autorise_mail']);

}
///////////////////////////SI MODIFICATION ACCEPTEE

////////////////////////////////////PASSWORD

}elseif(!empty($_POST['nouveau_mot_de_passe'])){

	$result = array("Texte_rapport"=>"<div class='alert alert-danger' ><span class='uk-icon-warning' ></span> Votre nouveau mot de passe à déjà été modifié.</div>","retour_validation"=>"","retour_lien"=>"");
}

$result = json_encode($result);
echo $result;

ob_end_flush();
?>