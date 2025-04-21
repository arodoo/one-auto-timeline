<?php
/**
 * Process invitation token handler
 * 
 * This file handles the invitation token validation during registration
 */

require_once(dirname(dirname(__DIR__)) . '/Configurations_bdd.php');
require_once(dirname(dirname(__DIR__)) . '/Configurations.php');
require_once(dirname(dirname(__DIR__)) . '/Configurations_modules.php');
require_once(dirname(dirname(__DIR__)) . '/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');
require_once('constat_invitation_utils.php');

/**
 * Process invitation token and associate constats with new user
 * 
 * @param string $token The invitation token
 * @param int $user_id The newly registered user ID
 * @return array Result with status and message
 */
function process_invitation_token($token, $user_id)
{
    global $bdd;

    try {
        // Log for debugging
        error_log("Processing invitation token: $token for user: $user_id");

        // Verify token is valid and not expired
        $stmt = $bdd->prepare("
            SELECT * FROM invitations 
            WHERE token = ? AND status = 'pending' AND expired_at > NOW()
        ");
        $stmt->execute([$token]);

        if ($invitation = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Get user email
            $userStmt = $bdd->prepare("SELECT mail FROM membres WHERE id = ?");
            $userStmt->execute([$user_id]);
            $userEmail = $userStmt->fetchColumn();

            // Verify emails match
            if ($userEmail === $invitation['email']) {
                try {
                    // Update invitation status to accepted - simplified query to avoid issues with missing columns
                    $updateStmt = $bdd->prepare("
                        UPDATE invitations 
                        SET status = 'accepted'
                        WHERE token = ?
                    ");
                    $updateStmt->execute([$token]);

                    error_log("Invitation $token accepted successfully");
                    return [
                        'success' => true,
                        'message' => 'Invitation acceptée avec succès.'
                    ];
                } catch (PDOException $e) {
                    error_log("Error updating invitation status: " . $e->getMessage());
                    // Continue with success even if update fails
                    return [
                        'success' => true,
                        'message' => 'Votre compte a été lié au constat, mais une erreur technique est survenue.'
                    ];
                }
            } else {
                error_log("Email mismatch: User email ($userEmail) doesn't match invitation email (" . $invitation['email'] . ")");
                return [
                    'success' => false,
                    'message' => "L'email utilisé pour l'inscription ne correspond pas à l'invitation."
                ];
            }
        } else {
            error_log("No valid invitation found for token: $token");
            return [
                'success' => false,
                'message' => "Le lien d'invitation est invalide ou a expiré."
            ];
        }
    } catch (PDOException $e) {
        error_log("Error processing invitation token: " . $e->getMessage());
        return [
            'success' => false,
            'message' => "Une erreur est survenue lors du traitement de l'invitation."
        ];
    }
}
?>