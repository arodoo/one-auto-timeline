<?php
ob_start();
header('Content-Type: application/json');
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

$dir_fonction = "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {

	try {
		$action = $_POST['action'];
		$idaction = $_POST['idaction'];
		$id_type_compte_categorie = $_POST['id_type_compte_categorie'];
		$nom = $_POST['nom'];
		$description = $_POST['description'];
		$departement = $_POST['departement'];
		$statut = $_POST['statut'];

		// Vérification des champs
		if (empty($statut) || empty($nom) || empty($description) || empty($departement) || empty($id_type_compte_categorie)) {
			$result = array("Texte_rapport" => "Tous les champs obligatoires doivent être remplis.", "retour_validation" => "erreur");
		} else {
			if ($action == "modifier-action") {
				$sql_update = $bdd->prepare("UPDATE membres_annonces_clients SET 
					statut = ?, 
					date = ?, 
					nom = ?, 
					description = ?, 
					id_type_compte_categorie = ?, 
					departement = ?
					WHERE id = ? 
					AND id_membre = ?");
				$sql_update->execute(array(
					$statut,
					time(),
					$nom,
					$description,
					$id_type_compte_categorie,
					$departement,
					$idaction,
					$id_oo
				));
				$sql_update->closeCursor();

				// Mise à jour du champ lien_produit
				$sql_update = $bdd->prepare("UPDATE membres_devis SET objet_de_la_demande = ?, description_de_la_demande=? WHERE id_annonce_client = ?");
				$sql_update->execute(array($nom, $description, $idaction));
				$sql_update->closeCursor();

				$result = array("Texte_rapport" => "Annonce modifiée !", "retour_validation" => "ok", "retour_lien" => "");
			}

			if ($action == "ajouter-action") {
				$sql_insert = $bdd->prepare("INSERT INTO membres_annonces_clients (statut, date, nom, description, id_type_compte_categorie, departement, id_membre, pseudo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
				$sql_insert->execute(array(
					$statut,
					time(),
					$nom,
					$description,
					$id_type_compte_categorie,
					$departement,
					$id_oo,
					$pseudo_oo
				));
				$id_annonce = $bdd->lastInsertId();
				$sql_insert->closeCursor();

				if ($statut == "activé") {

					// SELECT
					$req_select = $bdd->prepare("SELECT * FROM dpts WHERE id=?");
					$req_select->execute(array($departement));
					$ligne_select = $req_select->fetch();
					$req_select->closeCursor();
					$code = $ligne_select['code'];

					$type = "";
					if ($id_type_compte_categorie == 2) {
						$type = "dépannage";
					} elseif ($id_type_compte_categorie == 3 || $id_type_compte_categorie == 4) {
						$type = "annonce";
					} elseif ($id_type_compte_categorie == 6) {
						$type = "service";
					}

					$sql_select = $bdd->prepare("SELECT * FROM membres WHERE statut_compte = ? AND LEFT(cp, 2) = ?");
					$sql_select->execute(array($id_type_compte_categorie, $code));
					while ($row = $sql_select->fetch()) {

						$sql_insert = $bdd->prepare("INSERT INTO membres_devis (id_membre_utilisateur, id_membre_depanneur, objet_de_la_demande, description_de_la_demande, date_demande, date_statut, statut_devis, type, membres_annonces_clients_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
						$sql_insert->execute(array(
							$id_oo,
							$row['id'],
							$nom,
							$description,
							time(),
							time(),
							"Non traité",
							$type,
							$id_annonce
						));
						$membres_devis_id = $bdd->lastInsertId();
						
						$sql_insert->closeCursor();

						$de_nom = "$nomsiteweb"; // Nom de l'envoyeur
						$de_mail = "$emaildefault"; // Email de l'envoyeur
						$vers_nom = $row['prenom'] . " " . $row['nom']; // Nom du receveur
						$vers_mail = $row['mail']; // Email du receveur
						$sujet = "Nouveau devis sur $nomsiteweb"; // Sujet du mail
						$message_principalone = "<b>Bonjour " . $row['prenom'] . ",</b><br /><br />
							Vous avez une demande de devis envoyé par " . $prenom_oo . " " . $non_oo . ".<br />
							Objet de la demande : " . $nom . "<br />
							Connectez vous à votre espace dépanneur pour consulter la demande.<br />
							En cliquant <a href='" . $http . "" . $nomsiteweb . "/Devis/modifier/" . $membres_devis_id . "' target='blank_'>ici</a><br /><br />
							PS: Ne pas répondre à l'e-mail.<br />
							Cordialement,<br /><br />";
						mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);
					}
						$sql_select->closeCursor();
				}

				$result = array("Texte_rapport" => "La demande à été envoyée !", "retour_validation" => "ok", "retour_lien" => "");
			}
		}

		echo json_encode($result);

	} catch (Exception $e) {
		error_log("Error in Mes-annonces-client-action-ajouter-modifier-ajax.php: " . $e->getMessage());
		$result = array(
			"Texte_rapport" => "Une erreur est survenue. Détails: " . $e->getMessage(),
			"retour_validation" => "erreur",
			"variables" => array(
				"action" => $action,
				"idaction" => $idaction,
				"id_type_compte_categorie" => $id_type_compte_categorie,
				"nom" => $nom,
				"description" => $description,
				"departement" => $departement,
				"statut" => $statut
			),
			"tokens" => array(
				"statut",
				"time()",
				"nom",
				"description",
				"id_type_compte_categorie",
				"departement",
				"idaction",
				"id_oo",
				"pseudo_oo"
			)
		);
		echo json_encode($result);
	}

} else {
	header('location: /');
}

ob_end_flush();
?>
