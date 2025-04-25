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
        
        // Determine which table to query based on type - UPDATED with correct table names and column names
        switch ($type) {
            case 'mechanics':
                $sql = "SELECT id, nom, description, date, id_membre 
                        FROM membres_annonces 
                        WHERE statut = 'activé' AND id_categorie IN (SELECT id FROM configurations_categories WHERE nom_categorie IN ('mecanique', 'carrosserie'))
                        ORDER BY date DESC LIMIT 4";
                $stmt = $bdd->prepare($sql);
                $stmt->execute();
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($items as $item) {
                    // Get image from related images table
                    $imgSql = "SELECT nom_image FROM membres_annonces_images WHERE id_annonce_service = ? AND id_membre = ? LIMIT 1";
                    $imgStmt = $bdd->prepare($imgSql);
                    $imgStmt->execute([$item['id'], $item['id_membre']]);
                    $image = $imgStmt->fetchColumn();
                    
                    $response['items'][] = [
                        'id' => $item['id'],
                        'title' => $item['nom'],
                        'description' => $item['description'],
                        'image' => $image ? "/images/membres/" . $user . "/" . $image : "",
                        'date' => $item['date'],
                        'url' => "/Annonce/" . $item['id']
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
                    // Get image from related images table
                    $imgSql = "SELECT nom_image FROM membres_services_images WHERE id_annonce_service = ? AND id_membre = ? LIMIT 1";
                    $imgStmt = $bdd->prepare($imgSql);
                    $imgStmt->execute([$item['id'], $item['id_membre']]);
                    $image = $imgStmt->fetchColumn();
                    
                    $response['items'][] = [
                        'id' => $item['id'],
                        'title' => $item['nom'],
                        'description' => $item['description'],
                        'image' => $image ? "/images/membres/" . $user . "/" . $image : "",
                        'date' => $item['date'],
                        'url' => "/Service/" . $item['id']
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
                    // Get image from related images table
                    $imgSql = "SELECT nom_image FROM membres_annonces_ct_images WHERE id_annonce_service = ? AND id_membre = ? LIMIT 1";
                    $imgStmt = $bdd->prepare($imgSql);
                    $imgStmt->execute([$item['id'], $item['id_membre']]);
                    $image = $imgStmt->fetchColumn();
                    
                    $response['items'][] = [
                        'id' => $item['id'],
                        'title' => $item['nom'],
                        'description' => $item['description'],
                        'image' => $image ? "/images/membres/" . $user . "/" . $image : "",
                        'date' => $item['date'],
                        'url' => "/Centre-controle-technique/" . $item['id']
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