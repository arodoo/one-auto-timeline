<?php
/**
 * Subscription Banner Component
 * 
 * Shows a banner prompting users to subscribe if they have constats but aren't subscribed
 */

// Make sure util functions are available
if (!function_exists('get_banner_message')) {
    require_once(dirname(__FILE__) . '/../utils/constat_invitation_utils.php');
}

// Only show banner for logged in users
if (isset($_SESSION['id']) && !empty($_SESSION['id']) && isset($_SESSION['mail']) && !empty($_SESSION['mail'])) {
    $user_id = $_SESSION['id'];
    $user_email = $_SESSION['mail'];
    
    // Get banner message based on user status
    $banner_data = get_banner_message($user_id, $user_email);
    
    // Render the banner if needed
    if ($banner_data['show']) {
        echo render_subscription_banner($banner_data);
    }
}
?>