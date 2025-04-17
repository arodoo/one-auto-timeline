<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

ob_clean();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

require_once('/var/www/vhosts/mon-espace-auto.com/httpdocs/Configurations_bdd.php');
require_once('/var/www/vhosts/mon-espace-auto.com/httpdocs/Configurations.php');
require_once('/var/www/vhosts/mon-espace-auto.com/httpdocs/Configurations_modules.php');

if (!isset($bdd)) {
    die(json_encode(['success' => false, 'message' => 'Database connection error']));
}

if (empty($_SESSION['4M8e7M5b1R2e8s'])) {
    die(json_encode(['success' => false, 'message' => 'Session expired']));
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
if (!$email) {
    die(json_encode([
        'success' => false, 
        'message' => 'Format d\'email invalide',
        'alertStyle' => 'red filledlight',
        'alertColor' => '#ff0000',
        'alertIcon' => 'uk-icon-close'
    ]));
}

try {
    $stmt = $bdd->prepare("SELECT id FROM membres WHERE mail = ?");
    $stmt->execute([$email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $shareToken = bin2hex(random_bytes(16));
        die(json_encode([
            'success' => true,
            'userId' => $result['id'],
            'shareToken' => $shareToken,
            'alertStyle' => 'green filledlight',
            'alertColor' => '#009900',
            'alertIcon' => 'uk-icon-check',
            'message' => 'Email vérifié avec succès'
        ]));
    }
    
    die(json_encode([
        'success' => false,
        'message' => 'Email non trouvé. Veuillez vérifier l\'adresse.',
        'alertStyle' => 'red filledlight',
        'alertColor' => '#ff0000',
        'alertIcon' => 'uk-icon-close'
    ]));

} catch (Exception $e) {
    die(json_encode([
        'success' => false,
        'message' => 'Une erreur est survenue lors de la vérification',
        'alertStyle' => 'red filledlight',
        'alertColor' => '#ff0000',
        'alertIcon' => 'uk-icon-close'
    ]));
}