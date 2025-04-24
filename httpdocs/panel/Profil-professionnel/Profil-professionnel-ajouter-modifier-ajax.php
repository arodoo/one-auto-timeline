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
  
  // Initialize photo variables
  $photo1 = null;
  $photo2 = null;
  
  // Check if we already have photo records
  $req_check_photos = $bdd->prepare("SELECT photo1, photo2 FROM membres_profil_professionnel_imgs WHERE id_membre = ?");
  $req_check_photos->execute(array($id_oo));
  $existing_photos = $req_check_photos->fetch();

  // Process photo uploads if necessary
  $upload_dir = "../../images/profil-professionnel/$id_oo/";
  
  // Create directory if it doesn't exist
  if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
  }
  
  // Process photo1 if uploaded
  if (!empty($_FILES['photo1']['name'])) {
    $file_ext = pathinfo($_FILES['photo1']['name'], PATHINFO_EXTENSION);
    $photo1 = "photo1_" . time() . "." . $file_ext;
    $target_file = $upload_dir . $photo1;
    
    if (!move_uploaded_file($_FILES['photo1']['tmp_name'], $target_file)) {
      $result = array(
        "Texte_rapport" => "Erreur lors de l'upload de la photo 1",
        "retour_validation" => "erreur",
        "retour_lien" => "",
      );
      echo json_encode($result);
      exit;
    }
  } else if ($existing_photos && !empty($existing_photos['photo1'])) {
    // Keep existing photo if no new one uploaded
    $photo1 = $existing_photos['photo1'];
  }

  // Process photo2 if uploaded
  if (!empty($_FILES['photo2']['name'])) {
    $file_ext = pathinfo($_FILES['photo2']['name'], PATHINFO_EXTENSION);
    $photo2 = "photo2_" . time() . "." . $file_ext;
    $target_file = $upload_dir . $photo2;
    
    if (!move_uploaded_file($_FILES['photo2']['tmp_name'], $target_file)) {
      $result = array(
        "Texte_rapport" => "Erreur lors de l'upload de la photo 2",
        "retour_validation" => "erreur",
        "retour_lien" => "",
      );
      echo json_encode($result);
      exit;
    }
  } else if ($existing_photos && !empty($existing_photos['photo2'])) {
    // Keep existing photo if no new one uploaded
    $photo2 = $existing_photos['photo2'];
  }

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
      
      // Handle photos table update
      $req_check_photos_exist = $bdd->prepare("SELECT COUNT(*) FROM membres_profil_professionnel_imgs WHERE id_membre = ?");
      $req_check_photos_exist->execute(array($id_oo));
      $photos_exist = $req_check_photos_exist->fetchColumn();

      if ($photos_exist) {
        // Update existing photos
        $req_update_photos = $bdd->prepare("UPDATE membres_profil_professionnel_imgs SET 
          photo1 = ?, 
          photo2 = ?
          WHERE id_membre = ?");
        $req_update_photos->execute(array(
          $photo1,
          $photo2,
          $id_oo
        ));
      } else if ($photo1 || $photo2) {
        // Insert new photo records
        $req_insert_photos = $bdd->prepare("INSERT INTO membres_profil_professionnel_imgs (
          id_membre,
          photo1,
          photo2,
          date_ajout
          ) VALUES (?, ?, ?, NOW())");
        $req_insert_photos->execute(array(
          $id_oo,
          $photo1,
          $photo2
        ));
      }
      
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
      
      // Add photos if available
      if ($photo1 || $photo2) {
        $req_insert_photos = $bdd->prepare("INSERT INTO membres_profil_professionnel_imgs (
          id_membre,
          photo1,
          photo2,
          date_ajout
          ) VALUES (?, ?, ?, NOW())");
        $req_insert_photos->execute(array(
          $id_oo,
          $photo1,
          $photo2
        ));
      }
      
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