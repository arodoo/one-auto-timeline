<?php
require_once __DIR__ . '/../Models/VehicleModel.php';
require_once __DIR__ . '/../Models/BrandModel.php';
require_once __DIR__ . '/../Services/VehicleApiService.php';

class VehicleController {
    private $vehicleModel;
    private $brandModel;
    private $apiService;
    
    public function __construct($bdd) {
        $this->vehicleModel = new VehicleModel($bdd);
        $this->brandModel = new BrandModel($bdd);
        $this->apiService = new VehicleApiService();
    }
    
    public function index() {
        // Main entry point - renders the main template with tabs
        global $id_oo, $user;
        
        // Check if this is an AJAX request
        $is_ajax_request = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
                          
        if ($is_ajax_request) {
            // Default to list view for AJAX requests to index
            $this->list();
        } else {
            // For full page loads, include the main template with tabs
            include __DIR__ . '/../Views/main.php';
        }
    }
    
    public function list() {
        // List all vehicles for the current user
        global $id_oo, $user;
        
        // Get user vehicles for display
        $vehicles = $this->vehicleModel->findAllByUserId($id_oo);
        
        // Check if this is an AJAX request
        $is_ajax_request = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        if ($is_ajax_request) {
            // For AJAX, include only the list view
            include __DIR__ . '/../Views/list.php';
        } else {
            // For full page load, include the main template with tabs
            include __DIR__ . '/../Views/main.php';
        }
    }
    
    public function add() {
        // Add vehicle form (manual entry method)
        global $id_oo, $user;
        
        $brands = $this->brandModel->getAllBrands();
        $vehicle = null;
        
        // Check if this is an AJAX request
        $is_ajax_request = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        if ($is_ajax_request) {
            // For AJAX, include only the form view
            include __DIR__ . '/../Views/form.php';
        } else {
            // For full page load, include the main template with tabs
            include __DIR__ . '/../Views/main.php';
        }
    }
    
    public function lookup() {
        // API lookup form (API entry method)
        global $id_oo, $user;
        
        // Check if this is an AJAX request
        $is_ajax_request = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        if ($is_ajax_request) {
            // For AJAX, include only the API lookup form
            include __DIR__ . '/../Views/lookup.php';
        } else {
            // For full page load, include the main template with tabs
            include __DIR__ . '/../Views/main.php';
        }
    }
    
    public function lookupApi() {
        // API lookup vehicle by registration
        global $id_oo;
        
        $response = ['status' => 400, 'message' => 'Immatriculation invalide'];
        
        $immatriculation = isset($_POST['immat']) ? trim($_POST['immat']) : '';
        
        if (!empty($immatriculation)) {
            $response = $this->apiService->lookupByRegistration($immatriculation);
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    public function edit($id) {
        // Edit vehicle form
        global $id_oo, $user;
        
        $vehicle = $this->vehicleModel->findById($id, $id_oo);
        
        // Check if this is an AJAX request
        $is_ajax_request = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        if (!$vehicle) {
            if ($is_ajax_request) {
                echo '<div class="alert alert-warning">Véhicule introuvable ou vous n\'avez pas accès à ce véhicule</div>';
                return;
            } else {
                header("Location: /Profil-automobile?action=list");
                exit;
            }
        }
        
        $brands = $this->brandModel->getAllBrands();
        
        if ($is_ajax_request) {
            // For AJAX, include only the form view
            include __DIR__ . '/../Views/form.php';
        } else {
            // For full page load, include the main template with tabs
            include __DIR__ . '/../Views/main.php';
        }
    }
    
    public function delete($id) {
        // Delete vehicle
        global $id_oo, $user;
        
        $result = $this->vehicleModel->delete($id, $id_oo);
        
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['status' => 200, 'message' => 'Le véhicule a été supprimé avec succès']);
        } else {
            echo json_encode(['status' => 404, 'message' => 'Véhicule introuvable ou déjà supprimé']);
        }
        exit;
    }
    
    public function save() {
        // Ajax endpoint for saving vehicle data
        global $id_oo, $user;
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data && !empty($_POST)) {
            // Try to get data from POST if JSON parsing failed
            $data = $_POST;
        }
        
        if (!$data) {
            $this->jsonResponse(['status' => 400, 'message' => 'Données invalides']);
            return;
        }
        
        // Validate required fields
        $requiredFields = ['immat', 'marque', 'modele', 'date1erCir_fr'];
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $missingFields[] = $field;
            }
        }
        
        if (!empty($missingFields)) {
            $this->jsonResponse([
                'status' => 400, 
                'message' => 'Champs requis manquants', 
                'missingFields' => $missingFields
            ]);
            return;
        }
        
        $result = $this->vehicleModel->save($data, $id_oo);
        
        $this->jsonResponse($result);
    }
    
    public function getModels() {
        // Ajax endpoint to get models for a brand
        $brand = isset($_POST['marque']) ? $_POST['marque'] : '';
        
        if (empty($brand)) {
            echo '';
            exit;
        }
        
        $models = $this->brandModel->getModelsByBrand($brand);
        
        foreach ($models as $model) {
            echo '<option value="' . htmlspecialchars($model) . '">';
        }
        exit;
    }
    
    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?>