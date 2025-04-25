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
                $sql = "SELECT id, titre, description, date_publication, url_image 
                        FROM annonces 
                        WHERE statut = 'active' AND categorie IN ('mecanique', 'carrosserie')
                        ORDER BY date_publication DESC LIMIT 4";
                $stmt = $bdd->prepare($sql);
                $stmt->execute();
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($items as $item) {
                    $response['items'][] = [
                        'id' => $item['id'],
                        'title' => $item['titre'],
                        'description' => $item['description'],
                        'image' => $item['url_image'],
                        'date' => $item['date_publication'],
                        'url' => "/Annonce/" . $item['id']
                    ];
                }
                break;
                
            case 'services':
                $sql = "SELECT id, titre, description, date_publication, url_image 
                        FROM services_annonces 
                        WHERE statut = 'active'
                        ORDER BY date_publication DESC LIMIT 4";
                $stmt = $bdd->prepare($sql);
                $stmt->execute();
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($items as $item) {
                    $response['items'][] = [
                        'id' => $item['id'],
                        'title' => $item['titre'],
                        'description' => $item['description'],
                        'image' => $item['url_image'],
                        'date' => $item['date_publication'],
                        'url' => "/Service/" . $item['id']
                    ];
                }
                break;
                
            case 'control':
                $sql = "SELECT id, titre, description, date_publication, url_image 
                        FROM controle_technique_annonces 
                        WHERE statut = 'active'
                        ORDER BY date_publication DESC LIMIT 4";
                $stmt = $bdd->prepare($sql);
                $stmt->execute();
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($items as $item) {
                    $response['items'][] = [
                        'id' => $item['id'],
                        'title' => $item['titre'],
                        'description' => $item['description'],
                        'image' => $item['url_image'],
                        'date' => $item['date_publication'],
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