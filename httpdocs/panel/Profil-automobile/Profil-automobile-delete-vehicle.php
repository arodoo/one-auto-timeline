<?php
ob_start();
// INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

// Set header for JSON response
header('Content-Type: application/json');

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    global $id_oo;
    
    if (isset($_POST['vehicle_id']) && is_numeric($_POST['vehicle_id'])) {
        $vehicle_id = intval($_POST['vehicle_id']);
        
        try {
            // Security check: ensure the vehicle belongs to the logged in user
            $check_sql = "SELECT COUNT(*) FROM membres_profil_auto WHERE id = :id AND id_membre = :id_membre";
            $check_stmt = $bdd->prepare($check_sql);
            $check_stmt->execute([
                ':id' => $vehicle_id,
                ':id_membre' => $id_oo
            ]);
            
            $vehicle_belongs_to_user = $check_stmt->fetchColumn() > 0;
            
            if (!$vehicle_belongs_to_user) {
                echo json_encode([
                    'status' => 403,
                    'message' => 'Accès non autorisé à ce véhicule'
                ]);
                exit;
            }
            
            // Delete the vehicle
            $delete_sql = "DELETE FROM membres_profil_auto WHERE id = :id AND id_membre = :id_membre";
            $delete_stmt = $bdd->prepare($delete_sql);
            $delete_stmt->execute([
                ':id' => $vehicle_id,
                ':id_membre' => $id_oo
            ]);
            
            if ($delete_stmt->rowCount() > 0) {
                // After deleting, check if the user has any vehicles left
                $check_vehicles_sql = "SELECT COUNT(*) FROM membres_profil_auto WHERE id_membre = :id_membre";
                $check_vehicles_stmt = $bdd->prepare($check_vehicles_sql);
                $check_vehicles_stmt->execute([':id_membre' => $id_oo]);
                $remaining_vehicles = $check_vehicles_stmt->fetchColumn();
                
                // If no vehicles remain, set the vehicles_missing flag
                if ($remaining_vehicles == 0) {
                    $_SESSION['vehicles_missing'] = true;
                }
                
                echo json_encode([
                    'status' => 200,
                    'message' => 'Le véhicule a été supprimé avec succès'
                ]);
            } else {
                echo json_encode([
                    'status' => 404,
                    'message' => 'Véhicule introuvable ou déjà supprimé'
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 500,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'status' => 400,
            'message' => 'ID de véhicule manquant ou invalide'
        ]);
    }
} else {
    echo json_encode([
        'status' => 401,
        'message' => 'Non autorisé'
    ]);
}

ob_end_flush();
?>