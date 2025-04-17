<?php
class PDFCoordinates {
    private static $coordinates = [];
    private static $loaded = false;

    public static function loadCoordinates() {
        if (self::$loaded) {
            return;
        }
        
        $section1File = __DIR__ . '/coordinates/section1.php';
        $section2File = __DIR__ . '/coordinates/section2.php';
        $section3File = __DIR__ . '/coordinates/section3.php';
        $section4File = __DIR__ . '/coordinates/section4.php';
        $section5File = __DIR__ . '/coordinates/section5.php';
        $section8File = __DIR__ . '/coordinates/section8.php';
        $section10File = __DIR__ . '/coordinates/section10.php';  
        $section11File = __DIR__ . '/coordinates/section11.php';  
        
        if (!file_exists($section1File) || !file_exists($section2File) || 
            !file_exists($section3File) || !file_exists($section4File) ||
            !file_exists($section5File) || !file_exists($section8File) ||
            !file_exists($section10File) || !file_exists($section11File)) {
            error_log("ERROR: Coordinates files not found");
            throw new Exception("Coordinates files not found");
        }

        self::$coordinates = array_merge(
            require($section1File),
            require($section2File),
            require($section3File),
            require($section4File),
            require($section5File),
            require($section8File),
            require($section10File),  
            require($section11File)  
        );
        
        self::$loaded = true;
        error_log("DEBUG - Loaded coordinates keys: " . implode(', ', array_keys(self::$coordinates)));
    }

    public function __construct() {
        self::loadCoordinates();
    }

    public static function getCoordinatesByDBField($dbField) {
        if (!self::$loaded) {
            self::loadCoordinates();
        }

        if (!isset(self::$coordinates[$dbField])) {
            error_log("No coordinates found for DB field: $dbField");
            error_log("Available coordinate keys: " . implode(', ', array_keys(self::$coordinates)));
            return null;
        }
        return self::$coordinates[$dbField];
    }

    public static function getCoordinatesByFormId($formId) {
        return isset(self::$coordinates[$formId]) ? self::$coordinates[$formId] : null;
    }

    public static function getTemplatePath() {
        return $_SERVER['DOCUMENT_ROOT'] . '/panel/Constats/constant-form/PDFGenerator/assets/constat-amiable-accident-auto-template.pdf';
    }
}