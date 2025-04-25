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
    $mots_cles = isset($_POST['mot_cle_service']) ? trim($_POST['mot_cle_service']) : '';
    $id_categorie = isset($_POST['id_catgeorie_service']) ? $_POST['id_catgeorie_service'] : '';
    $id_departement = isset($_POST['id_departement_service']) ? $_POST['id_departement_service'] : '';

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $limit = 9;
    $offset = ($page - 1) * $limit;

    $query = "
        SELECT 
            i.id AS image_id,
            i.nom_image AS image_name,
            s.id AS service_id,
            s.title AS service_title,
            s.description AS service_description,
            s.specialite AS service_specialite,
            s.pseudo
        FROM 
            membres_services_images i
        INNER JOIN 
            membres_services s
        ON 
            i.id_annonce_service = s.id
        WHERE 
            s.statut = 'activé'
    ";

    $params = [];

    if (!empty($mots_cles)) {
        $query .= " AND (s.title LIKE :mots_cles OR s.description LIKE :mots_cles)";
        $params[':mots_cles'] = '%' . $mots_cles . '%';
    }

    if ($id_categorie !== '') {
        $query .= " AND s.id_categorie = :id_categorie";
        $params[':id_categorie'] = $id_categorie;
    }

    if ($id_departement !== '') {
        $query .= " AND s.id_departement = :id_departement";
        $params[':id_departement'] = $id_departement;
    }

    $query .= " GROUP BY s.id ORDER BY RAND() LIMIT :limit OFFSET :offset";

    $stmt = $bdd->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($services) {
        $response["status"] = "success";
        $response["message"] = count($services) > 0 ? "Services chargés avec succès." : "Aucun service trouvé.";
        $response["data"] = $services;
    } else {
        $response["status"] = "success";
        $response["message"] = "Aucun service trouvé pour les critères donnés.";
    }

   /*  // Debugging information
    $response["debug"] = [
        "query" => $query,
        "params" => $params
    ]; */
} catch (Exception $e) {
    $response["status"] = "error";
    $response["message"] = "Erreur: ";
}

// Enviar respuesta en formato JSON
echo json_encode($response, JSON_PRETTY_PRINT);
