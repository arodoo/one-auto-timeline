<?php
// filepath: panel/Profil-automobile/models/VehicleAPIModel.php
require_once 'BaseModel.php';

class VehicleAPIModel extends BaseModel {
    private $api_key = '1044e55fec6d47076629b305ad396dcd';
    private $host_name = 'apiplaqueimmatriculation.com';
    private $format = 'json';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function fetchVehicleInfo($immatriculation) {
        $api_url = "https://api.apiplaqueimmatriculation.com/carte-grise?host_name={$this->host_name}&immatriculation={$immatriculation}&token={$this->api_key}&format={$this->format}";
        
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        
        if ($error) {
            return [
                'status' => 500,
                'message' => 'Erreur API: ' . $error
            ];
        }
        
        if ($info['http_code'] != 200) {
            return [
                'status' => $info['http_code'],
                'message' => 'Erreur API: Code HTTP ' . $info['http_code']
            ];
        }
        
        $data = json_decode($response, true);
        
        // Check if API returned an error
        if (isset($data['error']) && $data['error']) {
            return [
                'status' => 400,
                'message' => $data['error_message'] ?? 'Erreur lors de la recherche du véhicule'
            ];
        }
        
        return [
            'status' => 200,
            'data' => $data
        ];
    }
    
    // Transform API data to database format
    public function transformToDbFormat($apiData) {
        $data = $apiData['data'];
        
        return [
            'immat' => $data['immat'] ?? '',
            'co2' => $data['co2'] ?? '',
            'energie' => $data['energie'] ?? '',
            'energieNGC' => $data['energieNGC'] ?? '',
            'genreVCG' => $data['genreVCG'] ?? '',
            'genreVCGNGC' => $data['genreVCGNGC'] ?? '',
            'puisFisc' => $data['puisFisc'] ?? '',
            'carrosserieCG' => $data['carrosserieCG'] ?? '',
            'marque' => $data['marque'] ?? '',
            'modele' => $data['modele'] ?? '',
            'date1erCir_us' => $data['date1erCir_us'] ?? '',
            'date1erCir_fr' => $data['date1erCir_fr'] ?? '',
            'collection' => $data['collection'] ?? '',
            'date30' => $data['date30'] ?? '',
            'vin' => $data['vin'] ?? '',
            'boite_vitesse' => $data['boite_vitesse'] ?? '',
            'puisFiscReel' => $data['puisFiscReel'] ?? '',
            'nr_passagers' => $data['nr_passagers'] ?? '',
            'nb_portes' => $data['nb_portes'] ?? '',
            'type_mine' => $data['type_mine'] ?? '',
            'couleur' => $data['couleur'] ?? '',
            'poids' => $data['poids'] ?? '',
            'cylindres' => $data['cylindres'] ?? '',
            'sra_id' => $data['sra_id'] ?? '',
            'sra_group' => $data['sra_group'] ?? '',
            'sra_commercial' => $data['sra_commercial'] ?? '',
            'code_moteur' => $data['code_moteur'] ?? '',
            'k_type' => $data['k_type'] ?? '',
            'db_c' => $data['db_c'] ?? '',
            'erreur' => $data['erreur'] ?? '',
            'nbr_req_restants' => $data['nbr_req_restants'] ?? 0,
            'logo_marque' => $data['logo_marque'] ?? ''
        ];
    }
}