<?php
class VehicleApiService {
    private $apiKey;
    private $apiUrl;
    
    public function __construct() {
        $this->apiKey = '1044e55fec6d47076629b305ad396dcd'; // API key should be in a configuration file in production
        $this->apiUrl = 'https://api.apiplaqueimmatriculation.com/carte-grise';
    }
    
    public function lookupByRegistration($registration) {
        // Clean registration number
        $registration = strtoupper(trim(str_replace(' ', '', $registration)));
        
        // Basic validation
        if (empty($registration) || strlen($registration) < 2 || strlen($registration) > 10) {
            return ['status' => 400, 'message' => 'Numéro d\'immatriculation invalide'];
        }
        
        // Format API URL
        $url = $this->apiUrl . '?host_name=apiplaqueimmatriculation.com&immatriculation=' . 
               urlencode($registration) . '&token=' . $this->apiKey . '&format=json';
        
        // Initialize cURL session
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 10 seconds timeout
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Should be true in production with proper SSL certs
        
        // Execute cURL request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        // Handle cURL errors
        if ($response === false) {
            return [
                'status' => 500, 
                'message' => 'Erreur de connexion à l\'API: ' . $curlError
            ];
        }
        
        // Handle HTTP errors
        if ($httpCode !== 200) {
            return [
                'status' => $httpCode,
                'message' => 'L\'API a retourné une erreur: ' . $httpCode
            ];
        }
        
        // Parse JSON response
        $data = json_decode($response, true);
        
        // Handle JSON parsing errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'status' => 500,
                'message' => 'Erreur de traitement des données de l\'API'
            ];
        }
        
        // Handle API errors
        if (isset($data['error']) || !isset($data['data'])) {
            return [
                'status' => 400,
                'message' => $data['message'] ?? 'Aucune donnée trouvée pour cette immatriculation'
            ];
        }
        
        // Map API response to our data format
        $vehicleData = $this->mapApiDataToVehicle($data['data'], $registration);
        
        return [
            'status' => 200,
            'data' => $vehicleData
        ];
    }
    
    private function mapApiDataToVehicle($apiData, $registration) {
        // Create formatted date for first registration date
        $date1erCir = isset($apiData['date_mec']) && !empty($apiData['date_mec']) ?
            date('Y-m-d', strtotime($apiData['date_mec'])) : '';
            
        return [
            'immat' => $registration,
            'marque' => $apiData['marque'] ?? '',
            'modele' => $apiData['modele'] ?? '',
            'date1erCir_fr' => $date1erCir,
            'date1erCir_us' => $date1erCir,
            'energieNGC' => $this->mapEnergie($apiData['energie'] ?? ''),
            'couleur' => $apiData['couleur'] ?? '',
            'puisFisc' => $apiData['puissance_fiscale'] ?? '',
            'boite_vitesse' => isset($apiData['boite']) ? ($apiData['boite'] === 'M' ? 'Manuelle' : 'Automatique') : '',
            'nb_portes' => $apiData['nombre_de_portes'] ?? '',
            'nr_passagers' => $apiData['nombre_de_places'] ?? '',
            'source' => 'api'
        ];
    }
    
    private function mapEnergie($energie) {
        // Map API energy codes to our energy values
        $mapping = [
            'ES' => 'Essence',
            'GO' => 'Diesel',
            'EL' => 'Électrique',
            'HH' => 'Hybride',
            'GP' => 'GPL'
        ];
        
        return $mapping[$energie] ?? $energie;
    }
}
?>