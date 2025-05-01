<?php
// filepath: panel/Profil-automobile/views/vehicle/delete_confirm.php
global $path_cms_general;
?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Suppression du véhicule</h4>
        <div>
            <a href="<?php echo $path_cms_general; ?>vehicles" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($vehicle)): ?>
            <div class="alert alert-danger">
                <h5><i class="fas fa-exclamation-triangle"></i> Attention</h5>
                <p>Vous êtes sur le point de supprimer le véhicule <strong><?php echo htmlspecialchars($vehicle['marque']); ?> <?php echo htmlspecialchars($vehicle['modele']); ?></strong> avec l'immatriculation <strong><?php echo htmlspecialchars($vehicle['immat']); ?></strong>.</p>
                <p>Cette action est <strong>irréversible</strong> et supprimera définitivement toutes les données associées à ce véhicule.</p>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Informations du véhicule</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th style="width: 40%">Immatriculation</th>
                                    <td><?php echo htmlspecialchars($vehicle['immat']); ?></td>
                                </tr>
                                <tr>
                                    <th>Marque</th>
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <form method="post" action="<?php echo $path_cms_general; ?>Profil-automobile?action=delete_vehicle">
                    <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['id']; ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Confirmer la suppression
                    </button>
                    <a href="<?php echo $path_cms_general; ?>vehicles" class="btn btn-secondary ml-2">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </form>
            </div>
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