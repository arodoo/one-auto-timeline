<?php
ob_start();
header('Content-Type: application/json');

// INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

// INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {

    $produit_id = $_POST['produit_id'];
    $quantity = $_POST['quantity']; // Obtener la cantidad seleccionada
    
    $query = $bdd->prepare("SELECT * FROM membres_produits WHERE id = :produit_id");
    $query->bindParam(':produit_id', $produit_id, PDO::PARAM_INT);
    $query->execute();
    $produit = $query->fetch(PDO::FETCH_ASSOC);

    if ($produit) {
        $libelle_details_article = $produit['nom_produit'];
        $libelle_quantite_article = $quantity; // Usar la cantidad seleccionada
        $libelle_prix_article = $produit['montant_livraison'];
        $libelle_tva_article = ''; 
        $libelle_taux_tva_article = ''; 
        $action_module_apres_paiement = ''; 
        $action_parametres_valeurs_explode = '';
        $libelle_id_article = $produit['id'];
        $pseudo_panier = $produit['pseudo'];

        ajout_panier(
            $libelle_details_article,
            $libelle_quantite_article,
            $libelle_prix_article,
            $libelle_tva_article,
            $libelle_taux_tva_article,
            $action_module_apres_paiement,
            $action_parametres_valeurs_explode,
            $libelle_id_article,
            $pseudo_panier,
            $produit_id
        );

        $response = ['status' => 'success'];
    } else {
        $response = [
            "Texte_rapport" => "Produit non trouvé. Impossible de réaliser le processus.",
            "retour_validation" => "erreur"
        ];
    }
} else {
    $response = [
        "Texte_rapport" => "Utilisateur non authentifié.",
        "retour_validation" => "erreur"
    ];
}

echo json_encode($response);
ob_end_flush();
?>
