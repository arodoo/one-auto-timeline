<?php
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

// Include function file
$dir_fonction = "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

// Check if user is logged in
if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    // Get carousel type
    $type = isset($_POST['type']) ? $_POST['type'] : '';

    try {
        // Initialize response
        $response = [
            'status' => 'success',
            'items' => []
        ];

        // Determine which table to query based on type
        switch ($type) {
            case 'mechanics':
                // Use a more inclusive query for mechanics announcements
                $sql = "SELECT ma.id, ma.nom, ma.description, ma.date, ma.id_membre 
                        FROM membres_annonces ma
                        WHERE ma.statut = 'activé' 
                        ORDER BY ma.date DESC LIMIT 4";
                $stmt = $bdd->prepare($sql);
                $stmt->execute();
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($items as $item) {
                    // Get the user's pseudo (required for correct image path)
                    $userSql = "SELECT pseudo FROM membres WHERE id = ?";
                    $userStmt = $bdd->prepare($userSql);
                    $userStmt->execute([$item['id_membre']]);
                    $userPseudo = $userStmt->fetchColumn();

                    // Get image from related images table
                    $imgSql = "SELECT nom_image FROM membres_annonces_images WHERE id_annonce_service = ? AND id_membre = ? LIMIT 1";
                    $imgStmt = $bdd->prepare($imgSql);
                    $imgStmt->execute([$item['id'], $item['id_membre']]);
                    $image = $imgStmt->fetchColumn();

                    // Use a default image if none found - UPDATED FALLBACK PATH
                    $imagePath = '/images/no-img.png';
                    if ($image && !empty($image) && $userPseudo) {
                        // Construct path with user's pseudo instead of ID
                        $imagePath = '/images/membres/' . $userPseudo . '/' . $image;
                    }

                    $response['items'][] = [
                        'id' => $item['id'],
                        'title' => $item['nom'],
                        'description' => $item['description'],
                        'image' => $imagePath,
                        'date' => $item['date'],
                        'url' => "/Page-annonce?id=" . $item['id']
                    ];
                }
                break;

            case 'services':
                $sql = "SELECT id, nom, description, date, id_membre 
                        FROM membres_services 
                        WHERE statut = 'activé'
                        ORDER BY date DESC LIMIT 4";
                $stmt = $bdd->prepare($sql);
                $stmt->execute();
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($items as $item) {
                    // Get the user's pseudo (required for correct image path)
                    $userSql = "SELECT pseudo FROM membres WHERE id = ?";
                    $userStmt = $bdd->prepare($userSql);
                    $userStmt->execute([$item['id_membre']]);
                    $userPseudo = $userStmt->fetchColumn();

                    // Get image from related images table
                    $imgSql = "SELECT nom_image FROM membres_services_images WHERE id_annonce_service = ? AND id_membre = ? LIMIT 1";
                    $imgStmt = $bdd->prepare($imgSql);
                    $imgStmt->execute([$item['id'], $item['id_membre']]);
                    $image = $imgStmt->fetchColumn();

                    // Use a default image if none found - UPDATED FALLBACK PATH
                    $imagePath = '/images/no-img.png';
                    if ($image && !empty($image) && $userPseudo) {
                        // Construct path with user's pseudo instead of ID
                        $imagePath = '/images/membres/' . $userPseudo . '/' . $image;
                    }

                    $response['items'][] = [
                        'id' => $item['id'],
                        'title' => $item['nom'],
                        'description' => $item['description'],
                        'image' => $imagePath,
                        'date' => $item['date'],
                        'url' => "/Page-service?id=" . $item['id']
                    ];
                }
                break;

            case 'control':
                $sql = "SELECT id, nom, description, date, id_membre 
                        FROM membres_annonces_ct 
                        WHERE statut = 'activé'
                        ORDER BY date DESC LIMIT 4";
                $stmt = $bdd->prepare($sql);
                $stmt->execute();
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($items as $item) {
                    // Get the user's pseudo (required for correct image path)
                    $userSql = "SELECT pseudo FROM membres WHERE id = ?";
                    $userStmt = $bdd->prepare($userSql);
                    $userStmt->execute([$item['id_membre']]);
                    $userPseudo = $userStmt->fetchColumn();

                    // Get image from related images table
                    $imgSql = "SELECT nom_image FROM membres_annonces_ct_images WHERE id_annonce_service = ? AND id_membre = ? LIMIT 1";
                    $imgStmt = $bdd->prepare($imgSql);
                    $imgStmt->execute([$item['id'], $item['id_membre']]);
                    $image = $imgStmt->fetchColumn();

                    // Use a default image if none found - UPDATED FALLBACK PATH
                    $imagePath = '/images/no-img.png';
                    if ($image && !empty($image) && $userPseudo) {
                        // Construct path with user's pseudo instead of ID
                        $imagePath = '/images/membres/' . $userPseudo . '/' . $image;
                    }

                    $response['items'][] = [
                        'id' => $item['id'],
                        'title' => $item['nom'],
                        'description' => $item['description'],
                        'image' => $imagePath,
                        'date' => $item['date'],
                        'url' => "/Page-controle?id=" . $item['id']
                    ];
                }
                break;

            default:
                $response['status'] = 'error';
                $response['message'] = 'Type invalide';
                break;
        }

        // Return response
        header('Content-Type: application/json');
        echo json_encode($response);

    } catch (Exception $e) {
        // Return error
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Une erreur est survenue: ' . $e->getMessage()
        ]);
    }

} else {
    // Return unauthorized error
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Non autorisé'
    ]);
}
?>