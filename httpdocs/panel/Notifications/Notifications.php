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

	if(isset($user) ){
		?>

		<script>
			$(document).ready(function (){

				//FUNCTION AJAX - LISTE NEWSLETTER
				function listeCompteMembre(){
					$.post({
						url : '/panel/Notifications/Notifications-action-liste-ajax.php',
						type : 'POST',
						data:{idaction:"<?php echo $_GET['idaction']; ?>"},
						dataType: "html",
						success: function (res) {
							$("#liste-compte-membre").html(res);
						}
					});
				}
				listeCompteMembre();
			});

		</script>

		<?php
		$action = $_GET['action'];
		$idaction = $_GET['idaction'];
		?>

		<div style='padding: 5px; text-align: center;'>

		<?php
		////////////////////////////////////////////////////////////////////////////////////////////FORMULAIRE AJOUTER - MODIFIER
		if($action == "consulter"){

			///////////////////////////////SELECT
			$req_select = $bdd->prepare("SELECT * FROM membres_logs WHERE id=? ORDER BY date_seconde DESC");
			$req_select->execute(array($idaction));
			$ligne_select = $req_select->fetch();
			$req_select->closeCursor();
			$idd = $ligne_select['id']; 
			$id_membre = $ligne_select['id_membre'];
			$pseudo = $ligne_select['pseudo'];
			$mail_compte_concerne = $ligne_select['mail_compte_concerne'];
			$module = $ligne_select['module'];
			$action_sujet = $ligne_select['action_sujet'];
			$action_libelle = $ligne_select['action_libelle'];
			$action = $ligne_select['action'];
			$date = $ligne_select['date'];
			$date_seconde = $ligne_select['date_seconde'];
			$heure = $ligne_select['heure'];
			$ip = $ligne_select['ip'];
			$navigateur = $ligne_select['navigateur'];
			$navigateur_version = $ligne_select['navigateur_version'];
			$referrer = $ligne_select['referrer'];
			$uri = $ligne_select['uri'];
			$cookies_autorisees = $ligne_select['cookies_autorisees'];
			$os = $ligne_select['os'];
			$langue = $ligne_select['langue'];
			$niveau = $ligne_select['niveau'];
			$lieu = $ligne_select['lieu'];
			//$compte_bloque = $ligne_select['compte_bloque'];

			///////////////////////////////SELECT
			$req_select = $bdd->prepare("SELECT * FROM membres where mail=?");
			$req_select->execute(array($mail_compte_concerne));
			$ligne_select = $req_select->fetch();
			$req_select->closeCursor();
			$idd2dddf = $ligne_select['id']; 
			$loginm = $ligne_select['pseudo'];
			$emailm = $ligne_select['mail'];
			$adminm = $ligne_select['admin'];
			$nomm = $ligne_select['nom'];
			$prenomm = $ligne_select['prenom'];
			$adressem = $ligne_select['adresse'];
			$cpm = $ligne_select['cp'];
			$villem = $ligne_select['ville'];
			$IM = $ligne_select['IM'];
			$IM_REGLEMENT = $ligne_select['IM_REGLEMENT'];
			$telephonepost = $ligne_select['Telephone'];
			$telephoneposportable = $ligne_select['Telephone_portable'];
			$cba = $ligne_select['newslettre'];
			$cbb = $ligne_select['reglement_accepte'];
			$FH = $ligne_select['femme_homme'];
			$datenaissance = $ligne_select['datenaissance'];
			$passwd = $ligne_select['pass'];
			$passwdd = $ligne_select['pass'];
			$pdate_etatdate_etat = $ligne_select['date_etat'];
			$date_enregistrement = $ligne_select['date_enregistrement'];
			$ip_inscription = $ligne_select['ip_inscription'];
			$compte_bloque = $ligne_select['compte_bloque'];
			$compte_bloque_date = $ligne_select['compte_bloque_date'];
			if($compte_bloque == "oui" && !empty($compte_bloque_date)){
				$compte_bloque_date = ", bloqué le ".date('d-m-Y', $compte_bloque_date)."";
			}else{
				$compte_bloque_date = "";
			}

			$paypost = $ligne_select['Pays'];
			$Client = $ligne_select['Client'];
			$nbractivation = $ligne_select['nbractivation'];
			$site_web = $ligne_select['site_web'];
			$pseudo_skype = $ligne_select['pseudo_skype'];
			$last_login = $ligne_select['last_login'];
			$last_ip = $ligne_select['last_ip'];
			$FH = $ligne_select['civilites'];
			$faxpost = $ligne_select['Fax'];
			$statut_compte = $ligne_select['statut_compte'];

			////////MISE A JOUR LECTURE 
			///////////////////////////////UPDATE
			$sql_update = $bdd->prepare("UPDATE membres_logs SET 
				lu=?, 
				lu_date=? 
				WHERE id=?");
			$sql_update->execute(array(
				'oui', 
				time(), 
				$idd));                     
			$sql_update->closeCursor();

			echo "<div style='text-align: center; max-width: 550px; margin-right: auto; margin-left: auto;'>";
				?>

				<div style='text-align: left;'>
					<?php
					if($action == "AVIS"){
						?>
						<h2>Un avis a été déposé sur une de vos vitrines</h2><br /><br />
						<?php
					}elseif($action == "DEMANDE"){
						?>
						<h2>Demande de campagne</h2><br /><br />
						<?php
					}elseif($action == "MODIFICATION"){
						?>
						<h2>Modifications de vos informations</h2><br /><br />
						<?php
					}elseif($action == "PROFIL DE PAIEMENT"){
						?>
						<h2>Modification de vos données de paiements</h2><br /><br />

						<?php
					}elseif($action == "NOUVELLE DEMANDE"){
						?>
						<h2>Nouvelle demande de campagne</h2><br /><br />

						<?php
					}elseif($action == "RESERVATION"  ){
						?>
						<h2>Réservation</h2><br /><br />

						<?php
					}elseif($action == "CONFIRMATION"  ){
						?>
						<h2>Demande de redéfinition de mot de passe</h2><br /><br />

						<?php
					}elseif($action == "PROFIL DE PAIEMENT"  ){
						?>
						<h2>Modification de vos données de paiements </h2><br /><br />

						<?php
					}else{
						?>
						<h2>Consulter le log de <?php echo "$mail_compte_concerne"; ?> </h2><br /><br />
						<?php
					}
					?>
				</div>

				<?php
				echo "<table style=' width: 100%; text-align: center;' cellpadding='2' cellspacing='2'>";
					?>

					<tr>
						<td style='text-align: left;' colspan='2'><strong> Rapport : </strong>
							<?php
							if($compte_bloque == "oui"){
								echo "<span class='label label-danger'>Compte bloqué</span>";
							}
							if($niveau == 1){
								echo "<span class='label label-danger'>Niveau important</span>";
							}elseif($niveau == 2){
								echo "<span class='label label-warning'>Niveau moyen</span>";
							}elseif($niveau == 3){
								echo "<span class='label label-label-info'>Niveau faible</span>";
							}elseif($niveau > 3){
								echo "<span class='label label-default'>Information</span>";
							}elseif(empty($niveau) ){
								echo "<span class='label label-default'>Information</span>";
							}else{
								echo "<span class='label label-default'>Information</span>";
							}
							?>
						</td>
					</tr>
					<tr>
						<td style='text-align: left;'>&nbsp;</td>
					</tr>

					<?php
					if($compte_bloque == "oui"){
						?>
						<tr>
							<td style='text-align: left; font-weight: bold;'><span class="uk-icon-calendar"></span>Compte bloqué le ?</td>
							<td style='text-align: left; color: red;'>  <?php echo "$compte_bloque_date"; ?></td>
						</tr>
						<tr>
							<td style='text-align: left;'>&nbsp;</td>
						</tr>
						<?php
					}
				echo "</table>";
				?>

				<div class="alert alert-info" role="alert" style="text-align: left;" >
					<p><strong><span class="uk-icon-warning"></span> Log du <?php echo "$date à $heure"; ?> </strong></p>
				</div>

				<table style=' width: 100%; text-align: center;' cellpadding='2' cellspacing='2'>

					<tr><td style='text-align: left; width: 40%;'><strong><span class="uk-icon-file-text"></span> Détails associés à la notification : </strong> <?php echo "$action_libelle"; ?></td></tr>
					<tr><td style='text-align: left;'>&nbsp;</td></tr>

					<tr><td style='text-align: left; width: 40%;'><strong><span class="uk-icon-file-text-o"></span>  Sujet : </strong> <?php echo "$action_sujet"; ?> </td></tr>
					<tr><td style='text-align: left;'>&nbsp;</td></tr>

				</table>

				<?php
				if($action != "AVIS" && $action != "DEMANDE" && $action != "RESERVATION"  ){
					?>
					<div class="well well-sm">
						<table style=' width: 100%; text-align: center;' cellpadding='2' cellspacing='2'>
							<tr><td style='text-align: left; width: 40%;'><strong><span class="uk-icon-user"></span>  Pseudo du compte : </strong></td>
							<td style='text-align: left;'><?php echo $pseudo; ?></td></tr>
							<tr><td style='text-align: left;'>&nbsp;</td></tr>

							<tr><td style='text-align: left; width: 40%;'><strong><span class="uk-icon-envelope-o"></span>  Mail du compte concerné : </strong></td>
							<td style='text-align: left;'><?php echo $mail_compte_concerne; ?></td></tr>
							<tr><td style='text-align: left;'>&nbsp;</td></tr>
						</table>
					</div>

					<div class="alert alert-warning" role="alert" style="text-align: left;" >
						<p><span class="uk-icon-warning"></span> <strong>Dernière connexion : </strong> <?php if(!empty($last_login) && $last_login != "time"){ echo date('d-m-Y à H:i', $last_login); }else{ echo "--"; } ?></p>
					</div>

					<div class="well well-sm">
						<table style=' width: 100%; text-align: center;' cellpadding='2' cellspacing='2'>
							<tr><td style='text-align: left; width: 40%;'><strong><span class="uk-icon-cog"></span> MODULE :</strong></td>
							<td style='text-align: left;'><?php echo "$module"; ?></td></tr>
							<tr><td style='text-align: left;'>&nbsp;</td></tr>

							<tr><td style='text-align: left; width: 40%;'><strong><span class="uk-icon-cogs"></span> ACTION :</strong></td>
							<td style='text-align: left;'><?php echo "$action"; ?></td></tr>
							<tr><td style='text-align: left;'>&nbsp;</td></tr>
						</table>
					</div>

					<?php
				}
				?>

				<?php
			echo "</div>
			<br /><br />";
		}
		////////////////////////////////////////////////////////////////////////////////////////////FORMULAIRE AJOUTER - MODIFIER

		////////////////////////////////////////////////////////////////////////////////////////////PAS D'ACTION
		if(!isset($action)){
			?>
			<div style='clear: both; margin-bottom: 20px;'></div>
			<div id='liste-compte-membre' style='clear: both;'></div>
			<?php
		} 
		////////////////////////////////////////////////////////////////////////////////////////////PAS D'ACTION

		echo "</div>";

	} else {
header("location: /");
	}
	?>
</div>