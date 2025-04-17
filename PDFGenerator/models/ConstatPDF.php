<?php
use setasign\Fpdi\Fpdi;

// Corregir la ruta del autoload usando $_SERVER['DOCUMENT_ROOT']
require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
require_once(__DIR__ . '/../utils/PDFGenerator.php');
require_once(__DIR__ . '/../config/templates.php');

class ConstatPDF {
    private $pdf;
    private $data;
    private $unique_id;
    private $bdd;
    private $pdfGenerator;

    public function __construct($unique_id, $bdd) {
        $this->pdf = new FPDI();
        $this->pdf->SetAutoPageBreak(false);
        $this->pdf->SetMargins(0, 0, 0);
        
        $this->unique_id = $unique_id;
        $this->bdd = $bdd;
        $this->pdfGenerator = new PDFGenerator($this->pdf);
        $this->loadData();
    }

    private function loadData() {
        $mainStmt = $this->bdd->prepare("
            SELECT 
                m.*,
                va.*,
                vb.*
            FROM constats_main m
            LEFT JOIN constats_vehicle_a va ON va.constat_id = m.unique_id
            LEFT JOIN constats_vehicle_b vb ON vb.constat_id = m.unique_id
            WHERE m.unique_id = ?
        ");
        
        $mainStmt->execute([$this->unique_id]);
        $this->data = $mainStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$this->data) {
            throw new Exception('Constat non trouvé');
        }

        foreach ($this->data as $key => $value) {
            if ($key === 's8_driver_marital_status') {
                continue;
            }
            
            if (in_array($key, [
                's8_is_regular_driver',
                's8_lives_with_insured',
                's8_is_employee',
                's10_has_police_report',
                's10_has_police_statement',
                's10_has_incident_report'
            ])) {
                continue;
            }
            
            if ($value === '1' || $value === 1 || $value === true || $value === $key) {
                $this->data[$key] = '4';
            }
            else if ($value === '0' || $value === 0 || $value === false || $value === null) {
                $this->data[$key] = '';
            }
        }
        
        error_log("Processed data for PDF: " . print_r($this->data, true));
    }
    
    public function generate() {
        return $this->pdfGenerator->generatePDF($this->data);
    }
}