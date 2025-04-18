<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

$modif = "oui";

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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {

	include("" . $dir_fonction . "function/inscription/controle_inscriptions.php");

	///////////////////////////////////////////////////////////////////////////////SI ERREUR
	if ($err != 1) {

		if (!empty($_POST['password']) && !empty($_POST['passwordclient2'])) {

			$passwordupdate = hash("sha256", $_POST['password']);

			///////////////////////////////UPDATE
			$sql_update = $bdd->prepare("UPDATE membres SET 
	pass=?
	WHERE pseudo=?");
			$sql_update->execute(array(
				htmlspecialchars($passwordupdate),
				$user
			));
			$sql_update->closeCursor();
		}

		if (!empty($Code_postal)) {
			$departement = substr($Code_postal, 0, 2);
		}

		$adresse = "$Adresse $Ville $Code_postal"; // Concatenate address
		$coordonnees = getLatLong($adresse); // Get latitude and longitude

		if ($coordonnees) {

			///////////////////////////////UPDATE
			$sql_update = $bdd->prepare("UPDATE membres SET 
	date_update=?, 
	date_update_ip=?, 
	nom=?, 
	prenom=?,
	civilites=?,
	newslettre=?,
	mail=?, 
	Pays=?, 
	Telephone=?, 
	Telephone_portable=?, 
	adresse=?, 
	cp=?, 
	departement=?, 
	ville=?,
	nom_professionnel=?,
	nom_commercial=?,
	longitude=?,
	latitude=?
	WHERE pseudo=?");
			$sql_update->execute(array(
				time(),
				$_SERVER['REMOTE_ADDR'],
				htmlspecialchars($Nom),
				htmlspecialchars($Prenom),
				htmlspecialchars($FH),
				htmlspecialchars($newslettre),
				htmlspecialchars($Mail),
				htmlspecialchars($Pays),
				htmlspecialchars($Telephone),
				htmlspecialchars($Telephone_portable),
				htmlspecialchars($Adresse),
				htmlspecialchars($Code_postal),
				htmlspecialchars($departement),
				htmlspecialchars($Ville),
				htmlspecialchars($Nom_societe),
				htmlspecialchars($nom_commercial),
				$coordonnees['longitude'],
				$coordonnees['latitude'],
				$user
			));
			$sql_update->closeCursor();

			if ($statut_compte_oo == 1) {

				///////////////////////////////SELECT
				$req_select = $bdd->prepare("SELECT * FROM membres_professionnel WHERE pseudo=?");
				$req_select->execute(array($user));
				$ligne_select = $req_select->fetch();
				$req_select->closeCursor();
				$id_pro = $ligne_select['id'];

				if (empty($id_pro)) {

					///////////////////////////////INSERT
					$sql_insert = $bdd->prepare("INSERT INTO membres_professionnel
	(id_membre,
	pseudo)
	VALUES (?,?)");
					$sql_insert->execute(array(
						$id_oo,
						$user
					));
					$sql_insert->closeCursor();
				}

				$sql_update = $bdd->prepare("UPDATE membres_professionnel SET
	Nom_societe=?,
	Numero_identification=?
	WHERE pseudo=?");
				$sql_update->execute(array(
					htmlspecialchars($Nom_societe),
					htmlspecialchars($Numero_identification),
					$user
				));
				$sql_update->closeCursor();
			}


			if ($statut_compte_oo > 1) {

				$sql_update = $bdd->prepare("UPDATE membres_professionnel SET
				Nom_societe=?,
				Numero_identification=?
				WHERE pseudo=?");
				$sql_update->execute(array(
					htmlspecialchars($Nom_societe),
					htmlspecialchars($Numero_identification),
					$user
				));
				$sql_update->closeCursor();
			}

			// Save insurance data
			if (isset($_POST['company_name']) || isset($_POST['contract_number'])) {
				try {
					// Check if a record already exists for this user
					$req_check = $bdd->prepare("SELECT id FROM membres_insurance WHERE id_membre = ?");
					$req_check->execute(array($id_oo));
					$exists = $req_check->fetch();
					$req_check->closeCursor();
					
					if ($exists) {
						// Update existing record
						$sql_update = $bdd->prepare("UPDATE membres_insurance SET 
							company_name = ?,
							contract_number = ?,
							green_card_number = ?,
							valid_from = ?,
							valid_to = ?,
							agency_name = ?,
							agency_office = ?,
							agency_address = ?,
							agency_country = ?,
							agency_email = ?,
							updated_at = NOW()
							WHERE id_membre = ?");
						$sql_update->execute(array(
							htmlspecialchars($_POST['company_name']),
							htmlspecialchars($_POST['contract_number']),
							htmlspecialchars($_POST['green_card_number']),
							!empty($_POST['valid_from']) ? intval($_POST['valid_from']) : null,
							!empty($_POST['valid_to']) ? intval($_POST['valid_to']) : null,
							htmlspecialchars($_POST['agency_name']),
							htmlspecialchars($_POST['agency_office']),
							htmlspecialchars($_POST['agency_address']),
							htmlspecialchars($_POST['agency_country']),
							htmlspecialchars($_POST['agency_email']),
							$id_oo
						));
						$sql_update->closeCursor();
					} else {
						// Insert new record
						$sql_insert = $bdd->prepare("INSERT INTO membres_insurance (
							id_membre,
							company_name,
							contract_number,
							green_card_number,
							valid_from,
							valid_to,
							agency_name,
							agency_office,
							agency_address,
							agency_country,
							agency_email)
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
						$sql_insert->execute(array(
							$id_oo,
							htmlspecialchars($_POST['company_name']),
							htmlspecialchars($_POST['contract_number']),
							htmlspecialchars($_POST['green_card_number']),
							!empty($_POST['valid_from']) ? intval($_POST['valid_from']) : null,
							!empty($_POST['valid_to']) ? intval($_POST['valid_to']) : null,
							htmlspecialchars($_POST['agency_name']),
							htmlspecialchars($_POST['agency_office']),
							htmlspecialchars($_POST['agency_address']),
							htmlspecialchars($_POST['agency_country']),
							htmlspecialchars($_POST['agency_email'])
						));
						$sql_insert->closeCursor();
					}
				} catch (Exception $e) {
					error_log("Error saving insurance data: " . $e->getMessage());
				}
			}

			// Save driver license data
			if (isset($_POST['license_number']) || isset($_POST['license_category'])) {
				try {
					// Check if a record already exists for this user
					$req_check = $bdd->prepare("SELECT id FROM membres_driver_license WHERE id_membre = ?");
					$req_check->execute(array($id_oo));
					$exists = $req_check->fetch();
					$req_check->closeCursor();
					
					if ($exists) {
						// Update existing record
						$sql_update = $bdd->prepare("UPDATE membres_driver_license SET 
							license_number = ?,
							license_category = ?,
							license_valid_until = ?,
							license_issue_date = ?,
							license_issue_place = ?,
							license_country = ?,
							license_restrictions = ?,
							license_authority = ?,
							updated_at = NOW()
							WHERE id_membre = ?");
						$sql_update->execute(array(
							htmlspecialchars($_POST['license_number']),
							htmlspecialchars($_POST['license_category']),
							!empty($_POST['license_valid_until']) ? intval($_POST['license_valid_until']) : null,
							!empty($_POST['license_issue_date']) ? intval($_POST['license_issue_date']) : null,
							htmlspecialchars($_POST['license_issue_place']),
							htmlspecialchars($_POST['license_country']),
							htmlspecialchars($_POST['license_restrictions']),
							htmlspecialchars($_POST['license_authority']),
							$id_oo
						));
						$sql_update->closeCursor();
					} else {
						// Insert new record
						$sql_insert = $bdd->prepare("INSERT INTO membres_driver_license (
							id_membre,
							license_number,
							license_category,
							license_valid_until,
							license_issue_date,
							license_issue_place,
							license_country,
							license_restrictions,
							license_authority)
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
						$sql_insert->execute(array(
							$id_oo,
							htmlspecialchars($_POST['license_number']),
							htmlspecialchars($_POST['license_category']),
							!empty($_POST['license_valid_until']) ? intval($_POST['license_valid_until']) : null,
							!empty($_POST['license_issue_date']) ? intval($_POST['license_issue_date']) : null,
							htmlspecialchars($_POST['license_issue_place']),
							htmlspecialchars($_POST['license_country']),
							htmlspecialchars($_POST['license_restrictions']),
							htmlspecialchars($_POST['license_authority'])
						));
						$sql_insert->closeCursor();
					}
					
					error_log("Driver license data saved successfully for user ID: " . $id_oo);
				} catch (Exception $e) {
					error_log("Error saving driver license data: " . $e->getMessage());
				}
			}

			$mail_compte_concerne = $Mail;
			$module_log = "PROFIL";
			$action_sujet_log = "Notification de modification de vos données personnelles";
			$action_libelle_log = "Notification de votre compte <b>$mail_compte_concerne</b> sur $nomsiteweb. Vos données personnelles ont été modifiées sur votre espace utilisateur.
       Si vous n'êtes pas à l'origine des modifications de vos informations personnelles, veuillez sans attendre contacter un administrateur sur la page
	<a href='" . $http . "" . $nomsiteweb . "/Contact' target='blank_' style='text-decoration: underline;' >Contact</a>";
			$action_log = "MODIFICATION";
			$niveau_log = "2";
			$compte_bloque = "";
			//log_h($mail_compte_concerne,$module_log,$action_sujet_log,$action_libelle_log,$action_log,$niveau_log,$compte_bloque);

			$result2 = array("Texte_rapport" => "Modifications effectuées ! ", "retour_validation" => "ok", "retour_lien" => "");
		} else {
			// Return error message if address not found
			$result2 = array("Texte_rapport" => "L'adresse n'a pas été trouvée !", "retour_validation" => "ok", "retour_lien" => "");
			$result2 = json_encode($result2);
			echo $result2;
		}
	}

	///////////////////////////////////////////////////////////////////////////////ACTION OK

	$result2 = json_encode($result2);
	echo $result2;
} else {
	header('location:/');
}

ob_end_flush();
