<?php
class Section2DataLoader
{
    private $bdd;
    private $id_oo;

    public function __construct($bdd, $id_oo)
    {
        $this->bdd = $bdd;
        $this->id_oo = $id_oo;
    }
    public function loadUserData()
    {
        try {
            // Initialize data array
            $data = [];

            // Load basic user data
            $stmt = $this->bdd->prepare("
                SELECT 
                    nom, prenom, adresse, cp, 
                    Telephone, Telephone_portable, Pays,
                    ville, datenaissance
                FROM membres 
                WHERE id = :id
            ");
            $stmt->execute(['id' => $this->id_oo]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Basic user data for filling form
            $data = [
                's2_insured_name' => $userData['nom'] ?? '',
                's2_insured_firstname' => $userData['prenom'] ?? '',
                's2_insured_address' => $userData['adresse'] ?? '',
                's2_insured_postal' => $userData['cp'] ?? '',
                's2_insured_contact' => $userData['Telephone'] ?: ($userData['Telephone_portable'] ?? ''),
                's2_insured_country' => $userData['Pays'] ?? 'France',
                's2_driver_name' => $userData['nom'] ?? '',
                's2_driver_firstname' => $userData['prenom'] ?? '',
                's2_driver_birthdate' => $userData['datenaissance'] ?? '',
                's2_driver_address' => $userData['adresse'] ?? '',
                's2_driver_country' => $userData['Pays'] ?? 'France',
                's2_driver_contact' => $userData['Telephone'] ?: ($userData['Telephone_portable'] ?? '')
            ];

            // Load insurance data for User A
            try {
                $stmt = $this->bdd->prepare("SELECT * FROM membres_insurance WHERE id_membre = ?");
                $stmt->execute(array($this->id_oo));

                if ($insurance = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Add insurance data to the result array
                    $data['s2_insurance_name'] = $insurance['company_name'] ?? '';
                    $data['s2_insurance_contract'] = $insurance['contract_number'] ?? '';
                    $data['s2_insurance_green_card'] = $insurance['green_card_number'] ?? '';
                    $data['s2_insurance_valid_from'] = $insurance['valid_from'] ?? '';
                    $data['s2_insurance_valid_to'] = $insurance['valid_to'] ?? '';
                    $data['s2_insurance_agency'] = $insurance['agency_office'] ?? ''; // New field: agency/office/broker 
                    $data['s2_agency_name'] = $insurance['agency_name'] ?? '';
                    $data['s2_agency_address'] = $insurance['agency_address'] ?? '';
                    $data['s2_agency_country'] = $insurance['agency_country'] ?? '';
                    $data['s2_agency_phone'] = $insurance['agency_email'] ?? '';

                    error_log("Section2DataLoader: Successfully loaded insurance data for user ID: " . $this->id_oo);
                } else {
                    error_log("Section2DataLoader: No insurance data found for user ID: " . $this->id_oo);
                }
            } catch (Exception $e) {
                error_log("Error loading insurance data in Section2DataLoader: " . $e->getMessage());
            }

            // Load driver license data for User A
            try {
                $licenseStmt = $this->bdd->prepare("SELECT * FROM membres_driver_license WHERE id_membre = ?");
                $licenseStmt->execute(array($this->id_oo));
                if ($license = $licenseStmt->fetch(PDO::FETCH_ASSOC)) {
                    // Map driver license data to form fields
                    $data['s2_license_number'] = $license['license_number'] ?? '';
                    $data['s2_license_category'] = $license['license_category'] ?? '';
                    $data['s2_license_valid_until'] = $license['license_valid_until'] ?? '';

                    // Map additional driver fields from license data
                    // Use license country for driver country if available
                    if (!empty($license['license_country'])) {
                        $data['s2_driver_country'] = $license['license_country'];
                    }

                    // Use driver birthdate if stored in license data
                    if (!empty($license['license_issue_date'])) {
                        $data['s2_driver_birthdate'] = $license['license_issue_date'];
                    }

                    error_log("Section2DataLoader: Successfully loaded driver license data for user ID: " . $this->id_oo);
                } else {
                    error_log("Section2DataLoader: No driver license data found for user ID: " . $this->id_oo);
                }
            } catch (Exception $e) {
                error_log("Error loading driver license data in Section2DataLoader: " . $e->getMessage());
            }

            return $data;
        } catch (Exception $e) {
            error_log("Error loading Section 2 data: " . $e->getMessage());
            return [];
        }
    }
}
