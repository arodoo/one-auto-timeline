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
            $sql = "SELECT * FROM membres_profil_auto WHERE id = :id AND id_membre = :id_membre";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([
                ':id' => $vehicle_id,
                ':id_membre' => $id_oo
            ]);
            
            $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($vehicle) {
                echo json_encode([
                    'status' => 200,
                    'data' => $vehicle
                ]);
            } else {
                echo json_encode([
                    'status' => 404,
                    'message' => 'Véhicule introuvable ou vous n\'avez pas accès à ce véhicule'
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 500,
                'message' => 'Erreur lors de la récupération des informations du véhicule: ' . $e->getMessage()
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