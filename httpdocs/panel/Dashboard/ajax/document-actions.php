<?php
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

// Include function file
$dir_fonction = "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

// Check if user is logged in
if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    global $id_oo;
    
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    try {
        // Define table name to match original implementation
        $images_dossier = "membres_profil_auto_documents";
        $nom_table = "membres_profil_auto_documents";
        
        // Add document action
        if ($action === 'addDocument') {
            $documentType = isset($_POST['documentType']) ? $_POST['documentType'] : '';
            
            // Check if category is selected
            if (empty($documentType)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Vous devez choisir une catégorie.'
                ]);
                exit;
            }
            
            // Check if file was uploaded
            if (!isset($_FILES['documentFile']) || $_FILES['documentFile']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Erreur lors du téléchargement du fichier.'
                ]);
                exit;
            }
            
            // Check file type
            $fileType = mime_content_type($_FILES['documentFile']['tmp_name']);
            $allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];
            
            if (!in_array($fileType, $allowedTypes)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Seules les images sont acceptées (JPG, PNG, GIF).'
                ]);
                exit;
            }
            
            // Generate unique file name with timestamp
            $date_image = date('d-m-Y');
            $nom_image = $date_image . '_' . time() . '_' . basename($_FILES['documentFile']['name']);
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/images/membres/$user/";
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $uploadFile = $uploadDir . $nom_image;
            
            // Get image dimensions and determine orientation
            list($width, $height) = getimagesize($_FILES['documentFile']['tmp_name']);
            $type_orientation = ($width > $height) ? 'paysage' : 'portrait';
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['documentFile']['tmp_name'], $uploadFile)) {
                // Save document to database using original table structure
                $sql = "INSERT INTO $nom_table 
                        (id_membre, 
                        pseudo, 
                        id_categorie, 
                        nom, 
                        lien, 
                        date) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $bdd->prepare($sql);
                $stmt->execute([
                    $id_oo,
                    $user,
                    $documentType,
                    $nom_image,
                    "/images/membres/$user/$nom_image",
                    time()
                ]);
                
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Document ajouté avec succès.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => "Erreur lors de l'enregistrement du fichier."
                ]);
            }
        } 
        // Delete document action
        else if ($action === 'deleteDocument') {
            $documentId = isset($_POST['document_id']) ? (int)$_POST['document_id'] : 0;
            
            // Get document file path
            $sql = "SELECT lien FROM $nom_table WHERE id = :id AND id_membre = :id_membre";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([
                ':id' => $documentId,
                ':id_membre' => $id_oo
            ]);
            $document = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($document) {
                // Delete file from server
                $filePath = $_SERVER['DOCUMENT_ROOT'] . $document['lien'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                // Delete from database
                $sql = "DELETE FROM $nom_table WHERE id = :id AND id_membre = :id_membre";
                $stmt = $bdd->prepare($sql);
                $stmt->execute([
                    ':id' => $documentId,
                    ':id_membre' => $id_oo
                ]);
                
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Document supprimé avec succès.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Document non trouvé.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Action non reconnue.'
            ]);
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Une erreur est survenue: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Non autorisé'
    ]);
}
?>