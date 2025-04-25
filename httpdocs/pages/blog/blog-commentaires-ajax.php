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

//REQUÊTE DE L'ARTICLE DU BLOG ASSOCIE
///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM codi_one_blog WHERE activer=? AND id=?");
$req_select->execute(array('oui',$_POST['idaction']));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idoneinfos_artciles_blog = $ligne_select['id'];
$id_categorie_artciles_blog = $ligne_select['id_categorie'];
$titre_blog_1_artciles_blog = $ligne_select['titre_blog_1'];
$titre_blog_2_artciles_blog = $ligne_select['titre_blog_2'];
$texte_article_blog = $ligne_select['texte_article'];
$url_fiche_blog_artciles_blog = $ligne_select['url_fiche_blog'];

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT COUNT(*) AS nbr_commentaire FROM codi_one_blog_commentaires WHERE id_article=?");
$req_select->execute(array($idoneinfos_artciles_blog));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$nbr_commentaire = $ligne_select['nbr_commentaire'];
//REQUÊTE DE L'ARTICLE DU BLOG ASSOCIE

///////////////////////////////////////////////AJOUTER UN COMMENTAIRE
if(!empty($idoneinfos_artciles_blog) && !empty($_POST['note_post']) && !empty($_POST['nom_post_commentaire']) && !empty($_POST['titre_post_commentaire']) && !empty($_POST['post_commentaire'])){

$now = time();

if(isset($user)){
$nom_post_commentaire = "$user";
}

///////////////////////////////INSERT
$sql_insert = $bdd->prepare("INSERT INTO codi_one_blog_commentaires
	(id_membre,
	pseudo,
	id_article,
	titre_commentaire,
	contenu_commentaire,
	note,
	activer,
	date_commentaire,
	plus,
	plus1)
	VALUES (?,?,?,?,?,?,?,?,?,?)");
$sql_insert->execute(array(
	$id_oo,
	$_POST['nom_post_commentaire'],
	$_POST['idaction'],
	$_POST['titre_post_commentaire'],
	$_POST['post_commentaire'],
	$_POST['note_post'],
	'oui',
	$now,
	'',
	''));                     
$sql_insert->closeCursor();

$de_nom = "$user"; //Nom de l'envoyeur
$de_mail = "$mail_oo"; //Email de l'envoyeur
$vers_nom = "$nomsiteweb"; //Nom du receveur
$vers_mail = "$emaildefault"; //Email du receveur
$sujet = "Nouveau commentaire dans le Blog sur $nomsiteweb"; //Sujet du mail

$message_principalone = "
Bonjour,<br /><br />
$nom_post_commentaire à Posté un nouveau commentaire sur le Blog concernant un article, ip de la personne ".$_SERVER['REMOTE_ADDR']." .<br /><br />
<b>Articles : $titre_blog_1_artciles_blog</b> <br />
Url de l'article <a href='".$http."".$nomsiteweb."/".$url_fiche_blog_artciles_blog."' target='blank_'> ".$http."".$nomsiteweb."/".$url_fiche_blog_artciles_blog." </a> <br /><br />
Note : $note_post <br />
Titre du commentaire : ".$_POST['titre_post_commentaire']." <br />
Commentaire : ".$_POST['post_commentaire']." <br />
<br />
Cordialement,";
mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);

$result = array("Texte_rapport"=>"Commentaire ajouté avec succès !","retour_validation"=>"ok","retour_lien"=>"");

}elseif(!empty($idoneinfos_artciles_blog) && empty($_POST['envoyer_commentaire_post']) || !empty($idoneinfos_artciles_blog) && empty($_POST['nom_post_commentaire']) || !empty($idoneinfos_artciles_blog) && empty($_POST['titre_post_commentaire']) || !empty($idoneinfos_artciles_blog) && empty($_POST['post_commentaire']) || !empty($idoneinfos_artciles_blog) && empty($_POST['note_post']) ){
$result = array("Texte_rapport"=>"Tous les champs doivent être remplis !","retour_validation"=>"","retour_lien"=>"");

///////////////////////////////////////////////AJOUTER UN COMMENTAIRE

}elseif(empty($idoneinfos_artciles_blog)) {
$result = array("Texte_rapport"=>"Une erreur c'est produite !","retour_validation"=>"","retour_lien"=>"");
header("HTTP/1.0 410 Gone");
}

$result = json_encode($result);
echo $result;

ob_end_flush();
?>