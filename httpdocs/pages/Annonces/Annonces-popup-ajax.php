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

    // Obtener id_membre de membres_annonces
    $query_service = "SELECT id_membre FROM membres_annonces WHERE id = ?";
    $stmt_service = $bdd->prepare($query_service);
    $stmt_service->execute([$id_service]);
    $service = $stmt_service->fetch();
    $id_membre_depanneur = $service['id_membre'];

    // Buscar si membres_annonces_clients_id es igual a id_service
    $query_check = "SELECT id FROM membres_devis WHERE membres_annonces_clients_id = ?";
    $stmt_check = $bdd->prepare($query_check);
    $stmt_check->execute([$id_service]);
    $existing_devis = $stmt_check->fetch();

    if ($existing_devis) {
        // Si existe, hacer la modificación
        $idaction = $existing_devis['id'];
        $query = "UPDATE membres_devis SET objet_de_la_demande = ?, description_de_la_demande = ?, date_demande = ?, membres_annonces_clients_id = ?, date_statut = ?, id_membre_depanneur = ?, type = ?, statut_devis = ? WHERE id = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$objet_de_la_demande, $description_de_la_demande, $date_demande, $id_service, $date_statut, $id_membre_depanneur, 'annonce', $statut, $idaction]);
        $response['retour_validation'] = 'ok';
        $response['Texte_rapport'] = 'Demande mise à jour avec succès.';
    } else {
        // Si no existe, hacer la inserción
        $query_devis = "INSERT INTO membres_devis (id_membre_utilisateur, id_membre_depanneur, membres_annonces_clients_id, objet_de_la_demande, description_de_la_demande, date_demande, date_statut, type, statut_devis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_devis = $bdd->prepare($query_devis);
        $stmt_devis->execute([$id_membre_utilisateur, $id_membre_depanneur, $id_service, $objet_de_la_demande, $description_de_la_demande, $date_demande, $date_statut, 'annonce', $statut]);

        $response['retour_validation'] = 'ok';
        $response['Texte_rapport'] = 'Demande ajoutée avec succès.';
    }
} else {
    $response['retour_validation'] = 'error';
    $response['Texte_rapport'] = 'Requête invalide.';
}

echo json_encode($response);
?>
