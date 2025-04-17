<?php
// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Clear all jumelage-related session variables
if (isset($_SESSION['jumelage_mode'])) {
    unset($_SESSION['jumelage_mode']);
}

if (isset($_SESSION['jumelage_constat_id'])) {
    unset($_SESSION['jumelage_constat_id']);
}

// Clear any other jumelage-related session variables
// Add more unset() calls here if you have other jumelage session variables

// Return success response
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Jumelage session cleared']);
?>