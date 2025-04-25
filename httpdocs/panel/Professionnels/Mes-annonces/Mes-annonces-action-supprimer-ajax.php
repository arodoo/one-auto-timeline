<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

try {
    if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
        $action = $_POST['action'];
        $idaction = $_POST['idaction'];

      /*   // Verificar si las variables necesarias están definidas
        if (!isset($id_oo)) {
            throw new Exception("Variable 'id_oo' no definida.");
        }

        if (empty($idaction)) {
            throw new Exception("ID de la acción no proporcionado.");
        } */

        ///////////////////////////////DELETE
        // Supprimer les images liées au produit
        $sql_select_images = $bdd->prepare("SELECT nom_image FROM membres_annonces_images WHERE id_annonce_service = ? AND id_membre=?");
        $sql_select_images->execute(array($idaction,$id_oo));
        $images = $sql_select_images->fetchAll(PDO::FETCH_ASSOC);
        $sql_select_images->closeCursor();

        foreach ($images as $image) {
            $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/images/membres/' . $user . '/' . $image['nom_image'];
            if (file_exists($imagePath)) {
                unlink($imagePath); // Supprimer l'image du serveur
            }
        }

        // Supprimer les entrées des images de la base de données
        $sql_delete_images = $bdd->prepare("DELETE FROM membres_annonces_images WHERE id_annonce_service = ?  AND id_membre=?");
        $sql_delete_images->execute(array($idaction,$id_oo));
        $sql_delete_images->closeCursor();

        // Supprimer le produit
        $sql_delete = $bdd->prepare("DELETE FROM membres_annonces WHERE id = ? AND id_membre = ?");
        $sql_delete->execute(array($idaction, $id_oo));
        $sql_delete->closeCursor();

        $result = array("Texte_rapport" => "Annonce supprimée.", "retour_validation" => "ok", "retour_lien" => "");

        echo json_encode($result);
    } else {
        header('location: /');
    }
} catch (Exception $e) {
    error_log("Erreur " );
    $result = array("Texte_rapport" => "Erreur ", "retour_validation" => "error");
    echo json_encode($result);
}

ob_end_flush();
?>