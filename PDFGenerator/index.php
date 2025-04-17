<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get real path of script
$script_path = dirname(__FILE__);
$root_path = dirname(dirname(dirname(dirname($script_path))));

// Include core configurations
require_once($root_path . '/Configurations_bdd.php');
require_once($root_path . '/Configurations.php');
require_once($root_path . '/Configurations_modules.php');
require_once($root_path . '/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');
require_once($root_path . '/vendor/autoload.php');
require_once(__DIR__ . '/models/ConstatPDF.php');
require_once(__DIR__ . '/utils/PDFGenerator.php');
require_once(__DIR__ . '/config/templates.php');

$unique_id = $_GET['id'] ?? null;

if (!$unique_id || empty($_SESSION['4M8e7M5b1R2e8s']) || empty($user)) {
    header('HTTP/1.1 403 Forbidden');
    die('Access denied or invalid ID');
}

try {
    $pdf = new ConstatPDF($unique_id, $bdd);
    $pdfDoc = $pdf->generate();
    
    header('Content-Type: application/pdf');
    header('Cache-Control: no-cache');
    header('Pragma: public');
    
    // Output the PDF directly
    echo $pdfDoc->Output('S');
    exit;
    
} catch (Exception $e) {
    error_log('PDF Generation Error: ' . $e->getMessage());
    die('Error generating PDF: ' . $e->getMessage());
}