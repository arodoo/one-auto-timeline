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

  define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

  ///////////////////////////////SELECT
  try {
    $req_select = $bdd->prepare("SELECT * FROM membres_profil_auto_documents WHERE id=? AND id_membre=?");
    $req_select->execute(array($idaction, $id_oo));
    $ligne_select = $req_select->fetch();
    $req_select->closeCursor();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
  }
  $nom = $ligne_select['nom'];


  if (!empty($id_projet) && !empty($id_client)) {
    $image_fond_a_supprimer = ROOT_PATH . "/images/$user/" . $nom;
    ///////////////////////////////DELETE FILE
    if (file_exists($image_fond_a_supprimer)) {
      unlink($image_fond_a_supprimer);
    }
  }

  ///////////////////////////////DELETE
  $sql_delete = $bdd->prepare("DELETE FROM membres_profil_auto_documents WHERE id=?");
  $sql_delete->execute(array($idaction));
  $sql_delete->closeCursor();

  $result = array("Texte_rapport" => "Fichier supprimé avec succès !", "retour_validation" => "ok", "retour_lien" => "");

  $result = json_encode($result);
  echo $result;

} else {
  header('location: /');
}

ob_end_flush();
?>