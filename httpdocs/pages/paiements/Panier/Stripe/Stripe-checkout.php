<?php
ob_start();
session_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../../Configurations_bdd.php');
require_once('../../../../Configurations.php');
require_once('../../../../Configurations_stripe_keys.php');
require_once('../../../../vendor/autoload.php');
////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../../../";
require_once('../../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');
$lasturl = $_SERVER['HTTP_REFERER'];
if (isset($user)) {
    $idaction = $_POST['idaction'];
    $line = [];
  /*   $id_reservation = null; */
    //////////////////////////////SELECT BOUCLE
    $req_boucle2 = $bdd->prepare("SELECT * FROM membres_panier_details WHERE pseudo=? ORDER BY id ASC");
    $req_boucle2->execute(array($user));

   
    /*  var_dump($ligne_prestataire); */
    if ($ligne_prestataire) {
        $id_prestataire = $ligne_prestataire['id_prestataire'];
        $req_paiement = $bdd->prepare("SELECT * FROM membres_profil_paiement WHERE id_membre=?");
        $req_paiement->execute(array($id_prestataire));
        $ligne_paiement = $req_paiement->fetch();
        $req_paiement->closeCursor();
        if ($ligne_paiement && isset($ligne_paiement['id_account'])) {
            $account_vendeur = $ligne_paiement['id_account'];
        } else {
            $account_vendeur = null;
        }
    }
    while ($ligne_boucle2 = $req_boucle2->fetch()) {
        $type = $ligne_boucle2['action_module_service_produit'];
        if ($ligne_boucle2['action_module_service_produit'] == "Abonnement") {
            $id_abonnement = $ligne_boucle2['id_panier_SERVICE_PRODUIT'];
            $libelle = $ligne_boucle2['libelle'];
            $TVA = sprintf('%.2f', $ligne_boucle2['TVA']);
            $TVA_TAUX = $ligne_boucle2['TVA_TAUX'];
            $PU_HT = sprintf('%.2f', $ligne_boucle2['PU_HT']);
            $quantite = $ligne_boucle2['quantite'];
            $PU_HT_total = sprintf('%.2f', (($PU_HT * $quantite)));
            $PU_TTC_total = sprintf('%.2f', ($PU_HT_total + $TVA));

            // Instead of using price ID directly, create a price with tax included
            $line[] = [
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'EUR',
                    'recurring' => [
                        'interval' => 'month',
                    ],
                    'product_data' => [
                        'name' => $libelle,
                        'metadata' => [
                            'type' => 'Abonnement'
                        ]
                    ],
                    'unit_amount' => round($PU_TTC_total * 100) // Convert to cents and include tax
                ]
            ];
            $calcul_tt = $calcul_tt + $PU_HT_total;
            $mode = "subscription";
        } elseif ($ligne_boucle2['action_module_service_produit'] == "Carte grise") {
            $id_carte = $ligne_boucle2['id_panier_SERVICE_PRODUIT'];
            $libelle = !empty($ligne_boucle2['libelle']) ? $ligne_boucle2['libelle'] : 'Carte grise';
            $TVA = sprintf('%.2f', $ligne_boucle2['TVA']);
            $TVA_TAUX = $ligne_boucle2['TVA_TAUX'];
            $PU_HT = sprintf('%.2f', $ligne_boucle2['PU_HT']);
            $quantite = $ligne_boucle2['quantite'];
            $PU_HT_total = sprintf('%.2f', (($PU_HT * $quantite)));
            $PU_TTC_total = sprintf('%.2f', ($PU_HT_total + $TVA));
            
            // Ensure we have a product name that's not empty
            $product_name = !empty($libelle) ? $libelle : 'Carte grise';
            error_log("Product name for line item: " . $product_name);
            
            $line[] = [
                'quantity' => $ligne_boucle2['quantite'],
                'price_data' => [
                    'currency' => 'EUR',
                    'product_data' => [
                        'name' => $product_name,
                        'metadata' => [
                            'type' => 'Carte grise'
                        ]
                    ],
                    'unit_amount' => round($PU_TTC_total * 100)
                ]
            ];
            $mode = "payment";
            
        }
    }
    $req_boucle2->closeCursor();
    
    // Add debug to see what's in the line items
    error_log("Line items before Stripe: " . print_r($line, true));
    
    if ($type == "Abonnement") {
        //var_dump($line);

        $session = $stripe->checkout->sessions->create([
            // 'paynmet_method_types' => ['card'],
            'mode' => "$mode",
            'line_items' => $line,
            //'payment_method_types' => ['card', 'paypal'],
            'metadata' => [
                'abonnement_id' => $id_abonnement,
                'id_membre' => $id_oo,
                'type' => "$type",
            ],
            //'automatic_payment_intent' => false,
            'success_url' => "https://$nomsiteweb/Paiement/Success",
            'cancel_url' => "https://$nomsiteweb/Paiement/Cancel",
        ]);
    } else if ($type == "Carte grise") {
        //var_dump($line);
        // $commission = round(($calcul_tt * $commission_plateforme) * 100);
        $session = $stripe->checkout->sessions->create([
            // 'paynmet_method_types' => ['card'],
            'mode' => "$mode",
            'line_items' => $line,
            //'payment_method_types' => ['card', 'paypal'],
            'metadata' => [
                'id_membre' => $id_oo,
                'type' => 'Carte grise',
                'id_carte' => $id_carte,  // Use the correct id from cart details
                'libelle' => $ligne_boucle2['libelle']
            ],
            // 'payment_intent_data' => [
            //     'application_fee_amount' => $commission * 100, //commission plateforme
            //     'transfer_data' => ['destination' => $account_vendeur], //id_account du vendeur
            //     //'receipt_email' => 'julio@codi-one.fr',
            // ],
            //'automatic_payment_intent' => false,
            'success_url' => "https://$nomsiteweb/Paiement/Success", // Fixed URL
            'cancel_url' => "https://$nomsiteweb/Paiement/Cancel",
        ]);
        error_log("Created Stripe session with metadata: " . print_r($session->metadata, true));
    }
    $result = array("Texte_rapport" => "Modifiée avec succès !", "retour_validation" => "ok", "retour_lien" => "$session->url");
    $result = json_encode($result);
    echo $result;
}