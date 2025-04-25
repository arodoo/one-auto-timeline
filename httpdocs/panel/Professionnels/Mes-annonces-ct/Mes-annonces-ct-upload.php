<?php
ob_start();
require_once(__DIR__ . '/../../../Configurations_bdd.php');
require_once(__DIR__ . '/../../../Configurations.php');
require_once(__DIR__ . '/../../../Configurations_modules.php');
$dir_fonction = __DIR__ . "/../../../";
require_once(__DIR__ . '/../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/membres/' . $user . '/';
$response = array();

if (!is_dir($uploadDir)) {
mkdir($uploadDir, 0777, true);
}

foreach ($_FILES['photos']['name'] as $key => $fileName) {
$fileTmpName = $_FILES['photos']['tmp_name'][$key];
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$allowedExts = array('jpg', 'jpeg', 'png');
$slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', pathinfo($fileName, PATHINFO_FILENAME)));
$timestamp = time();
$newFileName = $slug . '-' . $timestamp . '.' . $fileExt;
$filePath = $uploadDir . $newFileName;
$photoNum = $_POST['photo_num'][$key];

// Vérifier l'extension du fichier
if (in_array($fileExt, $allowedExts)) {
// Vérifier si le fichier est une image
$check = getimagesize($fileTmpName);
$mime = mime_content_type($fileTmpName);
if ($check !== false && strpos($mime, 'image/') === 0) {
if (move_uploaded_file($fileTmpName, $filePath)) {
// Vérifier si une image avec le même numéro existe déjà
$id_membre = $id_oo;
$pseudo = $user;
$id_produit = $_SESSION['id_temporaire_image_annonce'];

$sql = "SELECT nom_image FROM membres_annonces_ct_images WHERE id_membre = ? AND  id_annonce_service = ? AND numero = ?";
$stmt = $bdd->prepare($sql);
$stmt->execute([$id_membre, $id_produit, $photoNum]);
$existingImage = $stmt->fetch();

if ($existingImage) {
// Supprimer l'ancienne image du serveur
$oldFilePath = $uploadDir . $existingImage['nom_image'];
if (file_exists($oldFilePath)) {
unlink($oldFilePath);
}

// Supprimer l'ancienne image de la base de données
$sql = "DELETE FROM membres_annonces_ct_images WHERE id_membre = ? AND id_annonce_service = ? AND numero = ?";
$stmt = $bdd->prepare($sql);
$stmt->execute([$id_membre, $id_produit, $photoNum]);
}

// Insérer les informations de la nouvelle image dans la base de données
$sql = "INSERT INTO membres_annonces_ct_images (id_membre, pseudo, id_annonce_service, nom_image, numero) VALUES (?, ?, ?, ?, ?)";
$stmt = $bdd->prepare($sql);
$stmt->execute([$id_membre, $pseudo, $id_produit, $newFileName, $photoNum]);

$response[] = array("Texte_rapport" => "Le fichier a été téléchargé avec succès.", "retour_validation" => "ok", "retour_lien" => "");
} else {
$response[] = array("Texte_rapport" => "Erreur lors du téléchargement du fichier.", "retour_validation" => "erreur");
}
} else {
$response[] = array("Texte_rapport" => "Le fichier n'est pas une image valide.", "retour_validation" => "erreur");
}
} else {
$response[] = array("Texte_rapport" => "Le fichier a une extension non autorisée.", "retour_validation" => "erreur");
}
}

echo json_encode($response);
} else {
header('location: /');
}

ob_end_flush();
?>