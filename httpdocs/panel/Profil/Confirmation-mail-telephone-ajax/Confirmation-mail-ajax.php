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

//ON CREER LE CODE
$code_securite_aleatoire = create_password();

///////////////////////////////UPDATE
$sql_update = $bdd->prepare("UPDATE membres_profil SET 
	Confirmer_mail_code_securite=?,
	Confirmer_mail=?
	WHERE pseudo=?");
$sql_update->execute(array(
	$code_securite_aleatoire,
	'',
	$user));                     
$sql_update->closeCursor();

$mode_inscription_nbractivation_texte = "Pour confirmer votre compte, merci de cliquer sur le lien suivant ou<br/> 
de le copier coller dans la barre de recherche de votre navigateur : <br/> <br/> 
<a href='".$http."".$nomsiteweb."/Confirmation-mail-".$code_securite_aleatoire.".html' target='blank_'>".$http."".$nomsiteweb."/Confirmation-mail-".$code_securite_aleatoire.".html</a>";

/////////////////////TRANSMISSION DU MAIL
    $de_nom = "$nomsiteweb"; //Nom de l'envoyeur
    $de_mail = "$emaildefault"; //Email de l'envoyeur
    $vers_nom = "$nom_oo $prenom_oo"; //Nom du receveur
    $vers_mail = "$mail_oo"; //Email du receveur
    $sujet = mail_bi($type_scan="sujet",$id_mail_requete=6);

    $message_principalone = mail_bi($type_scan="",$id_mail_requete=6);
    mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);
/////////////////////TRANSMISSION DU MAIL

$result2 = array("Texte_rapport"=>"Mail envoyé à :<br /><small>".$mail_oo."</small> !","retour_validation"=>"ok","retour_lien"=>"");

$result2 = json_encode($result2);
echo $result2;

}else{
header('location: /index.html');
}

ob_end_flush();
?>