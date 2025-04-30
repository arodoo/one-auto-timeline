<?php
// Security check
if (empty($_SESSION['4M8e7M5b1R2e8s']) || empty($user)) {
    header("location: /");
    exit;
}
?>
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Détails du véhicule</h2>
            
            <!-- Include vehicle management menu -->
            <?php include(__DIR__ . '/menu.php'); ?>
            
            <div class="card">
                <div class="card-body">
                    <?php if ($vehicle): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h4><?php echo htmlspecialchars($vehicle['marque'] . ' ' . $vehicle['modele']); ?></h4>
                                <p class="text-muted">Immatriculation: <?php echo htmlspecialchars($vehicle['immat']); ?></p>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="/Vehicule-manuel/<?php echo $vehicle['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <button class="btn btn-danger delete-vehicle" data-id="<?php echo $vehicle['id']; ?>">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th>Date 1ère circulation:</th>
                                        <td><?php echo htmlspecialchars($vehicle['date1erCir_fr']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Énergie:</th>
                                        <td><?php echo htmlspecialchars($vehicle['energieNGC']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Couleur:</th>
                                        <td><?php echo htmlspecialchars($vehicle['couleur']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Puissance fiscale:</th>
                                        <td><?php echo htmlspecialchars($vehicle['puisFisc']); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th>Boîte de vitesse:</th>
                                        <td><?php echo htmlspecialchars($vehicle['boite_vitesse']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nombre de portes:</th>
                                        <td><?php echo htmlspecialchars($vehicle['nb_portes']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nombre de places:</th>
                                        <td><?php echo htmlspecialchars($vehicle['nr_passagers']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Dernier contrôle technique:</th>
                                        <td><?php echo htmlspecialchars($vehicle['date_dernier_control_tecnique']); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <a href="/Mes-vehicules" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour à la liste
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            Véhicule non trouvé ou vous n'avez pas accès à ce véhicule.
                        </div>
                        <a href="/Mes-vehicules" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle vehicle deletion
        $('.delete-vehicle').on('click', function() {
            const id = $(this).data('id');
            
            if (confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?')) {
                $.ajax({
                    url: '/Profil-automobile/delete/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 200) {
                            popup_alert(response.message, "green filledlight", "#009900", "uk-icon-check");
                            setTimeout(function() {
                                window.location.href = "/Mes-vehicules";
                            }, 1500);
                        } else {
                            popup_alert(response.message, "red filledlight", "#ff0000", "uk-icon-close");
                        }
                    },
                    error: function() {
                        popup_alert('Erreur lors de la suppression du véhicule', "red filledlight", "#ff0000", "uk-icon-close");
                    }
                });
            }
        });
    });
</script>