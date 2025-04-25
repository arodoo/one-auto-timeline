<?php
ob_start();
// INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    global $id_oo; // Declare the global variable
    $data = json_decode(file_get_contents('php://input'), true);

    $required_fields = [
        "immat", "co2", "energie", "energieNGC", "genreVCG", "genreVCGNGC",
        "puisFisc", "carrosserieCG", "marque", "modele", "date1erCir_us", "date1erCir_fr",
        "collection", "date30", "vin", "boite_vitesse", "puisFiscReel", "nr_passagers",
        "nb_portes", "type_mine", "couleur", "poids", "cylindres", "sra_id", "sra_group",
        "sra_commercial", "code_moteur", "k_type", "date_dernier_control_tecnique"
    ];

    $missing_fields = [];

    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (empty($missing_fields)) {
        $data['date_dernier_control_tecnique'] = strtotime($data['date_dernier_control_tecnique']);
        try {
            // Check if the record exists
            $check_sql = "SELECT COUNT(*) FROM membres_profil_auto WHERE immat = :immat";
            $check_stmt = $bdd->prepare($check_sql);
            $check_stmt->execute([':immat' => $data['immat']]);
            $record_exists = $check_stmt->fetchColumn() > 0;

            if ($data['userIsChanguingImmat']) {
                // Delete all records for the current user
                $delete_all_sql = "DELETE FROM membres_profil_auto WHERE id_membre = :id_membre";
                $delete_all_stmt = $bdd->prepare($delete_all_sql);
                $delete_all_stmt->execute([':id_membre' => $id_oo]);
                $record_exists = false;
            }

            if ($record_exists) {
                // Update the existing record
                $sql = "UPDATE membres_profil_auto SET
                    pseudo = :pseudo, annee_mise_en_circulation = :annee_mise_en_circulation, marque = :marque, modele = :modele, 
                    chevau_dynamique = :chevau_dynamique, cheveau_fiscaux = :cheveau_fiscaux, couleur = :couleur, boite = :boite, 
                    carburant = :carburant, description = :description, erreur = :erreur, co2 = :co2, energie = :energie, 
                    energieNGC = :energieNGC, genreVCG = :genreVCG, genreVCGNGC = :genreVCGNGC, puisFisc = :puisFisc, 
                    carrosserieCG = :carrosserieCG, date1erCir_us = :date1erCir_us, date1erCir_fr = :date1erCir_fr, collection = :collection, 
                    date30 = :date30, vin = :vin, boite_vitesse = :boite_vitesse, puisFiscReel = :puisFiscReel, nr_passagers = :nr_passagers, 
                    nb_portes = :nb_portes, type_mine = :type_mine, poids = :poids, cylindres = :cylindres, sra_id = :sra_id, 
                    sra_group = :sra_group, sra_commercial = :sra_commercial, logo_marque = :logo_marque, code_moteur = :code_moteur, 
                    k_type = :k_type, db_c = :db_c, date_dernier_control_tecnique = :date_dernier_control_tecnique, nbr_req_restants = :nbr_req_restants
                    WHERE immat = :immat";
            } else {
                // Insert a new record
                $sql = "INSERT INTO membres_profil_auto (
                    id_membre, pseudo, annee_mise_en_circulation, marque, modele, chevau_dynamique, cheveau_fiscaux, 
                    couleur, boite, carburant, description, immat, erreur, co2, energie, energieNGC, 
                    genreVCG, genreVCGNGC, puisFisc, carrosserieCG, date1erCir_us, date1erCir_fr, collection, 
                    date30, vin, boite_vitesse, puisFiscReel, nr_passagers, nb_portes, type_mine, poids, 
                    cylindres, sra_id, sra_group, sra_commercial, logo_marque, code_moteur, k_type, db_c, date_dernier_control_tecnique, nbr_req_restants
                ) VALUES (
                    :id_membre, :pseudo, :annee_mise_en_circulation, :marque, :modele, :chevau_dynamique, :cheveau_fiscaux, 
                    :couleur, :boite, :carburant, :description, :immat, :erreur, :co2, :energie, :energieNGC, 
                    :genreVCG, :genreVCGNGC, :puisFisc, :carrosserieCG, :date1erCir_us, :date1erCir_fr, :collection, 
                    :date30, :vin, :boite_vitesse, :puisFiscReel, :nr_passagers, :nb_portes, :type_mine, :poids, 
                    :cylindres, :sra_id, :sra_group, :sra_commercial, :logo_marque, :code_moteur, :k_type, :db_c, :date_dernier_control_tecnique, :nbr_req_restants
                )";
            }

            $stmt = $bdd->prepare($sql);
            $params = [
                ':pseudo' => $user,
                ':annee_mise_en_circulation' => $data['date1erCir_us'],
                ':marque' => $data['marque'],
                ':modele' => $data['modele'],
                ':chevau_dynamique' => $data['puisFiscReel'],
                ':cheveau_fiscaux' => $data['puisFisc'],
                ':couleur' => $data['couleur'],
                ':boite' => $data['boite_vitesse'],
                ':carburant' => $data['energieNGC'],
                ':description' => '',
                ':immat' => $data['immat'],
                ':erreur' => $data['erreur'] ?? '',
                ':co2' => $data['co2'],
                ':energie' => $data['energie'],
                ':energieNGC' => $data['energieNGC'],
                ':genreVCG' => $data['genreVCG'],
                ':genreVCGNGC' => $data['genreVCGNGC'],
                ':puisFisc' => $data['puisFisc'],
                ':carrosserieCG' => $data['carrosserieCG'],
                ':date1erCir_us' => $data['date1erCir_us'],
                ':date1erCir_fr' => $data['date1erCir_fr'],
                ':collection' => $data['collection'],
                ':date30' => $data['date30'],
                ':vin' => $data['vin'],
                ':boite_vitesse' => $data['boite_vitesse'],
                ':puisFiscReel' => $data['puisFiscReel'],
                ':nr_passagers' => $data['nr_passagers'],
                ':nb_portes' => $data['nb_portes'],
                ':type_mine' => $data['type_mine'],
                ':poids' => $data['poids'],
                ':cylindres' => $data['cylindres'],
                ':sra_id' => $data['sra_id'],
                ':sra_group' => $data['sra_group'],
                ':sra_commercial' => $data['sra_commercial'],
                ':logo_marque' => $data['logo_marque'] ?? '',
                ':code_moteur' => $data['code_moteur'],
                ':k_type' => $data['k_type'],
                ':db_c' => $data['db_c'],
                ':date_dernier_control_tecnique' => $data['date_dernier_control_tecnique'],
                ':nbr_req_restants' => $data['nbr_req_restants'] ?? 0
            ];

            if (!$record_exists) {
                $params[':id_membre'] = $id_oo;
            }

            $stmt->execute($params);

            $response = [
                'status' => 200,
                'message' => $record_exists ? 'Informations mises à jour avec succès' : 'Informations enregistrées avec succès'
            ];
        } catch (PDOException $e) {
            $response = [
                'status' => 500,
                'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
            ];
        }
    } else {
        $response = [
            'status' => 400,
            'message' => 'Certains champs sont manquants',
            'missingFields' => $missing_fields
        ];
    }

    echo json_encode($response);
}
ob_end_flush();
?>
