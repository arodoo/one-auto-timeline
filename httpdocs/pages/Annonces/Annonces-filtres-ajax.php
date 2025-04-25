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
    $mots_cles = isset($_POST['mot_cle_annonce']) ? trim($_POST['mot_cle_annonce']) : '';
    $id_categorie = isset($_POST['id_catgeorie_annonce']) ? $_POST['id_catgeorie_annonce'] : '';
    $id_departement = isset($_POST['id_departement_annonce']) ? $_POST['id_departement_annonce'] : '';

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $limit = 9;
    $offset = max(0, ($page - 1) * $limit);


    $query = "
            SELECT 
                i.id AS image_id,
                i.nom_image AS image_name,
                a.id AS annonce_id,
                a.title AS annonce_title,
                a.description AS annonce_description,
                a.specialite AS annonce_specialite,
                a.pseudo,
                AVG(n.note) AS avg_note
            FROM 
                membres_annonces_images i
            INNER JOIN 
                membres_annonces a
                ON i.id_annonce_service = a.id 
            INNER JOIN 
                membres m
                ON a.id_membre = m.id
            LEFT JOIN 
                membres_avis n
                ON a.id = n.id_page
            WHERE 
                a.statut = 'activé' 
                AND m.abonnement = 'oui'
	
    ";

    $params = [];

    if (!empty($mots_cles)) {
        $query .= " AND (a.title LIKE :mots_cles OR a.description LIKE :mots_cles)";
        $params[':mots_cles'] = '%' . $mots_cles . '%';
    }

    if ($id_categorie !== '') {
        $query .= " AND a.id_categorie = :id_categorie";
        $params[':id_categorie'] = $id_categorie;
    }

    if ($id_departement !== '') {
        $query .= " AND a.id_departement = :id_departement";
        $params[':id_departement'] = $id_departement;
    }

    $query .= " GROUP BY a.id ORDER BY RAND() LIMIT :limit OFFSET :offset";

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
        $response["message"] = count($annonces) > 0 ? "Services chargés avec succès." : "Aucun service trouvé.";
        $response["data"] = $annonces;
    } else {
        $response["status"] = "success";
        $response["message"] = "Aucune annonce trouvée.";
    }
} catch (Exception $e) {
    $response["status"] = "error";
    $response["message"] = "Erreur: " . $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);
