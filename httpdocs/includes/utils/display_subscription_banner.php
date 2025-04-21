<?php
/**
 * Utility function for displaying subscription banners
 * 
 * This file handles the logic for checking and displaying the appropriate subscription banner
 */

/**
 * Display the appropriate subscription banner based on user status
 * 
 * @param int $user_id User ID
 * @param string $user_email User email
 * @return void Echoes banner HTML if conditions are met
 */
function display_appropriate_banner($user_id, $user_email)
{
    // Debug line - log that function is being called
    error_log("BANNER CHECK: Checking banner for user ID: $user_id, email: $user_email");

    // Make sure utility functions are available
    if (!function_exists('get_pending_agency_constats')) {
        require_once(dirname(__FILE__) . '/constat_invitation_utils.php');
    }

    try {
        // Directly check if user has constats and isn't subscribed
        $pending_constats = get_pending_agency_constats($user_email);

        // Debug lines - log if constats were found
        error_log("BANNER CHECK: Found " . count($pending_constats) . " pending constats for email: $user_email");

        if (!empty($pending_constats)) {
            error_log("BANNER CHECK: Constat IDs found: " . implode(", ", array_column($pending_constats, 'unique_id')));
        }

        // Check subscription status
        $is_subscribed = is_user_subscribed($user_id);
        error_log("BANNER CHECK: User subscription status: " . ($is_subscribed ? 'Subscribed' : 'Not subscribed'));

        if (!empty($pending_constats) && !$is_subscribed) {
            // User has constats but isn't subscribed - show warning banner
            error_log("BANNER CHECK: Conditions met for showing banner");

            $banner_data = [
                'show' => true,
                'message' => "Vous avez des constats d'accident disponibles. Abonnez-vous pour y accéder et gérer les déclarations de vos clients.",
                'type' => 'warning',
                'button_text' => "S'abonner",
                'button_url' => "/Abonnement"
            ];

            // Display the banner
            echo render_subscription_banner($banner_data);
        } else {
            error_log("BANNER CHECK: Conditions NOT met for showing banner");
        }
    } catch (Exception $e) {
        // Log any errors that occur during the checks
        error_log("BANNER CHECK ERROR: " . $e->getMessage());
    }
}
?>