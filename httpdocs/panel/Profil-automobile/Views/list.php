<?php
// For AJAX requests, we don't need the security check as it's already done in the controller
$is_ajax_request = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if (!$is_ajax_request) {
    // Security check for direct access
    if (empty($_SESSION['4M8e7M5b1R2e8s']) || empty($user)) {
        header("location: /");
        exit;
    }
}
?>

<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">Liste de mes véhicules</h5>
    </div>
    <div class="card-body">
        <?php if (empty($vehicles)): ?>
            <div class="alert alert-info">
                <p><i class="fas fa-info-circle"></i> Vous n'avez pas encore ajouté de véhicule.</p>
                <p>Utilisez le menu "Ajouter" pour enregistrer votre premier véhicule.</p>
            </div>
        <?php else: ?>
            <table id="vehiclesTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Immatriculation</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Année</th>
                        <th>Carburant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicles as $vehicle): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($vehicle['immat']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['marque']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['modele']); ?></td>
                            <td>
                                <?php 
                                if (!empty($vehicle['date1erCir_fr'])) {
                                    echo date('Y', strtotime($vehicle['date1erCir_fr']));
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($vehicle['energieNGC']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="editVehicle(<?php echo $vehicle['id']; ?>)">
                                    <i class="fas fa-edit"></i> Modifier
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteVehicle(<?php echo $vehicle['id']; ?>, '<?php echo htmlspecialchars($vehicle['immat']); ?>')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    if ($('#vehiclesTable').length) {
        $('#vehiclesTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
            },
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": -1 } // Disable sorting on last column (actions)
            ]
        });
    }
});
</script>