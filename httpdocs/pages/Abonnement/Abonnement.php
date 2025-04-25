<?php 
if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    if ($abonnement_oo !== 'oui'): ?>
        <!-- Non-subscribed state -->
        <div id="abonnement-vide" class="row justify-content-center align-items-center" style="height: 100vh;">
            <div  class="col-12">
                <div class="row justify-content-center">
                    <div class="col-md-6 mb-md-5 mb-4">
                        <div class="pricing_box pricing_style2 rounded-0 animation animated fadeInUp" data-animation="fadeInUp"
                            data-animation-delay="0.4s" style="animation-delay: 0.4s; opacity: 1;">
                            <div class="pricing_ribbon">Populaire</div>
                            <div class="pr_title border-bottom bg-white">
                                <h4>Standard</h4>
                                <div class="price_tage">
                                    <div class="price_tag_left">
                                        <h2>€<?php echo $prix_abonnement; ?><span>/ mois</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="pr_content pt-3">
                                <ul class="list_none pr_list">
                                    <li>Annonces illimitées</li>
                                    <li>Demande de devis</li>
                                    <li>Contact direct avec les clients</li>
                                    <li>Messagerie privée</li>
                                    <li>Visibilité de vos annonces</li>
                                    <li>Présence dans l'annuaire</li>
                                </ul>
                            </div>
                            <div class="pr_footer">
                                <a id="btn-abonnement" href="#" class="btn btn-default rounded-0"
                                    onclick="return false;">S'abonner</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php include 'Abonnement-actif.php'; ?>
    <?php endif; 
} else {
    header('Location: /connexion');
    exit();
}
?>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Vous avez déjà des articles dans votre panier, ils seront supprimés. Êtes-vous sûr de continuer ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmSubscription">Confirmer</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Only run this code if the subscription button exists
    const btnAbonnement = document.getElementById('btn-abonnement');
    if (btnAbonnement) {
        btnAbonnement.addEventListener('click', function () {
            <?php if($total_item_panier != 0): ?>
                $('#confirmModal').modal('show');
            <?php else: ?>
                processSubscription();
            <?php endif; ?>
        });

        document.getElementById('confirmSubscription').addEventListener('click', function() {
            $('#confirmModal').modal('hide');
            processSubscription();
        });

        // Add click handler for cancel button
        document.querySelector('#confirmModal .btn-secondary').addEventListener('click', function() {
            $('#confirmModal').modal('hide');
        });

        function processSubscription() {
            const formData = new FormData();
            fetch('/function/panier/function_ajout_panier_abonnement_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.json();
                }
            })
            .then(data => {
                if (data && data.retour_validation === 'error') {
                    alert(data.Texte_rapport);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>