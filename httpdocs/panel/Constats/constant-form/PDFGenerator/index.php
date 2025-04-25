<?php
// Start clean output buffer
ob_start();

// Clear any previous output to prevent corruption
if (ob_get_level()) {
    ob_end_clean();
}

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
$legacy_id = $_GET['legacy_id'] ?? null;

if ($legacy_id) {
    // Look up the unique_id from legacy_id if needed
    try {
        $stmt = $bdd->prepare("SELECT unique_id FROM membres_constats WHERE id = ?");
        $stmt->execute([$legacy_id]);
        $unique_id = $stmt->fetchColumn();
    } catch (Exception $e) {
        // Just continue with null unique_id
    }
}

if (!$unique_id && !$legacy_id || empty($_SESSION['4M8e7M5b1R2e8s']) || empty($user)) {
    header('HTTP/1.1 403 Forbidden');
    die('Access denied or invalid ID');
}

try {
    // If we have legacy_id but no unique_id, handle legacy PDF directly
    if ($legacy_id && !$unique_id) {
        // Handle legacy PDFs here if needed
    }

    $pdf = new ConstatPDF($unique_id, $bdd);
    $pdfDoc = $pdf->generate();

    // Clear any previous output to prevent corruption
    if (ob_get_level())
        ob_end_clean();

    // Set comprehensive headers for cross-browser compatibility
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="constat_' . $unique_id . '.pdf"');
    header('Cache-Control: public, max-age=0, must-revalidate');
    header('Pragma: public');

    // Get PDF content as string to determine size
    $pdfContent = $pdfDoc->Output('S');
    header('Content-Length: ' . strlen($pdfContent));
    header('Accept-Ranges: bytes');

    // Output the PDF directly
    echo $pdfContent;
    exit;

} catch (Exception $e) {
    error_log('PDF Generation Error: ' . $e->getMessage());
    die('Error generating PDF: ' . $e->getMessage());
}