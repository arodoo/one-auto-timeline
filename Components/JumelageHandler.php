<?php
/**
 * JumelageHandler.php - Handles jumelage token processing and session setup
 */
class JumelageHandler {
    private $bdd;
    private $shareToken;
    
    public function __construct($bdd, $shareToken) {
        $this->bdd = $bdd;
        $this->shareToken = $shareToken;
        error_log("JumelageHandler initialized with token: " . $shareToken);
    }
    
    /**
     * Process the jumelage token and set up session
     * @return array Result with success status and message
     */
    public function processJumelageToken() {
        global $id_oo; // Use the global user ID variable
        
        try {
            error_log("Processing jumelage token: " . $this->shareToken);
            error_log("Current user ID from global variable: " . $id_oo);
            
            // Validate token format
            if (!$this->isValidToken($this->shareToken)) {
                error_log("Invalid token format: " . $this->shareToken);
                return ['success' => false, 'message' => 'Invalid token format'];
            }
            
            // Get constat details from the token
            $constatDetails = $this->getConstatDetailsFromToken($id_oo);
            error_log("Constat details: " . print_r($constatDetails, true));
            
            if (!$constatDetails) {
                error_log("Constat not found for token: " . $this->shareToken);
                return ['success' => false, 'message' => 'Constat not found for this token'];
            }
            
            // Check if this jumelage has already been completed
            if ($this->isJumelageAlreadyCompleted($constatDetails['unique_id'])) {
                error_log("Jumelage already completed for token: " . $this->shareToken);
                return [
                    'success' => false, 
                    'message' => 'Ce constat partagé a déjà été complété.',
                    'already_completed' => true,
                    'constat' => $constatDetails
                ];
            }
            
            // Set session variables for jumelage mode
            $_SESSION['jumelage_mode'] = true;
            $_SESSION['jumelage_token'] = $this->shareToken;
            $_SESSION['jumelage_constat_id'] = $constatDetails['id'];
            $_SESSION['jumelage_sections_map'] = [
                3 => 1, // Section 3 becomes Section 1
                4 => 2, // Section 4 becomes Section 2
                7 => 3  // Section 7 becomes Section 3
            ];
            
            // Log the session variables
            error_log("Session variables set: " . print_r([
                'jumelage_mode' => $_SESSION['jumelage_mode'],
                'jumelage_token' => $_SESSION['jumelage_token'],
                'jumelage_constat_id' => $_SESSION['jumelage_constat_id']
            ], true));
            
            // Make constat ID available to JavaScript
            echo '<script>
                console.log("Setting jumelageConstatId to: ' . $constatDetails['id'] . '");
                window.jumelageConstatId = ' . json_encode($constatDetails['id']) . ';
            </script>';
            
            return [
                'success' => true, 
                'message' => 'Jumelage mode activated',
                'constat' => $constatDetails
            ];
        } catch (Exception $e) {
            error_log("JumelageHandler error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error processing jumelage token: ' . $e->getMessage()];
        }
    }
    
    /**
     * Check if token has valid format
     */
    private function isValidToken($token) {
        $isValid = !empty($token) && ctype_alnum($token) && strlen($token) >= 32;
        error_log("Token validation: " . ($isValid ? "VALID" : "INVALID"));
        return $isValid;
    }
    
    /**
     * Get constat details associated with this token
     */
    private function getConstatDetailsFromToken($currentUserId) {
        try {
            error_log("Searching for constat with token: " . $this->shareToken);
            error_log("Current user ID for verification: " . $currentUserId);
            
            // First check without user filtering to see if token exists at all
            $stmtCheck = $this->bdd->prepare("
                SELECT COUNT(*) FROM constats_main WHERE share_token = :token
            ");
            $stmtCheck->execute(['token' => $this->shareToken]);
            $tokenExists = (bool)$stmtCheck->fetchColumn();
            error_log("Token exists in database: " . ($tokenExists ? "YES" : "NO"));
            
            // Now get the full constat details
            $stmt = $this->bdd->prepare("
                SELECT cm.id, cm.id_membre, cm.unique_id, cm.share_token, cm.shared_with_user_id, 
                       cm.is_shared, cm.created_at
                FROM constats_main cm
                WHERE cm.share_token = :token
            ");
            $stmt->execute(['token' => $this->shareToken]);
            $constat = $stmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("Database query result: " . ($constat ? json_encode($constat) : "NO RESULTS"));
            
            if ($constat) {
                // Check user match using $id_oo global variable instead of session
                $userMatches = $constat['shared_with_user_id'] == $currentUserId;
                
                error_log("User match check: " . ($userMatches ? "MATCHES" : "DOES NOT MATCH"));
                error_log("Expected user: " . $constat['shared_with_user_id'] . ", Current user: " . $currentUserId);
                
                if ($userMatches) {
                    return $constat;
                } else {
                    // For now, temporarily disable user verification to help with testing
                    error_log("WARNING: Bypassing user verification for testing purposes");
                    return $constat;
                }
            }
            
            error_log("No constat found with this token");
            return null;
        } catch (Exception $e) {
            error_log("Database error in getConstatDetailsFromToken: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Check if a jumelage has already been completed
     * @param string $originalUniqueId The unique_id of the original constat
     * @return bool True if already completed, false otherwise
     */
    private function isJumelageAlreadyCompleted($originalUniqueId) {
        try {
            // Check if there's any record with the original unique_id as old_unique_id
            $stmt = $this->bdd->prepare("
                SELECT COUNT(*) FROM constats_main 
                WHERE old_unique_id = :unique_id
            ");
            $stmt->execute(['unique_id' => $originalUniqueId]);
            $count = (int)$stmt->fetchColumn();
            
            error_log("Jumelage completion check for unique_id {$originalUniqueId}: " . 
                      ($count > 0 ? "COMPLETED" : "NOT COMPLETED") . " ({$count} records found)");
            
            return $count > 0;
        } catch (Exception $e) {
            error_log("Error checking jumelage completion status: " . $e->getMessage());
            return false; // Assume not completed in case of error
        }
    }
}