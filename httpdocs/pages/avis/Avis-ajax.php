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

$now = time();


$ipdulivredor = $_SERVER['REMOTE_ADDR'];

///////////////Si il y  a un nouveau signataire
if($_POST['action'] == "send" && !isset($_SESSION['Deja_envoye']) && !empty($_POST['commentaire_post']) && !isset($_SESSION['controlevalidation']) && $_POST['pseudomail1'] == "exemple@domaine.com" && !empty($_POST['pseudomail1']) && empty($_POST['eelogin']) && isset($_POST['eelogin']) && $_POST['eeemail'] == "" && !empty($_POST['note_post']) && !empty($_POST['titrepostlivredor']) && !empty($_POST['contenulivredor'])){

$_SESSION['Deja_envoye'] = "Déja envoyé";

///////////////////////////////INSERT
$sql_insert = $bdd->prepare("INSERT INTO avis
	(Titre,
	Contenu,
	date,
	Auteur,
	ip_post,
	Plus,
	plus1)
	VALUES (?,?,?,?,?,?,?)");
$sql_insert->execute(array(
	htmlspecialchars($_POST['titrepostlivredor']),
	htmlspecialchars($_POST['contenulivredor']),
	$now,
	$user,
	$ipdulivredor,
	htmlspecialchars($_POST['note_post']),
	''));                     
$sql_insert->closeCursor();

//////////////////////////////////////////////////////////////////////A inclure sur la page FONCTION MAIL

if(!empty($user)){
///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM membres WHERE  pseudo=?");
$req_select->execute(array($user));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$mailmmmty = $ligne_select['mail'];
}else{
$mailmmmty = "$emaildefault";
}

$de_nom = "$nom_proprietaire"; //Nom de l'envoyeur
$de_mail = "$mailmmmty"; //Email de l'envoyeur
$vers_nom = "$nomsiteweb"; //Nom du receveur
$vers_mail = "$emaildefault"; //Email du receveur
$sujet = "Un avis à été déposé sur $nomsiteweb"; //Sujet du mail

$message_principalone = "<b>Objet :</b> $sujet<br /><br />
<b>Bonjour,</b><br /><br />
Vous avez une nouveau avis déposé.<br />
Afin que l'avis soit visible par n'importe qui, il vous faut le valider. Connectez-vous à votre<br />
espace administrateur, puis cliquez sur le lien ci-dessous : <br />
<a href='".$http."".$nomsiteweb."/Avis' target='blank_' >Valider l'avis ici </a><br /><br />
PS: Ne pas répondre à l'e-mail.<br />
Cordialement, l'équipe<br /><br />";
mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);
//////////////////////////////////////////////////////////////////////A inclure sur la page FONCTION MAIL

$result = array("Texte_rapport"=>"Avis déposé avec succès !","retour_validation"=>"ok","retour_lien"=>$lasturl);

}elseif(!empty($_POST['commentaire_post']) && isset($_SESSION['Deja_envoye'])){
$result = array("Texte_rapport"=>"Avis déjà envoyé !","retour_validation"=>"","retour_lien"=>$lasturl);

}elseif(!empty($_POST['commentaire_post']) && isset($_SESSION['controlevalidation']) || $_POST['action'] == "send" && $_POST['pseudomail1'] != "exemple@domaine.com" || $_POST['action'] == "send" && empty($_POST['pseudomail1']) || $_POST['action'] == "send" && !empty($_POST['eelogin']) || $_POST['action'] == "send" && !isset($_POST['eelogin']) || $_POST['action'] == "send" && $_POST['eeemail'] != ""){
$_SESSION['controlevalidation'] = "NA";
$result = array("Texte_rapport"=>"Une erreur c'est produite !","retour_validation"=>"","retour_lien"=>$lasturl);

}elseif(!empty($_POST['commentaire_post']) && empty($_POST['titrepostlivredor']) || $_POST['action'] == "send" && empty($_POST['note_post']) || $_POST['action'] == "send" && empty($_POST['contenulivredor'])){
$result = array("Texte_rapport"=>"Tous les champs doivent être remplis !","retour_validation"=>"","retour_lien"=>$lasturl);
}
///////////////Si il y  a un nouveau signataire

$result = json_encode($result);
echo $result;

ob_end_flush();
?>
