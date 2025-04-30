<?php
class VehicleModel {
    private $bdd;
    
    public function __construct($bdd) {
        $this->bdd = $bdd;
    }
    
    public function findAllByUserId($userId) {
        $stmt = $this->bdd->prepare("SELECT * FROM membres_profil_auto WHERE id_membre = ? ORDER BY id DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findById($id, $userId) {
        $stmt = $this->bdd->prepare("SELECT * FROM membres_profil_auto WHERE id = ? AND id_membre = ?");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function save($data, $userId) {
        // Check if we're updating or inserting
        if (isset($data['vehicle_id']) && !empty($data['vehicle_id'])) {
            return $this->update($data, $userId);
        } else {
            return $this->insert($data, $userId);
        }
    }
    
    private function insert($data, $userId) {
        try {
            $sql = "INSERT INTO membres_profil_auto (
                        id_membre, source, immat, marque, modele, date1erCir_fr, date1erCir_us, 
                        energieNGC, couleur, puisFisc, boite_vitesse, nb_portes, nr_passagers, 
                        date_dernier_control_tecnique
                    ) VALUES (
                        :id_membre, :source, :immat, :marque, :modele, :date1erCir_fr, :date1erCir_us, 
                        :energieNGC, :couleur, :puisFisc, :boite_vitesse, :nb_portes, :nr_passagers,
                        :date_dernier_control_tecnique
                    )";
                    
            $stmt = $this->bdd->prepare($sql);
            $stmt->bindParam(':id_membre', $userId);
            $stmt->bindParam(':source', $data['source']);
            $stmt->bindParam(':immat', $data['immat']);
            $stmt->bindParam(':marque', $data['marque']);
            $stmt->bindParam(':modele', $data['modele']);
            $stmt->bindParam(':date1erCir_fr', $data['date1erCir_fr']);
            $stmt->bindParam(':date1erCir_us', $data['date1erCir_us'] ?? $data['date1erCir_fr']);
            $stmt->bindParam(':energieNGC', $data['energieNGC']);
            $stmt->bindParam(':couleur', $data['couleur']);
            $stmt->bindParam(':puisFisc', $data['puisFisc']);
            $stmt->bindParam(':boite_vitesse', $data['boite_vitesse']);
            $stmt->bindParam(':nb_portes', $data['nb_portes']);
            $stmt->bindParam(':nr_passagers', $data['nr_passagers']);
            $stmt->bindParam(':date_dernier_control_tecnique', $data['date_dernier_control_tecnique']);
            
            $stmt->execute();
            
            $vehicleId = $this->bdd->lastInsertId();
            
            return [
                'status' => 200,
                'message' => 'Véhicule ajouté avec succès',
                'id' => $vehicleId
            ];
        } catch (PDOException $e) {
            return [
                'status' => 500,
                'message' => 'Erreur lors de l\'enregistrement du véhicule: ' . $e->getMessage()
            ];
        }
    }
    
    private function update($data, $userId) {
        try {
            // First verify the vehicle belongs to this user
            $stmt = $this->bdd->prepare("SELECT id FROM membres_profil_auto WHERE id = ? AND id_membre = ?");
            $stmt->execute([$data['vehicle_id'], $userId]);
            
            if (!$stmt->fetch()) {
                return [
                    'status' => 403,
                    'message' => 'Vous n\'êtes pas autorisé à modifier ce véhicule'
                ];
            }
            
            $sql = "UPDATE membres_profil_auto SET
                        source = :source,
                        immat = :immat,
                        marque = :marque,
                        modele = :modele,
                        date1erCir_fr = :date1erCir_fr,
                        date1erCir_us = :date1erCir_us,
                        energieNGC = :energieNGC,
                        couleur = :couleur,
                        puisFisc = :puisFisc,
                        boite_vitesse = :boite_vitesse,
                        nb_portes = :nb_portes,
                        nr_passagers = :nr_passagers,
                        date_dernier_control_tecnique = :date_dernier_control_tecnique
                    WHERE id = :id AND id_membre = :id_membre";
                    
            $stmt = $this->bdd->prepare($sql);
            $stmt->bindParam(':id', $data['vehicle_id']);
            $stmt->bindParam(':id_membre', $userId);
            $stmt->bindParam(':source', $data['source']);
            $stmt->bindParam(':immat', $data['immat']);
            $stmt->bindParam(':marque', $data['marque']);
            $stmt->bindParam(':modele', $data['modele']);
            $stmt->bindParam(':date1erCir_fr', $data['date1erCir_fr']);
            $stmt->bindParam(':date1erCir_us', $data['date1erCir_us'] ?? $data['date1erCir_fr']);
            $stmt->bindParam(':energieNGC', $data['energieNGC']);
            $stmt->bindParam(':couleur', $data['couleur']);
            $stmt->bindParam(':puisFisc', $data['puisFisc']);
            $stmt->bindParam(':boite_vitesse', $data['boite_vitesse']);
            $stmt->bindParam(':nb_portes', $data['nb_portes']);
            $stmt->bindParam(':nr_passagers', $data['nr_passagers']);
            $stmt->bindParam(':date_dernier_control_tecnique', $data['date_dernier_control_tecnique']);
            
            $stmt->execute();
            
            return [
                'status' => 200,
                'message' => 'Véhicule mis à jour avec succès',
                'id' => $data['vehicle_id']
            ];
        } catch (PDOException $e) {
            return [
                'status' => 500,
                'message' => 'Erreur lors de la mise à jour du véhicule: ' . $e->getMessage()
            ];
        }
    }
    
    public function delete($id, $userId) {
        try {
            $stmt = $this->bdd->prepare("DELETE FROM membres_profil_auto WHERE id = ? AND id_membre = ?");
            $stmt->execute([$id, $userId]);
            
            // If any row was affected, the delete was successful
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // Log error
            error_log("Error deleting vehicle: " . $e->getMessage());
            return false;
        }
    }
}
?>