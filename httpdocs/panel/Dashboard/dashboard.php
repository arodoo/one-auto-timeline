<?php
/* Dashboard for account type 1
 * Displays gadgets and quick access to main features
 */

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    global $id_oo;
    ?>
    <div class="dashboard-container">
        <div class="row">
            <div class="col-12">
                <div class="dashboard-welcome mb-4">
                    <h3>Bienvenue <?php echo $prenom_oo; ?> <?php echo $nom_oo; ?></h3>
                    <p class="text-muted">Votre espace personnel automobile</p>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons Section -->
        <?php include('panel/Dashboard/components/action-buttons.php'); ?>
        
        <!-- Documents Section -->
        <?php include('panel/Dashboard/components/documents-section.php'); ?>
        
        <!-- Carousels Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Services recommandés</h4>
                    </div>
                    <div class="card-body">
                        <?php include('panel/Dashboard/components/carousel-section.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/panel/Dashboard/dashboard.js"></script>
    <link href="/panel/Dashboard/css/carousel-custom.css" rel="stylesheet">
    <script src="/panel/Dashboard/js/carousel-handler.js"></script>
    <?php
} else {
    header("location: /");
}
?>