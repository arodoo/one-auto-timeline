<?php
// filepath: panel/Profil-automobile/tests/e2e_test.php
// Simple E2E test script for vehicle management module

// Required configuration
require_once($_SERVER['DOCUMENT_ROOT'] . '/Configurations_bdd.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Configurations.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Configurations_modules.php');

$dir_fonction = "../../";
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

// Mock session data for testing
$_SESSION['4M8e7M5b1R2e8s'] = true;
$user = 'test_user';
$id_oo = 999; // Test user ID

// Load models
require_once('../models/VehicleModel.php');
require_once('../models/VehicleAPIModel.php');

// Results container
$results = [
    'total' => 0,
    'passed' => 0,
    'failed' => 0,
    'tests' => []
];

// Test helper function
function runTest($name, $callback) {
    global $results;
    
    $results['total']++;
    
    try {
        if ($callback()) {
            $results['passed']++;
            $results['tests'][] = ['name' => $name, 'status' => 'PASSED'];
            echo "✓ $name: PASSED\n";
        } else {
            $results['failed']++;
            $results['tests'][] = ['name' => $name, 'status' => 'FAILED'];
            echo "✗ $name: FAILED\n";
        }
    } catch (Exception $e) {
        $results['failed']++;
        $results['tests'][] = [
            'name' => $name, 
            'status' => 'FAILED', 
            'error' => $e->getMessage()
        ];
        echo "✗ $name: FAILED - {$e->getMessage()}\n";
    }
}

// Database cleanup
function cleanup() {
    global $bdd, $id_oo;
    $stmt = $bdd->prepare("DELETE FROM membres_profil_auto WHERE id_membre = :id_membre");
    $stmt->execute([':id_membre' => $id_oo]);
}

// Start testing
echo "Starting E2E tests for Vehicle Management Module\n";
echo "==============================================\n\n";

// Initialize models
$vehicleModel = new VehicleModel();
$apiModel = new VehicleAPIModel();

// Clean up before tests
cleanup();

// Test 1: Check database connection
runTest('Database Connection', function() use ($bdd) {
    return $bdd instanceof PDO;
});

// Test 2: Add vehicle manually
$testVehicleId = null;
runTest('Add Vehicle Manually', function() use ($vehicleModel, $id_oo, &$testVehicleId) {
    $data = [
        'immat' => 'TEST123',
        'marque' => 'Test Brand',
        'modele' => 'Test Model',
        'date1erCir_fr' => '01/01/2020',
        'date1erCir_us' => '2020-01-01',
        'couleur' => 'Black',
        'puisFisc' => '5',
        'boite_vitesse' => 'M',
        'energieNGC' => 'DIESEL',
        'co2' => '120',
        'energie' => 'GO',
        'genreVCG' => 'VP',
        'genreVCGNGC' => 'VP',
        'carrosserieCG' => 'CI',
        'collection' => '',
        'date30' => '',
        'vin' => 'TEST1234567890',
        'puisFiscReel' => '100',
        'nr_passagers' => '5',
        'nb_portes' => '5',
        'type_mine' => '',
        'poids' => '1200',
        'cylindres' => '4',
        'sra_id' => '',
        'sra_group' => '',
        'sra_commercial' => '',
        'code_moteur' => '',
        'k_type' => '',
        'db_c' => '',
        'date_dernier_control_tecnique' => '2022-01-01'
    ];
    
    $testVehicleId = $vehicleModel->addVehicle($data, $id_oo);
    return $testVehicleId > 0;
});

// Test 3: Get vehicle by ID
runTest('Get Vehicle by ID', function() use ($vehicleModel, $id_oo, $testVehicleId) {
    if (!$testVehicleId) return false;
    $vehicle = $vehicleModel->findByUserAndId($id_oo, $testVehicleId);
    return $vehicle && $vehicle['immat'] === 'TEST123';
});

// Test 4: Update vehicle
runTest('Update Vehicle', function() use ($vehicleModel, $id_oo, $testVehicleId) {
    if (!$testVehicleId) return false;
    
    $data = [
        'couleur' => 'Red',
        'puisFisc' => '6'
    ];
    
    $result = $vehicleModel->updateVehicle($testVehicleId, $data, $id_oo);
    if (!$result) return false;
    
    $vehicle = $vehicleModel->findByUserAndId($id_oo, $testVehicleId);
    return $vehicle && $vehicle['couleur'] === 'Red' && $vehicle['puisFisc'] === '6';
});

// Test 5: Check duplicate detection
runTest('Duplicate Detection', function() use ($vehicleModel, $id_oo) {
    return $vehicleModel->checkDuplicate('TEST123', $id_oo) === true;
});

// Test 6: Get vehicles list
runTest('Get Vehicles List', function() use ($vehicleModel, $id_oo) {
    $vehicles = $vehicleModel->getVehiclesList($id_oo);
    return is_array($vehicles) && count($vehicles) > 0;
});

// Test 7: Profile completion check
require_once('../services/ProfileCompletionService.php');
runTest('Profile Completion Service', function() use ($id_oo) {
    $profileService = new ProfileCompletionService();
    
    $hasVehicles = $profileService->checkVehicleExists();
    
    // Should have vehicles from previous tests
    return $hasVehicles === true;
});

// Test 8: Delete vehicle
runTest('Delete Vehicle', function() use ($vehicleModel, $id_oo, $testVehicleId) {
    if (!$testVehicleId) return false;
    
    $result = $vehicleModel->deleteVehicle($testVehicleId, $id_oo);
    if (!$result) return false;
    
    $vehicle = $vehicleModel->findByUserAndId($id_oo, $testVehicleId);
    return $vehicle === false;
});

// Clean up after tests
cleanup();

// Display results
echo "\n==============================================\n";
echo "Test Results: {$results['passed']}/{$results['total']} passed, {$results['failed']} failed\n";
echo "==============================================\n";

// Return exit code based on test results
exit($results['failed'] > 0 ? 1 : 0);