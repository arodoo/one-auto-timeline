<?php
// filepath: panel/Profil-automobile/controllers/BaseController.php
class BaseController {
    protected $user;
    protected $id_oo;
    protected $bdd;
    
    public function __construct() {
        global $user, $id_oo, $bdd;
        $this->user = $user;
        $this->id_oo = $id_oo;
        $this->bdd = $bdd;
        
        // Check authentication
        if (empty($_SESSION['4M8e7M5b1R2e8s']) || empty($user)) {
            if ($this->isAjaxRequest()) {
                $this->respondJson([
                    'status' => 401,
                    'message' => 'Non autorisé'
                ]);
                exit;
            } else {
                header("location: /");
                exit;
            }
        }
    }
    
    protected function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    protected function respondJson($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function render($view, $data = []) {
        // Extract data to make variables available in the view
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        include dirname(__DIR__) . '/views/' . $view . '.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    protected function renderWithLayout($view, $data = []) {
        $content = $this->render($view, $data);
        
        // Start output buffering for the layout
        ob_start();
        
        // Include the layout file with the content
        include dirname(__DIR__) . '/views/layouts/main.php';
        
        // Output the complete page
        echo ob_get_clean();
    }
}