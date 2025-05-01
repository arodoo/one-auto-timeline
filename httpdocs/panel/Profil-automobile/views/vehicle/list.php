<?php
// filepath: panel/Profil-automobile/views/vehicle/list.php
global $path_cms_general;
?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Liste de mes véhicules</h4>
        <div>
            <a href="<?php echo $path_cms_general; ?>vehicles/add" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Ajouter
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($vehicles)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Vous n'avez pas encore enregistré de véhicule. Utilisez le bouton ci-dessous pour ajouter votre premier véhicule.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table id="vehicles-table" class="table table-striped table-hover">
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
                        <?php foreach ($vehicles as $vehicle): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($vehicle['immat']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['marque']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['modele']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['date1erCir_fr']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['energieNGC']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['couleur']); ?></td>
                                <td>
                                    <a href="<?php echo $path_cms_general; ?>vehicles/view/<?php echo $vehicle['id']; ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                    <a href="<?php echo $path_cms_general; ?>vehicles/edit/<?php echo $vehicle['id']; ?>" 
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
            <a href="<?php echo $path_cms_general; ?>vehicles/search" class="btn btn-primary">
                <i class="fas fa-search"></i> Rechercher par immatriculation
            </a>
            
            <a href="<?php echo $path_cms_general; ?>vehicles/add" class="btn btn-outline-secondary ml-2">
                <i class="fas fa-plus"></i> Saisie manuelle des informations
            </a>
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
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Attention, cette action est irréversible.
                </div>
                <p>Êtes-vous sûr de vouloir supprimer le véhicule avec l'immatriculation <strong><span id="delete-vehicle-immat"></span></strong> ?</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="delete-vehicle-id">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-vehicle">Supprimer</button>
            </div>
        </div>
    </div>
</div>