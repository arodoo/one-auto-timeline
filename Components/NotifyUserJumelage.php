<?php
// Error reporting for debugging
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Start output buffering to capture any errors
ob_start();

// Use absolute paths to ensure we find the configuration files
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_bdd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations.php';

// Include the function file that contains mailsend function
require_once $_SERVER['DOCUMENT_ROOT'] . '/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php';

class JumelageNotifier {
    private $bdd;
    private $constatId;
    private $userBId;
    private $shareToken;
    private $userBEmail;
    private $userBName;
    private $userBPseudo;
    private $userAInfo;
    private $userAId;
    
    public function __construct($bdd) {
        $this->bdd = $bdd;
    }
    
    /**
     * Send notification email to User B about the jumelage
     * 
     * @param int $constatId The ID of the constat record
     * @param int $userBId User B's ID
     * @param string $shareToken The generated sharing token
     * @return array Success status and message
     */
    public function sendJumelageNotification($constatId, $userBId, $shareToken) {
        $this->constatId = $constatId;
        $this->userBId = $userBId;
        $this->shareToken = $shareToken;
        
        try {
            // Get User B info
            if (!$this->getUserBInfo()) {
                return ['success' => false, 'message' => 'User B information not found'];
            }
            
            // Get User A info
            if (!$this->getUserAInfo($constatId)) {
                return ['success' => false, 'message' => 'User A information not found'];
            }
            
            // Generate and send email
            if ($this->sendEmail()) {
                // Log the notification
                if ($this->logNotificationMessage()) {
                    return ['success' => true, 'message' => 'Notification sent successfully'];
                } else {
                    return ['success' => false, 'message' => 'Failed to log notification message'];
                }
            } else {
                return ['success' => false, 'message' => 'Failed to send notification email'];
            }
        } catch (Exception $e) {
            error_log("JumelageNotifier error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error processing notification: ' . $e->getMessage()];
        }
    }
    
    /**
     * Get User B information from database
     */
    private function getUserBInfo() {
        $stmt = $this->bdd->prepare("SELECT id, mail, prenom, nom, pseudo FROM membres WHERE id = ?");
        $stmt->execute([$this->userBId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $this->userBEmail = $user['mail'];
            $this->userBName = $user['prenom'] . ' ' . $user['nom'];
            $this->userBPseudo = $user['pseudo'];
            return true;
        }
        return false;
    }
    
    /**
     * Get User A information based on the constat
     */
    private function getUserAInfo($constatId) {
        global $id_oo; // Use the global user ID variable which is already available
        
        $stmt = $this->bdd->prepare("SELECT id, prenom, nom, pseudo FROM membres WHERE id = ?");
        $stmt->execute([$id_oo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $this->userAInfo = $user;
            $this->userAId = $user['id'];
            return true;
        }
        return false;
    }
    
    /**
     * Send the email notification
     */
    private function sendEmail() {
        global $nomsiteweb, $emaildefault, $http;
        
        // Update link format to the new SEO-friendly URL
        $link = $http . $nomsiteweb . "/Constat-amiable-accident/jumelage/" . $this->shareToken;
        
        $subject = "Constat partagé sur $nomsiteweb";
        
        $message = "<b>Bonjour " . htmlspecialchars($this->userBName) . ",</b><br /><br />
            " . htmlspecialchars($this->userAInfo['prenom'] . ' ' . $this->userAInfo['nom']) . " vous a invité(e) à compléter un constat amiable partagé.<br /><br />
            Vous pouvez accéder au formulaire en cliquant sur ce lien : <a href='" . $link . "' target='_blank'>Remplir le constat partagé</a><br /><br />
            Ce lien est valide pendant 7 jours. Après ce délai, il ne sera plus possible d'accéder au formulaire.<br /><br />
            Cordialement,<br />
            L'équipe de " . htmlspecialchars($nomsiteweb) . "<br />";
        
        // Use alternative approach if mailsend function is not available
        if (function_exists('mailsend')) {
            return mailsend(
                $this->userBEmail,                  // Recipient email
                $this->userBName,                   // Recipient name
                $emaildefault,                      // Sender email
                $nomsiteweb,                        // Sender name
                $subject,                           // Email subject
                $message                            // Email message
            );
        } else {
            // Fallback to PHP's built-in mail function
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
            $headers .= "From: $nomsiteweb <$emaildefault>" . "\r\n";
            
            // Log the fallback usage
            error_log("Using mail() fallback in NotifyUserJumelage.php because mailsend() is not available");
            
            return mail($this->userBEmail, $subject, $message, $headers);
        }
    }
    
    /**
     * Log the notification in the membres_messages table
     */
    private function logNotificationMessage() {
        try {
            // Current date components
            $currentDate = time();
            $day = date('d', $currentDate);
            $month = date('m', $currentDate);
            $year = date('Y', $currentDate);
            
            // Generate the link
            $link = $GLOBALS['http'] . $GLOBALS['nomsiteweb'] . "/Constat-amiable-accident/jumelage/" . $this->shareToken;
            
            // Create message content with HTML formatting for the link
            $messageTitle = "Invitation à compléter un constat partagé";
            $messageContent = "Vous avez été invité(e) à compléter un constat amiable partagé par " . 
                $this->userAInfo['prenom'] . " " . $this->userAInfo['nom'] . ".<br><br>" .
                "Cliquez sur ce lien pour y accéder : <a href='" . $link . "'>" . 
                "Remplir le constat partagé</a>";
            
            $stmt = $this->bdd->prepare("INSERT INTO membres_messages 
                (id_membre, pseudo, id_membre_destinataire, pseudo_destinataire, 
                id_article, titre_message, message, message_lu, 
                date_message, date_jour, date_mois, date_annee, suivi) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'non', ?, ?, ?, ?, 'Constat partagé')");
            
            return $stmt->execute([
                $this->userAId,                     // id_membre (sender)
                $this->userAInfo['pseudo'],         // pseudo (sender)
                $this->userBId,                     // id_membre_destinataire (recipient)
                $this->userBPseudo,                 // pseudo_destinataire (recipient)
                $this->constatId,                   // id_article (constat id)
                $messageTitle,                      // titre_message
                $messageContent,                    // message
                $currentDate,                       // date_message
                $day,                               // date_jour
                $month,                             // date_mois
                $year                               // date_annee
            ]);
        } catch (Exception $e) {
            error_log("Error logging notification: " . $e->getMessage());
            return false;
        }
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set proper content type for JSON response
    header('Content-Type: application/json');
    
    try {
        // Check if required parameters exist
        if (empty($_POST['constat_id']) || empty($_POST['user_b_id']) || empty($_POST['share_token'])) {
            throw new Exception("Missing required parameters");
        }
        
        $notifier = new JumelageNotifier($bdd);
        $result = $notifier->sendJumelageNotification(
            $_POST['constat_id'],
            $_POST['user_b_id'],
            $_POST['share_token']
        );
        
        echo json_encode($result);
    } catch (Exception $e) {
        // Log the error for server-side debugging
        error_log("NotifyUserJumelage error: " . $e->getMessage());
        
        // Return a structured error response
        echo json_encode([
            'success' => false, 
            'message' => 'Server error: ' . $e->getMessage()
        ]);
    }
    
    // End the script to prevent any additional output
    exit;
}

// Capture any output or errors
$output = ob_get_clean();
if (!empty($output)) {
    error_log("Unexpected output in NotifyUserJumelage.php: " . $output);
}
?>