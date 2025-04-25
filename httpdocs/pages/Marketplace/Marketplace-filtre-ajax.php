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
    
    $min_prix = isset($_POST['min_prix']) ? (int)$_POST['min_prix'] : 0;
    $max_prix = isset($_POST['max_prix']) ? (int)$_POST['max_prix'] : PHP_INT_MAX;
    $mots_cles = isset($_POST['mots_cles']) ? trim($_POST['mots_cles']) : '';

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $limit = 9;
    $offset = ($page - 1) * $limit;

    $query = "
        SELECT 
            mpi.id AS image_id,
            mpi.nom_image AS image_name,
            mp.id AS produit_id,
            mp.pseudo AS pseudo,
            mp.nom_produit AS produit_name,
            mp.description_produit AS produit_description,
            mp.montant_unite AS produit_price,
            mpi.id_membre AS membre_id
        FROM 
            membres_produits_images mpi
        INNER JOIN 
            membres_produits mp
        ON 
            mpi.id_produit = mp.id
        AND 
            mpi.id_membre = mp.id_membre
        WHERE 
            mp.montant_unite BETWEEN :min_prix AND :max_prix
        AND 
            (mp.nom_produit LIKE :mots_cles OR mp.description_produit LIKE :mots_cles)
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $bdd->prepare($query);
    $stmt->bindValue(':min_prix', $min_prix, PDO::PARAM_INT);
    $stmt->bindValue(':max_prix', $max_prix, PDO::PARAM_INT);
    $stmt->bindValue(':mots_cles', '%' . $mots_cles . '%', PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($produits) {
        $response["status"] = "success";
        $response["message"] = "Produits chargés avec succès.";
        $response["data"] = $produits; 
    } else {
        $response["status"] = "success";
        $response["message"] = "Aucun produit trouvé pour les critères donnés.";
    }
} catch (Exception $e) {
    $response["status"] = "error";
    $response["message"] = "Erreur";
}

echo json_encode($response);
