<?php
// Remove or comment these lines after debugging
error_log("DEBUG: _GET contents: " . print_r($_GET, true));
error_log("DEBUG: Request URI: " . $_SERVER['REQUEST_URI']);
error_log("DEBUG: Session contents: " . print_r($_SESSION, true));

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    $profil_complet_oo = "oui";
    $hideMainContainer = false; // Initialize the variable to control main container display

    // Check for jumelage mode from URL parameter
    $isJumelageMode = false;
    error_log("Initial isJumelageMode: " . ($isJumelageMode ? "true" : "false"));
    error_log("share_token in GET: " . (isset($_GET['share_token']) ? $_GET['share_token'] : "not set"));

    // First check if it's already in the session (in case of page reload)
    if (!empty($_SESSION['jumelage_mode'])) {
        $isJumelageMode = true;
        error_log("Jumelage mode found in session");
    }

    // Then check URL parameter
    if (!empty($_GET['share_token'])) {
        error_log("Processing share_token from URL: " . $_GET['share_token']);

        // Load JumelageHandler to process the token
        require_once 'Components/JumelageHandler.php';
        $jumelageHandler = new JumelageHandler($bdd, $_GET['share_token']);
        $result = $jumelageHandler->processJumelageToken();

        error_log("Token processing result: " . print_r($result, true));

        if ($result['success']) {
            $isJumelageMode = true;
            error_log("Jumelage mode activated from token");
        } else {
            error_log("Failed to activate jumelage mode: " . $result['message']);

            // If this jumelage has already been completed
            if (isset($result['already_completed']) && $result['already_completed']) {
                $hideMainContainer = true; // Set flag to hide main container
                
                // Show a message about the already completed jumelage
                echo '<div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Ce constat partagé a déjà été complété.</strong> Vous ne pouvez pas le remplir à nouveau.
                </div>';

                // If we have the constat details, we could offer a link to view it
                if (isset($result['constat'])) {
                    $originalUniqueId = $result['constat']['unique_id'];

                    // Get the completed constat unique_id
                    $stmt = $bdd->prepare("SELECT unique_id FROM constats_main WHERE old_unique_id = ?");
                    $stmt->execute([$originalUniqueId]);
                    $completedUniqueId = $stmt->fetchColumn();

                    if ($completedUniqueId) {
                        echo '<div class="alert alert-info">
                            <a href="/Constat-amiable-accident/pdf/' . $completedUniqueId . '" class="btn btn-info" target="_blank">
                                <i class="fas fa-file-pdf"></i> Voir le constat complété
                            </a>
                        </div>';
                    }
                }
            }
        }
    }    // Set global JavaScript indicators for jumelage mode
    if ($isJumelageMode) {
        echo '<script>document.body.classList.add("jumelage-mode");</script>';
        echo '<script>window.isJumelageMode = true;</script>';
        
        // Add this block to preload section3FormData for jumelage mode
        require_once 'Components/Section3DataLoader.php';
        $section3Loader = new Section3DataLoader($bdd, $id_oo, true); // Pass true for isJumelageMode
        $section3Data = $section3Loader->loadUserData();
        error_log("index-file-etape.php: Preloading section3FormData in jumelage mode for user ID: " . $id_oo);
        
        echo '<script>window.section3FormData = ' . json_encode($section3Data) . ';</script>';
        echo '<script>console.log("Preloaded section3FormData:", window.section3FormData);</script>';
        
        // Make jumelageConstatId available in JavaScript
        if (isset($_SESSION['jumelage_constat_id'])) {
            $jumelageConstatId = $_SESSION['jumelage_constat_id'];
            echo '<script>window.jumelageConstatId = ' . $jumelageConstatId . ';</script>';
            echo '<script>console.log("Setting jumelageConstatId to: " + window.jumelageConstatId);</script>';
        }
    }

    require_once 'dataHandler/FormManager.php';
    $formManager = new FormManager();
    $formDataJson = json_encode($formManager->getFormData());
    $formSingleton = FormSingleton::getInstance();
    $inputsJson = json_encode($formSingleton->getInputs());

    // Function to count section files
    function countSectionFiles()
    {
        $formHandlerPath = __DIR__ . '/FormHandler/';
        $files = glob($formHandlerPath . 'Section_*.php');
        return count($files);
    }

    // Get total sections dynamically
    $totalSections = countSectionFiles();    // Create sequential display numbers without gaps
    $displayNumbers = [];
    $counter = 1;
    for ($i = 1; $i <= $totalSections; $i++) {
        if ($i != 9) { // Skip only section 9
            $displayNumbers[$i] = $counter++;
        }
    }
    ?>
    <link rel="stylesheet" href="/panel/Constats/constant-form/index-file-etape.css">

    <?php if ($isJumelageMode && !$hideMainContainer): ?>
        <div class="alert alert-warning mb-3">
            <i class="fas fa-share-alt"></i> <strong>Mode Constat Partagé</strong> - Vous complétez la partie B d'un constat
            partagé avec vous.
        </div>
    <?php endif; ?>

    <?php if (!$hideMainContainer): ?>
    <div id="main-container" class="row">
        <div class="col-lg-12">
            <div class="card-body">
                <div id="smartwizard" class="form-wizard order-create sw sw-theme-default sw-justified">
                    <ul class="nav nav-wizard">
                        <?php
                        // Generate sections using a loop, excluding section 9
                        for ($i = 1; $i <= $totalSections; $i++) {
                            if ($i == 9)
                                continue; // Skip section 9
                    
                            // In jumelage mode, only show sections 3, 4, and 7
                            if ($isJumelageMode && !in_array($i, [3, 4, 7])) {
                                continue;
                            }

                            $sectionPath = "/panel/Constats/constant-form/FormHandler/Section_{$i}.php";                            // Get display number from our mapping
                            $sectionNumber = $displayNumbers[$i];
                            
                            // In jumelage mode, rename display numbers
                            if ($isJumelageMode) {
                                if ($i == 3)
                                    $sectionNumber = 1;
                                else if ($i == 4)
                                    $sectionNumber = 2;
                                else if ($i == 7)
                                    $sectionNumber = 3;
                            }
                            ?>
                            <li>
                                <a id="section-<?php echo $i; ?>"
                                    class="nav-link <?php echo $profil_complet_oo == "oui" ? '' : 'inactive'; ?>"
                                    href="javascript:void(0);"
                                    onclick="loadSection('<?php echo $sectionPath; ?>', <?php echo $i; ?>)"
                                    title="<?php echo $sectionNumber; ?>">
                                    <span><?php echo $sectionNumber; ?></span>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>

                    <div id="section-content" class="transition-section">
                        <!-- Dynamic content will be loaded here -->
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <button class="btn btn-primary disabled" onclick="navigateSection('prev')"
                            disabled>Précédent</button>
                        <div class="d-flex flex-column align-items-center" style="">
                            <?php if (!$isJumelageMode): ?>
                                <button type="button" class="btn btn-sm d-flex align-items-center shadow-sm"
                                    onclick="resetForm()" title="Réinitialiser le formulaire">
                                    <i class="fas fa-undo-alt me-2"></i>
                                    <span class="d-none d-sm-inline">Réinitialiser</span>
                                </button>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn-primary" onclick="navigateSection('next')">Suivant</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reset Confirmation Modal -->
        <div class="modal fade" id="resetConfirmModal" tabindex="-1" aria-labelledby="resetConfirmModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resetConfirmModalLabel">Confirmer la réinitialisation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Êtes-vous sûr de vouloir réinitialiser le formulaire ? Toutes les données saisies seront perdues.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-danger" id="confirmResetBtn">Réinitialiser</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Reset Confirmation Modal -->

    </div>
    <?php endif; ?>

    <script>
        const formData = <?php echo $formDataJson; ?>;
        const inputs = <?php echo $inputsJson; ?>;
        const totalSections = <?php echo $totalSections; ?>;
        const isJumelageMode = <?php echo $isJumelageMode ? 'true' : 'false'; ?>;

        function generateTables() {
            if (confirm('This will recreate all database tables. Are you sure?')) {
                fetch('/panel/Constats/constant-form/SchemaGenerator/generate-tables.php')
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                    })
                    .catch(error => alert('Error generating tables'));
            }
        }

    </script>
    <script src="/panel/Constats/constant-form/Components/canvas-damage.js"></script>
    <script src="/panel/Constats/constant-form/index-file-etape.js"></script>
    <script src="/panel/Constats/constant-form/JS/saveConstat.js"></script>
    <script src="/panel/Constats/constant-form/JS/clearLocalStorage.js"></script>
    <script src="/panel/Constats/constant-form/JS/resetFormFunct.js"></script>
    <?php if ($isJumelageMode && !$hideMainContainer): ?>
        <script src="/panel/Constats/constant-form/JS/index-file-etape-jumelage.js"></script>
    <?php endif; ?>
<?php
} else {
    header("location: /");
}
?>