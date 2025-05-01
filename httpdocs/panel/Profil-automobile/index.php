<?php
// filepath: panel/Profil-automobile/index.php
// Load required configuration files
require_once($_SERVER['DOCUMENT_ROOT'] . '/Configurations_bdd.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Configurations.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Configurations_modules.php');

$dir_fonction = "../../";
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

// Load controllers
require_once('controllers/VehicleController.php');
require_once('controllers/APIController.php');

// Simple routing logic
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Check if it's an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Route the request
if ($isAjax) {
    // AJAX request handling
    $api = new APIController();
    
    switch ($action) {
        case 'get_api_info':
            $api->getAPIInfo();
            break;
        case 'get_vehicle_info':
            $api->getVehicleInfo();
            break;
        case 'save_vehicle':
            $api->saveVehicle();
            break;
        case 'delete_vehicle':
            $api->deleteVehicle();
            break;
        case 'get_models':
            $api->getModels();
            break;
        default:
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 404,
                'message' => 'Action non trouvée'
            ]);
            break;
    }
} else {
    // Regular page request handling
    $controller = new VehicleController();
    
    switch ($action) {
        case 'index':
        case 'list':
            $controller->list();
            break;
        case 'api_search':
            $controller->apiSearch();
            break;
        case 'manual_form':
            $controller->manualForm();
            break;
        case 'edit':
            $controller->edit($id);
            break;
        case 'view':
            // New action for viewing vehicle details
            $controller->view($id);
            break;
        case 'delete':
            // New action for deletion confirmation page
            $controller->delete($id);
            break;
        default:
            $controller->list();
            break;
    }
}