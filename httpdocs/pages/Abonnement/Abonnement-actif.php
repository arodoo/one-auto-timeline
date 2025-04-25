<div id="abonnement-actif" class="row justify-content-center">
    <div class="col-12">
        <div class="row justify-content-center">
            <div class="pricing_box pricing_style2 rounded-0">
                <div class="pr_title border-bottom bg-white">
                    <h4>Abonnement Actif</h4>
                    <?php if ($cancel_scheduled_oo === 'oui'): ?>
                        <div class="alert alert-warning">
                            <p>Votre abonnement est programmé pour se terminer le <?php echo $subscription_end_date_oo; ?>
                            </p>
                            <button type="button" class="btn btn-success mt-2" id="reactivateBtn">
                                <span class="button-text">Réactiver mon abonnement</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                            </button>
                        </div>
                    <?php else: ?>
                        <p>Votre abonnement est actuellement actif</p>
                        <?php if (isset($date_abonnement_oo)): ?>
                            <p>Prochain paiement <?php echo date('d-m-Y', $date_abonnement_oo); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="pr_content pt-3">
                    <ul class="list_none pr_list">
                        <li>✓ Annonces illimitées</li>
                        <li>✓ Demande de devis</li>
                        <li>✓ Contact direct avec les clients</li>
                        <li>✓ Messagerie privée</li>
                        <li>✓ Visibilité de vos annonces</li>
                        <li>✓ Présence dans l'annuaire</li>
                    </ul>
                    <div class="text-center mt-4">
                        <?php if ($cancel_scheduled_oo !== 'oui'): ?>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#cancelModal">
                                Annuler mon abonnement
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Confirmer l'annulation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir annuler votre abonnement ?
            </div>
            <div class="modal-footer">
                <div class="row w-100 justify-content-end">
                    <div class="col-6">
                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Non, garder mon abonnement</button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-danger w-100" id="confirmCancel">Oui, annuler mon abonnement</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('confirmCancel').addEventListener('click', function () {
        fetch('pages/Abonnement/Abonnement-cancel-membership-ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    $('#cancelModal').modal('hide');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de l\'annulation. Veuillez réessayer.');
            });
    });

    // Replace reactivation handler
    document.getElementById('reactivateBtn')?.addEventListener('click', function () {
        const button = this;
        const buttonText = button.querySelector('.button-text');
        const spinner = button.querySelector('.spinner-border');

        // Disable button and show spinner
        button.disabled = true;
        buttonText.textContent = 'Réactivation en cours...';
        spinner.classList.remove('d-none');

        fetch('pages/Abonnement/reactivate-subscription-ajax.php', {
            method: 'POST'
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    buttonText.textContent = 'Réactivé avec succès!';
                    spinner.classList.add('d-none');

                    // Add success animation
                    button.classList.add('btn-light');
                    button.classList.remove('btn-success');

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.disabled = false;
                buttonText.textContent = 'Réactiver mon abonnement';
                spinner.classList.add('d-none');
                alert('Une erreur est survenue. Veuillez réessayer.');
            });
    });
</script>

<style>
    #reactivateBtn {
        transition: all 0.3s ease;
    }

    #reactivateBtn:disabled {
        opacity: 0.8;
        cursor: not-allowed;
    }

    .btn-light {
        animation: successPulse 0.5s ease;
    }

    @keyframes successPulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }
</style>