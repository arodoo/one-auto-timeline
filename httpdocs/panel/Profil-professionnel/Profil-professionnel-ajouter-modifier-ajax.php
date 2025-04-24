<?php
ob_start();
header('Content-Type: application/json');
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

  $titre_profil = $prenom_oo;
  $description = $_POST['description'];
  $title = $_POST['titre_profil'];
  $activer = "oui";
  $meta_description = "NA";
  $meta_keyword = "NA";

  // Function to generate a slug from a string
  function generateSlug($string)
  {
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
    return trim($slug, '-');
  }

  // Generate the URL profile slug
  $url_profil = "/Fiche/" . generateSlug($titre_profil) . "/$id_oo";

  // Function to create meta description and keywords
  function createMetaDescription($text, $length = 160)
  {
    if (strlen($text) <= $length) {
      return $text;
    }
    $last_space = strrpos(substr($text, 0, $length), ' ');
    return substr($text, 0, $last_space) . '...';
  }

  // Create meta description and keywords
  $meta_description = createMetaDescription($description);
  $meta_keyword = createMetaDescription($description);


  // Vérifier que tous les champs sont remplis
  if (empty($titre_profil) || empty($description)) {
    $result = array(
      "Texte_rapport" => "Tous les champs sont obligatoires!",
      "retour_validation" => "erreur",
      "retour_lien" => "",
    );
  } else {
    // Vérifier si le profil existe
    $req_check = $bdd->prepare("SELECT COUNT(*) FROM membres_profils WHERE id_membre = ?");
    $req_check->execute(array($id_oo));
    $profil_existe = $req_check->fetchColumn();

    if ($profil_existe) {
      // Mettre à jour le profil existant
      $req_update = $bdd->prepare("UPDATE membres_profils SET 
        titre_profil = ?, 
        url_profil = ?, 
        description = ?, 
        title = ?, 
        meta_description = ?, 
        meta_keyword = ?, 
        activer = ? 
        WHERE id_membre = ?");
      $req_update->execute(array(
        $titre_profil,
        $url_profil,
        $description,
        $title,
        $meta_description,
        $meta_keyword,
        $activer,
        $id_oo
      ));
      $result = array("Texte_rapport" => "Profil modifié !", "retour_validation" => "ok", "retour_lien" => "");
    } else {
      // Insérer un nouveau profil
      $req_insert = $bdd->prepare("INSERT INTO membres_profils (
        id_membre, 
        pseudo, 
        titre_profil, 
        description, 
        title, 
        meta_description, 
        meta_keyword, 
        activer, 
        url_profil 
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $req_insert->execute(array(
        $id_oo,
        $pseudo_oo,
        $titre_profil,
        $description,
        $title,
        $meta_description,
        $meta_keyword,
        $activer,
        $url_profil
      ));
      $result = array("Texte_rapport" => "Profil créé !", "retour_validation" => "ok", "retour_lien" => "");
    }
  }
  ////////////////////////////MODIFIER

  echo json_encode($result);

} else {
  header('location: /');
}

ob_end_flush();
?>