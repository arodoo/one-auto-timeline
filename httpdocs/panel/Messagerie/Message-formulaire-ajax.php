<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');
require '../../vendor/autoload.php';

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

require_once('../../function/sms.php');

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

	//date_default_timezone_set('UTC');
	$now = time();
	$icon1 = htmlspecialchars($_FILES['icon1']['name']);

	if (!empty($_POST['idaction'])) {

		//////////////////////////////////////On détermine le destinataire

		///////////////////////////SI REPONSE AU MESSAGE OUVERT
///////////////////////////////SELECT
		$req_select = $bdd->prepare("SELECT * FROM membres_messages 
	WHERE id=? 
	AND pseudo=?
	OR id=?
	AND pseudo_destinataire=?");
		$req_select->execute(array(
			$_POST['idaction'],
			$user,
			$_POST['idaction'],
			$user
		));
		$ligne_select = $req_select->fetch();
		$req_select->closeCursor();
		$id_membre_destinataire = $ligne_select['id_membre_destinataire'];
		$id_membre = $ligne_select['id_membre'];

		if ($id_oo == $id_membre) {
			$idconcerne_messsage = "$id_membre_destinataire";
		} elseif ($id_oo == $id_membre_destinataire) {
			$idconcerne_messsage = "$id_membre";
		}

		///////////////////////////SI REPONSE AU MESSAGE OUVERT

		///////////////////////////SI OUVERTURE MESSAGE
		if (empty($ligne_select['id'])) {
			///////////////////////////////SELECT
			$req_select = $bdd->prepare("SELECT * FROM membres WHERE pseudo=?");
			$req_select->execute(array($_POST['idaction']));
			$ligne_select = $req_select->fetch();
			$req_select->closeCursor();
			$id_membre_destinataire = $ligne_select['id'];
			$idconcerne_messsage = "$id_membre_destinataire";
		}
		///////////////////////////SI OUVERTURE MESSAGE

		//////////////////////////////////////On détermine le destinataire

		///////////////////////////////SELECT
		$req_select = $bdd->prepare("SELECT * FROM membres WHERE id=?");
		$req_select->execute(array($idconcerne_messsage));
		$ligne_select = $req_select->fetch();
		$req_select->closeCursor();
		$idd_destinataire = $ligne_select['id'];
		$pseudo2_destinataire = $ligne_select['pseudo'];
		$mail_destinataire = $ligne_select['mail'];
		$telephone_portable_destinataire = $ligne_select['Telephone_portable'];
		$telephone_destinataire = $ligne_select['Telephone'];
		$nom_destinataire = $ligne_select['nom'];
		$prenom_destinataire = $ligne_select['prenom'];
		$Pays_destinataire = $ligne_select['Pays'];
		$adresse_destinataire = $ligne_select['adresse'];
		$cp_destinataire = $ligne_select['cp'];
		$ville_destinataire = $ligne_select['ville'];
		$telephonepost_destinataire = $ligne_select['Telephone'];
		//////////////////////////////////////On détermine le destinataire

		/////////////////////////////////////////////////////////////////////////////////////////ACTION MESSAGE ET REPONSE INSERT
		if (!empty($_POST['message_prive_post'])) {

			//////////////////////////////////////POST ACTION UPLOAD
			if (!empty($icon1)) {
				if (
					!empty($icon1) && substr($icon1, -4) == "jpeg" || !empty($icon1) && substr($icon1, -3) == "jpg" || !empty($icon1) && substr($icon1, -3) == "JPG" || !empty($icon1) && substr($icon1, -3) == "png" || !empty($icon1) && substr($icon1, -3) == "PNG" || !empty($icon1) && substr($icon1, -3) == "gif" || !empty($icon1) && substr($icon1, -3) == "GIF" ||
					!empty($icon1) && substr($icon1, -3) == "txt" || !empty($icon1) && substr($icon1, -3) == "TXT" || !empty($icon1) && substr($icon1, -3) == "doc" || !empty($icon1) && substr($icon1, -3) == "DOC" || !empty($icon1) && substr($icon1, -3) == "ppt" || !empty($icon1) && substr($icon1, -3) == "PPT" || !empty($icon1) && substr($icon1, -3) == "xls" || !empty($icon1) && substr($icon1, -3) == "XLS" ||
					!empty($icon1) && substr($icon1, -3) == "pdf" || !empty($icon1) && substr($icon1, -3) == "PDF" || !empty($icon1) && substr($icon1, -3) == "odt" || !empty($icon1) && substr($icon1, -3) == "ODT" || !empty($icon1) && substr($icon1, -3) == "zip" || !empty($icon1) && substr($icon1, -3) == "ZIP" || !empty($icon1) && substr($icon1, -3) == "rar" || !empty($icon1) && substr($icon1, -3) == "RAR" ||
					!empty($icon1) && substr($icon1, -3) == "ace" || !empty($icon1) && substr($icon1, -3) == "ACE" || !empty($icon1) && substr($icon1, -2) == "gz" || !empty($icon1) && substr($icon1, -2) == "GZ" || !empty($icon1) && substr($icon1, -4) == "docx" || !empty($icon1) && substr($icon1, -4) == "DOCX" || !empty($icon1) && substr($icon1, -4) == "xlsx" || !empty($icon1) && substr($icon1, -4) == "XLSX" || !empty($icon1) && substr($icon1, -4) == "pptx" || !empty($icon1) && substr($icon1, -3) == "PPTX"
				) {
					include('Message-upload.php');
				} elseif (!empty($icon1)) {
					$erreur_upload_icon = "oui";
					$result = array("Texte_rapport" => "Le fichier n'a pas l'extension requise !", "retour_validation" => "", "retour_lien" => "");
				}
			}
			//////////////////////////////////////POST ACTION UPLOAD

			//////////////////////////////////////INSERT SI OUVERTURE ET CREATION MESSAGE
			if ($_POST['actionn'] == "creation" && empty($icon1) || $_POST['actionn'] == "creation" && !empty($icon1) && empty($erreur_upload_icon)) {

				///////////////////////////////SELECT
				$req_select = $bdd->prepare("SELECT * FROM membres WHERE pseudo=?");
				$req_select->execute(array($_POST['idaction']));
				$ligne_select = $req_select->fetch();
				$req_select->closeCursor();
				$idd_message_omm = $ligne_select['id'];
				$idd_message_o_pseudo = $ligne_select['pseudo'];
				$idd_message_o_mail = $ligne_select['mail'];

				if (!empty($cp_oo)) {
					$Code_departement = substr($cp_oo, 0, 2);
				}

				$string = strip_tags($_POST['message_prive_post_objet']);

				////////////////////////////////FILTRES
				$string = suppmail($string);
				$string = autolien($string);
				$string = supptel($string);
				//$string = supplien($string);
////////////////////////////////FILTRES

				$string_2 = strip_tags($_POST['message_prive_post']);

				////////////////////////////////FILTRES
				$string_2 = suppmail($string_2);
				$string_2 = autolien($string_2);
				$string_2 = supptel($string_2);
				//$string_2 = supplien($string_2);
////////////////////////////////FILTRES

				///////////////////////////////INSERT
				$sql_insert = $bdd->prepare("INSERT INTO membres_messages
	(id_membre,
	pseudo,
	id_membre_destinataire,
	pseudo_destinataire,
	id_article,
	titre_message,
	message,
	message_lu,
	date_lu,
	date_message,
	date_jour,
	date_mois,
	date_annee,
	departement,
	lu_par_administrateur,
	fichier,
	plus1)
	VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
				$sql_insert->execute(array(
					$id_oo,
					$user,
					$idd_message_omm,
					$idd_message_o_pseudo,
					$_POST['idaction'],
					$string,
					$string_2,
					'',
					'',
					time(),
					date('d', time()),
					date('m', time()),
					date('Y', time()),
					$Code_departement,
					'',
					$nouveau_nom_fichier,
					$now
				));
				$sql_insert->closeCursor();

				///////////////////////////////SELECT
				$req_select = $bdd->prepare("SELECT * FROM membres_messages WHERE plus1=?");
				$req_select->execute(array($now));
				$ligne_select = $req_select->fetch();
				$req_select->closeCursor();
				$idd_message_o_redirect = $ligne_select['id'];

				//////////////////////////////////////INSERT SI OUVERTURE ET CREATION MESSAGE

				$message_ouvert = "oui";
				$Objet = "<u>Objet : " . $_POST['message_prive_post_objet'] . " </u> <br /><br />";

				//////////////////////////////////////INSERT SI REPONSE AU MESSAGE OUVERT 
			} elseif ($_POST['actionn'] == "reponse" && empty($icon1) || $_POST['actionn'] == "reponse" && !empty($icon1) && empty($erreur_upload_icon)) {

				$string = strip_tags($_POST['message_prive_post']);

				////////////////////////////////FILTRES
				$string = suppmail($string);
				$string = autolien($string);
				$string = supptel($string);
				//$string = supplien($string);
////////////////////////////////FILTRES

				///////////////////////////////INSERT
				$sql_insert = $bdd->prepare("INSERT INTO membres_messages_reponse
	(id_membre,
	pseudo,
	id_message,
	titre_reponse_message,
	message_reponse,
	message_reponse_lu,
	date_reponse_lu,
	date_reponse_message,
	lu_par_administrateur,
	fichier,
	plus1)
	VALUES (?,?,?,?,?,?,?,?,?,?,?)");
				$sql_insert->execute(array(
					htmlspecialchars($id_oo),
					htmlspecialchars($user),
					htmlspecialchars($_POST['idaction']),
					'',
					htmlspecialchars($string),
					'',
					'',
					time(),
					'',
					htmlspecialchars($nouveau_nom_fichier),
					htmlspecialchars($now)
				));
				$sql_insert->closeCursor();

				///////////////////////////////SELECT

				if ($id_oo == $id_membre) {
					$id_membre_destinataire = "$id_membre_destinataire";
				} else {
					$id_membre_destinataire = "$id_oo";
				}

				///////////////////////////////SELECT
				$req_select = $bdd->prepare("SELECT * FROM membres WHERE id=?");
				$req_select->execute(array($id_membre_destinataire));
				$ligne_select = $req_select->fetch();
				$req_select->closeCursor();
				$idd_message_omm = $ligne_select['id'];
				$idd_message_o_pseudo = $ligne_select['pseudo'];
				$idd_message_o_mail = $ligne_select['mail'];

				$message_reponse = "oui";

				$idd_message_o_redirect = $_POST['idaction'];
				$Objet = "";

			}
			//////////////////////////////////////INSERT SI REPONSE AU MESSAGE OUVERT 

			if (!empty($mail_destinataire)) {
				//////////////////////////////////////Mail DESTINATAIRE
				$de_nom = "$nomsiteweb"; //Nom de l'envoyeur
				$de_mail = "$emaildefault"; //Email de l'envoyeur
				$vers_nom = "$prenom_destinataire $nom_destinataire"; //Nom du receveur
				$vers_mail = "$mail_destinataire"; //Email du receveur
				$sujet = "Nouveau message sur $nomsiteweb";

				$message_principalone = "
Objet : $sujet <br /><br />
Il y a un nouveau message déposé par <b>$prenom_oo</b> sur " . $nomsiteweb . ".<br /><br />
Vous pouvez le consulter en ligne sur le lien suivant : <a href='" . $http . "" . $nomsiteweb . "/Message-contact-liste-" . $idd_message_o_redirect . ".html'>Lire le message en ligne</a><br /><br />
$objet
<u>Message :</u> " . $_POST['message_prive_post'] . " <br /><br />
Bien cordialement,<br />
";

				mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);
				//////////////////////////////////////Mail DESTINATAIRE
			}

			$messagesms = "Bonjour, Il y a un nouveau message déposé par $prenom_oo 
 Vous pouvez le consulter en ligne sur le lien suivant : " . $http . "" . $nomsiteweb . "/Message-contact-liste-" . $idd_message_o_redirect . ".html
Message : " . $_POST['message_prive_post'];

			if (!empty($telephone_portable_destinataire)) {
				$telephone = "+33" . $telephone_portable_destinataire;
				$telephone = str_replace(' ', '', $telephone);
				send_sms($messagesms, $telephone);
			} elseif (!empty($telephone_destinataire)) {
				$telephone = "+33" . $telephone_portable_destinataire;
				$telephone = str_replace(' ', '', $telephone);
				send_sms($messagesms, $telephone);
			}

			if ($message_ouvert == "oui") {
				$result = array("Texte_rapport" => "Message envoyé avec succes !", "retour_validation" => "ok-ouverture", "retour_lien" => "Message-contact-liste-$idd_message_o_redirect.html");
			} elseif ($message_reponse == "oui") {
				$result = array("Texte_rapport" => "Message envoyé avec succes ! ", "retour_validation" => "ok-reponse", "retour_lien" => "");
			}

			/////////////////////////////////////////////////////////////////////////////////////////ACTION MESSAGE ET REPONSE INSERT

			//ON DESTROYE LE TITRE EN SESSION DES OBJETS DYNAMIQUES
			unset($_SESSION['titre_projet']);

			/////////////////////////////////////////////////////////////////////////////////////////ACTION MESSAGE ET REPONSE INSERT - SI PAS DE MESSAGE 
		} elseif (empty($_POST['message_prive_post'])) {
			$result = array("Texte_rapport" => "Vous devez écrire un message !", "retour_validation" => "", "retour_lien" => "");
		}
		/////////////////////////////////////////////////////////////////////////////////////////ACTION MESSAGE ET REPONSE INSERT - SI PAS DE MESSAGE 

		$result = json_encode($result);
		echo $result;

	}

}

ob_end_flush();
?>