<?php
require_once 'BaseModel.php';

class VehicleModel extends BaseModel {
    protected $table = 'membres_profil_auto';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function findByUser($userId) {
        return $this->findAll(['id_membre' => $userId], 'id DESC');
    }
    
    public function findByUserAndId($userId, $vehicleId) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id AND id_membre = :id_membre";
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute([
            ':id' => $vehicleId,
            ':id_membre' => $userId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function checkDuplicate($immat, $userId, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE immat = :immat AND id_membre = :id_membre";
        
        if ($excludeId) {
            $sql .= " AND id != :id";
        }
        
        $stmt = $this->bdd->prepare($sql);
        $params = [
            ':immat' => $immat,
            ':id_membre' => $userId
        ];
        
        if ($excludeId) {
            $params[':id'] = $excludeId;
        }
        
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    public function addVehicle($data, $userId) {
        $data['id_membre'] = $userId;
        $data['pseudo'] = $this->user;
        
        // Format date_dernier_control_technique if it exists
        if (!empty($data['date_dernier_control_tecnique'])) {
            $data['date_dernier_control_tecnique'] = strtotime($data['date_dernier_control_tecnique']);
        }
        
        return $this->insert($data);
    }
    
    public function updateVehicle($id, $data, $userId) {
        // Format date_dernier_control_technique if it exists
        if (!empty($data['date_dernier_control_tecnique'])) {
            $data['date_dernier_control_tecnique'] = strtotime($data['date_dernier_control_tecnique']);
        }
        
        // Check if vehicle belongs to user
        $vehicle = $this->findByUserAndId($userId, $id);
        if (!$vehicle) {
            return false;
        }
        
        return $this->update($id, $data);
    }
    
    public function deleteVehicle($id, $userId) {
        // Check if vehicle belongs to user
        $vehicle = $this->findByUserAndId($userId, $id);
        if (!$vehicle) {
            return false;
        }
        
        return $this->delete($id);
    }
    
    public function hasVehicles($userId) {
        return $this->count(['id_membre' => $userId]) > 0;
    }
    
    // Fetch only essential fields for listing
    public function getVehiclesList($userId) {
        $sql = "SELECT id, immat, marque, modele, date1erCir_fr, energieNGC, couleur 
                FROM {$this->table} 
                WHERE id_membre = :id_membre 
                ORDER BY id DESC";
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute([':id_membre' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}