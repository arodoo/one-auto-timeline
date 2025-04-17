<?php
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
