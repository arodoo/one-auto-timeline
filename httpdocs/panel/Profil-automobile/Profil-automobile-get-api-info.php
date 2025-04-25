<?php
header('Content-Type: application/json');

$immatriculation = $_POST['voir_immatriculation'] ?? null;

if (empty($immatriculation)) {
  $response = [
    'status' => 400,
    'message' => 'Veuillez renseigner l\'immatriculation'
  ];
  echo json_encode($response);
  exit;
}

$api_key = '1044e55fec6d47076629b305ad396dcd';
$host_name = 'apiplaqueimmatriculation.com';
$format = 'json';
$api_url = "https://api.apiplaqueimmatriculation.com/carte-grise?host_name=$host_name&immatriculation=$immatriculation&token=$api_key&format=$format";

$response = fetchVehicleInfo($api_url);

echo json_encode($response);

function fetchVehicleInfo($url)
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
  ]);

  $api_response = curl_exec($ch);
  if (curl_errno($ch)) {
    return [
      'status' => 500,
      'message' => 'Erreur de la requête API: ' . curl_error($ch)
    ];
  }
  curl_close($ch);

  $api_data = json_decode($api_response, true);

  if (isset($api_data['error']) || is_null($api_data)) {
    return [
      'status' => 400,
      'message' => 'Aucune information trouvée'
    ];
  }

  return [
    'status' => 200,
    'data' => $api_data
  ];
}
?>