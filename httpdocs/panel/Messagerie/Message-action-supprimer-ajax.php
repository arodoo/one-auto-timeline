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

if (isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && isset($user)) {

  ///////ON SUPPRIME REPONSE OU MESSAGE
  if (!empty($_POST['table'])) {
    ///////////////////////////////DELETE
    $sql_delete = $bdd->prepare("DELETE FROM " . $_POST['table'] . " WHERE id=?");
    $sql_delete->execute(array($_POST['idaction']));
    $sql_delete->closeCursor();
    $result = array("Texte_rapport" => "Message supprimé avec succès !", "retour_validation" => "ok", "retour_lien" => $_POST['retour_lien']);
  }
  ///////ON SUPPRIME REPONSE OU MESSAGE

  ///////ON SUPPRIME MESSAGE
  if ($_POST['table'] == "membres_messages") {
    $sql_delete = $bdd->prepare("DELETE FROM membres_messages WHERE id=?");
    $sql_delete->execute(array($_POST['idaction']));
    $sql_delete->closeCursor();

    ///////SI  LITIGE ON SUPPRIME IDMESSAGE
///////////////////////////////SELECT
    $req_select = $bdd->prepare("SELECT * FROM membres_messages_reponse WHERE id_message=?");
    $req_select->execute(array(htmlspecialchars($_POST['idaction'])));
    $ligne_select = $req_select->fetch();
    $req_select->closeCursor();
    $id_litige = $ligne_select['id'];
    $id_message = $ligne_select['id_message'];

    if (!empty($id_litige)) {
      ///////////////////////////////UPDATE
      $sql_update = $bdd->prepare("UPDATE membres_prestataire_litige SET 
	id_message=? 
	WHERE id=?");
      $sql_update->execute(array(
        "",
        htmlspecialchars($id_litige)
      ));
      $sql_update->closeCursor();
      $result = array("Texte_rapport" => "Tous les messages sont supprimés !", "retour_validation" => "ok", "retour_lien" => $_POST['retour_lien']);
    }
    ///////SI  LITIGE ON SUPPRIME IDMESSAGE

  }
  ///////ON SUPPRIME MESSAGE

  $result = json_encode($result);
  echo $result;

} else {
  header('location: /index.html');
}

ob_end_flush();
?>