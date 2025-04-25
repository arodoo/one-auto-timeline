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

date_default_timezone_set('Europe/Paris');
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


	///////////////////////////////////////////////////////////////////////Si action différent de formulaire 
	if ($_GET['actionn'] != "formulaire" && $_GET['actionn'] != "creation") {

		///////////////SI ADMINISTRATEUR
		if (!empty($_GET['message_auteur_user'])) {
			$user = $_GET['message_auteur_user'];
		}
		///////////////SI ADMINISTRATEUR

		///////////////////////////////////////////////Message - Ouverture
///////////////////////////////SELECT
		$req_select = $bdd->prepare("SELECT * FROM membres_messages 
	WHERE id=?
	AND pseudo=? 
	OR id=?
	AND pseudo_destinataire=?");
		$req_select->execute(array(
			$_GET['idaction'],
			$user,
			$_GET['idaction'],
			$user
		));
		$ligne_select = $req_select->fetch();
		$req_select->closeCursor();
		$idd_message_o = $ligne_select['id'];
		$id_membre_message_o = $ligne_select['id_membre'];
		$pseudo_message_o = $ligne_select['pseudo'];
		$id_membre_destinataire_message_o = $ligne_select['id_membre_destinataire'];
		$pseudo_destinataire_message_o = $ligne_select['pseudo_destinataire'];
		$id_article_message_o = $ligne_select['id_article'];
		$titre_message_message_o = $ligne_select['titre_message'];
		$message_message_o = nl2br($ligne_select['message']);
		$message_lu_message_o = $ligne_select['message_lu'];
		$date_lu_message_o1 = $ligne_select['date_lu'];
		if (!empty($date_lu_message_o1)) {
			$date_lu_message_o = date('d-m-Y', $date_lu_message_o1);
			$date_lu_message_oh = date('H\hi', $date_lu_message_o1);
		}
		$date_message_message_o1 = $ligne_select['date_message'];
		if (!empty($date_message_message_o1)) {
			$date_message_message_o = date('d-m-Y', $date_message_message_o1);
			$date_message_message_oh = date('H\hi', $date_message_message_o1);
		}
		$fichier_message_o = $ligne_select['fichier'];
		$ancre_message_o = $ligne_select['plus1'];

		$suivi = $ligne_select['suivi'];
		$suivi_commentaire = $ligne_select['suivi_commentaire'];

		///////////////////////////////SELECT
		$req_select = $bdd->prepare("SELECT * FROM membres WHERE pseudo=?");
		$req_select->execute(array($pseudo_message_o));
		$ligne_select = $req_select->fetch();
		$req_select->closeCursor();
		$idd_destinataire = $ligne_select['id'];
		$pseudo2_destinataire = $ligne_select['pseudo'];
		$mail_destinataire = $ligne_select['mail'];
		$nom_pseudo_message_o = $ligne_select['nom'];
		$prenom_pseudo_message_o = $ligne_select['prenom'];

		///////////////////////////////SELECT
		$req_select = $bdd->prepare("SELECT * FROM membres WHERE pseudo=?");
		$req_select->execute(array($pseudo_destinataire_message_o));
		$ligne_select = $req_select->fetch();
		$req_select->closeCursor();
		$idd_destinataire = $ligne_select['id'];
		$pseudo2_destinataire = $ligne_select['pseudo'];
		$mail_destinataire = $ligne_select['mail'];
		$nom_pseudo_destinataire_message_o = $ligne_select['nom'];
		$prenom_pseudo_destinataire_message_o = $ligne_select['prenom'];

		/////////////////////////MESSAGE LU
		if ($message_lu_message_o != "oui" && $user == $pseudo_destinataire_message_o) {
			///////////////////////////////SELECT
			$sql_update = $bdd->prepare("UPDATE membres_messages SET 
	message_lu=?, 
	date_lu=?
	WHERE id=?");
			$sql_update->execute(array(
				'oui',
				time(),
				$idd_message_o
			));
			$sql_update->closeCursor();

			$date_lu_message_o = date('d-m-Y', time());
			$date_lu_message_oh = date('H\hi', time());
			$message_lu_message_o = "oui";
		}
		/////////////////////////MESSAGE LU

		///////////////////////////////////////////////Message - Ouverture

	}
	///////////////////////////////////////////////////////////////////////Si action différent de formulaire 


	//////////////////////////////////////On détermine le destinataire
///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT * FROM membres_messages 
	WHERE id=?
	AND pseudo=? 
	OR id=?	
	AND pseudo_destinataire=?");
	$req_select->execute(array(
		$_GET['idaction'],
		$user,
		$_GET['idaction'],
		$user
	));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
	$id_membre = $ligne_select['id_membre'];
	$id_membre_destinataire = $ligne_select['id_membre_destinataire'];

	if ($id_oo == $id_membre) {
		$idconcerne_messsage = "$id_membre_destinataire";
	} elseif ($id_oo == $id_membre_destinataire) {
		$idconcerne_messsage = "$id_membre";
	}
	//////////////////////////////////////On détermine le destinataire

	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT * FROM membres WHERE id=?");
	$req_select->execute(array($idconcerne_messsage));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
	$idd_destinataire = $ligne_select['id'];
	$pseudo2_destinataire = $ligne_select['pseudo'];
	$mail_destinataire = $ligne_select['mail'];
	$nom_destinataire = $ligne_select['nom'];
	$prenom_destinataire = $ligne_select['prenom'];
	$Pays_destinataire = $ligne_select['Pays'];
	$adresse_destinataire = $ligne_select['adresse'];
	$cp_destinataire = $ligne_select['cp'];
	$ville_destinataire = $ligne_select['ville'];
	$telephonepost_destinataire = $ligne_select['Telephone'];
	////////////////////////////////////////////////On détermine le destinataire

	///////////////////////////////////////////LISTE MESSAGE

	if ($actionn != "formulaire") {

		//////////////////////////////////////////////////////////////////////MESSAGE PRIVE

		/////////////////////////NOMBRE DE MESSAGE
///////////////////////////////SELECT
		$req_select = $bdd->prepare("SELECT COUNT(*) AS nbrmessage FROM membres_messages_reponse WHERE id_message=?");
		$req_select->execute(array($idd_message_o));
		$ligne_select = $req_select->fetch();
		$req_select->closeCursor();
		$idd_message_o_redirectm = $ligne_select['nbrmessage'];
		$idd_message_o_redirectm = (1 + $idd_message_o_redirectm);
		/////////////////////////NOMBRE DE MESSAGE

		?>

		<?php
		if (!empty($suivi_commentaire) && $suivi == "oui") {
			?>
			<div class="alert alert-danger" role="alert" style="text-align: left;">
				<span class='uk-icon-warning'></span> <b>Message modéré par un administrateur !</b> <br />
				<b>Commentaire administrateur :</b> <?php echo nl2br($suivi_commentaire); ?>
			</div>
			<?php
		}
		?>

		<div class='bloc_titre_message' style='width: 100%;'>
			<div style='padding: 10px; float: left;'>
				<div class='prive_message' style='float: left;'><span class='uk-icon-sign-out'></span>
					<?php echo "Message ouvert par"; ?> <span
						style='font-weight: bold;'><?php echo "$prenom_pseudo_message_o $nom_pseudo_message_o"; ?></span> </div>
			</div>
			<?php if (empty($_GET['message_auteur_user'])) { ?>
				<div style='padding: 10px; float: right;'>
					<div style='float: right;'> <a href='#formulaire'><span class='uk-icon-edit'></span>
							<?php echo "Ecrire un message"; ?></a> </div>
				</div>
			<?php } ?>
		</div>
		<div style='clear: both;'></div>

		<div class='bloc_contenu_message'>
			<div style='padding: 10px;'>
				<div style='float: left;'>
					<div style='float: left; margin-right: 20px;'>
						<h2 style='font-size: 16px; margin:0px; padding: 0px;'><?php echo "Objet : $titre_message_message_o"; ?>
						</h2>
					</div>

					<div style='clear: both;'></div>
					<div style='float: left; margin-right: 10px; margin-top: 5px;'>
						<div class='btn btn-default' style=''><span class='uk-icon-user'></span>
							<?php echo "$prenom_pseudo_message_o $nom_pseudo_message_o"; ?></div>
						<div style='display: inline-block; padding-top: 5px;'>&#9656;</div>
						<div class='btn btn-default' style=''><span class='uk-icon-user'></span>
							<?php echo "$prenom_pseudo_destinataire_message_o $nom_pseudo_destinataire_message_o"; ?></div>
						<div class='btn btn-default' style=''>
							<?php echo "Messages <span class='badge'>$idd_message_o_redirectm</span>"; ?> </div>
					</div>
				</div>

				<div style='float: right; font-size: 14px; margin-top: 4px;'>
					<?php echo "<span class='uk-icon-clock-o' ></span>Posté le, $date_message_message_o à $date_message_message_oh"; ?>
				</div>
				<div style='clear: both;'></div>

			</div>

			<?php if (isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s'])) { ?>
				<div style='width:100%; text-align: left; margin-bottom: 20px;'>
					<a href='#' onclick='return false;' class='supprimer_message' data-id='<?php echo "$idd_message_o"; ?>'
						style='color:red; text-align: left;'>
						<div class='lecture_message' style='color:red; display: inline-block;'><span class='uk-icon-times'></span>
							<?php echo "Supprimer tous les messages"; ?></div>
					</a>
				</div>
			<?php } ?>

		</div>

		<div class='bloc_titre_message'>
			<div style='padding: 10px;'>
				<?php if ($pseudo_message_o == $user && empty($_GET['message_auteur_user'])) { ?>
					<div class='prive_message' style='float: left;'><span class='uk-icon-envelope-o'></span>
						<?php echo "Messages privés"; ?> <span style='font-weight: bold;'><?php echo "VOUS"; ?></span>: &nbsp;
					</div>
				<?php } else { ?>
					<div class='prive_message' style='float: left;'><span class='uk-icon-envelope-o'></span>
						<?php echo "Messages privés de"; ?> <span
							style='font-weight: bold;'><?php echo "$prenom_pseudo_message_o $nom_pseudo_message_o"; ?></span> &nbsp;
					</div>
				<?php } ?>
				<?php if (empty($_GET['message_auteur_user'])) { ?>
					<div style='float: right; font-weight: normal;'> <a
							href='/Messagerie.html'><?php echo "Retour à la messagerie"; ?></a></div>
				<?php } ?>
			</div>
		</div>

		<div class='bloc_contenu_message' id='M<?php echo "$Date_messagepcc"; ?>'>
			<div style='padding: 10px; text-align: left;'>

				<div style='clear: both;'></div>

				<div style='font-size: 14px; text-align: left;'>
					<p tyle='text-align: left;' id='M<?php echo "$ancre_message_o"; ?>'><?php echo "$message_message_o"; ?></p>
					<?php if (!empty($fichier_message_o)) { ?>
						<div style='margin-top: 7px;'><span class='color_1'>Fichier joint :</span> <a
								href='/images/membres/<?php echo "$pseudo_message_o"; ?>/<?php echo "$fichier_message_o"; ?>'
								target='blank_'><?php echo "$fichier_message_o"; ?> </a></div>
						<div style='margin-top: 7px; display: inline-block;'><span class='color2'>
								<?php if (isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s'])) { ?>
									<a href='#' class='supprimer_image' data-id='<?php echo "$idd_message_o"; ?>'
										data-table='membres_messages' onclick='return false;' style='color: red;'>
										<div class='lecture_message' style='color:red; display: inline-block;'><span
												class='uk-icon-times'></span> Supprimer le fichier </div>
									</a>
								<?php } ?>
						</div>
					<?php } ?>
					<?php if ($message_lu_message_o == "oui" && $user != $pseudo_destinataire_message_o) { ?>
						<div class='lecture_message'><span class='uk-icon-warning'></span> <?php echo "Message lu par"; ?>
							<b><?php echo "$prenom_pseudo_destinataire_message_o $nom_pseudo_destinataire_message_o"; ?></b>
							<?php echo "$le_message"; ?> 			<?php echo "$date_lu_message_o à $date_lu_message_oh"; ?></div>
					<?php } elseif ($user != $pseudo_destinataire_message_o) { ?>
						<div class='non_lecture_message'><span class='uk-icon-warning'></span> <?php echo "Message non lu par"; ?>
							<b><?php echo "$prenom_pseudo_destinataire_message_o $nom_pseudo_destinataire_message_o"; ?></b></div>
					<?php } elseif ($user == $pseudo_destinataire_message_o) { ?>
						<div class='lecture_message'><span class='uk-icon-thumbs-o-up'></span>
							<?php echo "Vous avez lu ce message le,"; ?> 			<?php echo "$date_lu_message_o à $date_lu_message_oh"; ?>
						</div>
					<?php } ?>
				</div>
				<div style='clear: both;'></div>
			</div>
		</div>

		<?php
		//////////////////////////////////////////////////////////////////////MESSAGE PRIVE

		//////////////////////////////////////////////////////////////////////LISTE REPONSES
///////////////////////////////SELECT BOUCLE
		$req_boucle = $bdd->prepare("SELECT * FROM membres_messages_reponse WHERE id_message=?  ORDER BY date_reponse_message ASC");
		$req_boucle->execute(array($idd_message_o));
		while ($ligne_boucle = $req_boucle->fetch()) {
			$idd_message_o_r = $ligne_boucle['id'];
			$id_membre_message_o_r = $ligne_boucle['id_membre'];
			$pseudo_message_o_r = $ligne_boucle['pseudo'];
			$id_article_message_o_r = $ligne_boucle['id_message'];
			$titre_message_message_o_r = $ligne_boucle['titre_reponse_message'];
			$message_message_o_r = nl2br($ligne_boucle['message_reponse']);
			$message_lu_message_o_r = $ligne_boucle['message_reponse_lu'];
			$date_lu_message_o1_r = $ligne_boucle['date_reponse_lu'];
			if (!empty($date_lu_message_o1_r)) {
				$date_lu_message_o_r = date('d-m-Y', $date_lu_message_o1_r);
				$date_lu_message_oh_r = date('H\hi', $date_lu_message_o1_r);
			}
			$date_message_message_o1_r = $ligne_boucle['date_reponse_message'];
			if (!empty($date_message_message_o1_r)) {
				$date_message_message_o_r = date('d-m-Y', $date_message_message_o1_r);
				$date_message_message_oh_r = date('H\hi', $date_message_message_o1_r);
			}
			$fichier_message_o_r = $ligne_boucle['fichier'];
			$ancre_message_o_r = $ligne_boucle['plus1'];

			///////////////////////////////////////////////////////////// On détermine le destinataire de la réponse
			if ($pseudo_message_o_r == $user && $user == $pseudo_destinataire_message_o) {
				$pseudo_attente_reponse = "$prenom_pseudo_message_o $nom_pseudo_message_o";

			} elseif ($pseudo_message_o_r == $user && $user == $pseudo_message_o) {
				$pseudo_attente_reponse = "$prenom_pseudo_destinataire_message_o $nom_pseudo_destinataire_message_o";

			}
			///////////////////////////////////////////////////////////// On détermine le destinataire de la réponse

			/////////////////////////MESSAGE REPONSE LU
			if ($date_lu_message_o1_r != "oui" && $user != $pseudo_message_o_r) {
				///////////////////////////////UPDATE
				$sql_update = $bdd->prepare("UPDATE membres_messages_reponse SET 
	message_reponse_lu=?, 
	date_reponse_lu=? 
	WHERE id=?");
				$sql_update->execute(array(
					'oui',
					time(),
					htmlspecialchars($idd_message_o_r)
				));
				$sql_update->closeCursor();

				$date_lu_message_o_r = date('d-m-Y', time());
				$date_lu_message_oh_r = date('H\hi', time());
				$message_lu_message_o_r = "oui";
			}
			/////////////////////////MESSAGE REPONSE LU

			?>

			<div class='bloc_titre_message'>
				<div style='padding: 10px;  text-align: left;'>

					<?php if ($pseudo_message_o_r == $user) { ?>
						<div class='prive_message' style='float: left;'><span class='uk-icon-envelope'></span>
							<?php echo "Réponse de"; ?> <span style='font-weight: bold;'><?php echo "VOUS"; ?></span> &nbsp; </div>
					<?php } else { ?>
						<div class='prive_message' style='float: left;'><span class='color_1'><span class='uk-icon-envelope'></span>
								<?php echo "Réponse de"; ?> <span
									style='font-weight: bold;'><?php echo "$prenom_pseudo_message_o $nom_pseudo_message_o"; ?></span></span>
							&nbsp; </div>
					<?php } ?>

					<div style='float: left; font-weight: normal;'><span class='uk-icon-clock-o'></span>
						<?php echo "$date_message_message_o_r à $date_message_message_oh_r"; ?> </div>
				</div>
			</div>

			<div class='bloc_contenu_message' id='M<?php echo "$Date_messagepcc"; ?>' style=' text-align: left;'>
				<div style='padding: 10px;  text-align: left;'>

					<!--<img src='/image/client_user_message.png' alt='client_user_message' width='30' style='float: left; margin-right: 10px;'/>-->
					<div style='float: left; margin-right: 10px; margin-top: 5px;' class='color_1'>
						<b><?php echo $pseudo_destinataire_message_o_r; ?></b></div>

					<div style='clear: both;'></div>

					<div style='font-size: 14px;  text-align: left;'>
						<p tyle='text-align: left;' id='M<?php echo "$ancre_message_o_r"; ?>'><?php echo "$message_message_o_r"; ?>
						</p>
						<?php if (!empty($fichier_message_o_r)) { ?>
							<div style='margin-top: 7px; display: inline-block;'><span class='color2'> <span
										class='uk-icon-download'></span> Fichier joint :</span> <a
									href='/images/membres/<?php echo "$pseudo_message_o_r"; ?>/<?php echo "$fichier_message_o_r"; ?>'
									target='blank_'><?php echo "$fichier_message_o_r"; ?></a></div>
							<?php if (isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s'])) { ?>
								<a href='#' class='supprimer_image' data-id='<?php echo "$idd_message_o_r"; ?>'
									data-table='membres_messages_reponse' onclick='return false;' style='color: red;'>
									<div class='lecture_message' style='color:red; display: inline-block;'><span
											class='uk-icon-times'></span> Supprimer le fichier </div>
								</a>
							<?php } ?>
						<?php } ?>

						<?php if (empty($_GET['message_auteur_user'])) { ?>
							<div style='clear:both;'></div>
							<?php if ($message_lu_message_o_r == "oui" && $user == $pseudo_message_o_r) { ?>
								<div class='lecture_message' style='display: inline-block;'><span class='uk-icon-warning'></span>
									<?php echo "Message lu par"; ?> <b><?php echo "$pseudo_attente_reponse"; ?></b> <span
										class='uk-icon-clock-o'></span>
									<?php echo "$le_message $date_lu_message_o_r à $date_lu_message_oh_r"; ?></div>
							<?php } elseif ($message_lu_message_o_r != "oui" && $user == $pseudo_message_o_r) { ?>
								<div class='non_lecture_message' style='display: inline-block;'><span class='uk-icon-warning'></span>
									<?php echo "Message non lu par"; ?> <b><?php echo "$pseudo_attente_reponse"; ?></b></div>
							<?php } elseif ($user != $pseudo_message_o_r) { ?>
								<div class='lecture_message' style='display: inline-block;'><span class='uk-icon-thumbs-o-up'></span>
									<?php echo "Vous avez lu ce message le,"; ?>
									<?php echo "$date_lu_message_o_r à $date_lu_message_oh_r"; ?></div>
							<?php } ?>

						<?php } ?>

						<?php if (isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s'])) { ?>
							<a href='#' onclick='return false;' class='supprimer_commentaire'
								data-id='<?php echo "$idd_message_o_r"; ?>' style='color:red; display: inline-block;'>
								<div class='lecture_message' style='color:red; display: inline-block;'><span
										class='uk-icon-times'></span> <?php echo "Supprimer le message"; ?></div>
							</a>
						<?php } ?>

					</div>
					<div style='clear: both;'></div>
				</div>
			</div>

			<?php
		}
		//////////////////////////////////////////////////////////////////////LISTE REPONSES
		$req_boucle->closeCursor();

	}
	///////////////////////////////////////////LISTE MESSAGE

} else {
	header('location: /index.html');
}

ob_end_flush();
?>