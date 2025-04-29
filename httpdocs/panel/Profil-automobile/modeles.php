<?php
ob_start();
// INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    global $id_oo;
    
    // Get the brand from POST request
    $marque = isset($_POST['marque']) ? trim($_POST['marque']) : '';
    
    if (empty($marque)) {
        echo json_encode([
            'status' => 400,
            'message' => 'Marque manquante'
        ]);
        exit;
    }
    
    try {
        // Query to get models for the selected brand
        $sql = "SELECT DISTINCT modele FROM configurations_modeles WHERE marque = :marque ORDER BY modele ASC";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([':marque' => $marque]);
        
        $models = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $models[] = $row['modele'];
        }
        
        if ($models) {
            // Format the models as option tags for datalist
            $options = '';
            foreach ($models as $model) {
                $options .= '<option value="' . htmlspecialchars($model) . '">' . htmlspecialchars($model) . '</option>';
            }
            echo $options;
        } else {
            echo '';
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 500,
            'message' => 'Erreur lors de la récupération des modèles: ' . $e->getMessage()
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