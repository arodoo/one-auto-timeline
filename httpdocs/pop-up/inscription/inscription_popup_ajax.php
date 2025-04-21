<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

/*****************************************************\
* Adresse e-mail => direction@codi-one.fr             *
* La conception est assujettie à une autorisation     *
* spéciale de codi-one.com. Si vous ne disposez pas de*
* cette autorisation, vous êtes dans l'illégalité.    *
* L'auteur de la conception est et restera            *
* codi-one.fr                                         *
* Codage, script & images (all contenu) sont réalisés * 
* par codi-one.fr                                     *
* La conception est à usage unique et privé.          *
* La tierce personne qui utilise le script se porte   *
* garante de disposer des autorisations nécessaires   *
*                                                     *
* Copyright ... Tous droits réservés auteur (Fabien B)*
\*****************************************************/

/////////////////////////////////////////////////////////////////////////////////////////////ACTION INSCRIPTION CONTRÔLES
if (!isset($_SESSION['creation_compte_ok'])) {
	require_once("../../function/inscription/controle_inscriptions.php");
	//echo "$result_erreur";
}
/////////////////////////////////////////////////////////////////////////////////////////////ACTION INSCRIPTION CONTRÔLES

/////////////////////////////////////////////////////////////////////////////////////////////ACTION INSCRIPTION
if (
	$err == 0 && !empty($_POST['Mail']) && !empty($_POST['password']) && $_POST['Type_inscription'] == "Abonnement" ||
	$err == 0 && !empty($_POST['Mail']) && !empty($_POST['password']) ||
	$err == 0 && !empty($_POST['Mail']) && !empty($_POST['password']) && ($pseudo_manuel == "" or $pseudo_manuel == "non")
) {
	$lasturl = $_SERVER['HTTP_REFERER'];

	$adresse = "$Adresse $Ville $Code_postal";

	$latitude = isset($_POST['lat']) ? $_POST['lat'] : null;
	$longitude = isset($_POST['lng']) ? $_POST['lng'] : null;

	$coordonnees = [$latitude, $longitude];

	if ($coordonnees) {

		$compte = creation_compte2($_POST, $mode_manuel = "");

		 // After successful registration, check for invitation token
		if (isset($_POST['invitation_token']) && !empty($_POST['invitation_token'])) {
			require_once('../../includes/utils/process-invitation.php');
			
			$token = $_POST['invitation_token'];
			$user_id = $compte[0]; // Assuming this contains the new user ID
			
			$result = process_invitation_token($token, $user_id);
			
			// If token processed successfully, add flag for subscription banner
			if ($result['success'] && isset($result['show_banner'])) {
				$_SESSION['show_subscription_banner'] = true;
				$_SESSION['has_pending_constats'] = true;
			}
		}

		//Si mode inscription avec envoi par mail avec un lien de confirmation
		if ($mod_inscription == 0) {
			$message_confirmation_inscription_mail = "<br /><br /> Le message que vous venez de recevoir contient un lien qui vous permettra de finaliser votre inscription.";
		}

		//Si mode inscription - Connexion automatique après inscription
		if ($mod_inscription == 2) {

			if ($compte['3'] == 1 || $compte['3'] == 6) {
				$result = array("Texte_rapport" => "<div style='text-align: center;'></div>", "retour_validation" => "ok", "retour_lien" => "/Gestion-de-votre-compte.html");
			} else {
				$result = array("Texte_rapport" => "<div style='text-align: center;'></div>", "retour_validation" => "ok", "retour_lien" => "/Guide");
			}

		} else {

			//Si mode inscription avec envoi par mail
			$result = array("Texte_rapport" => "<div style='text-align: center;'><h3> Félicitations !</h3> Un e-mail a été envoyé sur votre adresse (l'e-mail envoyé lors de votre inscription peut se trouver dans vos courriers indésirables)<br /><br />Nous vous remercions pour votre inscription. $message_confirmation_inscription_mail </div>", "retour_validation" => "ok", "retour_lien" => "");
		}
		$_SESSION['creation_compte_ok'] = "oui";

	} else {
		$result = array("Texte_rapport" => "Votre adresse n'a pas été trouvée !", "retour_validation" => "", "retour_lien" => "");
	}

}

$result = json_encode($result);
echo $result;

ob_end_flush();
?>