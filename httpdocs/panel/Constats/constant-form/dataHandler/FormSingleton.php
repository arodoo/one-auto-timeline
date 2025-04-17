<?php
class FormSingleton {
    private static $instance = null;
    private $inputs = [];
    private $values = [];
    private $p1Counter = 1;
    private $p2Counter = 1;

    private function __construct() {
        try {
            $this->initializeAllInputs();
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    private function initializeAllInputs() {
        foreach (range(1, 12) as $section) {
            $pattern = "/id=\"sc{$section}-(input\d+|canvas)\".*?data-db-name=\"([^\"]+)\"/s";
            $filePath = __DIR__ . "/../FormHandler/Section_{$section}.php";
            
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
                
                if (!empty($matches)) {
                    $prefix = ($section < 8) ? "P1-" : "P2-";
                    $counter = &$this->p1Counter;
                    
                    if ($section >= 8) {
                        $counter = &$this->p2Counter;
                    }

                    foreach ($matches as $match) {
                        $elementId = "sc{$section}-{$match[1]}";
                        $dbName = $match[2];
                        $key = $prefix . $counter++;
                        
                        // Include database mapping info
                        $tableName = 'constats_main';
                        if (strpos($dbName, 's2_') === 0) {
                            $tableName = 'constats_vehicle_a';
                        } else if (strpos($dbName, 's3_') === 0) {
                            $tableName = 'constats_vehicle_b';
                        }
                        
                        $this->inputs[$elementId] = [
                            'key' => $key,
                            'value' => '',
                            'dbName' => $dbName,
                            'table' => $tableName
                        ];
                    }
                }
            }
        }
    }

    private function handleError($e) {
        error_log("FormSingleton initialization error: " . $e->getMessage());
        throw $e;
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new FormSingleton();
        }
        return self::$instance;
    }

    public function getNextCounter($prefix) {
        if ($prefix === 'P1-') {
            return $prefix . $this->p1Counter++;
        }
        return $prefix . $this->p2Counter++;
    }

    public function getInputs() { return $this->inputs; }
    public function setValues($values) { $this->values = $values; }
    public function getValues() { return $this->values; }
}
?>
