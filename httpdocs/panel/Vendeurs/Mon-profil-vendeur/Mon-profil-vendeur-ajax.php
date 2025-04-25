<?php
// Clean start
ob_clean();
ob_start();

// Set proper content type
header('Content-Type: application/json');

// Define default response
$response = [
    "Texte_rapport" => "Une erreur s'est produite.",
    "retour_validation" => "error"
];

try {
    require_once('../../../Configurations_bdd.php');
    require_once('../../../Configurations.php');
    require_once('../../../Configurations_modules.php');
    require_once('../../../Configurations_stripe_keys.php');

    $dir_fonction = "../../../";
    require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

    if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {

        $req_select = $bdd->prepare("SELECT * FROM membres WHERE id = ? ");
        $req_select->execute(array($id_oo));
        $profile_data = $req_select->fetch();
        $req_select->closeCursor();

        $prenom = $profile_data['prenom'];
        $nom = $profile_data['nom'];
        $adresse = $profile_data['adresse'];
        $cp = $profile_data['cp'];
        $ville = $profile_data['ville'];
        $country = $profile_data['pays_naissance'] ? $profile_data['pays_naissance'] : "FR";

        $telephone = !empty($Telephone_oo) ? $Telephone_oo : $Telephone_portable_oo;
        error_log("Original phone: $telephone");
        
        // Ensure phone is in E.164 format for Stripe
        if (!empty($telephone)) {
            // Clean the number first (remove spaces, dashes, parentheses)
            $telephone = preg_replace('/\D/', '', $telephone);
            
            // Format French number if needed
            if (substr($telephone, 0, 1) == '0') {
                $telephone = '+33' . substr($telephone, 1);
            } else if (substr($telephone, 0, 1) != '+') {
                $telephone = '+' . $telephone;
            }
            
            error_log("Formatted phone: $telephone");
        }

        try {
            $req_select = $bdd->prepare("SELECT * FROM membres_profil_paiement WHERE id_membre = ? ");
            $req_select->execute(array($id_oo));
            $profile_p = $req_select->fetch();
            $req_select->closeCursor();

            if (empty($profile_p["id"])) {
                // Create stripe account with proper phone formatting
                $customer = $stripe->accounts->create([
                    'country' => !empty($country) ? $country : "FR",
                    'type' => 'express',
                    'email' => !empty($mail_oo) ? $mail_oo : null,
                    'capabilities' => [
                        'card_payments' => ['requested' => true],
                        'transfers' => ['requested' => true],
                    ],
                    'business_type' => 'individual',
                    'business_profile' => [
                        'url' => !empty($url) ? $url : "https://$nomsiteweb",
                        'support_phone' => $telephone
                    ],
                    'individual' => [
                        'first_name' => !empty($prenom) ? $prenom : "",
                        'last_name' => !empty($nom) ? $nom : "",
                        'address' => [
                            'line1' => !empty($adresse) ? $adresse : "",
                            'postal_code' => !empty($cp) ? $cp : "",
                            'city' => !empty($ville) ? $ville : ""
                        ],
                        'email' => !empty($mail_oo) ? $mail_oo : "",
                        'phone' => $telephone
                    ],
                    'settings' => [
                        'payouts' => ['schedule' => ['interval' => 'manual']]
                    ]
                ]);

                if ($customer->id) {
                    // Set profile_complet to 'non' until onboarding is complete
                    $sql_insert = $bdd->prepare("INSERT INTO membres_profil_paiement
                    (id_membre, pseudo, id_account, profil_complet)
                    VALUES (?,?,?,?)");
                    $sql_insert->execute([
                        $id_oo,
                        $user,
                        $customer->id,
                        'non' // Changed from 'oui' to 'non'
                    ]);
                    $sql_insert->closeCursor();
                    
                    $accountLink = $stripe->accountLinks->create([
                        'account' => $customer->id,
                        'refresh_url' => "https://$nomsiteweb/Mon-profil-vendeur/refresh",
                        'return_url' => "https://$nomsiteweb/Mon-profil-vendeur/return",
                        'type' => 'account_onboarding',
                    ]);

                    $response = [
                        "Texte_rapport" => "Redirection vers Stripe pour compléter votre profil.",
                        "retour_validation" => "ok",
                        "retour_lien" => $accountLink->url
                    ];
                }
            } else {
                // User already has an account, create a new link to resume onboarding
                try {
                    $accountLink = $stripe->accountLinks->create([
                        'account' => $profile_p["id_account"],
                        'refresh_url' => "https://$nomsiteweb/Mon-profil-vendeur/refresh",
                        'return_url' => "https://$nomsiteweb/Mon-profil-vendeur/return",
                        'type' => 'account_onboarding',
                    ]);
                    
                    $response = [
                        "Texte_rapport" => "Reprendre la configuration de votre compte Stripe.",
                        "retour_validation" => "ok",
                        "retour_lien" => $accountLink->url
                    ];
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    $response = [
                        "Texte_rapport" => "Erreur Stripe : " . $e->getMessage(),
                        "retour_validation" => "error"
                    ];
                    
                    // Log the error for debugging
                    error_log("Stripe Error: " . $e->getMessage() . " with phone: $telephone");
                }
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log("Stripe Error: " . $e->getMessage() . ", Phone format: $telephone");
            $response = [
                "Texte_rapport" => "Erreur Stripe : " . $e->getMessage(),
                "retour_validation" => "error"
            ];
        }
    } else {
        $response = [
            "Texte_rapport" => "Vous devez être connecté pour effectuer cette action.",
            "retour_validation" => "error"
        ];
    }
} catch (Exception $e) {
    $response = [
        "Texte_rapport" => "Erreur système: " . $e->getMessage(),
        "retour_validation" => "error"
    ];
    error_log("System error in Stripe onboarding: " . $e->getMessage());
}

// Ensure clean output
ob_end_clean();
echo json_encode($response);
exit;
