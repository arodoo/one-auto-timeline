<?php
ob_start();
// INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

header('Content-Type: application/json');

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    global $id_oo;
    
    $action = $_GET['action'] ?? null;
    
    if ($action === 'get') {
        $id_membre = $_GET['id_membre'] ?? $id_oo;
        
        try {
            $sql = "SELECT * FROM membres_info_personnelles WHERE id_membre = :id_membre LIMIT 1";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([':id_membre' => $id_membre]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($userData) {
                echo json_encode([
                    'status' => 200,
                    'message' => 'Informations personnelles récupérées avec succès',
                    'data' => $userData
                ]);
            } else {
                echo json_encode([
                    'status' => 404,
                    'message' => 'Aucune information personnelle trouvée'
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 500,
                'message' => 'Erreur lors de la récupération des informations: ' . $e->getMessage()
            ]);
        }
    } elseif ($action === 'save') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!empty($data)) {
            $id_membre = $data['id_membre'] ?? $id_oo;
            
            // Validate required fields
            $requiredFields = ['civilite', 'nom', 'prenom'];
            $missingFields = [];
            
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                echo json_encode([
                    'status' => 400,
                    'message' => 'Certains champs obligatoires sont manquants',
                    'missingFields' => $missingFields
                ]);
                exit;
            }
            
            try {
                // Check if record exists
                $check_sql = "SELECT COUNT(*) FROM membres_info_personnelles WHERE id_membre = :id_membre";
                $check_stmt = $bdd->prepare($check_sql);
                $check_stmt->execute([':id_membre' => $id_membre]);
                $recordExists = $check_stmt->fetchColumn() > 0;
                
                if ($recordExists) {
                    // Update
                    $sql = "UPDATE membres_info_personnelles SET 
                        civilite = :civilite, 
                        nom = :nom, 
                        prenom = :prenom, 
                        nom_usage = :nom_usage, 
                        complement_adresse = :complement_adresse, 
                        code_postal = :code_postal, 
                        ville = :ville, 
                        pays = :pays, 
                        telephone = :telephone
                        WHERE id_membre = :id_membre";
                } else {
                    // Insert
                    $sql = "INSERT INTO membres_info_personnelles (
                        id_membre, civilite, nom, prenom, nom_usage, 
                        complement_adresse, code_postal, ville, pays, telephone
                        ) VALUES (
                        :id_membre, :civilite, :nom, :prenom, :nom_usage, 
                        :complement_adresse, :code_postal, :ville, :pays, :telephone
                        )";
                }
                
                $stmt = $bdd->prepare($sql);
                $params = [
                    ':id_membre' => $id_membre,
                    ':civilite' => $data['civilite'] ?? '',
                    ':nom' => $data['nom'] ?? '',
                    ':prenom' => $data['prenom'] ?? '',
                    ':nom_usage' => $data['nom_usage'] ?? '',
                    ':complement_adresse' => $data['complement_adresse'] ?? '',
                    ':code_postal' => $data['code_postal'] ?? '',
                    ':ville' => $data['ville'] ?? '',
                    ':pays' => $data['pays'] ?? '',
                    ':telephone' => $data['telephone'] ?? ''
                ];
                
                $stmt->execute($params);
                
                echo json_encode([
                    'status' => 200,
                    'message' => $recordExists ? 'Informations mises à jour avec succès' : 'Informations enregistrées avec succès'
                ]);
            } catch (PDOException $e) {
                echo json_encode([
                    'status' => 500,
                    'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
                ]);
            }
        } else {
            echo json_encode([
                'status' => 400,
                'message' => 'Données manquantes'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 400,
            'message' => 'Action non reconnue'
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
