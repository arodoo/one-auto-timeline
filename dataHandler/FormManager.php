<?php

spl_autoload_register(function ($class) {
    include $class . '.php';
});

require_once 'FormSingleton.php';

class FormManager
{
    private $sectionsData;

    public function __construct()
    {
        $this->sectionsData = [];
        $directory = __DIR__; 
        foreach (glob($directory . '/Section*Data.php') as $file) {
            $className = basename($file, '.php');
            $this->sectionsData[] = new $className();
        }
    }

    public function getFormData()
    {
        $formSingleton = FormSingleton::getInstance();
        $allValues = [];

        foreach ($this->sectionsData as $sectionData) {
            $allValues = array_merge($allValues, $sectionData->getValues());
        }

        $formSingleton->setValues($allValues);
        return $formSingleton->getValues();
    }
}
?>