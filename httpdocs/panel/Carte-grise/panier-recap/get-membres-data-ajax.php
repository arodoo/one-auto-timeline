<?php
ob_start();
// INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

$dir_fonction = "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

header('Content-Type: application/json');

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    global $id_oo;
    
    try {
        $sql = "SELECT civilites, nom, prenom, adresse, cp, ville, Pays, Telephone, Telephone_portable 
                FROM membres WHERE id = :id_membre LIMIT 1";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([':id_membre' => $id_oo]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($userData) {
            // Map database fields to our form fields with better fallbacks
            $mappedData = [
                'civilite' => $userData['civilites'] ?? '',
                'nom' => $userData['nom'] ?? '',
                'prenom' => $userData['prenom'] ?? '',
                'nom_usage' => '', // Empty as requested
                'complement_adresse' => $userData['adresse'] ?? '', // Use adresse instead of Pays for complement_adresse
                'code_postal' => $userData['cp'] ?? '',
                'ville' => $userData['ville'] ?? '',
                'pays' => $userData['Pays'] ?? 'France métropolitaine', // Default to France if empty
                'telephone' => !empty($userData['Telephone_portable']) ? 
                               $userData['Telephone_portable'] : ($userData['Telephone'] ?? '')
            ];
            
            echo json_encode([
                'status' => 200,
                'message' => 'Informations personnelles récupérées avec succès',
                'data' => $mappedData
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
} else {
    echo json_encode([
        'status' => 401,
        'message' => 'Non autorisé'
    ]);
}
ob_end_flush();
?>