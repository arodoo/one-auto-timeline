<?php
ob_start();
header('Content-Type: application/json'); 

require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

// Mostrar en consola el dato recibido
if (isset($_POST['id'])) {
    $avisId = $_POST['id'];
    error_log("ID de l'avis reçu: " . $avisId);

    // Eliminar el avis de la tabla membres_avis
    $req_delete_avis = $bdd->prepare("DELETE FROM membres_avis WHERE id = ?");
    if ($req_delete_avis->execute(array($avisId))) {
        $result = array("Texte_rapport" => "Avis supprimé avec succès !", "retour_validation" => "ok");
    } else {
        $result = array("Texte_rapport" => "* Erreur lors de la suppression de l'avis *", "retour_validation" => "");
    }
} else {
    $result = array("Texte_rapport" => "* ID de l'avis non reçu *", "retour_validation" => "");
}

echo json_encode($result);
ob_end_flush();
?>