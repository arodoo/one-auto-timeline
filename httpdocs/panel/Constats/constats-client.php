<?php
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once($_SERVER['DOCUMENT_ROOT'] . '/Configurations_bdd.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Configurations.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../"; // Keep this for backward compatibility
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/utils/constat_invitation_utils.php');

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

// Include page header
$page_title = "Constats Clients";
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

        <?php 
        // Display subscription banner if not subscribed
        if (!$is_subscribed && !empty($client_constats)) {
            // Use the subscription banner component
            require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/utils/display_subscription_banner.php');
            $banner_data = [
                'message' => 'Vous avez ' . count($client_constats) . ' constat(s) client(s) en attente. Pour les consulter, veuillez souscrire à un abonnement.',
                'button_text' => 'S\'abonner maintenant',
                'button_url' => '/Abonnement'
            ];
            echo render_subscription_banner($banner_data);
        }
        ?>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Liste des constats clients</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($client_constats)): ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle mr-2"></i>
                                Aucun constat client n'a été trouvé.
                            </div>
                        <?php elseif (!$is_subscribed): ?>
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle mr-2"></i>
                                <strong>Accès limité</strong> - Pour accéder aux détails des constats, veuillez souscrire à un abonnement.
                            </div>
                            
                            <div class="table-responsive">
                                <table id="constats-table" class="display" style="min-width: 845px">
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
                                                        <a href="/Abonnement" 
                                                           class="btn btn-primary shadow btn-xs sharp me-1" 
                                                           title="Abonnement requis">
                                                            <i class="fas fa-lock"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table id="constats-table" class="display" style="min-width: 845px">
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
    </div>
</div>

<!-- Modal for constat details -->
<div class="modal fade" id="constat-details-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du constat</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="constat-details-content">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <a href="#" id="pdf-link" class="btn btn-primary" target="_blank">Voir PDF</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize datatable
        $('#constats-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json'
            },
            order: [[1, 'desc']]
        });
        
        // Handle details button click
        $('.constat-details-btn').on('click', function(e) {
            e.preventDefault();
            const constatId = $(this).data('id');
            const modal = $('#constat-details-modal');
            
            // Update PDF link
            const uniqueId = $(this).closest('tr').find('td:first').text();
            $('#pdf-link').attr('href', '/Constat-amiable-accident/pdf/' + uniqueId);
            
            // Show modal with loading spinner
            modal.modal('show');
            
            // Load details via AJAX
            $.ajax({
                url: '/panel/Constats/get-constat-details.php',
                type: 'GET',
                data: { id: constatId },
                success: function(response) {
                    $('#constat-details-content').html(response);
                },
                error: function() {
                    $('#constat-details-content').html('<div class="alert alert-danger">Erreur lors du chargement des détails</div>');
                }
            });
        });
    });
</script>

<?php
include('../page-panel-footer.php');
?>