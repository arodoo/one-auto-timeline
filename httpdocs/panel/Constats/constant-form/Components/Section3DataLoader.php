<?php
/**
 * Section3DataLoader - Loads user and insurance data for Section 3 (Vehicle B)
 * Only loads data when in jumelage mode AND the current user is User B
 */
class Section3DataLoader {
    private $bdd;
    private $user_id;
    private $is_jumelage;
    
    /**
     * Constructor
     * 
     * @param PDO $bdd Database connection
     * @param int $user_id Current user ID
     * @param bool $is_jumelage Whether we're in jumelage mode
     */
    public function __construct($bdd, $user_id, $is_jumelage = false) {
        $this->bdd = $bdd;
        $this->user_id = $user_id;
        $this->is_jumelage = $is_jumelage;
    }

    /**
     * Check if the current user is User B in a jumelage context
     * User B is the recipient who fills out the Vehicle B section
     * 
     * @return bool True if User B, false otherwise
     */
    private function isUserB() {
        if (!$this->is_jumelage) {
            return false;
        }
        
        // In jumelage mode, check if we're User B (recipient)
        // User B typically accesses via share_token or is marked in the session
        if ((isset($_GET['share_token']) && !empty($_GET['share_token'])) || 
            (isset($_SESSION['is_user_b']) && $_SESSION['is_user_b'] === true)) {
            return true;
        }
        
        return false;
    }

    /**
     * Load user data for form autofill, but only if we're User B in jumelage mode
     * 
     * @return array User and insurance data for Vehicle B
     */
    public function loadUserData() {
        $data = [];
        
        // Only load data for autofill if we're User B in jumelage mode
        if ($this->isUserB()) {
            error_log("Section3DataLoader: Loading Vehicle B data for user ID: " . $this->user_id);
            
            try {
                // Load basic user info
                $stmt = $this->bdd->prepare("SELECT * FROM membres WHERE id = ?");
                $stmt->execute(array($this->user_id));
                
                if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Basic user info for vehicle B
                    $data['s3_insured_name'] = $user['nom'];
                    $data['s3_insured_firstname'] = $user['prenom'];
                    $data['s3_insured_address'] = $user['adresse'];
                    $data['s3_insured_postal'] = $user['cp'];
                    $data['s3_insured_city'] = $user['ville'];
                    $data['s3_insured_contact'] = $user['mail'];
                    $data['s3_insured_country'] = $user['Pays'];
                    
                    // Driver info for vehicle B
                    $data['s3_driver_name'] = $user['nom'];
                    $data['s3_driver_firstname'] = $user['prenom'];
                    $data['s3_driver_birthdate'] = $user['datenaissance'];
                    $data['s3_driver_address'] = $user['adresse'];
                    $data['s3_driver_postal'] = $user['cp'];
                    $data['s3_driver_city'] = $user['ville'];
                    $data['s3_driver_country'] = $user['Pays'];
                    $data['s3_driver_phone'] = !empty($user['Telephone_portable']) ? 
                        $user['Telephone_portable'] : $user['Telephone'];
                }
                $stmt->closeCursor();
                  // Load insurance data
                $stmt = $this->bdd->prepare("SELECT * FROM membres_insurance WHERE id_membre = ?");
                $stmt->execute(array($this->user_id));
                
                if ($insurance = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $data['s3_insurance_name'] = $insurance['company_name'];
                    $data['s3_insurance_contract'] = $insurance['contract_number'];
                    $data['s3_insurance_green_card'] = $insurance['green_card_number'];                    $data['s3_insurance_valid_from'] = $insurance['valid_from'];
                    $data['s3_insurance_valid_to'] = $insurance['valid_to'];
                    
                    // Agency info
                    $data['s3_insurance_agency'] = $insurance['agency_office'] ?? ''; // New field for agency/office/broker
                    $data['s3_agency_name'] = $insurance['agency_name'];
                    $data['s3_agency_address'] = $insurance['agency_address'];
                    $data['s3_agency_country'] = $insurance['agency_country'];
                    $data['s3_agency_email'] = $insurance['agency_email'];
                }
                $stmt->closeCursor();
                
                error_log("Section3DataLoader: Successfully loaded Vehicle B data for user in jumelage mode");
            } catch (Exception $e) {
                error_log("Section3DataLoader error: " . $e->getMessage());
            }
        } else {
            error_log("Section3DataLoader: Not loading Vehicle B data - either not in jumelage mode or not user B");
        }
        
        return $data;
    }
}
