<?php
/**
 * Profile completion utilities - handles checking, redirecting, and displaying notifications
 */

/**
 * Check if user profile information is complete
 * 
 * @param int $account_type User account type
 * @return bool True if profile is complete, false otherwise
 */
function is_profile_complete($account_type) {
    global $bdd, $id_oo;
    
    $query = $bdd->prepare("SELECT civilites, nom, prenom, adresse, cp, ville, telephone, mail FROM membres WHERE id = ?");
    $query->execute([$id_oo]);
    $user_data = $query->fetch(PDO::FETCH_ASSOC);
    
    // Check if required fields are filled
    foreach ($user_data as $field => $value) {
        if (empty($value) && $field != 'telephone_portable') { // Telephone portable is optional
            return false;
        }
    }
    
    // For account types 2, 3, 6 check business information
    if (in_array($account_type, [2, 3, 6])) {
        $query = $bdd->prepare("SELECT nom_societe, numero_identification FROM membres WHERE id = ?");
        $query->execute([$id_oo]);
        $business_data = $query->fetch(PDO::FETCH_ASSOC);
        
        if (empty($business_data['nom_societe']) || empty($business_data['numero_identification'])) {
            return false;
        }
    }
    
    // For account type 1, check insurance information
    if ($account_type == 1) {
        $query = $bdd->prepare("SELECT company_name, contract_number FROM membres_insurance WHERE id_membre = ?");
        $query->execute([$id_oo]);
        $insurance_data = $query->fetch(PDO::FETCH_ASSOC);
        
        if (!$insurance_data || empty($insurance_data['company_name']) || empty($insurance_data['contract_number'])) {
            return false;
        }
    }
    
    return true;
}

/**
 * Check if user has registered vehicles
 * 
 * @return bool True if user has vehicles, false otherwise
 */
function has_vehicles() {
    global $bdd, $id_oo;
    
    $query = $bdd->prepare("SELECT COUNT(*) FROM membres_profil_auto WHERE id_membre = ?");
    $query->execute([$id_oo]);
    $vehicle_count = $query->fetchColumn();
    
    return ($vehicle_count > 0);
}

/**
 * Determine the redirection path based on profile completion
 * Also handles notification flags in a centralized way
 * 
 * @param int $account_type User account type
 * @return string|null Redirection URL or null if no redirection needed
 */
function get_redirection_path($account_type) {
    // ANTI-LOOP PROTECTION: If we've redirected recently, prevent further redirects
    if (isset($_SESSION['redirect_timestamp']) && (time() - $_SESSION['redirect_timestamp'] < 5)) {
        // Reset the cycle if we're caught in a loop
        unset($_SESSION['profile_incomplete']);
        unset($_SESSION['vehicles_missing']);
        return null;
    }
    
    // Set redirect timestamp
    $_SESSION['redirect_timestamp'] = time();
    
    // Safety check for user ID
    global $id_oo;
    if (empty($id_oo)) {
        return null;
    }
    
    // Add a bypass parameter for testing
    if (isset($_GET['bypass_redirect']) && $_GET['bypass_redirect'] == 'true') {
        return null;
    }
    
    // Current page detection
    $current_page = isset($_GET['page']) ? $_GET['page'] : '';
    
    // 1. Already on profile pages - clear the flag but don't redirect
    // Match both /Profil and /Gestion-de-votre-compte.html
    if ($current_page == 'Profil' || $current_page == 'Gestion-de-votre-compte') {
        if (isset($_SESSION['profile_incomplete'])) {
            unset($_SESSION['profile_incomplete']);
        }
        return null;
    }
    
    // 2. Already on automobile profile page - clear that flag but don't redirect
    if ($current_page == 'Profil-automobile' || $current_page == 'edit-vehicle') {
        if (isset($_SESSION['vehicles_missing'])) {
            unset($_SESSION['vehicles_missing']);
        }
        return null;
    }
    
    // 3. Check if profile is complete with error handling
    try {
        if (!is_profile_complete($account_type)) {
            $_SESSION['profile_incomplete'] = true;
            // Update URL to match the banner link
            return '/Gestion-de-votre-compte.html';
        }
    } catch (Exception $e) {
        // Error during profile check, don't redirect
        error_log("Error checking profile completion: " . $e->getMessage());
        return null;
    }
    
    // 4. Check if user has vehicles (only for account type 1) with error handling
    try {
        if ($account_type == 1 && !has_vehicles()) {
            $_SESSION['vehicles_missing'] = true;
            return '/Profil-automobile';
        }
    } catch (Exception $e) {
        // Error during vehicle check, don't redirect
        error_log("Error checking vehicles: " . $e->getMessage());
        return null;
    }
    
    // 5. Both profile and vehicles are complete, clear any flags
    if (isset($_SESSION['profile_incomplete'])) unset($_SESSION['profile_incomplete']);
    if (isset($_SESSION['vehicles_missing'])) unset($_SESSION['vehicles_missing']);
    
    return null;
}

/**
 * Display appropriate notification banner based on profile completion status
 * Now with links to appropriate pages and consistent styling
 */
function display_profile_completion_banner() {
    global $id_oo, $statut_compte_oo;
    
    // Only run these checks if we have an authenticated user
    if (empty($id_oo)) {
        return;
    }
    
    // Check profile completion status directly before showing banners
    // This ensures we only show relevant banners based on real-time status
    $profile_incomplete = false;
    $vehicles_missing = false;
    
    try {
        $profile_incomplete = !is_profile_complete($statut_compte_oo);
        if (!$profile_incomplete && $statut_compte_oo == 1) {
            $vehicles_missing = !has_vehicles();
        }
    } catch (Exception $e) {
        // Silently fail - don't show banners if we have database errors
        error_log("Error checking profile status for banners: " . $e->getMessage());
        return;
    }
    
    // Use consistent styling for both banners - both using the same alert-warning class
    if ($profile_incomplete) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Profil incomplet!</strong> Veuillez compléter 
                <a href="https://mon-espace-auto.com/Gestion-de-votre-compte.html" class="alert-link">vos informations personnelles</a> 
                pour accéder à toutes les fonctionnalités.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    } elseif ($vehicles_missing) {
        // Changed from alert-info to alert-warning for consistency
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Véhicules manquants!</strong> 
                <a href="https://mon-espace-auto.com/Profil-automobile" class="alert-link">Ajoutez au moins un véhicule</a> 
                à votre profil pour accéder à toutes les fonctionnalités.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
    
    // Clear session flags if they no longer apply
    if (!$profile_incomplete && isset($_SESSION['profile_incomplete'])) {
        unset($_SESSION['profile_incomplete']);
    }
    if (!$vehicles_missing && isset($_SESSION['vehicles_missing'])) {
        unset($_SESSION['vehicles_missing']);
    }
}