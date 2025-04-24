<?php
/**
 * Utility function for displaying subscription banners
 * 
 * This file handles the logic for checking and displaying the appropriate subscription banner
 */

/**
 * Display the appropriate subscription banner based on user status
 * 
 * @param int $user_id User ID (optional, will use global $id_oo if not provided)
 * @param string $user_email User email (optional, will use global $mail_oo if not provided)
 * @return void Echoes banner HTML if conditions are met
 */
function display_appropriate_banner($user_id = null, $user_email = null)
{
    global $id_oo, $mail_oo;  // Use the global variables from the system

    // Use global variables if parameters are empty
    $user_id = !empty($user_id) ? $user_id : $id_oo;
    $user_email = !empty($user_email) ? $user_email : $mail_oo;

    error_log("BANNER CHECK: Using user ID: $user_id, email: $user_email");

    // Make sure utility functions are available
    if (!function_exists('get_pending_agency_constats')) {
        require_once(dirname(__FILE__) . '/constat_invitation_utils.php');
    }

    try {
        // Get the banner message data using the utility function
        $banner_data = get_banner_message($user_id, $user_email);
        
        error_log("BANNER CHECK: Banner visibility: " . ($banner_data['show'] ? 'Visible' : 'Hidden'));
        
        // Display the banner if needed
        if ($banner_data['show']) {
            echo render_subscription_banner($banner_data);
        }
    } catch (Exception $e) {
        // Log any errors that occur during the checks
        error_log("BANNER CHECK ERROR: " . $e->getMessage());
    }
}
?>