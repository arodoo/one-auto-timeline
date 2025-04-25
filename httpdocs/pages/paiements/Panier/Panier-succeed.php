<?php
if (!isset($user)) {
    header('Location: /Identification');
    exit();
}

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$invoice_to_open = isset($_SESSION['latest_invoice']) ? $_SESSION['latest_invoice'] : null;
error_log("Success page - Invoice to open: " . $invoice_to_open);
unset($_SESSION['latest_invoice']); // Clear it after use
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Paiement réussi</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 64px;"></i>
                    </div>
                    <h5 class="card-title">Merci pour votre achat!</h5>
                    <p class="card-text">
                        Votre paiement a été traité avec succès.<br>
                        Un email de confirmation vous sera envoyé prochainement.
                    </p>
                    <div class="mt-4">
                        <a href="/Gestion-de-votre-compte.html" class="btn btn-primary me-2">
                            <i class="fas fa-user me-2"></i>Mon profil
                        </a>
                        <a href="/" class="btn btn-secondary">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($invoice_to_open): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Opening invoice: <?php echo $invoice_to_open; ?>');
        var invoiceUrl = '/facture/<?php echo $invoice_to_open; ?>/<?php echo $nomsiteweb; ?>';
        window.open(invoiceUrl, '_blank');
    });
</script>
<?php endif; ?>