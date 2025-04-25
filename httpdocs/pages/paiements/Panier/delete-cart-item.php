<?php
header('Content-Type: application/json');
session_start();
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

$dir_fonction = "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

if (!empty($user)) {
    $productId = $_POST['productId'] ?? null;
    if (!$productId) {
        echo json_encode([
            "retour_validation" => "erreur",
            "Texte_rapport" => "L'ID du produit est manquant"
        ]);
        exit;
    }

    try {
        $sql = "DELETE FROM membres_panier_details WHERE id = ? AND id_membre = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$productId, $id_oo]);

        // rowCount() > 0 indique qu'au moins une ligne a été supprimée
        if ($stmt->rowCount() > 0) {
            echo json_encode([
                "retour_validation" => "ok",
                "Texte_rapport" => "Produit supprimé avec succès"
            ]);
        } else {
            // Le produit n'existe pas ou n'appartient pas à cet utilisateur
            echo json_encode([
                "retour_validation" => "erreur",
                "Texte_rapport" => "Produit non trouvé ou n'appartient pas à l'utilisateur"
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "retour_validation" => "erreur",
            "Texte_rapport" => "Erreur lors de la suppression du produit "
        ]);
    }
} else {
    header('location: /index.html');
}
ob_end_flush();
?>
