 <?php
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');
require_once('../../includes/utils/constat_invitation_utils.php');

// Check if user is logged in
if (empty($_SESSION['4M8e7M5b1R2e8s']) || empty($user)) {
    header('Location: /');
    exit;
}

// Get user information
$user_id = $id_oo;
$user_email = $mail_oo;

// Check if user is subscribed
$is_subscribed = is_user_subscribed($user_id);

// Get constats where this user's email is listed as the agency
$client_constats = get_pending_agency_constats($user_email);

// Include header
include('../page-panel-header.php');
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Constats Clients</h4>
                    <p>Consultez les constats soumis par vos clients</p>
                </div>
            </div>
        </div>

        <?php if (!$is_subscribed): ?>
            <div class="row">
                <div class="col-xl-12">
                    <div class="alert alert-warning" role="alert">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="alert-heading">Abonnement requis</h4>
                                <p>Pour accéder aux constats de vos clients, vous devez souscrire à un abonnement professionnel.</p>
                            </div>
                            <div>
                                <a href="/Abonnement" class="btn btn-warning light">S'abonner</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Liste des constats clients</h4>
                        </div>
                        <div class="card-body">
                            <?php if (empty($client_constats)): ?>
                                <div class="alert alert-info">
                                    Aucun constat client n'a été trouvé.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table id="example5" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>Référence</th>
                                                <th>Date de l'accident</th>
                                                <th>Client</th>
                                                <th>Lieu</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($client_constats as $constat): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($constat['unique_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($constat['formatted_date'] ?? date('d/m/Y', strtotime($constat['s1_accident_date']))); ?></td>
                                                    <td><?php echo htmlspecialchars($constat['prenom'] . ' ' . $constat['nom']); ?></td>
                                                    <td><?php echo htmlspecialchars($constat['s1_accident_place'] ?? 'Non spécifié'); ?></td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="/Constat-amiable-accident/pdf/<?php echo htmlspecialchars($constat['unique_id']); ?>" 
                                                               class="btn btn-primary shadow btn-xs sharp me-1" 
                                                               target="_blank" 
                                                               title="Visualiser le PDF">
                                                                <i class="fas fa-file-pdf"></i>
                                                            </a>
                                                            <a href="/Constat-details/<?php echo htmlspecialchars($constat['id']); ?>" 
                                                               class="btn btn-info shadow btn-xs sharp" 
                                                               title="Voir les détails">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize datatable
        if($.fn.dataTable) {
            $('#example5').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json'
                },
                order: [[1, 'desc']]
            });
        }
    });
</script>

<?php
include('../page-panel-footer.php');
?>