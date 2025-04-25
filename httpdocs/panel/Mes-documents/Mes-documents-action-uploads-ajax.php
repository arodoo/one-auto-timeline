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

$idaction = $_POST['idaction'];
$action = $_POST['action'];
$id_projet = $_POST['id_projet'];

define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)){

	$images_dossier = "membres_profil_auto_documents";
	$nom_table = "membres_profil_auto_documents";

	if (!empty($id_projet)) {

		$_SESSION['id_categorie_document'] = $id_projet;

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			if (isset($_FILES['file'])) {

				// Vérifiez les erreurs de téléchargement
				if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
					$result = array("Texte_rapport" => "Erreur de téléchargement : " . $_FILES['file']['error'], "retour_validation" => "", "retour_lien" => "");
					echo json_encode($result);
					exit;
				}

				$file = $_FILES['file'];
				$uploadDir = ROOT_PATH . "/images/membres/$user/";
				$uploadFile = $uploadDir . basename($file['name']);

				// Vérification du type de fichier
				$fileType = mime_content_type($file['tmp_name']);
				$allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];

				if (in_array($fileType, $allowedTypes)) {

					$nom_image_calque = basename($file['name']);

					$date_image = date('d-m-Y');
					$nom_image_calque = $date_image . '_'.time().'_' . basename($file['name']);
					$uploadFile = $uploadDir . $nom_image_calque;

					$image_calque = basename($file['name']);
					$type_image = mime_content_type($file['tmp_name']);

					// Obtenir les dimensions de l'image
					list($width, $height) = getimagesize($file['tmp_name']);

					// Déterminer l'orientation
					if ($width > $height) {
						$type_orientation = 'paysage';
					} else {
						$type_orientation = 'portrait';
					}

					if (move_uploaded_file($file['tmp_name'], $uploadFile)) {

						try {
							$sql_update = $bdd->prepare("INSERT INTO $nom_table
							(id_membre,
							pseudo,
							id_categorie, 
							nom, 
							lien,
							date)
							VALUES (?,?,?,?,?,?)");
						$sql_update->execute(array(
							$id_oo,
							$user,
							$id_projet,
							$nom_image_calque,
							"/images/membres/$user/$nom_image_calque",
							time()
							));
						$sql_update->closeCursor();
						$lastInsertId = $bdd->lastInsertId();
						} catch (PDOException $e) {
							echo "Erreur : " . $e->getMessage();
							exit;
						}

						$result = array("Texte_rapport" => "Téléchargement effectué avec succès !", "retour_validation" => "ok", "retour_lien" => "");

					} else {
						$result = array("Texte_rapport" => "Erreur lors du téléchargement.", "retour_validation" => "", "retour_lien" => "");
					}

				} else {
					$result = array("Texte_rapport" => "Type de fichier non autorisé.", "retour_validation" => "", "retour_lien" => "");
				}

			} else {
				$result = array("Texte_rapport" => "Aucun fichier reçu.", "retour_validation" => "", "retour_lien" => "");
			}

		} else {
			$result = array("Texte_rapport" => "Méthode non autorisée.", "retour_validation" => "", "retour_lien" => "");
		}

	} else {
		$result = array("Texte_rapport" => "Vous devez choisir un projet.", "retour_validation" => "", "retour_lien" => "");
	}

$result = json_encode($result);
echo $result;

} else {
header('location: /');
}

ob_end_flush();
?>