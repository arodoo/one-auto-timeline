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
    $selectedId = $_POST['selectedId'] ?? null;
    $articleNumber = $_POST['articleNumber'] ?? null;

    if ($selectedId && $articleNumber) {
        // Realiza tu consulta aquí
        // Ejemplo:
        $req_membre_produit = $bdd->prepare("SELECT * FROM membres_produits WHERE node_ids_api = ? AND id_produit_api = ?  AND statut = 'activé'");
        $req_membre_produit->execute([$selectedId, $articleNumber]);
        $produit_offre = $req_membre_produit->fetchAll(PDO::FETCH_ASSOC);
        $count_Offre = $req_membre_produit->rowCount(); // Conteo de filas
        $req_membre_produit->closeCursor();


        echo json_encode($count_Offre);
    } else {
        echo json_encode(['error' => 'Parámetros faltantes']);
    }
}
