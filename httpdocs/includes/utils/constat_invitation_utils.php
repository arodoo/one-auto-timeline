<?php
/**
 * Utility functions for handling constat invitations and subscription checks
 */

/**
 * Check if user is registered
 * 
 * @param string $email Email to check
 * @return bool True if user is registered
 */
function is_user_registered($email) {
    global $bdd;
    
    try {
        $stmt = $bdd->prepare("SELECT COUNT(*) FROM membres WHERE mail = ?");
        $stmt->execute([$email]);
        
        return (int)$stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error checking if user is registered: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if user has a valid subscription
 * 
 * @param int $user_id User ID to check
 * @return bool True if user has active subscription
 */
function is_user_subscribed($user_id)
{
    global $bdd;

    try {
        error_log("SUBSCRIPTION CHECK: Checking subscription for user ID: $user_id");

        $stmt = $bdd->prepare("SELECT abonnement, subscription_end_date, cancel_scheduled 
                              FROM membres 
                              WHERE id = ?");
        $stmt->execute([$user_id]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Log the actual values found
            error_log("SUBSCRIPTION CHECK: Found abonnement: " . ($row['abonnement'] ?? 'null') .
                ", end_date: " . ($row['subscription_end_date'] ?? 'null') .
                ", cancel_scheduled: " . ($row['cancel_scheduled'] ?? 'null'));

            // Check if subscription is active (abonnement = 'oui')
            $is_active = $row['abonnement'] === 'oui';

            // Check if not canceled
            $not_canceled = $row['cancel_scheduled'] !== 'oui';

            // Check if not expired
            $not_expired = empty($row['subscription_end_date']) ||
                strtotime($row['subscription_end_date']) > time();

            error_log("SUBSCRIPTION CHECK: Is active? " . ($is_active ? 'Yes' : 'No') .
                ", Not canceled? " . ($not_canceled ? 'Yes' : 'No') .
                ", Not expired? " . ($not_expired ? 'Yes' : 'No'));

            return $is_active && $not_canceled && $not_expired;
        } else {
            error_log("SUBSCRIPTION CHECK: No subscription record found for user ID: $user_id");
        }
    } catch (PDOException $e) {
        error_log("SUBSCRIPTION CHECK ERROR: " . $e->getMessage());
    }

    return false;
}

/**
 * Get pending constats for an agency
 * 
 * @param string $email Agency email
 * @return array List of pending constats
 */
function get_pending_agency_constats($email) {
    global $bdd;
    $constats = [];
    
    try {
        // Check for constats where this email is in vehicle A agency phone field (which contains email)
        $stmt = $bdd->prepare("
            SELECT cm.*, cva.s2_agency_phone as agency_email,
                  DATE_FORMAT(cm.s1_accident_date, '%d/%m/%Y') as formatted_date,
                  m.prenom, m.nom
            FROM constats_main cm
            JOIN constats_vehicle_a cva ON cm.unique_id = cva.constat_id
            JOIN membres m ON cm.id_membre = m.id
            WHERE cva.s2_agency_phone = ?
        ");
        $stmt->execute([$email]);
        $constats = array_merge($constats, $stmt->fetchAll(PDO::FETCH_ASSOC));
        
        // Also check for constats where email is in vehicle B agency phone field
        $stmt = $bdd->prepare("
            SELECT cm.*, cvb.s3_agency_phone as agency_email,
                  DATE_FORMAT(cm.s1_accident_date, '%d/%m/%Y') as formatted_date,
                  m.prenom, m.nom 
            FROM constats_main cm
            JOIN constats_vehicle_b cvb ON cm.unique_id = cvb.constat_id
            JOIN membres m ON cm.id_membre = m.id
            WHERE cvb.s3_agency_phone = ?
        ");
        $stmt->execute([$email]);
        $constats = array_merge($constats, $stmt->fetchAll(PDO::FETCH_ASSOC));
        
        return $constats;
    } catch (PDOException $e) {
        error_log("Error getting pending agency constats: " . $e->getMessage());
        return [];
    }
}

/**
 * Create an invitation for an agency
 * 
 * @param string $email Agency email
 * @param int $constatId Constat ID
 * @param string $role Role ('a' or 'b')
 * @param int $inviterId ID of user who invited
 * @return string|bool Token if successful, false on failure
 */
function create_invitation($email, $constatId, $role, $inviterId) {
    global $bdd;
    
    try {
        // Generate a unique token
        $token = bin2hex(random_bytes(16));
        
        // Set expiration date to 7 days from now
        $expiryDate = date('Y-m-d H:i:s', strtotime('+7 days'));
        
        $stmt = $bdd->prepare("
            INSERT INTO invitations 
            (email, constat_id, token, status, created_at, expired_at) 
            VALUES (?, ?, ?, 'pending', NOW(), ?)
        ");
        
        $stmt->execute([$email, $constatId, $token, $expiryDate]);
        return $token;
    } catch (PDOException $e) {
        error_log("Error creating invitation: " . $e->getMessage());
        return false;
    }
}

/**
 * Send invitation email to agency
 * 
 * @param string $email Recipient email
 * @param string $token Invitation token
 * @param int $constatId Constat ID
 * @return bool True if sent successfully
 */
function send_invitation_email($email, $token, $constatId) {
    global $nomsiteweb, $emaildefault, $http;
    
    // Use the correct URL format that matches the .htaccess routing rules
    // Changed from /register?token= to /Inscription?token=
    $invitationLink = $http . $nomsiteweb . "/Inscription?token=" . $token;
    
    $subject = "Invitation à accéder à un constat d'accident";
    
    // Use template from includes/templates/invitation_email.php
    ob_start();
    include(dirname(__DIR__) . '/templates/invitation_email.php');
    $message = ob_get_clean();
    
    // Use mailsend function if available, otherwise use mail()
    if (function_exists('mailsend')) {
        return mailsend(
            $email,
            "Agence d'assurance",
            $emaildefault,
            $nomsiteweb,
            $subject,
            $message
        );
    } else {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: $nomsiteweb <$emaildefault>" . "\r\n";
        
        return mail($email, $subject, $message, $headers);
    }
}

/**
 * Check if user has any pending invitations
 * 
 * @param string $email User email
 * @return bool True if has pending invitations
 */
function has_pending_invitations($email) {
    global $bdd;
    
    try {
        $stmt = $bdd->prepare("
            SELECT COUNT(*) FROM invitations 
            WHERE email = ? AND status = 'pending' AND expired_at > NOW()
        ");
        $stmt->execute([$email]);
        
        return (int)$stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error checking pending invitations: " . $e->getMessage());
        return false;
    }
}

/**
 * Generate an appropriate banner message based on user status
 * 
 * @param int $user_id User ID
 * @param string $user_email User email
 * @return array Banner data including message and visibility flag
 */
function get_banner_message($user_id, $user_email) {
    $hasConstats = count(get_pending_agency_constats($user_email)) > 0;
    $isSubscribed = is_user_subscribed($user_id);
    
    $result = [
        'show' => false,
        'message' => '',
        'type' => 'info',
        'button_text' => '',
        'button_url' => ''
    ];
    
    if ($hasConstats && !$isSubscribed) {
        $result['show'] = true;
        $result['message'] = "Vous avez des constats d'accident disponibles. Abonnez-vous pour y accéder et gérer les déclarations de vos clients.";
        $result['type'] = 'warning';
        $result['button_text'] = "S'abonner";
        $result['button_url'] = "/Abonnement";
    }
    
    return $result;
}

/**
 * Render the subscription banner
 * 
 * @param array $data Banner data
 * @return string HTML for the banner
 */
function render_subscription_banner($data)
{
    ob_start();
    ?>
        <div class="alert alert-<?php echo $data['type']; ?> subscription-banner" role="alert" style="margin-top: 20px; margin-bottom: 20px; padding: 15px; background-color: #fff3cd; color: #856404; border-color: #ffeeba; border: 1px solid transparent; border-radius: 0.25rem; display: flex; justify-content: space-between; align-items: center;">
            <div style="flex: 1;">
                <strong>Action requise :</strong> <?php echo $data['message']; ?>
            </div>
            <?php if (!empty($data['button_text']) && !empty($data['button_url'])): ?>
                <div style="margin-left: 20px;">
                    <a href="<?php echo $data['button_url']; ?>" class="btn btn-warning" style="color: #212529; background-color: #ffc107; border-color: #ffc107; display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; cursor: pointer; text-decoration: none;">
                        <?php echo $data['button_text']; ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
}
?>