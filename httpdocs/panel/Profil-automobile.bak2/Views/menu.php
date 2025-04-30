<?php
// Get current page name to highlight active menu item
$current_page = basename($_SERVER['REQUEST_URI']);
if (strpos($current_page, '?') !== false) {
    $current_page = substr($current_page, 0, strpos($current_page, '?'));
}

// Check if we're on the vehicle-manuel page with an ID parameter
$is_edit_mode = false;
if (preg_match('/Vehicule-manuel\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
    $is_edit_mode = true;
    $current_page = 'Vehicule-manuel';
}
?>

<div class="mb-4">
    <h5>Gestion des véhicules</h5>
    
    <nav class="nav nav-pills flex-column flex-md-row mb-3">
        <a class="nav-link <?php echo (strpos($current_page, 'Mes-vehicules') !== false) ? 'active' : ''; ?>" 
           href="<?php echo $path_cms_general; ?>Mes-vehicules">
            <i class="fas fa-car"></i> Mes véhicules
        </a>
        <a class="nav-link <?php echo (strpos($current_page, 'Profil-automobile') !== false && strpos($current_page, 'ajouter') === false) ? 'active' : ''; ?>" 
           href="<?php echo $path_cms_general; ?>Profil-automobile">
            <i class="fas fa-search"></i> Recherche par immatriculation
        </a>
        <a class="nav-link <?php echo (strpos($current_page, 'Vehicule-manuel') !== false && !$is_edit_mode) ? 'active' : ''; ?>" 
           href="<?php echo $path_cms_general; ?>Vehicule-manuel">
            <i class="fas fa-plus"></i> Ajout manuel
        </a>
        <?php if ($is_edit_mode): ?>
        <a class="nav-link active" href="#">
            <i class="fas fa-edit"></i> Modifier un véhicule
        </a>
        <?php endif; ?>
    </nav>
</div>