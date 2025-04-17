<?php
require_once(__DIR__ . '/../../../../Configurations_bdd.php');

try {
    $pdo = $bdd;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Debug info array
    $debug = [
        'fields_found' => [],
        'sql' => [],
        'processing_log' => []  // Nuevo array para logs
    ];

    // Drop existing tables
    $pdo->exec("DROP TABLE IF EXISTS constats_main");
    $pdo->exec("DROP TABLE IF EXISTS constats_vehicle_a");
    $pdo->exec("DROP TABLE IF EXISTS constats_vehicle_b");

    // Common table fields
    $mainSql = "CREATE TABLE constats_main (
        id INT AUTO_INCREMENT PRIMARY KEY,
        unique_id VARCHAR(32) NOT NULL,
        id_membre INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,";
    
    $vehicleASql = "CREATE TABLE constats_vehicle_a (
        id INT AUTO_INCREMENT PRIMARY KEY,
        constat_id VARCHAR(32),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,";
    
    $vehicleBSql = "CREATE TABLE constats_vehicle_b (
        id INT AUTO_INCREMENT PRIMARY KEY,
        constat_id VARCHAR(32),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,";

    $formHandlerPath = __DIR__ . '/../FormHandler/';
    $mainFields = [];
    $vehicleAFields = [];
    $vehicleBFields = [];

    // Add ALL canvas fields manually
    $mainFields['s5_accident_sketch'] = 'LONGTEXT';   // Initial sketch
    $mainFields['s9_final_sketch'] = 'LONGTEXT';      // Final sketch
    $vehicleAFields['s2_impact_point'] = 'LONGTEXT';  // Vehicle A impact point
    $vehicleAFields['s6_signature_a'] = 'LONGTEXT';   // Driver A signature
    $vehicleBFields['s3_impact_point'] = 'LONGTEXT';  // Vehicle B impact point
    $vehicleBFields['s7_signature_b'] = 'LONGTEXT';   // Driver B signature

    foreach (glob($formHandlerPath . 'Section_*.php') as $file) {
        // Add debug output for each file
        $debug['files'][] = basename($file);
        $content = file_get_contents($file);
        
        // Add debug output for content
        $debug['content'][basename($file)] = substr($content, 0, 200); // First 200 chars
        
        // Find all inputs with data-db-name
        if (preg_match_all('/<input[^>]*data-db-name="([^"]+)"[^>]*>/', $content, $matches)) {
            $debug['found_fields'][basename($file)] = $matches[1];
        }
        
        // Store debug info instead of echoing
        $debug['processing_log'][] = "Processing file: " . basename($file);
        
        // Modified pattern to include both input and textarea
        $pattern = '/<(?:input|textarea)[^>]*data-db-name="([^"]+)"[^>]*>/';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
        
        $debug['fields_found'][basename($file)] = array_map(function($m) { return $m[1]; }, $matches);

        foreach ($matches as $match) {
            $fieldName = $match[1];
            
            // Special handling for different field types
            if (in_array($fieldName, [
                's1_accident_date',
                's1_accident_time',
                's2_insurance_valid_from',
                's2_insurance_valid_until',
                's2_permit_delivered',
                's3_insurance_valid_from',
                's3_insurance_valid_until',
                's3_permit_delivered',
                's11_current_day',
                's11_current_month',
                's11_current_year'
            ])) {
                $type = 'VARCHAR(20)';   // For date/time fields
            } elseif (in_array($fieldName, [
                's1_has_injuries', 
                's1_has_vehicle_damage', 
                's1_has_object_damage',
                's2_has_damage_coverage',
                's3_has_damage_coverage',
                's8_is_regular_driver',
                's8_lives_with_insured',
                's8_is_employee',
                's10_has_police_report',
                's10_has_police_statement',
                's10_has_incident_report',
                // Add Section 11 radio buttons
                's11_injured1_wore_protection',
                's11_injured2_wore_protection'
            ])) {
                $type = 'VARCHAR(3)';   // For yes/no values
            } elseif (in_array($fieldName, [
                's4_check_count_a',
                's4_check_count_b'
            ])) {
                $type = 'TINYINT UNSIGNED';  // For storing numbers 0-255
            } elseif ($fieldName === 's8_driver_marital_status') {
                $type = 'VARCHAR(10)';  // For marital status options
            } elseif (strpos($match[0], 'type="checkbox"') !== false) {
                $type = 'CHAR(1)';  // For regular checkboxes
            } else {
                preg_match('/data-maxlength=["\'](\d+)["\']/', $match[0], $lengthMatch);
                $length = !empty($lengthMatch[1]) ? (int)$lengthMatch[1] : 255;
                $type = ($length > 255) ? 'TEXT' : "VARCHAR($length)";
            }

            // Distribute fields to appropriate tables
            if (strpos($fieldName, 's2_') === 0) {
                $vehicleAFields[$fieldName] = $type;
            } elseif (strpos($fieldName, 's3_') === 0) {
                $vehicleBFields[$fieldName] = $type;
            } else {
                $mainFields[$fieldName] = $type;
            }
        }
    }

    // Complete SQL statements
    foreach ($mainFields as $field => $type) {
        $mainSql .= "\n`$field` $type,";
    }
    $mainSql = rtrim($mainSql, ',') . "\n)";

    foreach ($vehicleAFields as $field => $type) {
        $vehicleASql .= "\n`$field` $type,";
    }
    $vehicleASql .= "\nFOREIGN KEY (constat_id) REFERENCES constats_main(unique_id)\n)";

    foreach ($vehicleBFields as $field => $type) {
        $vehicleBSql .= "\n`$field` $type,";
    }
    $vehicleBSql .= "\nFOREIGN KEY (constat_id) REFERENCES constats_main(unique_id)\n)";

    // Store SQL statements in debug info
    $debug['sql']['main'] = $mainSql;
    $debug['sql']['vehicle_a'] = $vehicleASql;
    $debug['sql']['vehicle_b'] = $vehicleBSql;

    try {
        $pdo->exec($mainSql);
        $pdo->exec($vehicleASql);
        $pdo->exec($vehicleBSql);
        
        header('Content-Type: application/json');
        echo json_encode([
            "success" => true, 
            "message" => "Tables generated successfully",
            "debug" => $debug
        ]);
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode([
            "success" => false,
            "message" => "Database Error: " . $e->getMessage(),
            "debug" => $debug
        ]);
    }

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage(),
        "debug" => isset($debug) ? $debug : []
    ]);
}
?>