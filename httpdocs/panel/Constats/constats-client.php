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

<script>
    $(document).ready(function() {
        // Function to load constats list via AJAX
        function liste() {
            $.post({
                url: '/panel/Constats/constats-client-liste-ajax.php',
                type: 'POST',
                dataType: "html",
                success: function(res) {
                    $("#liste").html(res);
                }
            });
        }
        
        // Initial load
        liste();
        
        // Handle details button click (will be delegated in the AJAX loaded content)
        $(document).on('click', '.constat-details-btn', function(e) {
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

<div style='clear: both;'></div><br />
<div style='padding: 5px; text-align: center;'>
    <div class="row page-titles">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text" style="text-align: left;">
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
                    <!-- This div will be populated by AJAX -->
                    <div id="liste"></div>
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

<?php
include('../page-panel-footer.php');
?>