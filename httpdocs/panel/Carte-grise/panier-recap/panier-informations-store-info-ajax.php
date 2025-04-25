<?php
ob_start();
// INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

$dir_fonction = "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    $data = json_decode(file_get_contents('php://input'), true);

    //static data
    $id_membre = $id_oo;
    $id_pseudo = $pseudo_oo;
    $date_commande = time();
    $departement = substr($data['codePostal'], 0, 2);
    $numero_transaction = uniqid('TXN', true);
    $statut = 'non traitee';
    $statut_paiement = 'non paye';
    $date_statut = time();
    $date_vente = $date_commande;
    $heure_vente = date('H:i:s', $date_commande);


    $price = $data['price'] ?? NULL;

    $montant_commande_sans_frais = $data['montant_commande_sans_frais'] ?? NULL;
    $montant_commande_frais = $data['montant_commande_frais'] ?? NULL;
    $commission = $data['commission'] ?? NULL;

    $process = $data['process'] ?? NULL;
    $etatVehicule = $data['etatVehicule'] ?? NULL;
    $immatriculation = $data['immatriculation'] ?? NULL;
    $genre = $data['genre'] ?? NULL;
    $carburant = $data['carburant'] ?? NULL;
    $dateCirculation = isset($data['dateCirculation']) ? strtotime($data['dateCirculation']) : NULL;
    $emissionCO2 = $data['emissionCO2'] ?? NULL;
    $chevauxFiscaux = $data['chevauxFiscaux'] ?? NULL;
    $leasing = $data['leasing'] ?? NULL;
    $handicap = $data['handicap'] ?? NULL;
    $succession = $data['succession'] ?? NULL;
    $techChange = $data['techChange'] ?? NULL;
    $email = $data['email'] ?? NULL;
    $codePostal = $data['codePostal'] ?? NULL;
    $acceptPolicy = $data['acceptPolicy'] == 1 ? 'oui' : 'non';
    $nouvTitAcceptPolicy = $data['nouvTitAcceptPolicy'] == 1 ? 'oui' : 'non';
    $nouvTitTypeContact = $data['nouvTitTypeContact'] ?? NULL;
    $nouvTitCivilite = $data['nouvTitCivilite'] ?? NULL;
    $nouvTitNom = $data['nouvTitNom'] ?? NULL;
    $nouvTitPrenom = $data['nouvTitPrenom'] ?? NULL;
    $nouvTitNomUsage = $data['nouvTitNomUsage'] ?? NULL;
    $nouvTitAdresse = $data['nouvTitAdresse'] ?? NULL;
    $nouvTitVille = $data['nouvTitVille'] ?? NULL;
    $nouvTitPays = $data['nouvTitPays'] ?? NULL;
    $nouvTitTelephone = $data['nouvTitTelephone'] ?? NULL;
    $nouvTitRaisonSociale = $data['nouvTitRaisonSociale'] ?? NULL;
    $nouvTitSiret = $data['nouvTitSiret'] ?? NULL;
    $nouvTitComplementAdresse = $data['nouvTitComplementAdresse'] ?? NULL;
    $nouvTitCodePostal = $data['nouvTitCodePostal'] ?? NULL;
    $nouvTitCotitulaires = array_filter($data['nouvTitCotitulaires'] ?? [], function ($cotitulaire) {
        return !empty($cotitulaire['nouvTitCotTypeContact']) || !empty($cotitulaire['nouvTitCotNom']) || !empty($cotitulaire['nouvTitCotPrenom']) || !empty($cotitulaire['nouvTitCotNomUsage']) || !empty($cotitulaire['nouvTitCotRaisonSociale']) || !empty($cotitulaire['nouvTitCotNoSiret']);
    });

    // Get acceptCGV value from the data
    $acceptCGV = $data['acceptCGV'] == 1 ? 'oui' : 'non';
    
    // Validate fields
    $isValid = true;
    $invalidFields = [];

    // Validate accept CGV
    /* if (!$acceptCGV) {
        $isValid = false;
        $invalidFields[] = 'Vous devez accepter les conditions générales de vente.';
    } */

    if ($nouvTitTypeContact === 'particulier') {
        $nouvTitRaisonSociale = NULL;
        $nouvTitSiret = NULL;
        $nouvTitAdresse = NULL;
    }
    if ($nouvTitTypeContact === 'Entreprise') {
        if (empty($nouvTitAdresse)) {
            $isValid = false;
            $invalidFields[] = 'Le champ Adresse est obligatoire';
        }
        if (empty($nouvTitRaisonSociale)) {
            $isValid = false;
            $invalidFields[] = 'Le champ Raison Sociale est obligatoire';
        }
        if (empty($nouvTitSiret)) {
            $isValid = false;
            $invalidFields[] = 'Le champ SIRET est obligatoire';
        }
    }
    if (empty($nouvTitNom)) {
        $isValid = false;
        $invalidFields[] = 'Le champ Nom est obligatoire';
    }
    if (empty($nouvTitPrenom)) {
        $isValid = false;
        $invalidFields[] = 'Le champ Prénom est obligatoire';
    }
    if (empty($nouvTitNomUsage)) {
        $isValid = false;
        $invalidFields[] = 'Le champ Nom d\'usage est obligatoire';
    }
    if (empty($nouvTitComplementAdresse)) {
        $isValid = false;
        $invalidFields[] = 'Le champ Complement Adresse est obligatoire';
    }
    if (empty($nouvTitVille)) {
        $isValid = false;
        $invalidFields[] = 'Le champ Ville est obligatoire';
    }
    // Validate codePostal format (example: 5 digits) or check if empty
    if (empty($nouvTitCodePostal)) {
        $isValid = false;
        $invalidFields[] = 'Le champ Code postal est obligatoire';
    } elseif (!preg_match('/^\d{5}$/', $nouvTitCodePostal)) {
        $isValid = false;
        $invalidFields[] = 'Le code postal doit comporter exactement 5 chiffres.';
    }
    // Validate telephone format (example: 10 digits) or check if empty
    if (empty($nouvTitTelephone)) {
        $isValid = false;
        $invalidFields[] = 'Le champ Téléphone est obligatoire';
    } elseif (!preg_match('/^\d{10}$/', $nouvTitTelephone)) {
        $isValid = false;
        $invalidFields[] = 'Le numéro de téléphone doit comporter exactement 10 chiffres.';
    }
    // Validate policy
    if (!$nouvTitAcceptPolicy) {
        $isValid = false;
        $invalidFields[] = 'Vous devez accepter les conditions générales.';
    }

    // Validate cotitulaires
    foreach ($nouvTitCotitulaires as $uniqueId => $cotitulaire) {
        // Ignore empty cotitulaires
        if (empty($cotitulaire['nouvTitCotTypeContact']) && empty($cotitulaire['nouvTitCotNom']) && empty($cotitulaire['nouvTitCotPrenom']) && empty($cotitulaire['nouvTitCotNomUsage']) && empty($cotitulaire['nouvTitCotRaisonSociale']) && empty($cotitulaire['nouvTitCotNoSiret'])) {
            continue;
        }

        if (strpos($cotitulaire['nouvTitCotTypeContact'], 'particulierCotitulaire_') !== false) {
            $cotitulaire['nouvTitCotTypeContact'] = 'particulier';
            if (empty($cotitulaire['nouvTitCotTypeContact']) || empty($cotitulaire['nouvTitCotNom']) || empty($cotitulaire['nouvTitCotPrenom']) || empty($cotitulaire['nouvTitCotNomUsage'])) {
                $isValid = false;
                $invalidFields[] = 'Tous les champs du cotitulaire sont obligatoires';
                break;
            }
        } elseif (strpos($cotitulaire['nouvTitCotTypeContact'], 'entrepriseCotitulaire_') !== false) {
            $cotitulaire['nouvTitCotTypeContact'] = 'entreprise';
            if (empty($cotitulaire['nouvTitCotRaisonSociale']) || empty($cotitulaire['nouvTitCotNoSiret'])) {
                $isValid = false;
                $invalidFields[] = 'Tous les champs du cotitulaire sont obligatoires';
                break;
            }
        }

        // Clear the fields according to the type of contact
        if ($cotitulaire['nouvTitCotTypeContact'] === 'particulier') {
            // Set entreprise fields to empty
            $cotitulaire['nouvTitCotRaisonSociale'] = NULL;
            $cotitulaire['nouvTitCotNoSiret'] = NULL;
        } elseif ($cotitulaire['nouvTitCotTypeContact'] === 'entreprise') {
            // Set particulier fields to empty
            $cotitulaire['nouvTitCotNom'] = NULL;
            $cotitulaire['nouvTitCotPrenom'] = NULL;
            $cotitulaire['nouvTitCotNomUsage'] = NULL;
        }
    }

    if ($isValid) {
        try {
            // Insert into membres_cartes_grise
            $sql = "INSERT INTO membres_cartes_grise (
            id_membre, pseudo, date_commande, departement, numero_transaction, statut,
            statut_paiement, date_statut, date_vente, heure_vente, montant_commande_sans_frais, montant_commande_frais, montant_commission,
            process, etatVehicule, immatriculation, genre, carburant, dateCirculation, 
            emissionCO2, chevauxFiscaux, leasing, handicap, succession, techChange, 
            email, codePostal, acceptPolicy, nouvTitTypeContact, nouvTitCivilite, 
            nouvTitNom, nouvTitPrenom, nouvTitNomUsage, nouvTitComplementAdresse, 
            nouvTitVille, nouvTitPays, nouvTitTelephone, nouvTitRaisonSociale, 
            nouvTitSiret, nouvTitAdresse, nouvTitCodePostal, nouvTitAcceptPolicy
            ) VALUES (
            :id_membre, :pseudo, :date_commande, :departement, :numero_transaction, :statut,
            :statut_paiement, :date_statut, :date_vente, :heure_vente, :montant_commande_sans_frais, :montant_commande_frais, :montant_commission,
            :process, :etatVehicule, :immatriculation, :genre, :carburant, :dateCirculation, 
            :emissionCO2, :chevauxFiscaux, :leasing, :handicap, :succession, :techChange, 
            :email, :codePostal, :acceptPolicy, :nouvTitTypeContact, :nouvTitCivilite, 
            :nouvTitNom, :nouvTitPrenom, :nouvTitNomUsage, :nouvTitComplementAdresse, 
            :nouvTitVille, :nouvTitPays, :nouvTitTelephone, :nouvTitRaisonSociale, 
            :nouvTitSiret, :nouvTitAdresse, :nouvTitCodePostal, :nouvTitAcceptPolicy
            )";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([
            ':id_membre' => $id_membre,
            ':pseudo' => $id_pseudo,
            ':date_commande' => $date_commande,
            ':departement' => $departement,
            ':numero_transaction' => $numero_transaction,
            ':statut' => $statut,
            ':statut_paiement' => $statut_paiement,
            ':date_statut' => $date_statut,
            ':date_vente' => $date_vente,
            ':heure_vente' => $heure_vente,
            ':montant_commande_sans_frais' => $montant_commande_sans_frais,
            ':montant_commande_frais' => $montant_commande_frais,
            ':montant_commission' => $commission,
            ':process' => $process,
            ':etatVehicule' => $etatVehicule,
            ':immatriculation' => $immatriculation,
            ':genre' => $genre,
            ':carburant' => $carburant,
            ':dateCirculation' => $dateCirculation,
            ':emissionCO2' => $emissionCO2,
            ':chevauxFiscaux' => $chevauxFiscaux,
            ':leasing' => $leasing,
            ':handicap' => $handicap,
            ':succession' => $succession,
            ':techChange' => $techChange,
            ':email' => $email,
            ':codePostal' => $codePostal,
            ':acceptPolicy' => $acceptPolicy,
            ':nouvTitTypeContact' => $nouvTitTypeContact,
            ':nouvTitCivilite' => $nouvTitCivilite,
            ':nouvTitNom' => $nouvTitNom,
            ':nouvTitPrenom' => $nouvTitPrenom,
            ':nouvTitNomUsage' => $nouvTitNomUsage,
            ':nouvTitComplementAdresse' => $nouvTitComplementAdresse,
            ':nouvTitVille' => $nouvTitVille,
            ':nouvTitPays' => $nouvTitPays,
            ':nouvTitTelephone' => $nouvTitTelephone,
            ':nouvTitRaisonSociale' => $nouvTitRaisonSociale,
            ':nouvTitSiret' => $nouvTitSiret,
            ':nouvTitAdresse' => $nouvTitAdresse,
            ':nouvTitCodePostal' => $nouvTitCodePostal,
            ':nouvTitAcceptPolicy' => $nouvTitAcceptPolicy
            ]);

            // Get the inserted ID
            $id_carte_grise = $bdd->lastInsertId();

            // Insert into membres_cartes_grise_cotitulaires
            $sql = "INSERT INTO membres_cartes_grise_cotitulaires (id_carte_grise, typeContact, nom, prenom, nomUsage, raisonSociale, noSiret) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $bdd->prepare($sql);
            foreach ($nouvTitCotitulaires as $cotitulaire) {
                $typeContact = strpos($cotitulaire['nouvTitCotTypeContact'], 'entreprise') !== false ? 'entreprise' : 'particulier';
                $stmt->execute([$id_carte_grise, $typeContact, $cotitulaire['nouvTitCotNom'], $cotitulaire['nouvTitCotPrenom'], $cotitulaire['nouvTitCotNomUsage'], $cotitulaire['nouvTitCotRaisonSociale'], $cotitulaire['nouvTitCotNoSiret']]);
            }

            // Return the inserted ID as JSON response
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['id_carte_grise' => $id_carte_grise]);

        } catch (PDOException $e) {

            // Return the error as JSON response
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'Error inserting into the database: ' . $e->getMessage()]);
        }
    } else {
        // Return only the first invalid field as JSON response
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['invalidFields' => [$invalidFields[0]]]);
    }
}
?>