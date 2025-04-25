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

    $lien_devis = null;

    if (!empty($_FILES['lien_devis']['name'])) {
      $file_extension = strtolower(pathinfo($_FILES['lien_devis']['name'], PATHINFO_EXTENSION));
      $allowed_extensions = ['jpeg', 'jpg', 'pdf', 'png'];

      if (in_array($file_extension, $allowed_extensions)) {
        $lien_devis = hash('sha256', $_FILES['lien_devis']['name']) . '.' . $file_extension;
        $url_temp_3 = $_FILES['lien_devis']['tmp_name'];
        $url_target_3 = "../../images/membres/" . $user . "/" . $lien_devis;

        if (!move_uploaded_file($url_temp_3, $url_target_3)) {
          $result = array(
            "Texte_rapport" => "Une erreur s'est produite lors du téléchargement de votre fichier !",
            "retour_validation" => "",
            "retour_lien" => ""
          );
          echo json_encode($result);
          exit;
        }
      } else {
        $result = array(
          "Texte_rapport" => "Format de fichier non supporté !",
          "retour_validation" => "",
          "retour_lien" => ""
        );
        echo json_encode($result);
        exit;
      }
    }

    $sql_update = $bdd->prepare("
      UPDATE membres_devis SET 
        statut_devis = ?, 
        date_statut = ?" . ($lien_devis ? ", lien_devis = ?" : "") . " 
        WHERE id = ? 
        AND id_membre_depanneur = ?
    ");
    $params = [$statut_devis, time()];
    if ($lien_devis) {
      $params[] = "/images/membres/" . $user . "/" . $lien_devis;
    }
    $params[] = $idaction;
    $params[] = $id_oo;
    $sql_update->execute($params);
    $sql_update->closeCursor();

    ///////////////////////////////SELECT
    $req_select = $bdd->prepare("SELECT * FROM membres_devis WHERE id=? AND id_membre_depanneur=?");
    $req_select->execute(array($idaction, $id_oo));
    $ligne_select = $req_select->fetch();
    $req_select->closeCursor();
    $idoneinfos = $ligne_select['id'];

    ///////////////////////////////SELECT
    $req_select_utilisateur = $bdd->prepare("SELECT * FROM membres WHERE id=?");
    $req_select_utilisateur->execute(array($ligne_select['id_membre_utilisateur']));
    $ligne_select_utilisateur = $req_select_utilisateur->fetch();
    $req_select_utilisateur->closeCursor();

    $de_nom = "$nomsiteweb"; //Nom de l'envoyeur
    $de_mail = "$emaildefault"; //Email de l'envoyeur
    $vers_nom = "" . $ligne_select_utilisateur['prenom'] . " " . $ligne_select_utilisateur['nom'] . ""; //Nom du receveur
    $vers_mail = $ligne_select_utilisateur['mail']; //Email du receveur
    $sujet = "Nouveau devis sur $nomsiteweb";
    $message_principalone = "
      <b>Bonjour " . $ligne_select_utilisateur['prenom'] . ",</b><br /><br />
      Vous avez un devis envoyé par un dépanneur.<br />
      Pour le consulter, accédez à Mon espace auto en cliquant 
      <a href='" . $http . $nomsiteweb . "/images/membres/" . $user . "/" . $lien_devis . "' target='_blank'>ici</a>.<br /><br />
      Cordialement,";
    $message_principalone = trim($message_principalone);
      
    mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);

    $result = array("Texte_rapport" => "Devis mis à jour !", "retour_validation" => "ok", "retour_lien" => "");

  } else {
    $result = array(
      "Texte_rapport" => "Action non reconnue",
      "retour_validation" => "",
      "retour_lien" => ""
    );
  }

  ////////////////////////////MODIFIER

  $result = json_encode($result);
  echo $result;

} else {
  error_log("Accès refusé"); // Log de acceso denegado
  echo json_encode(array("Texte_rapport" => "Accès refusé", "retour_validation" => "error"));
}

ob_end_flush();
?>