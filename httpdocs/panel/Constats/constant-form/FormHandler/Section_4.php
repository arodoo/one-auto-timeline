<?php
// Section 4: Form Handling Logic
include_once '../Components/FormHeaderGenerator.php';

// Ensure the session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if we're in jumelage mode for user B
$isJumelageModeUserB = isset($_SESSION['jumelage_mode']) && $_SESSION['jumelage_mode'] === true;
?>
<div class="container">
    <?php
    echo FormHeaderGenerator::generateHeader(
        'CIRCONSTANCES',
        'Cochez une, deux ou aucune des cases',
        'Indiquez uniquement les actions qui étaient en cours au moment de l\'accident pour chaque conducteur (A ou B)',
        '',
        '',
        ''
    );
    ?>
    <div class="alert alert-info mt-3 jumelage-info-message" style="display: none;">
        <i class="fas fa-info-circle"></i> Les cases du conducteur B seront remplies par <span class="jumelage-email"></span>
    </div>
    
    <!-- First row of checkboxes -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>En stationnement / à l'arrêt</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input1" data-db-name="s4_parked_a" value="x">
                <label class="form-check-label" for="sc4-input1">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input2" data-db-name="s4_parked_b" value="x">
                <label class="form-check-label" for="sc4-input2">Conducteur B</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Quittait un stationnement / ouvrait une portière</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input3" data-db-name="s4_leaving_parking_a"
                    value="x">
                <label class="form-check-label" for="sc4-input3">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input4" data-db-name="s4_leaving_parking_b"
                    value="x">
                <label class="form-check-label" for="sc4-input4">Conducteur B</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Prenait un stationnement</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input5" data-db-name="s4_entering_parking_a"
                    value="x">
                <label class="form-check-label" for="sc4-input5">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input6" data-db-name="s4_entering_parking_b"
                    value="x">
                <label class="form-check-label" for="sc4-input6">Conducteur B</label>
            </div>
        </div>
    </div>
    
    <!-- Second row of checkboxes -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Sortait d'un parking, d'un lieu privé, d'un chemin de terre</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input7" data-db-name="s4_exiting_private_a"
                    value="x">
                <label class="form-check-label" for="sc4-input7">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input8" data-db-name="s4_exiting_private_b"
                    value="x">
                <label class="form-check-label" for="sc4-input8">Conducteur B</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>S'engageait dans un parking, un lieu privé, un chemin de terre</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input9" data-db-name="s4_entering_private_a"
                    value="x">
                <label class="form-check-label" for="sc4-input9">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input10" data-db-name="s4_entering_private_b"
                    value="x">
                <label class="form-check-label" for="sc4-input10">Conducteur B</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>S'engageait sur une place à sens giratoire</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input11" data-db-name="s4_entering_roundabout_a"
                    value="x">
                <label class="form-check-label" for="sc4-input11">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input12" data-db-name="s4_entering_roundabout_b"
                    value="x">
                <label class="form-check-label" for="sc4-input12">Conducteur B</label>
            </div>
        </div>
    </div>
    
    <!-- Third row of checkboxes -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Roulait sur une place à sens giratoire</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input13" data-db-name="s4_in_roundabout_a"
                    value="x">
                <label class="form-check-label" for="sc4-input13">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input14" data-db-name="s4_in_roundabout_b"
                    value="x">
                <label class="form-check-label" for="sc4-input14">Conducteur B</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Heurtait à l'arrière, en roulant dans le même sens et sur une même file</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input15" data-db-name="s4_rear_collision_a"
                    value="x">
                <label class="form-check-label" for="sc4-input15">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input16" data-db-name="s4_rear_collision_b"
                    value="x">
                <label class="form-check-label" for="sc4-input16">Conducteur B</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Roulait dans le même sens et sur une file différente</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input17" data-db-name="s4_same_direction_a"
                    value="x">
                <label class="form-check-label" for="sc4-input17">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input18" data-db-name="s4_same_direction_b"
                    value="x">
                <label class="form-check-label" for="sc4-input18">Conducteur B</label>
            </div>
        </div>
    </div>
    
    <!-- Fourth row of checkboxes -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Changeait de file</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input19" data-db-name="s4_changing_lane_a"
                    value="x">
                <label class="form-check-label" for="sc4-input19">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input20" data-db-name="s4_changing_lane_b"
                    value="x">
                <label class="form-check-label" for="sc4-input20">Conducteur B</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Doublait</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input21" data-db-name="s4_overtaking_a"
                    value="x">
                <label class="form-check-label" for="sc4-input21">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input22" data-db-name="s4_overtaking_b"
                    value="x">
                <label class="form-check-label" for="sc4-input22">Conducteur B</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Virait à droite</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input23" data-db-name="s4_turning_right_a"
                    value="x">
                <label class="form-check-label" for="sc4-input23">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input24" data-db-name="s4_turning_right_b"
                    value="x">
                <label class="form-check-label" for="sc4-input24">Conducteur B</label>
            </div>
        </div>
    </div>
    
    <!-- Fifth row of checkboxes -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Virait à gauche</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input25" data-db-name="s4_turning_left_a"
                    value="x">
                <label class="form-check-label" for="sc4-input25">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input26" data-db-name="s4_turning_left_b"
                    value="x">
                <label class="form-check-label" for="sc4-input26">Conducteur B</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Reculait</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input27" data-db-name="s4_reversing_a"
                    value="x">
                <label class="form-check-label" for="sc4-input27">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input28" data-db-name="s4_reversing_b"
                    value="x">
                <label class="form-check-label" for="sc4-input28">Conducteur B</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Empiétait sur une voie réservée à la circulation en sens inverse</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input29" data-db-name="s4_opposite_lane_a"
                    value="x">
                <label class="form-check-label" for="sc4-input29">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input30" data-db-name="s4_opposite_lane_b"
                    value="x">
                <label class="form-check-label" for="sc4-input30">Conducteur B</label>
            </div>
        </div>
    </div>
    
    <!-- Sixth row of checkboxes -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Venait de droite (dans une carrefour)</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input31" data-db-name="s4_from_right_a"
                    value="x">
                <label class="form-check-label" for="sc4-input31">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input32" data-db-name="s4_from_right_b"
                    value="x">
                <label class="form-check-label" for="sc4-input32">Conducteur B</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>N'avait pas observé un signal de priorité ou un feu rouge</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input33" data-db-name="s4_ignored_priority_a"
                    value="x">
                <label class="form-check-label" for="sc4-input33">Conducteur A</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="sc4-input34" data-db-name="s4_ignored_priority_b"
                    value="x">
                <label class="form-check-label" for="sc4-input34">Conducteur B</label>
            </div>
        </div>
    </div>
    
    <!-- Counter row -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Nombre de cases marquées</label>
            <div class="form-control-plaintext">
                <span>Conducteur A: </span>
                <input type="hidden" id="sc4-input35" data-db-name="s4_check_count_a" value="0">
                <span id="checkCount_A">0</span>
            </div>
            <div class="form-control-plaintext">
                <span>Conducteur B: </span>
                <input type="hidden" id="sc4-input36" data-db-name="s4_check_count_b" value="0">
                <span id="checkCount_B">0</span>
            </div>
        </div>
    </div>
</div>

<?php if ($isJumelageModeUserB): ?>
<script src="/panel/Constats/constant-form/JS/Section4Jumelage.js"></script>
<?php endif; ?>
<script>
// Utility functions
function loadCheckboxState() {
    // Load saved checkbox states from localStorage
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        const key = checkbox.getAttribute('id');
        const savedData = localStorage.getItem(key);
        if (savedData) {
            const data = JSON.parse(savedData);
            checkbox.checked = data.value === 'x';
        }
    });
}

function updateCheckCount() {
    const checkboxesA = Array.from(document.querySelectorAll('input[type="checkbox"][data-db-name*="_a"]'));
    const checkboxesB = Array.from(document.querySelectorAll('input[type="checkbox"][data-db-name*="_b"]'));
    
    const checksA = checkboxesA.filter(cb => cb.checked).length;
    const checksB = checkboxesB.filter(cb => cb.checked).length;
    
    // Update hidden inputs
    document.getElementById('sc4-input35').value = checksA.toString();
    document.getElementById('sc4-input36').value = checksB.toString();
    
    // Update display spans
    document.getElementById('checkCount_A').textContent = checksA;
    document.getElementById('checkCount_B').textContent = checksB;
    
    // Store in localStorage both as utility and form data
    localStorage.setItem('util_count_a', JSON.stringify({
        table: 'constats_main',
        dbName: 's4_check_count_a',
        value: checksA.toString()
    }));
    localStorage.setItem('util_count_b', JSON.stringify({
        table: 'constats_main',
        dbName: 's4_check_count_b',
        value: checksB.toString()
    }));
    
    // Store as regular form inputs for database storage
    localStorage.setItem('sc4-input35', JSON.stringify({
        table: 'constats_main',
        dbName: 's4_check_count_a',
        value: checksA.toString()
    }));
    localStorage.setItem('sc4-input36', JSON.stringify({
        table: 'constats_main',
        dbName: 's4_check_count_b',
        value: checksB.toString()
    }));
}

// Define an empty handleJumelageState function when not in jumelage mode
<?php if (!$isJumelageModeUserB): ?>
function handleJumelageState() {
    // For User A: check for jumelage info in localStorage
    const jumelageEmail = localStorage.getItem('meta-sc3-jumelage_email');
    
    if (jumelageEmail) {
        // Show and update banner
        const banner = document.querySelector('.jumelage-info-message');
        banner.style.display = 'block';
        banner.querySelector('.jumelage-email').textContent = jumelageEmail;
        
        // Disable B checkboxes for User A
        const checkboxes = document.querySelectorAll('input[type="checkbox"][data-db-name*="_b"]');
        checkboxes.forEach(checkbox => {
            checkbox.disabled = true;
            checkbox.parentElement.style.opacity = '0.5';
            checkbox.parentElement.style.cursor = 'not-allowed';
        });
    }
}
<?php endif; ?>

// Initialize immediately since this script is loaded with the section
(function initialize() {
    loadCheckboxState();
    updateCheckCount();
    handleJumelageState(); // Now this function is always defined
    
    // Add change listeners
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateCheckCount);
    });
})();
</script>