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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)){

$action = $_POST['action'];
$idaction = $_POST['idaction'];


$path = "../../images/constats/$idaction";
if (!is_dir($path)) {
  mkdir($path, 0777, true);
}

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
  $imageTmpName = $_FILES['image']['tmp_name'];
  $imageName = basename($_FILES['image']['name']);
  $imagePath = $path . '/' . $imageName;

  // Move the uploaded file to the target directory
  if (move_uploaded_file($imageTmpName, $imagePath)) {
    // Insert the image information into the database
    $stmt = $bdd->prepare("INSERT INTO constats_images (id_membre, pseudo, id_constat, img) VALUES (:user_id, :pseudo, :action_id, :image_path)");
    $stmt->bindParam(':user_id', $id_oo);
    $stmt->bindParam(':pseudo', $user);
    $stmt->bindParam(':action_id', $idaction);
    $stmt->bindParam(':image_path', $imageName);
 

    if ($stmt->execute()) {
      $result = array("Texte_rapport" => "Image ajoutée!", "retour_validation" => "ok", "retour_lien" => "");
    } else {
      $result = array("Texte_rapport" => "Erreur lors de l'enregistrement dans la base de données.", "retour_validation" => "error", "retour_lien" => "");
    }
  } else {
    $result = array("Texte_rapport" => "Erreur lors du téléchargement de l'image.", "retour_validation" => "error", "retour_lien" => "");
  }
} else {
  $result = array("Texte_rapport" => "Aucune image reçue ou erreur lors de l'envoi.", "retour_validation" => "error", "retour_lien" => "");
}

////////////////////////////AJOUTER

$result = json_encode($result);
echo $result;

}else{
header('location: /');
}

ob_end_flush();
?>
