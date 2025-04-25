<?php

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

?>

<div class="contact-form-wrapper background-white p30">

	<?php

	if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {

		$now = time();
		$action = $_GET['action'];
		$idaction = $_GET['idaction'];

		///////////////////////////////////////FILTRES
		if ($action == "Message-ouverts") {
			$_SESSION['Type_recherche_filtre_message'] = "Ouverts";
			$_SESSION['Type_recherche_filtre_message_SQL'] = "";
			$_SESSION['Type_recherche_filtre_message_TITRE'] = "Les messages ouverts par vous";
		} elseif ($action == "Message-en-attente") {
			$_SESSION['Type_recherche_filtre_message'] = "Message-en-attente";
			$_SESSION['Type_recherche_filtre_message_SQL'] = "";
			$_SESSION['Type_recherche_filtre_message_TITRE'] = "Vos messages en attente de lecture";
		} elseif ($action == "Messages-lus") {
			$_SESSION['Type_recherche_filtre_message'] = "Messages-lus";
			$_SESSION['Type_recherche_filtre_message_SQL'] = "";
			$_SESSION['Type_recherche_filtre_message_TITRE'] = "Vos messages lus";
		} elseif ($action == "Messages-non-lus") {
			$_SESSION['Type_recherche_filtre_message'] = "Messages-non-lus";
			$_SESSION['Type_recherche_filtre_message_SQL'] = "";
			$_SESSION['Type_recherche_filtre_message_TITRE'] = "Vos messages non lus";
		} elseif ($action == "Tous-les-messages") {
			$_SESSION['Type_recherche_filtre_message'] = "";
			$_SESSION['Type_recherche_filtre_message_SQL'] = "";
			$_SESSION['Type_recherche_filtre_message_TITRE'] = "";
		}
		///////////////////////////////////////FILTRES

		if (!empty($_SESSION['Type_recherche_filtre_message_TITRE'])) {
			echo "<div style='text-align: left; margin-bottom: 20px;'> <h2> " . $_SESSION['Type_recherche_filtre_message_TITRE'] . " </h2></div>";
		}

		/////////////////////////////////////////MESSAGERIE MENU
		include('Messagerie-menu.php');
		/////////////////////////////////////////MESSAGERIE MENU

	?>

		<script>
			$(document).ready(function() {
				$('#Tableau_a').DataTable({
					"columnDefs": [],
					"language": {
						"sProcessing": "Traitement en cours...",
						"sSearch": "Rechercher&nbsp;:",
						"sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
						"sInfo": "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
						"sInfoEmpty": "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
						"sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
						"sInfoPostFix": "",
						"sLoadingRecords": "Chargement en cours...",
						"sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
						"sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
						"oPaginate": {
							"sFirst": "Premier",
							"sPrevious": "Pr&eacute;c&eacute;dent",
							"sNext": "Suivant",
							"sLast": "Dernier"
						},
						"oAria": {
							"sSortAscending": ": activer pour trier la colonne par ordre croissant",
							"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
						}
					},
					"order": [[ 0, "desc" ]], // Add this line to force descending order
					"ordering": false // Or this line to disable ordering completely
				});
			});
		</script>

		<div class="tableau_message">

			<table id='Tableau_a' class="display" style="text-align: center; width: 100%; margin-top: 15px; " cellpadding="2" cellspacing="2">

				<thead>
					<tr>
						<th style="text-align: center;"></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th style="text-align: center;"></th>
					</tr>
				</tfoot>
				<tbody>

					<?php
					///////////////////////////////SELECT BOUCLE
					$req_boucle = $bdd->prepare("SELECT * FROM membres_messages 
	WHERE pseudo=?
	OR pseudo_destinataire=? 
	ORDER BY date_message DESC");
					$req_boucle->execute(array(
						$user,
						$user
					));
					while ($ligne_boucle = $req_boucle->fetch()) {
						$idd_message_o = $ligne_boucle['id'];
						$id_membre_message_o = $ligne_boucle['id_membre'];
						$pseudo_message_o = $ligne_boucle['pseudo'];
						$id_membre_destinataire_message_o = $ligne_boucle['id_membre_destinataire'];
						$pseudo_destinataire_message_o = $ligne_boucle['pseudo_destinataire'];
						$id_article_message_o = $ligne_boucle['id_article'];
						$titre_message_message_o = $ligne_boucle['titre_message'];
						$message_message_o = nl2br($ligne_boucle['message']);
						$message_lu_message_o = $ligne_boucle['message_lu'];

						$date_lu_message_o1 = $ligne_boucle['date_lu'];
						if (!empty($date_lu_message_o1)) {
							$date_lu_message_o = date('d-m-Y', $date_lu_message_o1);
							$date_lu_message_oh = date('H\hi', $date_lu_message_o1);
						}

						$date_message_message_o1 = $ligne_boucle['date_message'];
						if (!empty($date_message_message_o1)) {
							$date_message_message_o = date('d-m-Y', $date_message_message_o1);
							$date_message_message_oh = date('H\hi', $date_message_message_o1);
						}

						$fichier_message_o = $ligne_boucle['fichier'];
						$ancre_message_o = $ligne_boucle['plus1'];

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

						/////////////////////////NOMBRE DE MESSAGE
						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT COUNT(*) AS nbrmessage FROM membres_messages_reponse WHERE id_message=?");
						$req_select->execute(array($idd_message_o));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_redirectm = $ligne_select['nbrmessage'];
						$idd_message_o_redirectm = (1 + $idd_message_o_redirectm);
						/////////////////////////NOMBRE DE MESSAGE

						/////////////////////////NOMBRE DE fichier
						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT COUNT(*) AS nbrfichier FROM membres_messages WHERE id=? AND fichier!=?");
						$req_select->execute(array($idd_message_o, ''));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$nbrfichiernbrfichier = $ligne_select['nbrfichier'];

						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT COUNT(*) AS nbrfichier_r FROM membres_messages_reponse WHERE id_message=? AND fichier!=?");
						$req_select->execute(array($idd_message_o, ''));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$nbrfichiernbrfichier_r = $ligne_select['nbrfichier_r'];
						$nbrfichiernbrfichier_r_total = ($nbrfichiernbrfichier + $nbrfichiernbrfichier_r);
						/////////////////////////NOMBRE DE fichier

						/////////////////////////////////////////////////////////DERNIER MESSAGE
						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages_reponse WHERE id_message=? ORDER BY date_reponse_message DESC");
						$req_select->execute(array($idd_message_o));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_r = $ligne_select['id'];
						$id_membre_message_o_r = $ligne_select['id_membre'];
						$pseudo_message_o_r = $ligne_select['pseudo'];
						$id_article_message_o_r = $ligne_select['id_message'];
						$titre_message_message_o_r = $ligne_select['titre_reponse_message'];
						$message_message_o_r = nl2br($ligne_select['message_reponse']);
						$message_lu_message_o_r = $ligne_select['message_reponse_lu'];

						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres WHERE pseudo=?");
						$req_select->execute(array($pseudo_message_o_r));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_destinataire = $ligne_select['id'];
						$pseudo2_destinataire = $ligne_select['pseudo'];
						$mail_destinataire = $ligne_select['mail'];
						$nom_pseudo_message_o_r = $ligne_select['nom'];
						$prenom_pseudo_message_o_r = $ligne_select['prenom'];

						$date_lu_message_o1_r = $ligne_select['date_reponse_lu'];
						if (!empty($date_lu_message_o1_r)) {
							$date_lu_message_o_r = date('d-m-Y', $date_lu_message_o1_r);
							$date_lu_message_oh_r = date('H\hi', $date_lu_message_o1_r);
						}

						$date_message_message_o1_r = $ligne_select['date_reponse_message'];
						if (!empty($date_message_message_o1_r)) {
							$date_message_message_o_r = date('d-m-Y', $date_message_message_o1_r);
							$date_message_message_oh_r = date('H\hi', $date_message_message_o1_r);
						}

						$fichier_message_o_r = $ligne_select['fichier'];
						$ancre_message_o_r = $ligne_select['plus1'];

						if ($pseudo_message_o_r == $user && $pseudo_message_o_r != $pseudo_destinataire_message_o) {
							$pseudo_attente_reponse = "$prenom_pseudo_message_o_r $nom_pseudo_message_o_r";
						} else {
							$pseudo_attente_reponse = "$prenom_pseudo_destinataire_message_o $nom_pseudo_destinataire_message_o";
						}
						/////////////////////////////////////////////////////////DERNIER MESSAGE

						$message_message_o_r_len = strlen($message_message_o_r);
						$message_message_o_r = substr("$message_message_o_r", 0, 140);

						if ($message_message_o_r_len > 140) {
							$suite = "...";
						}

						////////////////////////////////////////////////////SI MESSAGE LU
						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages 
	WHERE id=? 
	AND pseudo_destinataire=?
	AND message_lu=?");
						$req_select->execute(array(
							$idd_message_o,
							$user,
							'oui'
						));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_rccl = $ligne_select['id'];

						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages_reponse 
	WHERE id_message=?
	AND pseudo!=?
	AND message_reponse_lu=?");
						$req_select->execute(array(
							$idd_message_o,
							$user,
							'oui'
						));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_rcll = $ligne_select['id'];
						////////////////////////////////////////////////////SI MESSAGE LU

						////////////////////////////////////////////////////SI MESSAGE NON LU
						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages 
	WHERE id=?
	AND pseudo_destinataire=? 
	AND message_lu!=?");
						$req_select->execute(array(
							$idd_message_o,
							$user,
							'oui'
						));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_rcc = $ligne_select['id'];

						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages_reponse 
	WHERE id_message=?
	AND pseudo!=? 
	AND message_reponse_lu!=?
");
						$req_select->execute(array(
							$idd_message_o,
							$user,
							'oui'
						));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_rc = $ligne_select['id'];
						////////////////////////////////////////////////////SI MESSAGE NON LU

						////////////////////////////////////////////////////MESSAGE EN ATTENTE DE LECTURE
						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages 
	WHERE id=?
	AND pseudo=?
	AND message_lu!=?");
						$req_select->execute(array(
							$idd_message_o,
							$user,
							'oui'
						));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_rccc = $ligne_select['id'];

						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_messages_reponse WHERE 
	id_message=? 
	AND pseudo=?
	AND message_reponse_lu!=?");
						$req_select->execute(array(
							$idd_message_o,
							$user,
							'oui'
						));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$idd_message_o_rcccc = $ligne_select['id'];
						////////////////////////////////////////////////////MESSAGE EN ATTENTE DE LECTURE

						/////////////////////////////FILTRES RECHERCHE
						if (
							!empty($_SESSION['Type_recherche_filtre_message']) && $_SESSION['Type_recherche_filtre_message'] == "Ouverts" && $pseudo_message_o == $user ||
							!empty($_SESSION['Type_recherche_filtre_message']) && $_SESSION['Type_recherche_filtre_message'] == "Messages-lus" && !empty($idd_message_o_rccl) ||
							!empty($_SESSION['Type_recherche_filtre_message']) && $_SESSION['Type_recherche_filtre_message'] == "Messages-lus" && !empty($idd_message_o_rcll) ||
							!empty($_SESSION['Type_recherche_filtre_message']) && $_SESSION['Type_recherche_filtre_message'] == "Messages-non-lus" && !empty($idd_message_o_rcc) ||
							!empty($_SESSION['Type_recherche_filtre_message']) && $_SESSION['Type_recherche_filtre_message'] == "Messages-non-lus" && !empty($idd_message_o_rc) ||
							!empty($_SESSION['Type_recherche_filtre_message']) && $_SESSION['Type_recherche_filtre_message'] == "Message-en-attente" && !empty($idd_message_o_rccc) ||
							!empty($_SESSION['Type_recherche_filtre_message']) && $_SESSION['Type_recherche_filtre_message'] == "Message-en-attente" && !empty($idd_message_o_rcccc) ||
							empty($_SESSION['Type_recherche_filtre_message'])
						) {
					?>

							<tr>
								<td>
									<table id='tablei<?php echo "$id_offr"; ?>' class='table_titre' style='margin-bottom: 10px; font-weight: normal; width: 100%; cursor: pointer;' onmouseover="this.style.backgroundColor='#F2F2F2'" onmouseout="this.style.backgroundColor=''" onclick="document.location.replace('/Message-contact-liste-<?php echo "$idd_message_o"; ?>.html');">

										<tr>
											<td style='padding-left: 10px; padding-top: 10px; vertical-align: middle; border-top-left-radius: 6px;' colspan='3'>
												<div class='btn btn-default' style=''><span class='uk-icon-user'></span> <?php echo "$prenom_pseudo_message_o $nom_pseudo_message_o"; ?></div>
												<div style='display: inline-block; padding-top: 5px;'>&#9656;</div>
												<div class='btn btn-default' style=''><span class='uk-icon-user'></span> <?php echo "$prenom_pseudo_destinataire_message_o $nom_pseudo_destinataire_message_o"; ?></div>
												<div class='btn btn-default' style=''><?php echo "Messages <span class='badge'>$idd_message_o_redirectm</span>"; ?> </div>
												<div class='btn btn-default' style=''><span class='uk-icon-clock-o'></span> <?php echo "Ouvert le, $date_message_message_o"; ?> </div>

												<div style='display: inline-block; float: right; margin-top: 5px;'>
													<?php if (!empty($idd_message_o_rcc) || !empty($idd_message_o_rc)) { ?>
														<span class="alert alert-danger" style='float: right; margin-right: 5px; padding-top: 0px; padding-bottom: 0px;'><?php echo "<span class='uk-icon-warning' ></span> Message non lu"; ?></span>
													<?php } ?>
													<?php if (!empty($idd_message_o_rccc) || !empty($idd_message_o_rcccc)) { ?>
														<span class="alert alert-warning" style='float: right; margin-right: 5px; padding-top: 0px; padding-bottom: 0px;'><?php echo "<span class='uk-icon-warning' ></span> Message en attente de lecture"; ?></span>
													<?php } ?>
													<?php if (empty($idd_message_o_rcccc) && empty($idd_message_o_rccc) && empty($idd_message_o_rcc) && empty($idd_message_o_rc)) { ?>
														<span class="alert alert-info" style='float: right; margin-right: 5px; padding-top: 0px; padding-bottom: 0px;'><?php echo "<span class='uk-icon-thumbs-o-up' ></span> Pas de nouveau message"; ?></span>
													<?php } ?>
												</div>

											</td>
										</tr>

										<tr>
											<td colspan='3' style='text-align: left; padding-left: 10px;'>
												<div style='margin-bottom: 0px; margin-left: 0px; padding: 5px;'>
													<h2 style='font-size: 16px; margin:0px; padding: 0px;color:black;'><?php echo "Objet : $titre_message_message_o"; ?></h2>
												</div>
											</td>
										</tr>

										<tr>
											<td colspan='3' style='text-align: left; padding-left: 10px; padding-bottom: 10px;'>

												<?php if (!empty($idd_message_o_r)) { ?>
													<div style='margin-bottom: 15px;'>&#9656; <?php echo "$message_message_o_r $suite"; ?></div>
												<?php } ?>

												<div style='font-size: 12px;'>
													<?php if (!empty($idd_message_o_r)) { ?>
														<?php echo "<span><span class='uk-icon-clipboard' ></span> Dernier message de <span style='font-weight: bold;'>$prenom_pseudo_message_o_r $nom_pseudo_message_o_r</span> &nbsp; &nbsp; 
<span> <span class='uk-icon-clock-o' ></span>  Posté le " . $date_message_message_o_r . " à " . $date_message_message_oh_r . "</span></span> &nbsp; &nbsp;";

														/* if($nbrfichiernbrfichier_r_total < 2){
echo "<span><span class='uk-icon-download' ></span> $nbrfichiernbrfichier_r_total fichier disponible</span></span> ";
}else{
echo "<span><span class='uk-icon-download' ></span> $nbrfichiernbrfichier_r_total fichiers disponibles</span></span> ";
}
*/

														?>
													<?php } else { ?>
														<!--
<div style='margin-bottom: 15px;'>&#9656;  <?php echo "$message_message_o_r $suite"; ?></div>
-->
													<?php } ?>
												</div>

											</td>
										</tr>
									</table>
								</td>
							</tr>

						<?php

							unset($idd_message_o_r);
							unset($idd_message_o_rccc);
							unset($idd_message_o_rccc);
							unset($idd_message_o_rcc);
							unset($idd_message_o_rc);
							unset($idd_message_o_redirectm);
							unset($suite);
						}
					}

					//////////////////////////////////////////SI AUCUN MESSAGE
					if (empty($idd_message_o)) {
						?>
						<tr>
							<td style='text-align: center;'>
								<div style='padding: 10px;'>
									<?php echo "Il y a aucun message pour le moment !"; ?>
								</div>
							</td>
						</tr>
					<?php
					}
					//////////////////////////////////////////SI AUCUN MESSAGE

					?>
				</tbody>
			</table>

		</div>
		<br>
		<br>
	<?php


	} else {
		header('location: /index.html');
	}
	?>

</div>