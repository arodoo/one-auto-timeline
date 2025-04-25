<?php
ob_start();
header('Content-Type: application/json'); 

// INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

// INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    $produit_id = $_POST['produit_id']; 
    $lien_produit = $_POST['lien_produit'] ?? ''; // Récupère le lien du produit
    $response = [];

    try {
        $sql_check = $bdd->prepare("SELECT COUNT(*) FROM membres_produits_favoris WHERE id_membre = ? AND id_produit = ?");
        $sql_check->execute([$id_oo, $produit_id]);
        $is_already_favorite = $sql_check->fetchColumn() > 0;

        if ($is_already_favorite) {
            // Supprimer des favoris
            $sql_delete = $bdd->prepare("DELETE FROM membres_produits_favoris WHERE id_membre = ? AND id_produit = ?");
            $sql_delete->execute([$id_oo, $produit_id]);
            $response = [
                "Texte_rapport" => "Produit retiré de vos favoris.",
                "retour_validation" => "ok",
                "retour_action" => "removed",
                "color" => "red"
            ];
        } else {
            // Ajouter aux favoris avec le lien du produit
            $timestamp = time();
            $sql_insert = $bdd->prepare("INSERT INTO membres_produits_favoris (id_membre, pseudo, id_produit, lien_produit, date) VALUES (?, ?, ?, ?, ?)");
            $sql_insert->execute([$id_oo, $user, $produit_id, $lien_produit, $timestamp]);
            $response = [
                "Texte_rapport" => "Produit ajouté à vos favoris !",
                "retour_validation" => "ok",
                "retour_action" => "added",
                "color" => "green"
            ];
        }
    } catch (Exception $e) {
        $response = [
            "Texte_rapport" => "Erreur inattendue: " . $e->getMessage(),
            "retour_validation" => "erreur"
        ];
    }
} else {
    $response = [
        "Texte_rapport" => "Utilisateur non authentifié.",
        "retour_validation" => "erreur"
    ];
}

echo json_encode($response);
ob_end_flush();
