<?php
class Section2DataLoader {
    private $bdd;
    private $id_oo;

    public function __construct($bdd, $id_oo) {
        $this->bdd = $bdd;
        $this->id_oo = $id_oo;
    }

    public function loadUserData() {
        try {
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

            return [
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
        } catch (Exception $e) {
            error_log("Error loading Section 2 data: " . $e->getMessage());
            return [];
        }
    }
}
