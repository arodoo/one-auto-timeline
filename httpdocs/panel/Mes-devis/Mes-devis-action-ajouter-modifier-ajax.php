<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../";
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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {

  $action = $_POST['action'];
  $idaction = $_POST['idaction'];
  $statut_devis = $_POST['statut_devis'];

  ////////////////////////////MODIFIER
  if ($action == "modifier-action") {

    ///////////////////////////////UPDATE
    $sql_update = $bdd->prepare("UPDATE membres_devis SET 
statut_devis = ?, 
date_statut = ? 
WHERE id = ? 
AND id_membre_utilisateur = ?");
    $sql_update->execute(array(
      $statut_devis,
      time(),
      $idaction,
      $id_oo
    ));
    $sql_update->closeCursor();

    ///////////////////////////////SELECT
    $req_select = $bdd->prepare("SELECT * FROM membres_devis WHERE id=? AND id_membre_utilisateur=?");
    $req_select->execute(array($idaction, $id_oo));
    $ligne_select = $req_select->fetch();
    $req_select->closeCursor();
    $idoneinfos = $ligne_select['id'];

    ///////////////////////////////SELECT
    $req_select_depanneur = $bdd->prepare("SELECT * FROM membres WHERE id=?");
    $req_select_depanneur->execute(array($ligne_select['id_membre_depanneur']));
    $ligne_select_depanneur = $req_select_depanneur->fetch();
    $req_select_depanneur->closeCursor();

    ///////////////////////////////SELECT
    $req_select_utilisateur = $bdd->prepare("SELECT * FROM membres WHERE id=?");
    $req_select_utilisateur->execute(array($ligne_select['id_membre_utilisateur']));
    $ligne_select_utilisateur = $req_select_utilisateur->fetch();
    $req_select_utilisateur->closeCursor();

    if ($statut_devis == "Accepté") {

      $de_nom = "$nomsiteweb"; //Nom de l'envoyeur
      $de_mail = "$emaildefault"; //Email de l'envoyeur
      $vers_nom = "" . $ligne_select_depanneur['prenom'] . " " . $ligne_select_depanneur['nom'] . ""; //Nom du receveur
      $vers_mail = $ligne_select_depanneur['mail']; //Email du receveur
      $sujet = "Devis accepté sur $nomsiteweb";
      $message_principalone = "<b>Bonjour " . $ligne_select_depanneur['prenom'] . ", </b><br /><br />  
Vous avez un devis accepté par " . $ligne_select_utilisateur['prenom'] . " " . $ligne_select_utilisateur['nom'] . ". <br /> 	
Pour le consulter, accédez à Mon espace auto en cliquant <a href='" . $http . $nomsiteweb . "/images/membres/" . $ligne_select_depanneur['pseudo'] . "/" . $ligne_select['lien_devis'] . "' target='blank_' >ici</a>.
<br />
Cordialement,
<br />";
      mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);

      $result = array("Texte_rapport" => "Devis accepté !", "retour_validation" => "ok", "retour_lien" => "");
    } else {

      $de_nom = "$nomsiteweb"; //Nom de l'envoyeur
      $de_mail = "$emaildefault"; //Email de l'envoyeur
      $vers_nom = "" . $ligne_select_depanneur['prenom'] . " " . $ligne_select_depanneur['nom'] . ""; //Nom du receveur
      $vers_mail = $ligne_select_depanneur['mail']; //Email du receveur
      $sujet = "Devis refusé sur $nomsiteweb";
      $message_principalone = "<b>Bonjour " . $ligne_select_depanneur['prenom'] . ", </b><br /><br />  
Vous avez un devis refusé par " . $ligne_select_utilisateur['prenom'] . " " . $ligne_select_utilisateur['nom'] . ". <br /> 	
Pour le consulter, accédez à Mon espace auto en cliquant <a href='" . $http . $nomsiteweb . "/images/membres/" . $ligne_select_depanneur['pseudo'] . "/" . $ligne_select['lien_devis'] . "' target='blank_' >ici</a>.
<br />
Cordialement,
<br />";
      mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);

      $result = array("Texte_rapport" => "Devis refusé !", "retour_validation" => "ok", "retour_lien" => "");
    }
  }
  ////////////////////////////MODIFIER

  $result = json_encode($result);
  echo $result;
} else {
  header('location: /');
}

ob_end_flush();
