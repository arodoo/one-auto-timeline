<?php
ob_start();
header('Content-Type: application/json');

require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');


$response = [
    "status" => "error",
    "message" => "Une erreur inattendue s'est produite.",
    "data" => []
];




try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_annonce = isset($_POST['id_annonce']) ? intval($_POST['id_annonce']) : 0;
        $valoracion = isset($_POST['valoracion']) ? intval($_POST['valoracion']) : 0;
        $review_text = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';
        $id_auteur = $id_oo;
        $time = time();

        $req_select_annonce = $bdd->prepare("SELECT * FROM membres_annonces_ct WHERE id=?");
        $req_select_annonce->execute(array($id_annonce));
        $ligne_select_annonce = $req_select_annonce->fetch();
        $req_select_annonce->closeCursor();

        if ($id_annonce > 0) {

            $stmt_check = $bdd->prepare("SELECT * FROM membres_avis WHERE id_page = ? AND id_auteur = ?");
            $stmt_check->execute([$id_annonce, $id_auteur]);
            $existing_review = $stmt_check->fetch();

            if ($existing_review) {
                // S'il existe déjà, effectuez une mise à jour
                $stmt = $bdd->prepare("UPDATE membres_avis SET note = ?, commentaire = ?, updated_at = ? WHERE id_page = ? AND id_auteur = ?");
                $success = $stmt->execute([$valoracion, $review_text, $time, $id_annonce, $id_auteur]);
            } else {
                // S'il n'existe pas, effectuez une insertion
                $stmt = $bdd->prepare("INSERT INTO membres_avis (id_page, note, commentaire, id_auteur, type, id_membre, pseudo, date, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $success = $stmt->execute([
                    $id_annonce,
                    $valoracion,
                    $review_text,
                    $id_auteur,
                    "annonce",
                    $ligne_select_annonce['id_membre'],
                    $ligne_select_annonce['pseudo'],
                    $time,
                    $time
                ]);
            }

            if ($success) {
                $response['status'] = "success";
                $response['message'] = "Valorisation envoyée avec succès.";
            } else {
                $response['status'] = "error";
                $response['message'] = "Erreur lors de l'envoi de la valorisation.";
            }
        } else {
            $response['status'] = "error";
            $response['message'] = "Données invalides.";
        }
    } else {
        $response['status'] = "error";
        $response['message'] = "Méthode non autorisée.";
    }
} catch (Exception $e) {

    $response["status"] = "error";
    $response["message"] = "Erreur: ";
}


echo json_encode($response, JSON_PRETTY_PRINT);
