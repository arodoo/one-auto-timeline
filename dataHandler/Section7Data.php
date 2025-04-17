<?php

// Este archivo maneja los datos de la sección 7 del formulario.

require_once 'FormSingleton.php';

class Section7Data {
    private $values = [];

    public function __construct() {
        $form = FormSingleton::getInstance();
        $inputs = $form->getInputs();
        foreach ($inputs as $id => $key) {
            if (strpos($id, 'sc7-') === 0) {
                $this->values[$id] = ['id' => $id, 'key' => $key, 'value' => ''];
            }
        }
    }

    public function setValue($input, $value) {
        if (isset($this->values[$input])) {
            $this->values[$input]['value'] = $value;
        }
    }

    public function getValues() {
        return $this->values;
    }
}
?>