<?php
ob_start();
header('Content-Type: application/json');

session_start();

require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['type_vehicule'])) {
        $assemblyGroupType = $input['type_vehicule'];
        $data = [
            "getArticles" => [
                "articleCountry" => "FR",
                "provider" => $provider_oo, 
                "lang" => "fr",
                "perPage" => 0,
                "page" => 1,
                "assemblyGroupFacetOptions" => [
                    "enabled" => true,
                    "assemblyGroupType" => $assemblyGroupType,
                    "includeCompleteTree" => true
                ]
            ]
        ];
    }
  
    else if (isset($input['assemblyGroupNodeIds']) && isset($input['searchQuery'])) {
        $assemblyGroupNodeIds = $input['assemblyGroupNodeIds'];
        $searchQuery = $input['searchQuery'];
        $data = [
            "getArticles" => [
                "articleCountry" => "FR",
                "provider" => $provider, 
                "searchQuery" => $searchQuery,
                "searchType" => 0,
                "assemblyGroupNodeIds" => $assemblyGroupNodeIds,
                "lang" => "fr",
                "perPage" => 100,
                "page" => 1,
                "includeAll" => true
            ]
        ];
    }

    else if (isset($input['assemblyGroupNodeIds'])) {
        $assemblyGroupNodeIds = $input['assemblyGroupNodeIds'];
        $_SESSION['assemblyGroupNodeIds'] = $assemblyGroupNodeIds; // Save the assemblyGroupNodeIds in the session
        $data = [
            "getArticles" => [
                "articleCountry" => "FR",
                "provider" => $provider, 
                "assemblyGroupNodeIds" => $assemblyGroupNodeIds,
                "lang" => "fr",
                "perPage" => 100,
                "page" => 1,
                "includeAll" => true
            ]
        ];
    }

    else {
        header("Content-Type: application/json");
        echo json_encode(["error" => "Aucune donnée valide envoyée"]);
        exit;
    }

    $dataJson = json_encode($data);

    $ch = curl_init($urlTecalliance);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "X-Api-Key: $apiKeyTech",
        "Content-Length: " . strlen($dataJson)
    ]);

    $response = curl_exec($ch);
    $error = curl_errno($ch) ? curl_error($ch) : null;
    curl_close($ch);

    header("Content-Type: application/json");
    echo json_encode([
        "response" => json_decode($response, true),
        "error" => $error
    ]);
    exit;
}
