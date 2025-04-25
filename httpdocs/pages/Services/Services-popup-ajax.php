<?php
ob_start();
header('Content-Type: application/json');
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $objet_de_la_demande = $_POST['objet_de_la_demande'];
    $description_de_la_demande = $_POST['description_de_la_demande'];
    $id_membre_utilisateur = $id_oo;
    $id_service = $_POST['id_service'];
    $date_demande = time();
    $date_statut = time();
    $statut = "Non traité";


    $query_service = "SELECT id_membre FROM membres_services WHERE id = ?";
    $stmt_service = $bdd->prepare($query_service);
    $stmt_service->execute([$id_service]);
    $service = $stmt_service->fetch();
    $id_membre_depanneur = $service['id_membre'];


    $query_check = "SELECT id FROM membres_devis WHERE membres_annonces_clients_id = ?";
    $stmt_check = $bdd->prepare($query_check);
    $stmt_check->execute([$id_service]);
    $existing_devis = $stmt_check->fetch();



    $query_devis = "INSERT INTO membres_devis (id_membre_utilisateur, id_membre_depanneur, membres_annonces_clients_id, objet_de_la_demande, description_de_la_demande, date_demande, date_statut, type, statut_devis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_devis = $bdd->prepare($query_devis);
    $stmt_devis->execute([$id_membre_utilisateur, $id_membre_depanneur, $id_service, $objet_de_la_demande, $description_de_la_demande, $date_demande, $date_statut, 'service', $statut]);

    $response['retour_validation'] = 'ok';
    $response['Texte_rapport'] = 'Demande ajoutée avec succès.';
} else {
    $response['retour_validation'] = 'error';
    $response['Texte_rapport'] = 'Requête invalide.';
}

echo json_encode($response);
