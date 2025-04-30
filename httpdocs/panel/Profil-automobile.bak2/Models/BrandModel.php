<?php
class BrandModel {
    private $bdd;
    
    public function __construct($bdd) {
        $this->bdd = $bdd;
    }
    
    public function getAllBrands() {
        $stmt = $this->bdd->prepare("SELECT DISTINCT rappel_marque FROM configurations_modeles ORDER BY rappel_marque ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function getModelsByBrand($brand) {
        $stmt = $this->bdd->prepare("SELECT DISTINCT nom_modele FROM configurations_modeles WHERE rappel_marque = ? ORDER BY nom_modele ASC");
        $stmt->execute([$brand]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>