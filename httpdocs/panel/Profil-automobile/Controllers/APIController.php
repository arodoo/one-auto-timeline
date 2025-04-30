<?php
// filepath: panel/Profil-automobile/controllers/APIController.php
require_once 'BaseController.php';
require_once dirname(__DIR__) . '/models/VehicleModel.php';
require_once dirname(__DIR__) . '/models/VehicleAPIModel.php';
require_once dirname(__DIR__) . '/services/ProfileCompletionService.php';

class APIController extends BaseController {
    private $vehicleModel;
    private $vehicleAPIModel;
    private $profileService;
    
    public function __construct() {
        parent::__construct();
        $this->vehicleModel = new VehicleModel();
        $this->vehicleAPIModel = new VehicleAPIModel();
        $this->profileService = new ProfileCompletionService();
    }
    
    public function getAPIInfo() {
        // Get immatriculation from POST
        $immatriculation = $_POST['voir_immatriculation'] ?? null;
        
        if (empty($immatriculation)) {
            $this->respondJson([
                'status' => 400,
                'message' => 'Veuillez renseigner l\'immatriculation'
            ]);
        }
        
        // Call API
        $response = $this->vehicleAPIModel->fetchVehicleInfo($immatriculation);
        
        $this->respondJson($response);
    }
    
    public function getVehicleInfo() {
        // Get vehicle ID from POST
        $vehicleId = isset($_POST['vehicle_id']) ? (int)$_POST['vehicle_id'] : null;
        
        if (!$vehicleId) {
            $this->respondJson([
                'status' => 400,
                'message' => 'ID de véhicule manquant ou invalide'
            ]);
        }
        
        // Get vehicle data
        $vehicle = $this->vehicleModel->findByUserAndId($this->id_oo, $vehicleId);
        
        if ($vehicle) {
            $this->respondJson([
                'status' => 200,
                'data' => $vehicle
            ]);
        } else {
            $this->respondJson([
                'status' => 404,
                'message' => 'Véhicule introuvable ou vous n\'avez pas accès à ce véhicule'
            ]);
        }
    }
    
    public function saveVehicle() {
        // Get data from POST
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            $this->respondJson([
                'status' => 400,
                'message' => 'Données invalides'
            ]);
        }
        
        // Check required fields
        $required_fields = [
            "immat", "marque", "modele", "date1erCir_us", "date1erCir_fr", 
            "couleur", "puisFisc", "boite_vitesse", "energieNGC"
        ];
        
        $missing_fields = [];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                $missing_fields[] = $field;
            }
        }
        
        if (!empty($missing_fields)) {
            $this->respondJson([
                'status' => 400,
                'message' => 'Certains champs obligatoires sont manquants',
                'missingFields' => $missing_fields
            ]);
        }
        
        // Check if vehicle ID is provided for update
        $vehicleId = isset($data['vehicle_id']) ? (int)$data['vehicle_id'] : null;
        $isUpdate = false;
        
        if ($vehicleId) {
            // Verify ownership
            $vehicle = $this->vehicleModel->findByUserAndId($this->id_oo, $vehicleId);
            
            if (!$vehicle) {
                $this->respondJson([
                    'status' => 403,
                    'message' => 'Accès non autorisé à ce véhicule'
                ]);
            }
            
            $isUpdate = true;
        }
        
        // Check for duplicate license plate
        if ($this->vehicleModel->checkDuplicate($data['immat'], $this->id_oo, $isUpdate ? $vehicleId : null)) {
            $this->respondJson([
                'status' => 409,
                'message' => 'Un véhicule avec cette immatriculation existe déjà'
            ]);
        }
        
        // Save data
        try {
            if ($isUpdate) {
                $result = $this->vehicleModel->updateVehicle($vehicleId, $data, $this->id_oo);
                
                if ($result) {
                    $this->respondJson([
                        'status' => 200,
                        'message' => 'Véhicule mis à jour avec succès',
                        'vehicle_id' => $vehicleId
                    ]);
                } else {
                    $this->respondJson([
                        'status' => 500,
                        'message' => 'Erreur lors de la mise à jour du véhicule'
                    ]);
                }
            } else {
                $vehicleId = $this->vehicleModel->addVehicle($data, $this->id_oo);
                
                // Update profile completion status
                $this->profileService->updateVehicleStatus(true);
                
                $this->respondJson([
                    'status' => 200,
                    'message' => 'Nouveau véhicule ajouté avec succès',
                    'vehicle_id' => $vehicleId
                ]);
            }
        } catch (PDOException $e) {
            $this->respondJson([
                'status' => 500,
                'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
            ]);
        }
    }
    
    public function deleteVehicle() {
        // Get vehicle ID from POST
        $vehicleId = isset($_POST['vehicle_id']) ? (int)$_POST['vehicle_id'] : null;
        
        if (!$vehicleId) {
            $this->respondJson([
                'status' => 400,
                'message' => 'ID de véhicule manquant ou invalide'
            ]);
        }
        
        try {
            $result = $this->vehicleModel->deleteVehicle($vehicleId, $this->id_oo);
            
            if ($result) {
                // Check if user has any vehicles left
                $hasVehicles = $this->vehicleModel->hasVehicles($this->id_oo);
                
                // Update profile completion status
                $this->profileService->updateVehicleStatus($hasVehicles);
                
                $this->respondJson([
                    'status' => 200,
                    'message' => 'Le véhicule a été supprimé avec succès'
                ]);
            } else {
                $this->respondJson([
                    'status' => 404,
                    'message' => 'Véhicule introuvable ou déjà supprimé'
                ]);
            }
        } catch (PDOException $e) {
            $this->respondJson([
                'status' => 500,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ]);
        }
    }
    
    public function getModels() {
        $marque = isset($_POST['marque']) ? trim($_POST['marque']) : '';
        
        if (empty($marque)) {
            $this->respondJson([
                'status' => 400,
                'message' => 'Marque manquante'
            ]);
        }
        
        try {
            $stmt = $this->bdd->prepare("SELECT DISTINCT modele FROM configurations_modeles WHERE marque = :marque ORDER BY modele ASC");
            $stmt->execute([':marque' => $marque]);
            
            $models = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $models[] = $row['modele'];
            }
            
            // Format the models as option tags for datalist
            $options = '';
            foreach ($models as $model) {
                $options .= '<option value="' . htmlspecialchars($model) . '">' . htmlspecialchars($model) . '</option>';
            }
            
            echo $options;
            exit;
        } catch (PDOException $e) {
            $this->respondJson([
                'status' => 500,
                'message' => 'Erreur lors de la récupération des modèles: ' . $e->getMessage()
            ]);
        }
    }
}