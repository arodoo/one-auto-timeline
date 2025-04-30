<?php
// Direct test output
echo "<!-- DEBUG: Profil-automobile module index.php is executing -->";

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug marker to confirm module loading
echo "<!-- DEBUG: Profil-automobile module is loading correctly -->";

require_once($_SERVER['DOCUMENT_ROOT'] . '/Configurations_bdd.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Configurations.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Configurations_modules.php');

$dir_fonction = "../../";
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');


// Auto-load controllers and models
spl_autoload_register(function($class) {
    $paths = [
        __DIR__ . '/Controllers/',
        __DIR__ . '/Models/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Security check
if (empty($_SESSION['4M8e7M5b1R2e8s']) || empty($user)) {
    header("location: /");
    exit;
}

// Check if this is an AJAX request
$is_ajax_request = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// If this is not an AJAX request and no specific action is set, call index method
if (!$is_ajax_request && empty($_GET['action'])) {
    $controller = new VehicleController($bdd);
    $controller->index();
    exit;
}

// Parse URL parameters from the centralized routing pattern
if (isset($_GET['params'])) {
    $params = trim($_GET['params'], '/');
    $segments = explode('/', $params);
    
    if (!empty($segments[0])) {
        $_GET['action'] = $segments[0];
    }
    
    if (isset($segments[1]) && is_numeric($segments[1])) {
        $_GET['idaction'] = (int)$segments[1];
    }
}

// Initialize controller
$controller = new VehicleController($bdd);

// Get action and ID from parameters
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['idaction']) ? (int)$_GET['idaction'] : null;

// Route to appropriate controller method
switch($action) {
    case 'edit':
        $controller->edit($id);
        break;
    case 'delete':
        $controller->delete($id);
        break;
    case 'add':
        $controller->add();
        break;
    case 'list':
    case 'mes-vehicules':
        $controller->list();
        break;
    case 'getModels':
        $controller->getModels();
        break;
    case 'save':
        $controller->save();
        break;
    case 'manual':
    case 'vehicule-manuel':
        if ($id) {
            $controller->edit($id);
        } else {
            $controller->add();
        }
        break;
    default:
        $controller->list();
        break;
}
?>