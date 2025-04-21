<?php
// API endpoint to get constat details for modal display
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

// Include function file
$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

// Check if user is logged in
if (empty($_SESSION['4M8e7M5b1R2e8s']) || empty($user)) {
    header('HTTP/1.1 401 Unauthorized');
    echo 'Unauthorized access';
    exit;
}

// Check if id parameter is provided
if (empty($_GET['id'])) {
    header('HTTP/1.1 400 Bad Request');
    echo 'Missing constat ID';
    exit;
}

$constat_id = intval($_GET['id']);

try {
    // Get constat details
    $stmt = $bdd->prepare("
        SELECT cm.*, 
               DATE_FORMAT(cm.s1_accident_date, '%d/%m/%Y') as formatted_date,
               m.prenom, m.nom, m.mail as user_email, m.Telephone as user_phone,
               va.s2_vehicle_brand, va.s2_vehicle_plate, 
               va.s2_insurance_name, va.s2_insurance_contract,
               va.s2_agency_name, va.s2_agency_address, va.s2_agency_phone
        FROM constats_main cm
        LEFT JOIN membres m ON m.id = cm.id_membre
        LEFT JOIN constats_vehicle_a va ON va.constat_id = cm.unique_id
        WHERE cm.id = ?
    ");
    
    $stmt->execute([$constat_id]);
    $constat = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$constat) {
        throw new Exception('Constat non trouvé');
    }
    
    // Output HTML for modal
    ?>
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-md-12">
                <h5 class="mb-3">Informations sur l'accident</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Date</th>
                            <td><?php echo htmlspecialchars($constat['formatted_date']); ?></td>
                        </tr>
                        <tr>
                            <th>Heure</th>
                            <td><?php echo htmlspecialchars($constat['s1_accident_time'] ?? 'Non renseigné'); ?></td>
                        </tr>
                        <tr>
                            <th>Lieu</th>
                            <td><?php echo htmlspecialchars($constat['s1_accident_place'] ?? 'Non renseigné'); ?></td>
                        </tr>
                        <tr>
                            <th>Blessés</th>
                            <td><?php echo ($constat['s1_has_injuries'] === 'yes') ? 'Oui' : 'Non'; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <h5 class="mb-3">Informations sur le client</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Nom complet</th>
                            <td><?php echo htmlspecialchars($constat['prenom'] . ' ' . $constat['nom']); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo htmlspecialchars($constat['user_email'] ?? 'Non renseigné'); ?></td>
                        </tr>
                        <tr>
                            <th>Téléphone</th>
                            <td><?php echo htmlspecialchars($constat['user_phone'] ?? 'Non renseigné'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="col-md-6">
                <h5 class="mb-3">Informations sur le véhicule</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Marque</th>
                            <td><?php echo htmlspecialchars($constat['s2_vehicle_brand'] ?? 'Non renseigné'); ?></td>
                        </tr>
                        <tr>
                            <th>Immatriculation</th>
                            <td><?php echo htmlspecialchars($constat['s2_vehicle_plate'] ?? 'Non renseigné'); ?></td>
                        </tr>
                        <tr>
                            <th>Assureur</th>
                            <td><?php echo htmlspecialchars($constat['s2_insurance_name'] ?? 'Non renseigné'); ?></td>
                        </tr>
                        <tr>
                            <th>N° Contrat</th>
                            <td><?php echo htmlspecialchars($constat['s2_insurance_contract'] ?? 'Non renseigné'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle mr-2"></i>
                    Pour consulter l'ensemble des détails du constat, veuillez télécharger le PDF complet.
                </div>
            </div>
        </div>
    </div>
    <?php
    
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>