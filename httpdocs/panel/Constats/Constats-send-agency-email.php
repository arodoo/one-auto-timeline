<?php
// Enable error reporting for debugging
ini_set('display_errors', 0); // Don't display to user
error_reporting(E_ALL);

// Log the initial request details
error_log("Agency email request received: " . json_encode($_POST));

// Buffer all output to prevent any unexpected text being sent before headers
ob_start();

// Set JSON header
header('Content-Type: application/json');

try {
    // Include required configuration files
    require_once('../../Configurations_bdd.php');
    require_once('../../Configurations.php');
    require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');
    require_once('../../includes/utils/constat_invitation_utils.php');

    // Check if user is logged in
    if (empty($_SESSION['4M8e7M5b1R2e8s']) || empty($user)) {
        throw new Exception('User not authenticated.');
    }

    // Check required parameters
    if (!isset($_POST['constat_id']) || empty($_POST['unique_id'])) {
        throw new Exception('Missing required parameters.');
    }

    $constatId = $_POST['constat_id'];
    $uniqueId = $_POST['unique_id'];
    
    error_log("Processing constat: ID=$constatId, Unique ID=$uniqueId");
    
    // Get the constat details to determine if user is A or B and gather accident info
    try {
        $mainStmt = $bdd->prepare("SELECT cm.*, 
                                  DATE_FORMAT(cm.s1_accident_date, '%d/%m/%Y') as formatted_date, 
                                  cm.s1_accident_time, 
                                  cm.s1_accident_place, 
                                  cm.s1_accident_location,
                                  cm.user_a_id, cm.shared_with_user_id, cm.is_shared, cm.id_membre
                                  FROM constats_main cm 
                                  WHERE cm.id = ? AND cm.unique_id = ?");
        $mainStmt->execute([$constatId, $uniqueId]);
        
        if ($mainStmt->rowCount() === 0) {
            throw new Exception('Constat not found.');
        }
        
        $constatData = $mainStmt->fetch(PDO::FETCH_ASSOC);
        error_log("Found constat data: " . json_encode($constatData));
    } catch (PDOException $e) {
        error_log("Database error in main query: " . $e->getMessage());
        throw new Exception('Database error while retrieving constat information.');
    }
    
    // Determine if current user is user A or B
    $currentUserId = $id_oo; // Current logged-in user from the session
    $isUserB = ($constatData['is_shared'] && $currentUserId == $constatData['shared_with_user_id']);
    
    error_log("Current user ID: $currentUserId, Is user B: " . ($isUserB ? "yes" : "no"));
    
    // Get user information for better email personalization
    try {
        $userStmt = $bdd->prepare("SELECT id, prenom, nom, mail, telephone, adresse, cp, ville, pays 
                                  FROM membres WHERE id = ?");
        $userStmt->execute([$constatData['id_membre']]);
        
        if ($userStmt->rowCount() === 0) {
            throw new Exception('User information not found.');
        }
        
        $userData = $userStmt->fetch(PDO::FETCH_ASSOC);
        error_log("Found user data for member id " . $constatData['id_membre'] . ": " . json_encode($userData));
    } catch (PDOException $e) {
        error_log("Database error in user query: " . $e->getMessage());
        throw new Exception('Database error while retrieving user information.');
    }
    
    // Select the right table based on user role
    $table = $isUserB ? 'constats_vehicle_b' : 'constats_vehicle_a';
    $prefix = $isUserB ? 's3' : 's2';
    
    error_log("Using table $table with prefix $prefix");
    
    // Get the vehicle information to find agency email and other vehicle details
    try {
        $stmt = $bdd->prepare("SELECT * FROM $table WHERE constat_id = ?");
        $stmt->execute([$uniqueId]); // Using uniqueId as foreign key
        
        if ($stmt->rowCount() === 0) {
            throw new Exception("Veehicle information not found. Please make sure you have completed the vehicle information section of this constat.");
        }
        
        $vehicleData = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("Found vehicle data in $table: " . json_encode(array_keys($vehicleData)));
    } catch (PDOException $e) {
        error_log("Database error in vehicle query: " . $e->getMessage());
        throw new Exception('Database error while retrieving vehicle information.');
    }
    
    // Find agency email - use phone field since email field doesn't exist
    $agencyEmail = null;
    $phoneColumn = "{$prefix}_agency_phone";
    
    error_log("Looking for agency email in column: $phoneColumn");
    
    if (!empty($vehicleData[$phoneColumn])) {
        error_log("Found potential agency email: " . $vehicleData[$phoneColumn]);
        
        if (filter_var($vehicleData[$phoneColumn], FILTER_VALIDATE_EMAIL)) {
            $agencyEmail = $vehicleData[$phoneColumn];
            error_log("Agency email validated: $agencyEmail");
        } else {
            error_log("Value in $phoneColumn is not a valid email: " . $vehicleData[$phoneColumn]);
            throw new Exception("The value in the agency phone field is not a valid email address. Please enter a valid email address in the '" . ($isUserB ? "Vehicle B" : "Vehicle A") . "' section of the constat form (in the Agency Phone field).");
        }
    } else {
        error_log("No agency email found in $phoneColumn column");
        throw new Exception("No agency email found. Please add your agency's email address in the '" . ($isUserB ? "Vehicle B" : "Vehicle A") . "' section of the constat form (in the Agency Phone field).");
    }
    
    // Generate the same link used by the "Visualize" button
    $pdfLink = $http . $nomsiteweb . "/Constat-amiable-accident/pdf/" . $uniqueId;
    error_log("Generated PDF link: $pdfLink");
    
    // Extract useful information for the email
    $userName = $userData['prenom'] . ' ' . $userData['nom'];
    $userEmail = $userData['mail'];
    $userPhone = $userData['telephone'];
    $userAddress = $userData['adresse'] . ', ' . $userData['cp'] . ' ' . $userData['ville'] . ', ' . $userData['pays'];
    
    $accidentDate = $constatData['formatted_date'] ?? 'Non renseigné';
    $accidentTime = $constatData['s1_accident_time'] ?? 'Non renseigné';
    $accidentPlace = $constatData['s1_accident_place'] ?? 'Non renseigné';
    $accidentLocation = $constatData['s1_accident_location'] ?? '';
    
    // Correct field mapping based on database schema
    $vehicleMake = $vehicleData["{$prefix}_vehicle_brand"] ?? 'Non renseigné';
    // Model doesn't exist in schema, leaving as "Non renseigné"
    $vehicleModel = 'Non renseigné';
    $vehiclePlate = $vehicleData["{$prefix}_vehicle_plate"] ?? 'Non renseigné';
    
    // Format insurance information with correct field names
    $insurerName = $vehicleData["{$prefix}_insurance_name"] ?? 'Non renseigné';
    $policyNumber = $vehicleData["{$prefix}_insurance_contract"] ?? 'Non renseigné';
    $agencyName = $vehicleData["{$prefix}_agency_name"] ?? 'Non renseigné';

    // Check if agency is registered, if not send an invitation
    if (!is_user_registered($agencyEmail)) {
        error_log("Agency email $agencyEmail is not registered. Sending invitation.");
        
        // Generate token and create invitation
        $agencyRole = $isUserB ? 'b' : 'a';
        $token = create_invitation($agencyEmail, $constatId, $agencyRole, $currentUserId);
        
        if ($token) {
            // Send invitation email
            $invitationSent = send_invitation_email($agencyEmail, $token, $constatId);
            
            if ($invitationSent) {
                error_log("Invitation sent successfully to $agencyEmail");
                // Return success response with invitation_sent flag
                echo json_encode([
                    'success' => true,
                    'message' => "Une invitation a été envoyée à l'agence $agencyEmail pour créer un compte et accéder au constat.",
                    'email' => $agencyEmail,
                    'invitation_sent' => true,
                    'role' => $isUserB ? 'B' : 'A'
                ]);
                ob_end_flush();
                exit;
            } else {
                error_log("Failed to send invitation email to $agencyEmail");
                throw new Exception("Impossible d'envoyer l'invitation à l'agence. Veuillez réessayer.");
            }
        } else {
            error_log("Failed to create invitation for $agencyEmail");
            throw new Exception("Erreur lors de la création de l'invitation pour l'agence.");
        }
    }
    
    // Continue with normal email sending for registered agencies
    error_log("Agency email $agencyEmail is registered. Sending constat notification.");
    
    // Prepare email subject and content - removed "Déclaration #"
    $subject = "Constat d'accident - " . $constatId;
    
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Constat d'accident automobile</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                max-width: 650px;
                margin: 0 auto;
            }
            .header {
                background-color: #e3e151;
                color: white;
                padding: 20px;
                text-align: center;
                border-radius: 5px 5px 0 0;
            }
            .content {
                padding: 20px;
                border: 1px solid #ddd;
                border-top: none;
                border-radius: 0 0 5px 5px;
            }
            .section {
                margin-bottom: 20px;
                padding-bottom: 20px;
                border-bottom: 1px solid #eee;
            }
            .section:last-child {
                border-bottom: none;
                margin-bottom: 0;
                padding-bottom: 0;
            }
            h2 {
                color: #e3e151;
                margin-top: 0;
            }
            h3 {
                color: #0077b6;
                margin-top: 20px;
                margin-bottom: 10px;
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background-color: #e3e151;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 15px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table td {
                padding: 8px;
                border-bottom: 1px solid #eee;
            }
            table td:first-child {
                font-weight: bold;
                width: 40%;
            }
            .footer {
                margin-top: 20px;
                font-size: 12px;
                text-align: center;
                color: #777;
            }
            @media only screen and (max-width: 600px) {
                body {
                    width: 100% !important;
                }
            }
        </style>
    </head>
    <body>
        <div class='header'>
            <h1>Constat d'Accident Automobile</h1>
            <p>#$constatId</p>
        </div>
        
        <div class='content'>
            <div class='section'>
                <h2>Informations sur l'assuré</h2>
                <table>
                    <tr>
                        <td>Nom complet:</td>
                        <td>$userName</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>$userEmail</td>
                    </tr>
                    <tr>
                        <td>Téléphone:</td>
                        <td>$userPhone</td>
                    </tr>
                    <tr>
                        <td>Adresse:</td>
                        <td>$userAddress</td>
                    </tr>
                </table>
            </div>
            
            <div class='section'>
                <h2>Détails de l'accident</h2>
                <table>
                    <tr>
                        <td>Date:</td>
                        <td>$accidentDate</td>
                    </tr>
                    <tr>
                        <td>Heure:</td>
                        <td>$accidentTime</td>
                    </tr>
                    <tr>
                        <td>Lieu:</td>
                        <td>$accidentPlace</td>
                    </tr>
                    <tr>
                        <td>Pays:</td>
                        <td>$accidentLocation</td>
                    </tr>
                </table>
            </div>
            
            <div class='section'>
                <h2>Informations sur le véhicule</h2>
                <table>
                    <tr>
                        <td>Marque:</td>
                        <td>$vehicleMake</td>
                    </tr>
                    <tr>
                        <td>Modèle:</td>
                        <td>$vehicleModel</td>
                    </tr>
                    <tr>
                        <td>Plaque d'immatriculation:</td>
                        <td>$vehiclePlate</td>
                    </tr>
                    <tr>
                        <td>Assureur:</td>
                        <td>$insurerName</td>
                    </tr>
                    <tr>
                        <td>N° de police:</td>
                        <td>$policyNumber</td>
                    </tr>
                    <tr>
                        <td>Agence:</td>
                        <td>$agencyName</td>
                    </tr>
                </table>
            </div>
            
            <div class='section'>
                <h2>Document du constat</h2>
                <p>Vous pouvez consulter et télécharger le constat complet en cliquant sur le lien ci-dessous :</p>
                <p style='text-align: center;'>
                    <a href='$pdfLink' class='btn'>Voir le constat</a>
                </p>
                <p><small>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur : $pdfLink</small></p>
            </div>
            
            <div class='footer'>
                <p>Ce message est généré automatiquement, merci de ne pas y répondre.</p>
                <p>© " . date('Y') . " $nomsiteweb - Tous droits réservés</p>
            </div>
        </div>
    </body>
    </html>";
    
    error_log("Preparing to send email to agency: $agencyEmail");
    
    // Check if mailsend function exists, otherwise fallback to standard mail function
    $sent = false;
    if (function_exists('mailsend')) {
        error_log("Using mailsend function");
        // Use the mailsend function
        try {
            $sent = mailsend(
                $agencyEmail,
                "Agence d'assurance",
                $emaildefault,
                $nomsiteweb,
                $subject,
                $message
            );
            error_log("mailsend result: " . ($sent ? "success" : "failed"));
        } catch (Exception $e) {
            error_log("Error in mailsend function: " . $e->getMessage());
            throw new Exception("Error sending email via mailsend: " . $e->getMessage());
        }
    } else {
        error_log("mailsend function not available, using PHP mail() function");
        // Fallback to PHP's built-in mail function
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: $nomsiteweb <$emaildefault>" . "\r\n";
        
        // Log the fallback usage
        error_log("Using mail() fallback in Constats-send-agency-email.php because mailsend() is not available");
        
        try {
            $sent = mail($agencyEmail, $subject, $message, $headers);
            error_log("mail() function result: " . ($sent ? "success" : "failed"));
        } catch (Exception $e) {
            error_log("Error in PHP mail() function: " . $e->getMessage());
            throw new Exception("Error sending email via mail(): " . $e->getMessage());
        }
    }
    
    if (!$sent) {
        error_log("Email sending failed by both methods");
        throw new Exception("Failed to send email. Please try again or contact support.");
    }
    
    error_log("Email successfully sent to agency: $agencyEmail");
    
    // Check if the user has a subscription
    $agencyId = null;
    $userStmt = $bdd->prepare("SELECT id FROM membres WHERE mail = ?");
    $userStmt->execute([$agencyEmail]);
    if ($userStmt->rowCount() > 0) {
        $agencyId = $userStmt->fetch(PDO::FETCH_ASSOC)['id'];
        $is_subscribed = is_user_subscribed($agencyId);
    } else {
        $is_subscribed = false;
    }
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Email sent successfully to agency.',
        'email' => $agencyEmail,
        'role' => $isUserB ? 'B' : 'A',
        'is_subscribed' => $is_subscribed
    ]);
    
} catch (Exception $e) {
    // Log the error
    error_log("Error in Constats-send-agency-email.php: " . $e->getMessage());
    
    // Error response
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// End output buffering and send the response
ob_end_flush();
?>