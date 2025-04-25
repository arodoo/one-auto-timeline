<?php
// Prevent any output/errors before PDF generation
error_reporting(0);
ini_set('display_errors', 0);

// Clean all existing buffers
while (ob_get_level()) {
    ob_end_clean();
}

// Start fresh output buffer
ob_start();

require_once('../../function/pdf/html2pdf.class.php');

try {
    // Get the HTML content
    ob_start();
    include('facture-client-pdf-html2pdf.php');
    $content = ob_get_clean();
    
    $html2pdf = new HTML2PDF('P', 'A4', 'fr');
    $html2pdf->setDefaultFont('Arial');
    $html2pdf->pdf->SetTitle('Facturation');
    $html2pdf->writeHTML($content);

    // Clean all buffers before output
    while (ob_get_level()) {
        ob_end_clean();
    }

    // Send PDF headers
    header('Content-Type: application/pdf');
    header('Cache-Control: private, must-revalidate');
    
    $html2pdf->Output('Facture-' . $_GET['idaction'] . '-' . date("d-m-Y", time()) . '.pdf', 'D');
    exit;
    
} catch (HTML2PDF_exception $e) {
    ob_end_clean();
    echo $e->getMessage();
    exit;
}
