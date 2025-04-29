<?php
if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    global $id_oo;
    
    // Fetch user's vehicles from membres_profil_auto
    $user_vehicles = [];
    try {
        $sql = "SELECT id, immat, marque, modele, date1erCir_fr, energieNGC, couleur 
                FROM membres_profil_auto WHERE id_membre = :id_membre 
                ORDER BY id DESC";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([':id_membre' => $id_oo]);
        $user_vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle error
    }
?>
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Gestion de mes véhicules</h2>
            
            <!-- Include vehicle management menu -->
            <?php include('panel/Profil-automobile/includes/menu-vehicle.php'); ?>
            
            <div class="card">
                <div class="card-header">
                    <h4>Liste de mes véhicules</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($user_vehicles)): ?>
                        <div class="alert alert-info">
                            Vous n'avez pas encore enregistré de véhicule. Utilisez le bouton ci-dessous pour ajouter votre premier véhicule.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Immatriculation</th>
                                        <th>Marque</th>
                                        <th>Modèle</th>
                                        <th>Date de circulation</th>
                                        <th>Carburant</th>
                                        <th>Couleur</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($user_vehicles as $vehicle): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($vehicle['immat']); ?></td>
                                            <td><?php echo htmlspecialchars($vehicle['marque']); ?></td>
                                            <td><?php echo htmlspecialchars($vehicle['modele']); ?></td>
                                            <td><?php echo htmlspecialchars($vehicle['date1erCir_fr']); ?></td>
                                            <td><?php echo htmlspecialchars($vehicle['energieNGC']); ?></td>
                                            <td><?php echo htmlspecialchars($vehicle['couleur']); ?></td>
                                            <td>
                                                <a href="/Profil-automobile-modifier/<?php echo $vehicle['id']; ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-vehicle" 
                                                        data-id="<?php echo $vehicle['id']; ?>" 
                                                        data-immat="<?php echo htmlspecialchars($vehicle['immat']); ?>">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-3">
                        <a href="/Profil-automobile-ajouter" class="btn btn-success">
                            <i class="fas fa-plus"></i> Ajouter un nouveau véhicule
                        </a>
                        
                        <a href="/Vehicule-manuel" class="btn btn-outline-secondary ml-2">
                            <i class="fas fa-pencil-alt"></i> Saisie manuelle des informations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour la suppression de véhicule -->
<div class="modal fade" id="deleteVehicleModal" tabindex="-1" role="dialog" aria-labelledby="deleteVehicleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVehicleModalLabel">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce véhicule ? Cette action est irréversible.
                <p><strong>Immatriculation:</strong> <span id="delete-vehicle-immat"></span></p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="delete-vehicle-id">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-vehicle">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Delete vehicle confirmation
        $(document).on("click", ".delete-vehicle", function() {
            const vehicleId = $(this).data("id");
            const vehicleImmat = $(this).data("immat");
            
            $("#delete-vehicle-id").val(vehicleId);
            $("#delete-vehicle-immat").text(vehicleImmat);
            $("#deleteVehicleModal").modal("show");
        });

        // Delete vehicle action
        $("#confirm-delete-vehicle").click(function() {
            const vehicleId = $("#delete-vehicle-id").val();
            
            $.ajax({
                url: '/panel/Profil-automobile/Profil-automobile-delete-vehicle.php',
                type: 'POST',
                data: { vehicle_id: vehicleId },
                dataType: "json",
                success: function(res) {
                    if (res.status === 200) {
                        popup_alert(res.message, "green filledlight", "#009900", "uk-icon-check");
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        popup_alert(res.message || "Erreur de suppression", "red filledlight", "#ff0000", "uk-icon-close");
                    }
                    $("#deleteVehicleModal").modal("hide");
                },
                error: function() {
                    popup_alert("Erreur lors de la suppression du véhicule", "red filledlight", "#ff0000", "uk-icon-close");
                    $("#deleteVehicleModal").modal("hide");
                }
            });
        });
    });
</script>
<?php
} else {
    header("location: /");
}
?>