<?php
use setasign\Fpdi\Fpdi;

/**
 * PDF Decompressor utility class
 * Uses GhostScript to decompress PDFs that FPDI's free parser cannot handle
 */
class PDFDecompressor {
    /**
     * Decompress a PDF file using GhostScript
     * 
     * @param string $inputFile Path to the original PDF file
     * @return string Path to the decompressed PDF file
     * @throws Exception If GhostScript is not installed or decompression fails
     */
    public static function decompress($inputFile) {
        // Check if GhostScript is installed
        exec('gs --version', $output, $returnCode);
        if ($returnCode !== 0) {
            throw new Exception('GhostScript is not installed. Please install it before proceeding.');
        }
        
        // Create temporary output file
        $outputFile = tempnam(sys_get_temp_dir(), 'decompressed_') . '.pdf';
        
        // Command to decompress the PDF using GhostScript
        $cmd = sprintf(
            'gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/prepress -sOutputFile=%s %s 2>&1',
            escapeshellarg($outputFile),
            escapeshellarg($inputFile)
        );
        
        // Execute the command
        exec($cmd, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new Exception('Failed to decompress PDF: ' . implode("\n", $output));
        }
        
        return $outputFile;
    }
}

class PDFGenerator {
    private $pdf;
    private $pageSections = [
        1 => ['s1_', 's2_', 's3_', 's4_', 's5_', 's6_', 's7_'],
        2 => ['s8_', 's10_', 's11_', 's5_']
    ];

    public function __construct(Fpdi $pdf) {
        $this->pdf = $pdf;
    }

    public function generatePDF($data) {
        $templatePath = PDFCoordinates::getTemplatePath();
        if (!file_exists($templatePath)) {
            throw new Exception("Template not found at: " . $templatePath);
        }

        try {
            // First attempt with original file
            $pageCount = $this->pdf->setSourceFile($templatePath);
        } catch (Exception $e) {
            // If original attempt fails, try to decompress and use that file instead
            error_log("Using GhostScript to decompress PDF due to: " . $e->getMessage());
            
            try {
                $decompressedPath = PDFDecompressor::decompress($templatePath);
                $pageCount = $this->pdf->setSourceFile($decompressedPath);
                
                // Register cleanup of the temporary file
                register_shutdown_function(function() use ($decompressedPath) {
                    if (file_exists($decompressedPath)) {
                        @unlink($decompressedPath);
                    }
                });
            } catch (Exception $decompressException) {
                throw new Exception("Failed to process PDF: " . $decompressException->getMessage(), 0, $e);
            }
        }
        
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $this->addPageFromTemplate($pageNo);
            $this->writeDataForPage($pageNo, $data);
        }

        return $this->pdf;
    }

    private function addPageFromTemplate($pageNo) {
        $this->pdf->AddPage();
        $tplIdx = $this->pdf->importPage($pageNo);
        $this->pdf->useTemplate($tplIdx, 0, 0, null, null, true);
    }

    private function writeDataForPage($pageNo, $data) {
        if (!isset($this->pageSections[$pageNo])) {
            return;
        }

        $this->pdf->SetFont('Helvetica', '', 8);
        $this->pdf->SetAutoPageBreak(false);
        PDFCoordinates::loadCoordinates();
        
        if ($pageNo === 1) {
            $this->processSection4Lines($data);
        }
        
        foreach ($data as $field => $value) {
            if (empty($value) && $value !== '0' && $value !== 0) continue;

            $belongsToPage = false;
            foreach ($this->pageSections[$pageNo] as $sectionPrefix) {
                if (strpos($field, $sectionPrefix) === 0) {
                    $belongsToPage = true;
                    break;
                }
            }
            
            if (!$belongsToPage) continue;

            $coords = PDFCoordinates::getCoordinatesByDBField($field);
            if (!$coords) continue;

            $this->processField($field, $value, $coords);
        }
    }

    private function processSection4Lines($data) {
        $section4Pairs = [
            ['s4_parked_a', 's4_parked_b'],
            ['s4_leaving_parking_a', 's4_leaving_parking_b'],
            ['s4_entering_parking_a', 's4_entering_parking_b'],
            ['s4_exiting_private_a', 's4_exiting_private_b'],
            ['s4_entering_private_a', 's4_entering_private_b'],
            ['s4_entering_roundabout_a', 's4_entering_roundabout_b'],
            ['s4_in_roundabout_a', 's4_in_roundabout_b'],
            ['s4_rear_collision_a', 's4_rear_collision_b'],
            ['s4_same_direction_a', 's4_same_direction_b'],
            ['s4_changing_lane_a', 's4_changing_lane_b'],
            ['s4_overtaking_a', 's4_overtaking_b'],
            ['s4_turning_right_a', 's4_turning_right_b'],
            ['s4_turning_left_a', 's4_turning_left_b'],
            ['s4_reversing_a', 's4_reversing_b'],
            ['s4_opposite_lane_a', 's4_opposite_lane_b'],
            ['s4_from_right_a', 's4_from_right_b'],
            ['s4_ignored_priority_a', 's4_ignored_priority_b']
        ];

        $this->pdf->SetLineWidth(0.2);
        
        foreach ($section4Pairs as $pair) {
            $fieldA = $pair[0];
            $fieldB = $pair[1];
            
            if (empty($data[$fieldA]) && empty($data[$fieldB])) {
                $coordsA = PDFCoordinates::getCoordinatesByDBField($fieldA);
                $coordsB = PDFCoordinates::getCoordinatesByDBField($fieldB);
                
                if ($coordsA && $coordsB) {
                    $this->pdf->Line(
                        $coordsA['x'] + 1.5,
                        $coordsA['y'] + 1,
                        $coordsB['x'] + 1.5,
                        $coordsB['y'] + 1
                    );
                }
            }
        }
    }

    private function writeCheckmark($coords) {
        $this->pdf->SetFont('ZapfDingbats', '', $coords['font_size'] ?? 10);
        $this->pdf->SetXY($coords['x'], $coords['y']);
        $this->pdf->Write(0, '4');
    }

    private function writeText($text, $coords) {
        $this->pdf->SetFont('Helvetica', '', $coords['font_size'] ?? 8);
        $this->pdf->SetXY($coords['x'], $coords['y']);
        
        $processedText = $this->normalizeText($text);
        
        if (isset($coords['max_x']) && isset($coords['max_y'])) {
            $this->writeMultiLineText($processedText, $coords);
        } else {
            $this->pdf->Write(0, $processedText);
        }
    }

    private function writeMultiLineText($text, $coords) {
        $width = $coords['max_x'] - $coords['x'];
        $spacing = $coords['spacing'] ?? 4;
        
        $this->pdf->MultiCell(
            $width,
            $spacing,
            $text,
            0,
            'L',
            false
        );
    }

    private function processField($field, $value, $coords) {
        try {
            if (isset($coords['positions'])) {
                $currentPage = $this->pdf->PageNo();
                if (isset($coords['positions'][$currentPage])) {
                    $positionCoords = $coords['positions'][$currentPage];
                    $this->processSingleField($field, $value, array_merge($positionCoords, [
                        'type' => $coords['type'] ?? null,
                        'description' => $coords['description'] ?? null
                    ]));
                }
            } else {
                $this->processSingleField($field, $value, $coords);
            }
        } catch (Exception $e) {
            error_log("Error processing field $field: " . $e->getMessage());
        }
    }

    private function processSingleField($field, $value, $coords) {
        if (isset($coords['type'])) {
            if ($coords['type'] === 'image') {
                $this->insertImage($value, $coords);
                return;
            } else if ($coords['type'] === 'checkbox' && ($value === '4' || $value === 'x' || $value === true || $value === '1')) {
                $this->writeCheckmark($coords);
                return;
            }
        }

        if (is_array($coords)) {
            if (isset($coords['yes'])) {
                $this->processYesNoField($value, $coords);
            } else if (isset($coords['single']) || isset($coords['married']) || isset($coords['other'])) {
                if (isset($coords[$value])) {
                    $this->writeCheckmark($coords[$value]);
                }
            } else {
                $this->writeText($value, $coords);
            }
        } else {
            if ($value === '4' || $value === 'x') {
                $this->writeCheckmark($coords);
            } else {
                $this->writeText($value, $coords);
            }
        }
    }

    private function processYesNoField($value, $coords) {
        if ($value === 'yes' && isset($coords['yes'])) {
            $this->writeCheckmark($coords['yes']);
        } else if ($value === 'no' && isset($coords['no'])) {
            $this->writeCheckmark($coords['no']);
        }
    }

    private function processMaritalStatus($value, $coords) {
        foreach ($coords as $status => $statusCoords) {
            if ($value === $status || $value === '4' || $value === 'X') {
                $this->writeCheckmark($statusCoords);
            }
        }
    }

    private function normalizeText($text) {
        $text = str_replace(
            ['â€™', '\'', '\'', '"', '"', '´', '`'],
            "'",
            $text
        );
        
        return iconv('UTF-8', 'CP1252//TRANSLIT', $text);
    }

    private function insertImage($base64data, $coords) {
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64data));
        
        $tempFile = tempnam(sys_get_temp_dir(), 'img');
        $tempFileWithExt = $tempFile . '.png';
        rename($tempFile, $tempFileWithExt);
        
        file_put_contents($tempFileWithExt, $imageData);
        
        $width = isset($coords['max_x']) ? ($coords['max_x'] - $coords['x']) : null;
        $height = isset($coords['max_y']) ? ($coords['max_y'] - $coords['y']) : null;
        
        $this->pdf->Image(
            $tempFileWithExt,
            $coords['x'],
            $coords['y'],
            $width,
            $height,
            'PNG'
        );
        
        unlink($tempFileWithExt);
    }
}