<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once ('../../../Configurations_bdd.php');
require_once ('../../../Configurations.php');
require_once ('../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../../";
require_once ('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)){

$_POST = json_decode(file_get_contents('php://input'), true);
$note = $_POST['rating'];
$content = $_POST['avis_content'];
$id_etablissement = $_POST['id_etablissement'];
$now = (new \DateTime())->format('d/m/Y H:i:s');

// AJOUT NOUVEL AVIS
$sql_avis = $bdd->prepare("INSERT INTO membres_etablissements_avis(id_membre,pseudo,note,commentaire,date_avis,id_etablissement) VALUES(?,?,?,?,?,?)");
$sql_avis->execute(array($id_user, $user, $note, $content, $now, $id_etablissement));                     
$sql_avis->closeCursor();

// RECUP AVIS POUR FAIRE MOYENNE
$sql_avis = $bdd->prepare("SELECT * FROM membres_etablissements_avis WHERE id_etablissement=?");
$sql_avis->execute(array($id_etablissement )); 
$avis = $sql_avis->fetchAll();                    
$sql_avis->closeCursor();

$totalAvis = 0;
$nombreAvis = 0;

foreach ($avis as $av) {
  $totalAvis = $totalAvis + $av['note'];
  $nombreAvis++;
}

$moyenne = round(($totalAvis / $nombreAvis), 0);

// MAJ MOYENNE AVIS ETABLISSEMENT
$sql_avis = $bdd->prepare("UPDATE membres_etablissements SET avis=? WHERE id=?");
$sql_avis->execute( [ $moyenne, $id_etablissement ]);                     
$sql_avis->closeCursor();

// RECUP AVIS POUR FAIRE MOYENNE
$sql_avise = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id=?");
$sql_avise->execute(array($id_etablissement)); 
$avise = $sql_avise->fetch();               
$sql_avise->closeCursor();

 /********************************************************************* MAIL ADMINISTRATEUR */
 $prenom_oo = $membreco['prenom'];
 $nom_oo = $membreco['nom'];
 $mail_oo = $membreco['mail'];
 $de_nom = "$prenom_oo $nom_oo"; //Nom de l'envoyeur
 $de_mail = "$mail_oo"; //Email de l'envoyeur
 $vers_nom = "$nomsiteweb"; //Nom du receveur
 $vers_mail = "$emaildefault"; //Email du receveur
 $sujet = "Un avis a été déposé sur $nomsiteweb"; //Sujet du mail
 $message_principalone = "<b>Objet :</b> $sujet<br /><br />
  <b>Bonjour,</b><br /><br />
  Vous avez une nouveau avis déposé.<br />
  Afin que l'avis soit visible par n'importe qui, il vous faut le valider. Connectez-vous à votre<br />
  espace administrateur, puis cliquez sur le lien ci-dessous : <br />
  <a href='".$http."".$nomsiteweb."/".$avise['nom_etablissement_url']."/".$avise['id']."' target='blank_' >Consulter l'avis </a><br /><br />
  PS: Ne pas répondre à l'e-mail.<br />
  Cordialement, la team PEP'S<br /><br />";
 mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone); 

$result = array("Texte_rapport"=>"Avis ajouté avec succès !","retour_validation"=>"ok","retour_lien"=>"");

$result = json_encode($result);
echo $result;

}else{
header('location: /index.html');
}

ob_end_flush();
?>