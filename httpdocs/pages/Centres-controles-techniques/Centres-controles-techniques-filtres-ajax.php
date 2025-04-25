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
    $mots_cles = isset($_POST['mots_cles_marketplace']) ? trim($_POST['mots_cles_marketplace']) : '';
    $min_prix = isset($_POST['min_prix']) ? (float)$_POST['min_prix'] : 0;
    $max_prix = isset($_POST['max_prix']) ? (float)$_POST['max_prix'] : 0;
    $id_departement = isset($_POST['id_departement_annonce']) ? $_POST['id_departement_annonce'] : '';

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $limit = 9;
    $offset = ($page - 1) * $limit;

    $query = "
        SELECT 
            i.id AS image_id,
            i.nom_image AS image_name,
            a.id AS annonce_id,
            a.nom AS annonce_nom,
            a.description AS annonce_description,
            a.prix AS annonce_prix,
            a.pseudo,
            COALESCE(AVG(n.note), 0) AS avg_note, 
            COALESCE(COUNT(n.id), 0) AS total_reviews
        FROM 
            membres_annonces_ct_images i
        INNER JOIN 
            membres_annonces_ct a ON i.id_annonce_service = a.id 
        LEFT JOIN 
            membres_avis n ON a.id = n.id_page
        WHERE 
            a.statut = 'activé'
    ";

    $params = [];

    if (!empty($mots_cles)) {
        $query .= " AND (a.nom LIKE :mots_cles OR a.description LIKE :mots_cles)";
        $params[':mots_cles'] = '%' . $mots_cles . '%';
    }

    if ($min_prix > 0) {
        $query .= " AND a.prix >= :min_prix";
        $params[':min_prix'] = $min_prix;
    }

    if ($max_prix > 0) {
        $query .= " AND a.prix <= :max_prix";
        $params[':max_prix'] = $max_prix;
    }

    if ($id_departement !== '') {
        $query .= " AND a.id_departement = :id_departement";
        $params[':id_departement'] = $id_departement;
    }

    $query .= " GROUP BY a.id ORDER BY a.id DESC LIMIT :limit OFFSET :offset";

    $stmt = $bdd->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($annonces) {
        $response["status"] = "success";
        $response["message"] = "Annonces chargées avec succès.";
        $response["data"] = $annonces;
    } else {
        $response["status"] = "success";
        $response["message"] = "Aucune annonce trouvée.";
    }

    // Debugging information
    $response["debug"] = [
        "query" => $query,
        "params" => $params
    ];
} catch (Exception $e) {
    $response["status"] = "error";
    $response["message"] = "Erreur: " . $e->getMessage();
    $response["debug"] = [
        "query" => $query,
        "params" => $params
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT);
