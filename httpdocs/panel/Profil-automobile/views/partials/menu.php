<?php
// filepath: panel/Profil-automobile/views/partials/menu.php
global $path_cms_general;

// Determine current page
$current_action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Check if we're on edit mode
$is_edit_mode = ($current_action === 'edit');
$is_view_mode = ($current_action === 'view');
?>
<div class="mb-4">
    <h5>Gestion des véhicules</h5>
    
    <nav class="nav nav-pills flex-column flex-md-row mb-3">
        <a class="nav-link <?php echo ($current_action === 'list') ? 'active' : ''; ?>" 
           href="<?php echo $path_cms_general; ?>vehicles">
            <i class="fas fa-car"></i> Mes véhicules
        </a>
        <a class="nav-link <?php echo ($current_action === 'api_search') ? 'active' : ''; ?>" 
           href="<?php echo $path_cms_general; ?>vehicles/search">
            <i class="fas fa-search"></i> Recherche par immatriculation
        </a>
        <a class="nav-link <?php echo ($current_action === 'manual_form' && !$is_edit_mode) ? 'active' : ''; ?>" 
           href="<?php echo $path_cms_general; ?>vehicles/add">
            <i class="fas fa-plus"></i> Ajout manuel
        </a>
        <?php if ($is_edit_mode): ?>
        <a class="nav-link active" href="#">
            <i class="fas fa-edit"></i> Modifier un véhicule
        </a>
        <?php endif; ?>
        <?php if ($is_view_mode): ?>
        <a class="nav-link active" href="#">
            <i class="fas fa-eye"></i> Détails du véhicule
        </a>
        <?php endif; ?>
    </nav>
</div>