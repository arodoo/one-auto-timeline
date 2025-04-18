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

<!--<script src="/js/dist/clipboard.min.js"></script>-->

<script>
	$(document).ready(function() {

		//AJAX
		$(document).on("change", "#remember-me", function() {
			facturation_autre();
		});

		function facturation_autre() {
			if ($('#remember-me').is(':checked')) {
				$("#autre_adresse_facturation").css("display", "");
			} else {
				$("#autre_adresse_facturation").css("display", "none");
			}
		}
		facturation_autre();
	});

	//new ClipboardJS('.btn');
</script>

<div id='is' class="contact-form-wrapper background-white p30" style='text-align: left;'>

	<?php

	/////////Variable * => ok si inscription
	if ($modif != "oui") {
		$inscription_ok = "*";
	} else {
		$inscription_ok = "";
	}
	/////////Variable * => ok si inscription

	?>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div style='clear: both;'></div>

	<div class="alert alert-success background_color" role="alert">
		<p style="margin-bottom: 0px;"><span class="uk-icon-user"></span> <b>Dernière connexion</b> le,
			<?php
			if (!empty($last_login)) {
				echo date('d-m-Y à H:i', $last_login);
			} else {
				echo date('d-m-Y à H:i', time());

				///////////////////////////////UPDATE
				$sql_update = $bdd->prepare("UPDATE membres SET 
			last_ip=?,
			last_login=? 
			WHERE pseudo=?");
				$sql_update->execute(array(
					$_SERVER['REMOTE_ADDR'],
					time(),
					htmlspecialchars($user)
				));
				$sql_update->closeCursor();
			}
			?>
		</p>
	</div>

	<hr />
	<h2 class="style_color">Mon compte</h2>
	<hr />

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div class="row style_color">
		<label class="control-label col-sm-2"><?php echo "Type de compte $inscription_ok"; ?></label>
		<div class="col-sm-5">

			<?php

			$req_bouclem = $bdd->prepare("SELECT * FROM membres WHERE id=?");
			$req_bouclem->execute(array($id_oo));
			$ligne_bouclem = $req_bouclem->fetch();
			$statut_compte = $ligne_bouclem['statut_compte'];

			$req_bouclem2 = $bdd->prepare("SELECT * FROM membres_type_de_compte WHERE id=?");
			$req_bouclem2->execute(array($statut_compte));
			$ligne_bouclem2 = $req_bouclem2->fetch();
			$statut_compte2 = $ligne_bouclem2['Nom_type'];

			echo $statut_compte2;

			?>

		</div>
	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div class="row style_color">
		<label class="control-label col-sm-2">Abonné à la newsletter</label>
		<div class="col-sm-5">
			<input type="checkbox" id='newslettre' name='newslettre' <?php if ($newslettre_oo == "1") {
																			echo "checked";
																		} ?> />
		</div>
	</div>

	<?php
	if ($ligne_bouclem['statut_compte'] == 1) {
	?>

		<div class="row style_color">
			<label class="control-label col-sm-2"><?php echo "Nom du commercial"; ?> </label>
			<div class="col-sm-5">
				<input type="text" id='nom_commercial' name='nom_commercial' class="form-control" placeholder=""
					autocomplete="off" value="<?php echo "$nom_commercial"; ?>"
					style='<?php echo "$coloorm"; ?> margin-bottom: 0px;' />
			</div>
		</div>


		<div style='clear: both; margin-bottom: 15px;'></div>

	<?php
	}
	?>

	<div class="row style_color">
		<label class="control-label col-sm-2"><?php echo "Adresse mail $inscription_ok"; ?> *</label>
		<div class="col-sm-5">
			<input type="text" id='Mail' name='Mail' class="form-control" placeholder="" autocomplete="off"
				value="<?php echo "$Mail"; ?>" style='<?php echo "$coloorm"; ?> margin-bottom: 0px;' />
		</div>
	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div class="row style_color">
		<label class="control-label col-sm-2"><?php echo "Mot de passe actuel $inscription_ok"; ?>*</label>
		<div class="col-sm-5">
			<input type="password" id='password_actuel' name='password_actuel' class="form-control" id="password_actuel"
				placeholder="<?php echo "Mot de passe"; ?>" value="<?php echo "$passwordclient"; ?>"
				style='<?php echo "$coloorppasse"; ?> margin-bottom: 15px;' />
		</div>
	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div id="rappot_mot_de_passe_nouveau" class="alert alert-warning" role="alert"
		style="margin-bottom: 10px; display: none;"><span class="uk-icon-exclamation-circle"></span> <b>Mot de passe</b>
		: Alphanumérique, 8 caractères avec miniscules et majuscules. Ex : Ni7Co1As</div>

	<div class="row style_color">
		<label class="control-label col-sm-2"><?php echo "Nouveau mot de passe $inscription_ok"; ?></label>
		<div class="col-sm-5">
			<input type="password" id='password' name='password' class="form-control" id="passwordclient"
				placeholder="<?php echo "Mot de passe"; ?>" value="<?php echo "$passwordclient"; ?>"
				style='<?php echo "$coloorppasse"; ?> margin-bottom: 15px;' />
		</div>
		<div class="col-sm-5">
			<input type="password" id='passwordclient2' name='passwordclient2' class="form-control" id="passwordclient2"
				placeholder="<?php echo "Confirmer mot de passe"; ?>" value="<?php echo "$passwordclient2"; ?>"
				style='<?php echo "$coloorppasse"; ?> margin-bottom: 15px;' />
		</div>
	</div>

	<div style='clear: both;'></div>

	<hr />
	<h2 class="style_color"><?php echo "Mes coordonnées"; ?></h2>
	<hr />

	<div class="row style_color">
		<label class="control-label col-sm-2"><?php echo "Civilité"; ?> *</label>
		<div class="col-sm-2">
			<select id="FH" name="FH" class="form-control" style='margin-bottom: 15px; <?php echo "$coloorpr"; ?>'>
				<option value="">Sélection</option>
				<option <?php if ($civilites_oo == "Madame") {
							echo "selected";
						} ?> value="Madame">Madame</option>
				<option <?php if ($civilites_oo == "Monsieur") {
							echo "selected";
						} ?> value="Monsieur">Monsieur</option>
			</select>
		</div>
	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div class="row style_color">
		<div class="col-sm-6">
			<label class="control-label"><?php echo "Nom"; ?> *</label>
			<input type="text" id='Nom' name='Nom' class="form-control" placeholder="" value="<?php echo "$Nom"; ?>"
				style='margin-bottom: 15px; <?php echo "$coloorn"; ?>' />
		</div>
		<div class="col-sm-6">
			<label class="control-label"><?php echo "Prénom"; ?> *</label>
			<input id='Prenom' name='Prenom' type="text" class="form-control" placeholder=""
				value="<?php echo "$Prenom"; ?>" style='margin-bottom: 15px; <?php echo "$coloorpr"; ?>' />
		</div>
	</div>

	<div style='clear: both;'></div>

	<div class="row col-sm-12 style_color">
		<label class="control-label"><?php echo "Adresse"; ?> *</label>
		<input type="text" id='Adresse' name='Adresse' class="form-control" placeholder="<?php echo "Adresse"; ?>"
			value="<?php echo "$Adresse"; ?>" style='<?php echo "$coloorpaaa"; ?>' />
	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div class="row">
		<div class="col-sm-6 style_color">
			<label class="control-label">Code postal*</label>
			<input id='Code_postal' name='Code_postal' type="text" class="form-control"
				placeholder="<?php echo "Code postal"; ?>" value="<?php echo "$Code_postal"; ?>"
				style='margin-bottom: 15px; <?php echo "$coloorpccc"; ?>' />
		</div>

		<div class="col-sm-6 style_color">
			<label class="control-label">Ville*</label>
			<input type="text" class="form-control" placeholder="<?php echo "Ville"; ?>" id='Ville' name='Ville'
				value="<?php echo "$Ville"; ?>" style='margin-bottom: 15px; <?php echo "$coloorpvvv"; ?>' />
		</div>
	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div class="background-white">
		<hr />
		<h2 class="style_color"><?php echo "Coordonnées de contact"; ?></h2>
		<hr />

		<div class="row">
			<div class="col-sm-6 style_color" style='margin-bottom: 15px;'>
				<label>Téléphone fixe</label>
				<input type="text" id='Telephone' name='Telephone' class="form-control"
					placeholder="<?php echo 'Téléphone'; ?>" value="<?php echo htmlspecialchars($Telephone); ?>"
					style='<?php echo "$coloorpccc1telfixe"; ?>'
					maxlength="10" pattern="\d{10}" inputmode="numeric" required />
			</div>


			<div class="col-sm-6 style_color" style='margin-bottom: 15px;'>
				<label>Téléphone portable</label>
				<input type="text" id='Telephone_portable' name='Telephone_portable' class="form-control"
					placeholder="<?php echo 'Portable'; ?>"
					value="<?php echo htmlspecialchars(!empty($Telephone_portable) ? $Telephone_portable : $Telephone); ?>"
					style='<?php echo "$coloorpccc1portable"; ?>'
					maxlength="10" pattern="\d{10}" inputmode="numeric" required />
			</div>

		</div>

	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<div class="background-white">
		<hr />
		<h2 class="style_color"><?php echo "Informations d'assurance"; ?></h2>
		<hr />

		<script>
		$(document).ready(function() {
			// Convert date inputs to timestamps before form submission
			$("#modification_post").click(function() {
				// Get date values
				var fromDate = $("#valid_from_date").val();
				var toDate = $("#valid_to_date").val();
				
				// Convert to timestamps if dates are provided
				if(fromDate) {
					var timestamp = Math.floor(new Date(fromDate).getTime() / 1000);
					$("#valid_from").val(timestamp);
					console.log("From date converted: " + fromDate + " -> " + timestamp);
				}
				
				if(toDate) {
					var timestamp = Math.floor(new Date(toDate).getTime() / 1000);
					$("#valid_to").val(timestamp);
					console.log("To date converted: " + toDate + " -> " + timestamp);
				}
			});
			
			// Initialize date fields from timestamps when page loads
			var fromTimestamp = $("#valid_from").val();
			var toTimestamp = $("#valid_to").val();
			
			if(fromTimestamp && fromTimestamp > 0) {
				var date = new Date(fromTimestamp * 1000);
				var dateString = date.getFullYear() + '-' + 
								String(date.getMonth() + 1).padStart(2, '0') + '-' + 
								String(date.getDate()).padStart(2, '0');
				$("#valid_from_date").val(dateString);
				console.log("Initialized from date: " + fromTimestamp + " -> " + dateString);
			}
			
			if(toTimestamp && toTimestamp > 0) {
				var date = new Date(toTimestamp * 1000);
				var dateString = date.getFullYear() + '-' + 
								String(date.getMonth() + 1).padStart(2, '0') + '-' + 
								String(date.getDate()).padStart(2, '0');
				$("#valid_to_date").val(dateString);
				console.log("Initialized to date: " + toTimestamp + " -> " + dateString);
			}
		});
		</script>

		<div class="row">
			<div class="col-sm-6 style_color" style='margin-bottom: 15px;'>
				<label>Nom de la société d'assurance</label>
				<input type="text" id='company_name' name='company_name' class="form-control"
					placeholder="Nom de la société d'assurance"
					value="<?php echo isset($insurance_data) ? htmlspecialchars($insurance_data['company_name']) : ''; ?>" />
			</div>
			<div class="col-sm-6 style_color" style='margin-bottom: 15px;'>
				<label>N° de contrat</label>
				<input type="text" id='contract_number' name='contract_number' class="form-control"
					placeholder="N° de contrat"
					value="<?php echo isset($insurance_data) ? htmlspecialchars($insurance_data['contract_number']) : ''; ?>" />
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 style_color" style='margin-bottom: 15px;'>
				<label>N° de carte verte</label>
				<input type="text" id='green_card_number' name='green_card_number' class="form-control"
					placeholder="N° de carte verte"
					value="<?php echo isset($insurance_data) ? htmlspecialchars($insurance_data['green_card_number']) : ''; ?>" />
			</div>
			<div class="col-sm-6 style_color" style='margin-bottom: 15px;'>
				<label>Agence d'assurance</label>
				<input type="text" id='agency_name' name='agency_name' class="form-control"
					placeholder="Nom de l'agence"
					value="<?php echo isset($insurance_data) ? htmlspecialchars($insurance_data['agency_name']) : ''; ?>" />
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 style_color" style='margin-bottom: 15px;'>
				<label>Adresse de l'agence</label>
				<input type="text" id='agency_address' name='agency_address' class="form-control"
					placeholder="Adresse de l'agence"
					value="<?php echo isset($insurance_data) ? htmlspecialchars($insurance_data['agency_address']) : ''; ?>" />
			</div>
			<div class="col-sm-6 style_color" style='margin-bottom: 15px;'>
				<label>Pays de l'agence</label>
				<input type="text" id='agency_country' name='agency_country' class="form-control"
					placeholder="Pays de l'agence"
					value="<?php echo isset($insurance_data) ? htmlspecialchars($insurance_data['agency_country']) : ''; ?>" />
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 style_color" style='margin-bottom: 15px;'>
				<label>Email de l'agence</label>
				<input type="text" id='agency_email' name='agency_email' class="form-control"
					placeholder="Email de l'agence"
					value="<?php echo isset($insurance_data) ? htmlspecialchars($insurance_data['agency_email']) : ''; ?>" />
			</div>
			<div class="col-sm-6 style_color" style='margin-bottom: 15px;'>
				<label>Validité</label>
				<div class="row">
					<div class="col-sm-6">
						<label>Du</label>
						<input type="date" id='valid_from_date' name='valid_from_date' class="form-control"
							value="<?php echo isset($insurance_data) && !empty($insurance_data['valid_from']) ? date('Y-m-d', $insurance_data['valid_from']) : ''; ?>" />
						<input type="hidden" id='valid_from' name='valid_from' 
							value="<?php echo isset($insurance_data) ? htmlspecialchars($insurance_data['valid_from']) : ''; ?>" />
					</div>
					<div class="col-sm-6">
						<label>Au</label>
						<input type="date" id='valid_to_date' name='valid_to_date' class="form-control"
							value="<?php echo isset($insurance_data) && !empty($insurance_data['valid_to']) ? date('Y-m-d', $insurance_data['valid_to']) : ''; ?>" />
						<input type="hidden" id='valid_to' name='valid_to'
							value="<?php echo isset($insurance_data) ? htmlspecialchars($insurance_data['valid_to']) : ''; ?>" />
					</div>
				</div>
			</div>
		</div>
	</div>

	<div style='clear: both; margin-bottom: 15px;'></div>

	<?php
	if ($statut_compte_oo == 2 || $statut_compte_oo == 3 || $statut_compte_oo == 4 || $statut_compte_oo == 5 || $statut_compte_oo == 6) {
	?>

		<div style='clear: both; margin-bottom: 15px;'></div>

		<div class="background-white">
			<hr />
			<h2 class="style_color"><?php echo "Informations"; ?></h2>
			<hr />

			<div class="row style_color">
				<div class="col-sm-6" style='margin-bottom: 15px;'>
					<label>Nom société</label>
					<input type="text" id='Nom_societe' name='Nom_societe' class="form-control"
						placeholder="<?php echo "Nom société"; ?>*" value="<?php echo "$Nom_societe"; ?>"
						style='<?php echo "$coloorpccc1telfixe"; ?>' />
				</div>


				<div class="col-sm-6" style='margin-bottom: 15px;'>
					<label>Siret</label>
					<input type="text" id='Numero_identification' name='Numero_identification' class="form-control"
						placeholder="<?php echo "Siret"; ?>" value="<?php echo "$Numero_identification"; ?>"
						style='<?php echo "$coloorpccc1portable"; ?>' />
				</div>
			</div>

		</div>

		<div style='clear: both; margin-bottom: 15px;'></div>

	<?php
	}
	?>

	<?php
	if ($modif != "oui") {
	?>
		<div class="row style_color">
			<label class="control-label col-sm-6"></label>
			<div class="col-sm-10">
				<div class="checkbox">
					<label> <input id='cbb' name='cbb' type="checkbox" checked="checked"
							value='1' /><?php echo "Je m'inscris à la newsletter"; ?></label>
				</div>
			</div>
		</div>
	<?php
	}

	//////////////////////////////////////SI LES CONDITIONS GENERALES EXISTES
	if (!empty($lien_conditions_generales_compte)) {
	?>
		<div style="clear: both;"></div>
		<div class="row">
			<div class="col-sm-12" style="margin-bottom: 15px;">
				<?php echo "$lien_conditions_generales_compte"; ?></a>
			</div>
		</div>
	<?php
	}
	?>

	<div class="row">
		<div class="col-sm-12">
			<b style="font-weight : normal;">"Les données collectées par la plateforme sont nécessaires pour compléter
				votre profil. Vous disposez d'un droit d'accès, de rectification, d'opposition, de limitation du
				traitement, de suppression, de portabilité.
				Pour plus d'informations consultez notre <a class="style_color" href="/Traitements-de-mes-donnees"
					target="_blank">politique de confidentialité</a>"</b>
		</div>
	</div>


	<div class="row style_color">
		<label class="control-label col-sm-6"></label>
		<div class="col-sm-10">
			<small><?php echo "P.S : Tous les champs précédés d'une étoile (*) doivent être obligatoirement remplis."; ?></small>
		</div>
	</div>

	<div style="clear: both;"></div>

	<div class="row" style="margin-top: 15px;">
		<div class="col-sm-12" style="text-align: center;">
			<?php
			if ($modif != "oui") {
			?>
				<button type='button' id='creation_post' class='btn btn-default btn-white w-space btn-couleur'
					style='text-align: center; display: inline-block;' onclick="return false;">ENREGISTRER</button>
			<?php
			} else {
			?>
				<button type='button' id='modification_post' class='btn btn-default btn-white w-space btn-couleur'
					style='text-align: center; display: inline-block;' onclick="return false;">ENREGISTRER</button>
			<?php
			}
			?>
		</div>
	</div>

</div>


<div style='clear: both; margin-bottom: 20px;'></div>