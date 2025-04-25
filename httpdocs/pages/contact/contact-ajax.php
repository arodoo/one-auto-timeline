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

$now = time();

////////////////////ON INTERDIT LES ENVOIS MULTIPLES INFERIEURS A L'INTERVALE AUTORISE
/////NOMBRE EN MINUTES ENTRE CHAQUE ENVOI D'E-MAIL
$restime = "120";

$timeRESTRICTION = $_SESSION['timeetime'];
$teeeeeeeeeeeeee = ($now-$timeRESTRICTION);

//////////rapport du temps restant avant la prochaine demande
if(($now-$timeRESTRICTION) < $restime || ($now-$timeRESTRICTION) == $restime){

//time actuelle - le time en session
$calculrseTIME =($now-$timeRESTRICTION);
$calculrseTIME = ceil($calculrseTIME/60);
$calculrseTIMEDEUX = ceil($restime/60-$calculrseTIME);

//SINGULIER OU PLURIEL?
if($calculrseTIMEDEUX > 1){
	$calculrseTIMEDEUX = "$calculrseTIMEDEUX minutes";
}elseif($calculrseTIMEDEUX == 0){
	$calculrseTIMEDEUX = "quelques secondes";
}else{
	$calculrseTIMEDEUX = "$calculrseTIMEDEUX minute";
}

}

//Contrôle de la syntaxe associée à l'e-mail
if(!empty($_POST['mailpost']) && preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/", $_POST['mailpost'])){
	$array = explode('@', $_POST['mailpost']);
	$ap = $array[1];
	$domain = checkdnsrr($ap);
}

if(!empty($_POST['pseudomail']) && $domain == true && !empty($_POST['objetpost']) && !empty($_POST['messagepost'])){

	$now = time();

	$objetpost=$_POST['objetpost'];
	$Namepost=$_POST['Namepost'];
	$messagepost=$_POST['messagepost'];
	$mailpost=$_POST['mailpost'];

	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT * FROM membres_type_de_compte WHERE id=?");
	$req_select->execute(array($statut_compte_oo));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
	$id_statut_compte_membre = $ligne_select['Nom_type'];

	if(!empty($id_statut_compte_membre)){
		$id_statut_compte_membre_texte = "<b>Type de compte : $id_statut_compte_membre</b> <br />";
	}

	//////////////////////////////////////////////////////////////////////MAIL ADMINISTRATEUR DU SITE INTERNET
	//Envoi de l'email test
	$de_nom = "Contact de ".htmlspecialchars($_POST['Namepost']).""; //Nom de l'envoyeur
	$de_mail = htmlspecialchars($_POST['mailpost']); //Email de l'envoyeur
	$vers_nom = "$nomsiteweb"; //Nom du receveur
	$vers_mail = "$emaildefault"; //Email du receveur
	$sujet = "Page contact : ".htmlspecialchars($objetpost)." sur $nomsiteweb"; //Sujet du mail

	$message_principalone = "
		<b>Nom et mail : ".htmlspecialchars($_POST['Namepost'])." ".htmlspecialchars($_POST['mailpost'])."</b> <br />
		$id_statut_compte_membre_texte
		<br />
		".htmlspecialchars($messagepost)."<br /><br />
		Cordialement, l'équipe<br /><br />";

	mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);
	//////////////////////////////////////////////////////////////////////MAIL ADMINISTRATEUR DU SITE INTERNET

$result = array("Texte_rapport"=>"Message envoyé avec succès ! ","retour_validation"=>"ok","retour_lien"=>"");

}elseif( empty($_POST['Namepost']) || empty($_POST['mailpost']) || empty($_POST['objetpost']) || empty($_POST['messagepost'])){
	$result = array("Texte_rapport"=>"Tous les champs doivent être remplis !","retour_validation"=>"","retour_lien"=>"");

}elseif(($now-$timeRESTRICTION) < $restime){
	$result = array("Texte_rapport"=>"Vous pourrez effectuer une autre demande dans quelques instants !","retour_validation"=>"","retour_lien"=>$lasturl);

}elseif($domain == false){
	$result = array("Texte_rapport"=>"Votre email n'est pas correct !","retour_validation"=>"","retour_lien"=>"");

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Envoyer le mail

$result = json_encode($result);
echo $result;

ob_end_flush();
?>