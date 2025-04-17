<?php
class CanvasDamage {
    private $inputId;
    private $vehicleId;
    private $imagePath;
    private $sectionId;

    public function __construct($inputId, $vehicleId, $imagePath = '/images/constant-amiable-accident/point_de_choc.jpg') {
        $this->inputId = $inputId;
        $this->vehicleId = $vehicleId;
        $this->imagePath = $imagePath;
        // Extract section number from inputId (sc2-input34 -> 2)
        preg_match('/sc(\d+)-/', $inputId, $matches);  
        $this->sectionId = $matches[1];
    }

    private function getStyles() {
        return '<link rel="stylesheet" href="/panel/Constats/constant-form/Components/canvas-damage.css">';
    }

    private function getScripts() {
        return '<script src="/panel/Constats/constant-form/Components/canvas-damage.js"></script>';
    }

    public function render() {
        $canvasId = "damage-canvas-{$this->vehicleId}";
        $imageId = "vehicle-{$this->vehicleId}-img";
        
        return $this->getStyles() . <<<HTML
        <div class="vehicle-damage-container">
            <img src="{$this->imagePath}" id="{$imageId}" class="vehicle-image" onload="if(typeof initDamageCanvas === 'function') initDamageCanvas('{$this->inputId}', '{$this->vehicleId}')">
            <canvas id="{$canvasId}" class="damage-canvas"></canvas>
            <input type="hidden" id="{$this->inputId}" data-maxlength="12" data-db-name="s{$this->sectionId}_impact_point">
        </div>
HTML;
    }
}
?>