<?php
// Fix paths to use absolute server paths
require_once('/var/www/vhosts/mon-espace-auto.com/httpdocs/Configurations_bdd.php');
require_once('/var/www/vhosts/mon-espace-auto.com/httpdocs/Configurations.php');
require_once('/var/www/vhosts/mon-espace-auto.com/httpdocs/Configurations_modules.php');

// Fix function include path
$dir_fonction = "/var/www/vhosts/mon-espace-auto.com/httpdocs/";
require_once('/var/www/vhosts/mon-espace-auto.com/httpdocs/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start logging
error_log("Starting constat save process");

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    try {
        error_log("Session validated, processing data");

        // Get and log JSON data
        $rawData = file_get_contents('php://input');
        error_log("Received data: " . $rawData);
        $data = json_decode($rawData, true);

        if ($data === null) {
            throw new Exception("JSON decode failed: " . json_last_error_msg());
        }

        // Check if we're in jumelage mode
        $isJumelageMode = !empty($data['is_jumelage_mode']) && $data['is_jumelage_mode'] === true;
        error_log("Is jumelage mode: " . ($isJumelageMode ? "YES" : "NO"));
        
        // Validate required fields based on mode
        if ($isJumelageMode) {
            // In jumelage mode, sc3-input22 (email agency B) is required
            if (empty($data['constats_vehicle_b']['s3_agency_phone'])) {
                error_log("Error: Missing required email for agency B in jumelage mode");
                echo json_encode([
                    "success" => false,
                    "message" => "L'email de l'agence B est obligatoire (Section 1)",
                    "Texte_rapport" => "Veuillez remplir le champ Email de l'agence B obligatoire dans la Section 1"
                ]);
                exit;
            }
        } else {
            // In normal mode, sc2-input22 (email agency A) is required
            if (empty($data['constats_vehicle_a']['s2_agency_phone'])) {
                error_log("Error: Missing required email for agency A");
                echo json_encode([
                    "success" => false,
                    "message" => "L'email de l'agence A est obligatoire (Section 2)",
                    "Texte_rapport" => "Veuillez remplir le champ Email de l'agence A obligatoire dans la Section 2"
                ]);
                exit;
            }
        }

        // Start transaction
        $bdd->beginTransaction();
        error_log("Transaction started");

        // Insert or update main data
        if (!empty($data['constats_main'])) {
            // Check if this is a jumelage update
            $isUpdate = !empty($data['constats_main']['id']);
            error_log("Is this an update? " . ($isUpdate ? "YES" : "NO")); 
            error_log("Constat ID: " . ($isUpdate ? $data['constats_main']['id'] : "NONE"));
            
            if ($isUpdate) {
                // UPDATE existing record
                $id = $data['constats_main']['id'];
                $unique_id = $id; // We must capture unique_id before unsetting id
                
                // Find unique_id and stored checkbox counts for this record first
                $stmt = $bdd->prepare("SELECT unique_id, id_membre, s4_check_count_a, s4_check_count_b FROM constats_main WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($result) {
                    $unique_id = $result['unique_id'];
                    $user_a_id = $result['id_membre']; // Store original user's ID for later
                    $stored_check_count_a = $result['s4_check_count_a'];
                    $stored_check_count_b = $result['s4_check_count_b'];
                    
                    error_log("Found unique_id for update: " . $unique_id);
                    error_log("Found user_a_id: " . $user_a_id);
                    error_log("Stored check counts - A: " . $stored_check_count_a . ", B: " . $stored_check_count_b);
                } else {
                    error_log("Warning: Could not find unique_id for ID: " . $id);
                }
                
                unset($data['constats_main']['id']); // Remove ID from data array
                
                // In jumelage mode, preserve s4_check_count_a from the database
                if ($isJumelageMode) {
                    error_log("Jumelage mode: Preserving s4_check_count_a from DB: " . $stored_check_count_a);
                    // Remove s4_check_count_a from update data
                    unset($data['constats_main']['s4_check_count_a']);
                }
                
                // Don't update empty fields
                $updates = [];
                foreach ($data['constats_main'] as $field => $value) {
                    if ($value !== null && $value !== '') {
                        $updates[] = "`$field` = :$field";
                    }
                }
                
                if (!empty($updates)) {
                    $sql = "UPDATE constats_main SET " . implode(', ', $updates) . " WHERE id = :id";
                    error_log("Update SQL: " . $sql);
                    
                    $stmt = $bdd->prepare($sql);
                    $stmt->bindValue(":id", $id);
                    
                    foreach ($data['constats_main'] as $key => $value) {
                        if ($value !== null && $value !== '') {
                            $stmt->bindValue(":$key", $value);
                        }
                    }
                    $stmt->execute();
                    error_log("Updated constats_main record with ID: " . $id);
                }
            } else {
                // INSERT new record
                // Generate unique_id
                $unique_id = bin2hex(random_bytes(16));

                $data['constats_main']['unique_id'] = $unique_id;
                $data['constats_main']['id_membre'] = $id_oo;

                $fields = implode('`, `', array_keys($data['constats_main']));
                $values = implode(', :', array_keys($data['constats_main']));
                $sql = "INSERT INTO constats_main (`$fields`) VALUES (:$values)";

                $stmt = $bdd->prepare($sql);
                foreach ($data['constats_main'] as $key => $value) {
                    $stmt->bindValue(":$key", $value);
                }
                $stmt->execute();
            }
        }

        // For vehicle data tables in jumelage mode, we need to handle it differently
        if ($isUpdate) {
            // Process vehicle data - for updates we need to check if records exist already
            foreach (['constats_vehicle_a', 'constats_vehicle_b'] as $table) {
                if (!empty($data[$table])) {
                    // Check if record already exists
                    $checkStmt = $bdd->prepare("SELECT COUNT(*) FROM $table WHERE constat_id = :constat_id");
                    $checkStmt->execute(['constat_id' => $unique_id]);
                    $recordExists = (bool)$checkStmt->fetchColumn();
                    
                    error_log("$table record exists: " . ($recordExists ? "YES" : "NO"));
                    
                    if ($recordExists) {
                        // Update existing record
                        $updates = [];
                        foreach ($data[$table] as $field => $value) {
                            if ($value !== null && $value !== '') {
                                $updates[] = "`$field` = :$field";
                            }
                        }
                        
                        if (!empty($updates)) {
                            $sql = "UPDATE $table SET " . implode(', ', $updates) . " WHERE constat_id = :constat_id";
                            error_log("Update SQL for $table: " . $sql);
                            
                            $stmt = $bdd->prepare($sql);
                            $stmt->bindValue(":constat_id", $unique_id);
                            
                            foreach ($data[$table] as $key => $value) {
                                if ($value !== null && $value !== '') {
                                    $stmt->bindValue(":$key", $value);
                                }
                            }
                            $stmt->execute();
                            error_log("Updated $table record");
                        }
                    } else {
                        // Insert new record
                        $fields = implode('`, `', array_keys($data[$table]));
                        $values = implode(', :', array_keys($data[$table]));
                        $sql = "INSERT INTO $table (`$fields`, `constat_id`) VALUES (:$values, :constat_id)";
                        error_log("Insert SQL for $table: " . $sql);
                        
                        $stmt = $bdd->prepare($sql);
                        foreach ($data[$table] as $key => $value) {
                            $stmt->bindValue(":$key", $value);
                        }
                        $stmt->bindValue(":constat_id", $unique_id);
                        $stmt->execute();
                        error_log("Inserted new $table record");
                    }
                }
            }
        } else {
            // For new records, process vehicle data as before
            foreach (['constats_vehicle_a', 'constats_vehicle_b'] as $table) {
                if (!empty($data[$table])) {
                    $fields = implode('`, `', array_keys($data[$table]));
                    $values = implode(', :', array_keys($data[$table]));
                    $sql = "INSERT INTO $table (`$fields`, `constat_id`) VALUES (:$values, :constat_id)";

                    $stmt = $bdd->prepare($sql);
                    foreach ($data[$table] as $key => $value) {
                        $stmt->bindValue(":$key", $value);
                    }
                    $stmt->bindValue(":constat_id", $unique_id);
                    $stmt->execute();
                }
            }
        }

        // If this is a jumelage update, create a copy for user B
        $user_b_copy_id = null;
        if ($isJumelageMode && $isUpdate) {
            error_log("Creating copy of constat for user B (ID: $id_oo)");
            
            // Get the current state of the constat with all updated values
            $stmt = $bdd->prepare("SELECT * FROM constats_main WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $original_constat = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$original_constat) {
                throw new Exception("Could not find updated constat with ID: $id");
            }
            
            error_log("Current s4_check_count_a value: " . $original_constat['s4_check_count_a']);
            
            // 2. Generate a new unique_id for user B's copy
            $new_unique_id = bin2hex(random_bytes(16));
            
            // 3. Create new record with modified fields
            $new_constat = $original_constat;
            unset($new_constat['id']); // Remove auto-increment ID
            $new_constat['unique_id'] = $new_unique_id; // Set new unique_id
            $new_constat['id_membre'] = $id_oo; // Set to user B's ID
            $new_constat['user_a_id'] = $user_a_id; // Set user_a_id to original user's ID
            $new_constat['old_unique_id'] = $unique_id; // Set relationship to original constat
            
            // 4. Insert the new record
            $fields = implode('`, `', array_keys($new_constat));
            $values = implode(', :', array_keys($new_constat));
            $sql = "INSERT INTO constats_main (`$fields`) VALUES (:$values)";
            
            $stmt = $bdd->prepare($sql);
            foreach ($new_constat as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            $user_b_copy_id = $bdd->lastInsertId();
            error_log("Created copy for user B with ID: $user_b_copy_id and unique_id: $new_unique_id");
            
            // 5. Copy vehicle records (constats_vehicle_a and constats_vehicle_b)
            foreach (['constats_vehicle_a', 'constats_vehicle_b'] as $table) {
                $stmt = $bdd->prepare("SELECT * FROM $table WHERE constat_id = :constat_id");
                $stmt->execute(['constat_id' => $unique_id]);
                $vehicle_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($vehicle_records as $record) {
                    // Remove id and update constat_id
                    unset($record['id']);
                    $record['constat_id'] = $new_unique_id;
                    
                    $fields = implode('`, `', array_keys($record));
                    $values = implode(', :', array_keys($record));
                    $sql = "INSERT INTO $table (`$fields`) VALUES (:$values)";
                    
                    $stmt = $bdd->prepare($sql);
                    foreach ($record as $key => $value) {
                        $stmt->bindValue(":$key", $value);
                    }
                    $stmt->execute();
                }
                error_log("Copied $table records to user B's constat");
            }
            
            // Use the new unique_id for redirection
            $unique_id = $new_unique_id;
        }

        $bdd->commit();
        error_log("Transaction committed successfully. Final unique_id: " . $unique_id);
        echo json_encode([
            "success" => true,
            "message" => $isUpdate ? "Constat updated successfully" : "Constat saved successfully",
            "unique_id" => $unique_id,
            "user_b_copy_id" => $user_b_copy_id
        ]);

    } catch (Exception $e) {
        error_log("Error occurred: " . $e->getMessage());
        $bdd->rollBack();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    error_log("Session validation failed");
    echo json_encode(["success" => false, "message" => "Session expired or unauthorized access"]);
}
?>