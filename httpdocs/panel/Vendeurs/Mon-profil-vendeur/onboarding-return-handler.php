<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Load just what we need
require_once('../../../Configurations_bdd.php');
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

// Simple check for logged in user
if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user) && !empty($id_oo)) {
    
    // Direct update - that's all we need to do
    $sql_update = $bdd->prepare("UPDATE membres_profil_paiement SET profil_complet = 'oui' WHERE id_membre = ?");
    $result = $sql_update->execute([$id_oo]);
    
    // Log for debugging
    if ($result) {
        error_log("SUCCESS: Profile marked complete for user ID: $id_oo");
    } else {
        error_log("FAILED: Could not update profile for user ID: $id_oo");
    }
}

// Always redirect back to the profile page
header("Location: /Mon-profil-vendeur?action=return");
exit;