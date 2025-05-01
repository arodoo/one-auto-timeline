<?php
// filepath: panel/Profil-automobile/views/vehicle/view.php
global $path_cms_general;
?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Détails du véhicule</h4>
        <div>
            <a href="<?php echo $path_cms_general; ?>vehicles" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($vehicle)): ?>
            <div class="row mb-4">
                <div class="col-md-8">
                    <h3><?php echo htmlspecialchars($vehicle['marque'] . ' ' . $vehicle['modele']); ?></h3>
                    <h5 class="text-muted">Immatriculation: <?php echo htmlspecialchars($vehicle['immat']); ?></h5>
                </div>
                <div class="col-md-4 text-right">
                    <a href="<?php echo $path_cms_general; ?>vehicles/edit/<?php echo $vehicle['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <button class="btn btn-danger delete-vehicle" data-id="<?php echo $vehicle['id']; ?>" 
                            data-immat="<?php echo htmlspecialchars($vehicle['immat']); ?>">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5><i class="fas fa-info-circle"></i> Informations principales</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <tr>
                                    <th style="width: 40%">Marque</th>
                                    <td><?php echo htmlspecialchars($vehicle['marque']); ?></td>
                                </tr>
                                <tr>
                                    <th>Modèle</th>
                                    <td><?php echo htmlspecialchars($vehicle['modele']); ?></td>
                                </tr>
                                <tr>
                                    <th>Date de circulation</th>
                                    <td><?php echo htmlspecialchars($vehicle['date1erCir_fr']); ?></td>
                                </tr>
                                <tr>
                                    <th>Carburant</th>
                                    <td><?php echo htmlspecialchars($vehicle['energieNGC']); ?></td>
                                </tr>
                                <tr>
                                    <th>Couleur</th>
                                    <td><?php echo htmlspecialchars($vehicle['couleur']); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5><i class="fas fa-cogs"></i> Caractéristiques techniques</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <tr>
                                    <th style="width: 40%">Puissance fiscale</th>
                                    <td><?php echo htmlspecialchars($vehicle['puisFisc'] ?? '-'); ?> CV</td>
                                </tr>
                                <tr>
                                    <th>Boîte de vitesse</th>
                                    <td>
                                    <?php 
                                        $boite = $vehicle['boite_vitesse'] ?? '-';
                                        switch($boite) {
                                            case 'M': echo 'Manuelle'; break;
                                            case 'A': echo 'Automatique'; break;
                                            case 'SA': echo 'Semi-automatique'; break;
                                            default: echo htmlspecialchars($boite);
                                        }
                                    ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nombre de portes</th>
                                    <td><?php echo htmlspecialchars($vehicle['nb_portes'] ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Nombre de places</th>
                                    <td><?php echo htmlspecialchars($vehicle['nr_passagers'] ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Émission CO2</th>
                                    <td><?php echo !empty($vehicle['co2']) ? htmlspecialchars($vehicle['co2']) . ' g/km' : '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(!empty($vehicle['date_dernier_control_tecnique'])): ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5><i class="fas fa-clipboard-check"></i> Contrôle technique</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <tr>
                                    <th style="width: 40%">Date du dernier contrôle</th>
                                    <td>
                                        <?php 
                                            echo date('d/m/Y', $vehicle['date_dernier_control_tecnique']); 
                                            
                                            // Calculate next control date (2 years later)
                                            $nextDate = strtotime('+2 years', $vehicle['date_dernier_control_tecnique']);
                                            $now = time();
                                            
                                            $daysRemaining = ceil(($nextDate - $now) / (60 * 60 * 24));
                                            
                                            echo '<br><small class="text-muted">Prochain contrôle: ' . date('d/m/Y', $nextDate) . '</small>';
                                            
                                            if ($daysRemaining < 30 && $daysRemaining > 0) {
                                                echo '<br><span class="badge badge-warning">À renouveler dans ' . $daysRemaining . ' jours</span>';
                                            } elseif ($daysRemaining <= 0) {
                                                echo '<br><span class="badge badge-danger">Contrôle technique expiré</span>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <?php if(!empty($vehicle['vin'])): ?>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5><i class="fas fa-id-card"></i> Identité du véhicule</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <tr>
                                    <th style="width: 40%">Numéro de série (VIN)</th>
                                    <td><?php echo htmlspecialchars($vehicle['vin']); ?></td>
                                </tr>
                                <?php if(!empty($vehicle['type_mine'])): ?>
                                <tr>
                                    <th>Type Mine</th>
                                    <td><?php echo htmlspecialchars($vehicle['type_mine']); ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Véhicule non trouvé.
            </div>
            <a href="<?php echo $path_cms_general; ?>vehicles" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        <?php endif; ?>
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
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Êtes-vous sûr de vouloir supprimer ce véhicule ? Cette action est irréversible.
                </div>
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