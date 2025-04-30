<?php
// filepath: panel/Profil-automobile/services/ProfileCompletionService.php
class ProfileCompletionService {
    private $bdd;
    private $id_oo;
    
    public function __construct() {
        global $bdd, $id_oo;
        $this->bdd = $bdd;
        $this->id_oo = $id_oo;
    }
    
    public function updateVehicleStatus($hasVehicles) {
        if ($hasVehicles) {
            // If vehicles exist, remove the flag
            if (isset($_SESSION['vehicles_missing'])) {
                unset($_SESSION['vehicles_missing']);
            }
        } else {
            // If no vehicles, set the flag
            $_SESSION['vehicles_missing'] = true;
        }
    }
    
    public function checkVehicleExists() {
        $stmt = $this->bdd->prepare("SELECT COUNT(*) FROM membres_profil_auto WHERE id_membre = :id_membre");
        $stmt->execute([':id_membre' => $this->id_oo]);
        return $stmt->fetchColumn() > 0;
    }
}