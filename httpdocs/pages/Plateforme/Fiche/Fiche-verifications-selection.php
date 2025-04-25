<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

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

	$mission_id = $_POST['mission_id'];
	$extra_id = $_POST['extra_id'];

	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT * FROM membres_etablissements_demandes_missions WHERE id_membre=? AND id_prestataire=? AND id_etablissement_mission=?");
	$req_select->execute(array($id_oo, $extra_id, $mission_id));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();

	$sql = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id_membre=?");
	$sql->execute(array($extra_id));
	$extra = $sql->fetch();
	$sql->closeCursor();

	$sql = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id=?");
	$sql->execute(array($mission_id));
	$mission = $sql->fetch();
	$sql->closeCursor();
	$date_debut = $mission['date_debut'];
	$date_fin = $mission['date_fin'];

	if (!empty($mission_id)) {

		if (empty($ligne_select['id'])) {

			$_SESSION['sql_requete_verif'] = "";
			$_SESSION['params_verif'] = [];
			array_push($_SESSION['params_verif'], $extra_id);

			$date_debut = $date_debut;
			$date_fin = ($date_fin + 86340);

			// Récupère le numéro du jour de la semaine (1 = lundi, 7 = dimanche)
			$jourSemaine = date('N');
			// Vérifie si le numéro est pair ou impair
			if ($jourSemaine % 2 == 0) {
				$jourSemainePairImpair = "";
			} else {
				$jourSemainePairImpair = "2";
			}

			function obtenirJoursSemaine($date_debut, $date_fin)
			{

				global $bdd;

				// Calculer le nombre de jours dans l'intervalle
				$jour_nbr = ($date_fin - $date_debut) / 86400;
				$jour_nbr = round($jour_nbr);
				// Initialiser le tableau qui contiendra les jours de la semaine
				$joursSemaine = array();
				// Boucler sur le nombre de jours et ajouter chaque jour au tableau

				if (empty($_SESSION['courrent_mission_id'])) {
					for ($i = 0; $i < $jour_nbr; $i++) {
						// Calculer le timestamp du jour actuel dans la boucle
						$timestamp = $date_debut + ($i * 86400);
						// Obtenir le jour de la semaine correspondant au timestamp
						$jourSemaine = strftime('%A', $timestamp);
						// Ajouter le jour de la semaine au tableau
						array_push($joursSemaine, $jourSemaine);
					}
				} else {

					///////////////////////////////SELECT
					$req_select = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id=?");
					$req_select->execute(array($_SESSION['courrent_mission_id']));
					$ligne_select = $req_select->fetch();
					$req_select->closeCursor();

					if ($ligne_select['lundi'] == "oui") {
						array_push($joursSemaine, "Monday");
					}
					if ($ligne_select['mardi'] == "oui") {
						array_push($joursSemaine, "Tuesday");
					}
					if ($ligne_select['mercredi'] == "oui") {
						array_push($joursSemaine, "Wednesday");
					}
					if ($ligne_select['jeudi'] == "oui") {
						array_push($joursSemaine, "Thursday");
					}
					if ($ligne_select['vendredi'] == "oui") {
						array_push($joursSemaine, "Friday");
					}
					if ($ligne_select['samedi'] == "oui") {
						array_push($joursSemaine, "Saturday");
					}
					if ($ligne_select['dimanche'] == "oui") {
						array_push($joursSemaine, "Sunday");
					}
				}

				return $joursSemaine;
			}

			function obtenirJoursSemaine2($date_debut, $date_fin)
			{

				// Calculer le nombre de jours dans l'intervalle
				$jour_nbr = ($date_fin - $date_debut) / 86400;
				$jour_nbr = round($jour_nbr);
				// Initialiser le tableau qui contiendra les jours de la semaine
				$joursSemaine = array();
				// Boucler sur le nombre de jours et ajouter chaque jour au tableau


				for ($i = 0; $i < $jour_nbr; $i++) {
					// Calculer le timestamp du jour actuel dans la boucle
					$timestamp = $date_debut + ($i * 86400);
					// Obtenir le jour de la semaine correspondant au timestamp
					$jourSemaine = strftime('%A', $timestamp);
					// Ajouter le jour de la semaine au tableau
					array_push($joursSemaine, $jourSemaine);
				}

				return $joursSemaine;
			}

			$joursInterval = obtenirJoursSemaine($date_debut, $date_fin);

			$req_boucle = $bdd->prepare("SELECT * FROM membres_etablissements_indisponibilites WHERE 
	(date_debut<=? AND date_fin>=?) AND type != 'Mission'
	");
			$req_boucle->execute(array($date_debut, $date_fin)); //(date_debut>=? AND date_fin<=?) OR  OR (date_fin>=? AND date_debut<=?) OR (date_fin>=? AND date_debut<=?)
			while ($ligne = $req_boucle->fetch()) { //$date_debut, $date_fin,,$date_debut, $date_debut, $date_fin, $date_fin

				$_SESSION['sql_requete_verif'] .= "AND NOT(m.id_membre=?)";
				array_push($_SESSION['params_verif'], $ligne['id_membre']);
			}


			////////////////////INDISPONIBILITES EXACT SELON LA MISSION DU PRO
			$req_boucle = $bdd->prepare("SELECT * FROM membres_etablissements_indisponibilites WHERE 
		(date_debut>=? AND date_fin<=?) OR
		(date_debut<=? AND date_fin>=?)
		OR (date_fin>=? AND date_debut<=?) OR (date_fin>=? AND date_debut<=?) AND type = 'Mission'
		");
			$req_boucle->execute(array($date_debut, $date_fin, $date_debut, $date_fin, $date_debut, $date_debut, $date_fin, $date_fin)); //  
			while ($ligne = $req_boucle->fetch()) {
				$creneau_matin = $ligne['creneau_matin'];
				$creneau_midi = $ligne['creneau_midi'];
				$creneau_soir = $ligne['creneau_soir'];
				$type = $ligne['type']; // Indisponibilité, Réservé

				$count = 0;
				$req_select = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id=?");
				$req_select->execute(array($mission_id));
				$ligne_select_et = $req_select->fetch();
				$req_select->closeCursor();
				$creneau_matin_et = $ligne_select_et['creneau_matin'];
				$creneau_midi_et = $ligne_select_et['creneau_midi'];
				$creneau_soir_et = $ligne_select_et['creneau_soir'];

				//////////VERIFIER LA DISPONIBILITES DES CRENEAUX UTILISATEURS SELON LA MISSION
				if ($creneau_matin == "oui" && $creneau_matin_et == "oui") {
					$note_matin = "oui";
				}
				if ($creneau_midi == "oui" && $creneau_midi_et == "oui") {
					$note_midi = "oui";
				}
				if ($creneau_soir == "oui" && $creneau_soir_et == "oui") {
					$note_soir = "oui";
				}

				$joursIntervalIndispo = obtenirJoursSemaine2($ligne['date_debut'], $ligne['date_fin']);

				$pass = false;

				if ($ligne_select_et['dimanche'] == "oui" && is_array($joursIntervalIndispo) && in_array("Sunday", $joursIntervalIndispo) && $type == "Indisponibilité" || $ligne_select_et['dimanche'] == "oui" && is_array($joursIntervalIndispo) && in_array("Sunday", $joursIntervalIndispo) &&  $type == "Réservé" && (!empty($note_matin) || !empty($note_midi) || !empty($note_soir))) {
					$pass = true;
				} elseif ($ligne_select_et['lundi'] == "oui" && is_array($joursIntervalIndispo) && in_array("Monday", $joursIntervalIndispo) && $type == "Indisponibilité" || $ligne_select_et['lundi'] == "oui" && is_array($joursIntervalIndispo) && in_array("Monday", $joursIntervalIndispo) &&  $type == "Réservé" && (!empty($note_matin) || !empty($note_midi) || !empty($note_soir))) {
					$pass = true;
				} elseif ($ligne_select_et['mardi'] == "oui" && is_array($joursIntervalIndispo) && in_array("Tuesday", $joursIntervalIndispo) && $type == "Indisponibilité" || $ligne_select_et['mardi'] == "oui" && is_array($joursIntervalIndispo) && in_array("Tuesday", $joursIntervalIndispo) && $type == "Réservé" && (!empty($note_matin) || !empty($note_midi) || !empty($note_soir))) {
					$pass = true;
				} elseif ($ligne_select_et['mercredi'] == "oui" && is_array($joursIntervalIndispo) && in_array("Wednesday", $joursIntervalIndispo) && $type == "Indisponibilité" || $ligne_select_et['mercredi'] == "oui" && is_array($joursIntervalIndispo) && in_array("Wednesday", $joursIntervalIndispo) && $type == "Réservé" && (!empty($note_matin) || !empty($note_midi) || !empty($note_soir))) {
					$pass = true;
				} elseif ($ligne_select_et['jeudi'] == "oui" && is_array($joursIntervalIndispo) && in_array("Thursday", $joursIntervalIndispo) && $type == "Indisponibilité" || $ligne_select_et['jeudi'] == "oui" && is_array($joursIntervalIndispo) && in_array("Thursday", $joursIntervalIndispo) && $type == "Réservé" && (!empty($note_matin) || !empty($note_midi) || !empty($note_soir))) {
					$pass = true;
				} elseif ($ligne_select_et['vendredi'] == "oui" && is_array($joursIntervalIndispo) && in_array("Friday", $joursIntervalIndispo) && $type == "Indisponibilité" || $ligne_select_et['vendredi'] == "oui" && is_array($joursIntervalIndispo) && in_array("Friday", $joursIntervalIndispo) && $type == "Réservé" && (!empty($note_matin) || !empty($note_midi) || !empty($note_soir))) {
					$pass = true;
				} elseif ($ligne_select_et['samedi'] == "oui" && is_array($joursIntervalIndispo) && in_array("Saturday", $joursIntervalIndispo) && $type == "Indisponibilité" || $ligne_select_et['samedi'] == "oui" && is_array($joursIntervalIndispo) && in_array("Saturday", $joursIntervalIndispo) && $type == "Réservé" && (!empty($note_matin) || !empty($note_midi) || !empty($note_soir))) {
					$pass = true;
				}

				if ($pass) {
					$_SESSION['sql_requete_verif'] .= "AND NOT(m.id_membre=?)";
					array_push($_SESSION['params_verif'], $ligne['id_membre']);
				}
			}
			$req_boucle->closeCursor();

			$Monday = "";
			$Tuesday = "";
			$Wednesday = "";
			$Thursday = "";
			$Friday = "";
			$Saturday = "";
			$Sunday = "";

			//semaines2
			//semaines2
			if (in_array("Monday", $joursInterval)) {
				global $Monday;
				$Monday = " AND ";
				if ($jourSemainePairImpair == "") {
					$sql_requete_jours_dispo .= " AND (semaine_p='Semaine paire' AND (( (meh.horaire_lundi_crenau1_debut IS NOT NULL && meh.horaire_lundi_crenau1_debut != '')  && (meh.horaire_lundi_crenau1_fin IS NOT NULL && meh.horaire_lundi_crenau1_fin != '') )) )";
				} elseif ($jourSemainePairImpair == 2) {
					$sql_requete_jours_dispo .= "AND (semaines2='2' AND semaine2_p='Semaine impaire' AND (( (meh.horaire2_lundi_crenau1_debut IS NOT NULL && meh.horaire2_lundi_crenau1_debut != '')  && (meh.horaire2_lundi_crenau1_fin IS NOT NULL && meh.horaire2_lundi_crenau1_fin != '') )) 
				|| semaines2='1' AND (semaine_p='Semaine paire' AND (( (meh.horaire_lundi_crenau1_debut IS NOT NULL && meh.horaire_lundi_crenau1_debut != '')  && (meh.horaire_lundi_crenau1_fin IS NOT NULL && meh.horaire_lundi_crenau1_fin != '') )) ) )";
				}
			}
			if (in_array("Tuesday", $joursInterval)) {
				global $Tuesday;
				$Tuesday = " AND ";
				if ($jourSemainePairImpair == "") {
					$sql_requete_jours_dispo .= " AND (semaine_p='Semaine paire' AND (( (meh.horaire_mardi_crenau1_debut IS NOT NULL && meh.horaire_mardi_crenau1_debut != '')  && (meh.horaire_mardi_crenau1_fin IS NOT NULL && meh.horaire_mardi_crenau1_fin != '') )))";
				} elseif ($jourSemainePairImpair == 2) {
					$sql_requete_jours_dispo .= " AND (semaines2='2' AND semaine2_p='Semaine impaire' AND (( (meh.horaire2_mardi_crenau1_debut IS NOT NULL && meh.horaire2_mardi_crenau1_debut != '')  && (meh.horaire2_mardi_crenau1_fin IS NOT NULL && meh.horaire2_mardi_crenau1_fin != '') )) 
				|| semaines2='1' AND (semaine_p='Semaine paire' AND (( (meh.horaire_mardi_crenau1_debut IS NOT NULL && meh.horaire_mardi_crenau1_debut != '')  && (meh.horaire_mardi_crenau1_fin IS NOT NULL && meh.horaire_mardi_crenau1_fin != '') ))) )";
				}
			}
			if (in_array("Wednesday", $joursInterval)) {
				global $Wednesday;
				$Wednesday = " AND ";
				if ($jourSemainePairImpair == "") {
					$sql_requete_jours_dispo .= "AND (semaine_p='Semaine paire' AND (( (meh.horaire_mercredi_crenau1_debut IS NOT NULL && meh.horaire_mercredi_crenau1_debut != '')  && (meh.horaire_mercredi_crenau1_fin IS NOT NULL && meh.horaire_mercredi_crenau1_fin != '') )))";
				} elseif ($jourSemainePairImpair == 2) {
					$sql_requete_jours_dispo .= "AND (semaines2='2' AND semaine2_p='Semaine impaire' AND (( (meh.horaire2_mercredi_crenau1_debut IS NOT NULL && meh.horaire2_mercredi_crenau1_debut != '')  && (meh.horaire2_mercredi_crenau1_fin IS NOT NULL && meh.horaire2_mercredi_crenau1_fin != '') )) 
				|| semaines2='1' AND (semaine_p='Semaine paire' AND (( (meh.horaire_mercredi_crenau1_debut IS NOT NULL && meh.horaire_mercredi_crenau1_debut != '')  && (meh.horaire_mercredi_crenau1_fin IS NOT NULL && meh.horaire_mercredi_crenau1_fin != '') ))))";
				}
			}
			if (in_array("Thursday", $joursInterval)) {
				global $Thursday;
				$Thursday = " AND ";
				if ($jourSemainePairImpair == "") {
					$sql_requete_jours_dispo .= "AND (semaine_p='Semaine paire' AND (( (meh.horaire_jeudi_crenau1_debut IS NOT NULL && meh.horaire_jeudi_crenau1_debut != '')  && (meh.horaire_jeudi_crenau1_fin IS NOT NULL && meh.horaire_jeudi_crenau1_fin != '') )))";
				} elseif ($jourSemainePairImpair == 2) {
					$sql_requete_jours_dispo .= "AND (semaines2='2' AND semaine2_p='Semaine impaire' AND (( (meh.horaire2_jeudi_crenau1_debut IS NOT NULL && meh.horaire2_jeudi_crenau1_debut != '')  && (meh.horaire2_jeudi_crenau1_fin IS NOT NULL && meh.horaire2_jeudi_crenau1_fin != '') )) 
				|| semaines2='1' AND (semaine_p='Semaine paire' AND (( (meh.horaire_jeudi_crenau1_debut IS NOT NULL && meh.horaire_jeudi_crenau1_debut != '')  && (meh.horaire_jeudi_crenau1_fin IS NOT NULL && meh.horaire_jeudi_crenau1_fin != '') ))) )";
				}
			}
			if (in_array("Friday", $joursInterval)) {
				global $Friday;
				$Friday = " AND ";
				if ($jourSemainePairImpair == "") {
					$sql_requete_jours_dispo .= "AND (semaine_p='Semaine paire' AND (( (meh.horaire_vendredi_crenau1_debut IS NOT NULL && meh.horaire_vendredi_crenau1_debut != '')  && (meh.horaire_vendredi_crenau1_fin IS NOT NULL && meh.horaire_vendredi_crenau1_fin != '') )))";
				} elseif ($jourSemainePairImpair == 2) {
					$sql_requete_jours_dispo .= "AND (semaines2='2' AND semaine2_p='Semaine impaire' AND (( (meh.horaire2_vendredi_crenau1_debut IS NOT NULL && meh.horaire2_vendredi_crenau1_debut != '')  && (meh.horaire2_vendredi_crenau1_fin IS NOT NULL && meh.horaire2_vendredi_crenau1_fin != '') )) 
				|| semaines2='1' AND (semaine_p='Semaine paire' AND (( (meh.horaire_vendredi_crenau1_debut IS NOT NULL && meh.horaire_vendredi_crenau1_debut != '')  && (meh.horaire_vendredi_crenau1_fin IS NOT NULL && meh.horaire_vendredi_crenau1_fin != '') ))))";
				}
			}
			if (in_array("Saturday", $joursInterval)) {
				global $Saturday;
				$Saturday = " AND ";
				if ($jourSemainePairImpair == "") {
					$sql_requete_jours_dispo .= "AND (semaine_p='Semaine paire' AND (( (meh.horaire_samedi_crenau1_debut IS NOT NULL && meh.horaire_samedi_crenau1_debut != '')  && (meh.horaire_samedi_crenau1_fin IS NOT NULL && meh.horaire_samedi_crenau1_fin != '') )))";
				} elseif ($jourSemainePairImpair == 2) {
					$sql_requete_jours_dispo .= "AND (semaines2='2' AND semaine2_p='Semaine impaire' AND (( (meh.horaire2_samedi_crenau1_debut IS NOT NULL && meh.horaire2_samedi_crenau1_debut != '')  && (meh.horaire2_samedi_crenau1_fin IS NOT NULL && meh.horaire2_samedi_crenau1_fin != '') )) 
				|| semaines2='1' AND (semaine_p='Semaine paire' AND (( (meh.horaire_samedi_crenau1_debut IS NOT NULL && meh.horaire_samedi_crenau1_debut != '')  && (meh.horaire_samedi_crenau1_fin IS NOT NULL && meh.horaire_samedi_crenau1_fin != '') )))";
				}
			}
			if (in_array("Sunday", $joursInterval)) {
				if ($jourSemainePairImpair == "") {
					$sql_requete_jours_dispo .= "AND (semaine_p='Semaine paire' AND (( (meh.horaire_dimanche_crenau1_debut IS NOT NULL && meh.horaire_dimanche_crenau1_debut != '')  && (meh.horaire_dimanche_crenau1_fin IS NOT NULL && meh.horaire_dimanche_crenau1_fin != '') )) )";
				} elseif ($jourSemainePairImpair == 2) {
					$sql_requete_jours_dispo .= "AND (semaines2='2' AND semaine2_p='Semaine impaire' AND (( (meh.horaire2_dimanche_crenau1_debut IS NOT NULL && meh.horaire2_dimanche_crenau1_debut != '')  && (meh.horaire2_dimanche_crenau1_fin IS NOT NULL && meh.horaire2_dimanche_crenau1_fin != '') )) 
				|| semaines2='1' AND (semaine_p='Semaine paire' AND (( (meh.horaire_dimanche_crenau1_debut IS NOT NULL && meh.horaire_dimanche_crenau1_debut != '')  && (meh.horaire_dimanche_crenau1_fin IS NOT NULL && meh.horaire_dimanche_crenau1_fin != '') )) ))";
				}
			}
			if (!empty($sql_requete_jours_dispo)) {
				$_SESSION['sql_requete'] .= "" . $sql_requete_jours_dispo . "";
			}



			/////////////////////////////////////// REQUETE  //LEFT JOIN membres_etablissements_categories_sous as s ON m.id = s.id_etablissement
			$req_filtres = $bdd->prepare("SELECT *, m.id_membre FROM membres_etablissements as m 
		INNER JOIN membres_etablissements_categories as c ON m.id = c.id_etablissement 
		LEFT JOIN membres as me ON m.id_membre = me.id
		LEFT JOIN membres_etablissements_horaires as meh ON m.id = meh.id_etablissement
		WHERE m.id_membre=? AND me.activer='oui' AND type_demande=1 AND m.documents_telecharges='oui' " .
				$_SESSION['sql_requete_verif'] . " " .
				"GROUP BY m.id ");
			$req_filtres->execute($_SESSION['params_verif']);
			$ligne_boucle = $req_filtres->fetch();
			$req_filtres->closeCursor();
			$r = $ligne_boucle['id'];

			//Si Match 
			if (!empty($r)) {
				$result = array("Texte_rapport" => "Vérifications effectuées !", "retour_validation" => "ok", "retour_lien" => "");
			} else {
				$result = array("Texte_rapport" => "Extra indisponible pour cette mission. !", "retour_validation" => "", "retour_lien" => "");
			}
		} else {
			$result = array("Texte_rapport" => "Vous avez déjà demandé l'extra.", "retour_validation" => "", "retour_lien" => "");
		}
	} else {
		$result = array("Texte_rapport" => "Vous devez sélectionner une mission.", "retour_validation" => "", "retour_lien" => "");
	}

	$result = json_encode($result);
	echo $result;
} else {
	header('location: /');
}

ob_end_flush();
