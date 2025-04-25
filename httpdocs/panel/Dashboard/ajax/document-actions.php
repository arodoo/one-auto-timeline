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
        // Add document action
        if ($action === 'addDocument') {
            $documentType = isset($_POST['documentType']) ? $_POST['documentType'] : '';
            $documentName = isset($_POST['documentName']) ? $_POST['documentName'] : '';
            
            // Check if file was uploaded
            if (!isset($_FILES['documentFile']) || $_FILES['documentFile']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Erreur lors du téléchargement du fichier.'
                ]);
                exit;
            }
            
            // Check file type
            $allowedTypes = ['application/pdf'];
            if (!in_array($_FILES['documentFile']['type'], $allowedTypes)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Seuls les fichiers PDF sont acceptés.'
                ]);
                exit;
            }
            
            // Generate unique file name
            $fileName = uniqid('doc_') . '.pdf';
            $uploadPath = '../../../documents/membres/' . $id_oo . '/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Move uploaded file
            if (!move_uploaded_file($_FILES['documentFile']['tmp_name'], $uploadPath . $fileName)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => "Erreur lors de l'enregistrement du fichier."
                ]);
                exit;
            }
            
            // Save document to database
            $sql = "INSERT INTO membres_documents (id_membre, type, nom, fichier, date_ajout) 
                    VALUES (:id_membre, :type, :nom, :fichier, NOW())";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([
                ':id_membre' => $id_oo,
                ':type' => $documentType,
                ':nom' => $documentName,
                ':fichier' => 'membres/' . $id_oo . '/' . $fileName
            ]);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Document ajouté avec succès.'
            ]);
            
        } 
        // Delete document action
        else if ($action === 'deleteDocument') {
            $documentId = isset($_POST['document_id']) ? (int)$_POST['document_id'] : 0;
            
            // Get document file path
            $sql = "SELECT fichier FROM membres_documents WHERE id = :id AND id_membre = :id_membre";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([
                ':id' => $documentId,
                ':id_membre' => $id_oo
            ]);
            $document = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($document) {
                // Delete file
                $filePath = '../../../documents/' . $document['fichier'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                // Delete from database
                $sql = "DELETE FROM membres_documents WHERE id = :id AND id_membre = :id_membre";
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