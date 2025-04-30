<?php
// filepath: panel/Profil-automobile/controllers/VehicleController.php
require_once 'BaseController.php';
require_once dirname(__DIR__) . '/models/VehicleModel.php';
require_once dirname(__DIR__) . '/services/ProfileCompletionService.php';

class VehicleController extends BaseController {
    private $vehicleModel;
    private $profileService;
    
    public function __construct() {
        parent::__construct();
        $this->vehicleModel = new VehicleModel();
        $this->profileService = new ProfileCompletionService();
    }
    
    public function index() {
        // Default action - show vehicle list
        return $this->list();
    }
    
    public function list() {
        // Get vehicles for current user
        $vehicles = $this->vehicleModel->getVehiclesList($this->id_oo);
        
        // Render the view with data
        $this->renderWithLayout('vehicle/list', [
            'vehicles' => $vehicles,
            'active_tab' => 'list'
        ]);
    }
    
    public function apiSearch() {
        $this->renderWithLayout('vehicle/api_search', [
            'active_tab' => 'api_search'
        ]);
    }
    
    public function manualForm() {
        // Get car brands for the dropdown
        $brands = $this->getBrands();
        
        $this->renderWithLayout('vehicle/manual_form', [
            'vehicle' => null,
            'brands' => $brands,
            'active_tab' => 'manual'
        ]);
    }
    
    public function edit($id = null) {
        if (!$id) {
            header("Location: /Profil-automobile");
            return;
        }
        
        // Get vehicle data
        $vehicle = $this->vehicleModel->findByUserAndId($this->id_oo, $id);
        
        if (!$vehicle) {
            header("Location: /Profil-automobile");
            return;
        }
        
        // Get car brands for the dropdown
        $brands = $this->getBrands();
        
        $this->renderWithLayout('vehicle/manual_form', [
            'vehicle' => $vehicle,
            'brands' => $brands,
            'active_tab' => 'edit'
        ]);
    }
    
    private function getBrands() {
        $brands = [];
        try {
            $sql = "SELECT DISTINCT marque FROM configurations_modeles ORDER BY marque ASC";
            $stmt = $this->bdd->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $brands[] = $row['marque'];
            }
        } catch (PDOException $e) {
            // Handle error
        }
        return $brands;
    }
}