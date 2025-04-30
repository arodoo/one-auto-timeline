<?php
// Security check
if (empty($_SESSION['4M8e7M5b1R2e8s']) || empty($user)) {
    header("location: /");
    exit;
}
?>
<div style="border: 2px solid red; padding: 10px; margin: 10px; background: #fff;">
  TEST CONTENT - If you see this, the view is loading
</div>
<div class="container-fluid" id="profil-automobile-container">
    <div class="content-section">
        <h2>Gestion de mes véhicules</h2>
        
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="vehicleTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?php echo (!isset($activeTab) || $activeTab == 'list') ? 'active' : ''; ?>" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true">
                    <i class="fas fa-car"></i> Mes véhicules
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-plus-circle"></i> Ajouter
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" id="api-tab-link" data-toggle="tab" href="#api">Par immatriculation</a>
                    <a class="dropdown-item" id="add-tab-link" data-toggle="tab" href="#add">Manuellement</a>
                </div>
            </li>
            <li class="nav-item" id="edit-tab-item" style="<?php echo (isset($activeTab) && $activeTab == 'edit') ? '' : 'display: none;'; ?>">
                <a class="nav-link <?php echo (isset($activeTab) && $activeTab == 'edit') ? 'active' : ''; ?>" id="edit-tab" data-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="false">
                    <i class="fas fa-edit"></i> Modifier
                </a>
            </li>
        </ul>
        
        <!-- Tab content -->
        <div class="tab-content mt-3" id="vehicleTabsContent">
            <div class="tab-pane fade <?php echo (!isset($activeTab) || $activeTab == 'list') ? 'show active' : ''; ?>" id="list" role="tabpanel" aria-labelledby="list-tab">
                <div id="list-content" class="content-container">
                    <?php if (isset($vehicles) && !$is_ajax_request): ?>
                        <?php include __DIR__ . '/list.php'; ?>
                    <?php else: ?>
                    <!-- Vehicle list content will load here -->
                    <div class="text-center py-5">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Chargement en cours...</span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="tab-pane fade <?php echo (isset($activeTab) && $activeTab == 'api') ? 'show active' : ''; ?>" id="api" role="tabpanel" aria-labelledby="api-tab">
                <div id="api-content" class="content-container">
                    <?php if (isset($activeTab) && $activeTab == 'api' && !$is_ajax_request): ?>
                        <?php include __DIR__ . '/lookup.php'; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="tab-pane fade <?php echo (isset($activeTab) && $activeTab == 'add') ? 'show active' : ''; ?>" id="add" role="tabpanel" aria-labelledby="add-tab">
                <div id="add-content" class="content-container">
                    <?php if (isset($activeTab) && $activeTab == 'add' && !$is_ajax_request): ?>
                        <?php include __DIR__ . '/form.php'; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="tab-pane fade <?php echo (isset($activeTab) && $activeTab == 'edit') ? 'show active' : ''; ?>" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                <div id="edit-content" class="content-container">
                    <?php if (isset($activeTab) && $activeTab == 'edit' && !$is_ajax_request && isset($vehicle)): ?>
                        <?php include __DIR__ . '/form.php'; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Add compatible styles */
.content-container {
    min-height: 300px;
    padding: 15px;
}
</style>

<!-- JavaScript for dynamic content loading -->
<script>
// Store the current state for browser history
let currentState = {
    tab: 'list',
    id: null,
    method: null
};

// On page load
$(document).ready(function() {
    // Get initial state from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const action = urlParams.get('action') || 'list';
    const id = urlParams.get('idaction');
    const method = urlParams.get('method');
    
    // Set initial state
    currentState = { tab: action, id: id, method: method };
    
    // Load initial content based on URL
    if (action === 'add' && method === 'api') {
        loadTabContent('api', 'lookup');
        activateTabFromAction('api');
    } else {
        loadTabContent(action, action, id);
        activateTabFromAction(action);
    }
    
    // Handle tab clicks
    $('#vehicleTabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        const tabId = $(e.target).attr('href').substring(1);
        handleTabChange(tabId);
    });

    // Handle dropdown tab items
    $('#api-tab-link').on('click', function(e) {
        e.preventDefault();
        $(this).tab('show');
        handleTabChange('api');
    });

    $('#add-tab-link').on('click', function(e) {
        e.preventDefault();
        $(this).tab('show');
        handleTabChange('add');
    });
});

// Load content into tab
function loadTabContent(tabId, action, id = null) {
    let url = '?action=' + action;
    if (id) url += '&idaction=' + id;
    if (tabId === 'api') url += '&method=api';
    
    $.ajax({
        url: url,
        type: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function(data) {
            $('#' + tabId + '-content').html(data);
            
            // Update URL without reloading page
            const newUrl = window.location.pathname + url;
            history.pushState(currentState, '', newUrl);

            // Initialize DataTable if it exists in this tab
            if (tabId === 'list' && $('#vehiclesTable').length) {
                initializeDataTable();
            }
        },
        error: function() {
            $('#' + tabId + '-content').html('<div class="alert alert-danger">Erreur lors du chargement du contenu</div>');
        }
    });
}

// Initialize DataTable
function initializeDataTable() {
    $('#vehiclesTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
        },
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": -1 } // Disable sorting on last column (actions)
        ]
    });
}

// Handle tab change
function handleTabChange(tabId) {
    // Map tab IDs to actions
    const tabIdToAction = {
        'list': 'list',
        'api': 'lookup',
        'add': 'add',
        'edit': 'edit'
    };
    
    const action = tabIdToAction[tabId] || tabId;
    currentState.tab = tabId;
    currentState.action = action;
    
    if (tabId === 'api') {
        currentState.method = 'api';
    } else if (tabId === 'add') {
        currentState.method = 'manual';
    }
    
    // Load content for this tab if not already loaded
    if ($('#' + tabId + '-content').is(':empty') || tabId === 'list') {
        loadTabContent(tabId, action, tabId === 'edit' ? currentState.id : null);
    }
}

// Activate the appropriate tab based on action
function activateTabFromAction(action) {
    let tabId;
    
    switch(action) {
        case 'edit':
            tabId = 'edit';
            $('#edit-tab-item').show();
            break;
        case 'add':
            tabId = 'add';
            break;
        case 'lookup':
            tabId = 'api';
            break;
        case 'list':
        default:
            tabId = 'list';
            break;
    }
    
    $('#vehicleTabs a[href="#' + tabId + '"]').tab('show');
}

// Function to handle editing a vehicle
function editVehicle(id) {
    currentState.id = id;
    $('#edit-tab-item').show();
    loadTabContent('edit', 'edit', id);
    $('#vehicleTabs a[href="#edit"]').tab('show');
}

// Function to handle deleting a vehicle
function deleteVehicle(id, immat) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ' + immat + ' ?')) {
        $.ajax({
            url: '?action=delete&idaction=' + id,
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    popup_alert(response.message, "green filledlight", "#009900", "uk-icon-check");
                    // Reload the list tab after deletion
                    loadTabContent('list', 'list');
                } else {
                    popup_alert(response.message || "Erreur de suppression", "red filledlight", "#ff0000", "uk-icon-close");
                }
            },
            error: function() {
                popup_alert("Erreur lors de la suppression du véhicule", "red filledlight", "#ff0000", "uk-icon-close");
            }
        });
    }
}
</script>