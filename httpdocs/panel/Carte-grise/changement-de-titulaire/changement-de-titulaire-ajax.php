<?php

// Capturar la entrada JSON
$data = json_decode(file_get_contents('php://input'), true);


$process = $data['process'] ?? '';
$etatVehicule = $data['etatVehicule'] ?? '';
$immatriculation = $data['immatriculation'] ?? '';
$genre = $data['genre'] ?? '';
$carburant = $data['carburant'] ?? '';
$dateCirculation = $data['dateCirculation'] ?? '';
$emissionCO2 = $data['emissionCO2'] ?? '';
$chevauxFiscaux = $data['chevauxFiscaux'] ?? '';
$leasing = $data['leasing'] ?? '';
$handicap = $data['handicap'] ?? '';
$succession = $data['succession'] ?? '';
$techChange = $data['techChange'] ?? '';
$email = $data['email'] ?? '';
$codePostal = $data['codePostal'] ?? '';
$acceptPolicy = $data['acceptPolicy'] ?? false;


$response = [
    'process' => $process,
    'etatVehicule' => $etatVehicule,
    'immatriculation' => $immatriculation,
    'genre' => $genre,
    'carburant' => $carburant,
    'dateCirculation' => $dateCirculation,
    'emissionCO2' => $emissionCO2,
    'chevauxFiscaux' => $chevauxFiscaux,
    'leasing' => $leasing,
    'handicap' => $handicap,
    'succession' => $succession,
    'techChange' => $techChange,
    'email' => $email,
    'codePostal' => $codePostal,
    'acceptPolicy' => $acceptPolicy
];


header('Content-Type: application/json');
echo json_encode($response);
?>