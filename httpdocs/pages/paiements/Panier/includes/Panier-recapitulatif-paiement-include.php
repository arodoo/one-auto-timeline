<div class='col-sm-12 col-md-7' style="margin-bottom:40px;">

	<div style='text-align: left;'>
		<h2>Paiement en ligne</h2>
	</div>
	<div style="clear:both;"></div>
	<hr />

	<?php
	//////////////////////////////////BANDEAU PANIER ACTIVE
	if ($activer_bandeau_page_panier == "oui") {
	?>
		<div class="alert <?php echo "$type_bandeau_page_panier"; ?> alert-dismissible" role="alert"
			style="position: relative; margin-top: 20px;">
			<div class="container" style="width: 90%; position: relative;">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<span class="<?php echo "$type_icone_page_panier"; ?> "></span>
				<?php echo "$contenu_bandeau_page_panier"; ?>
			</div>
		</div>
	<?php
	}
	//////////////////////////////////BANDEAU PANIER ACTIVE
	?>

	<?php

	/////////////////////////////////////////SI AUCUNE REMISE ATTRIBUEE - ON AFFICHE LE CHAMP CODE PROMOTION
	if ($abonnement_oo == 'oui') {
		if ($_SESSION['Offre_id_abonnement'] != 1) {
			if (empty($Remise) && $_SESSION['type_paiement'] == "Panier" || empty($Remise) && empty($_SESSION['type_paiement'])) {
	?>
				<div style='text-align: left; margin-bottom: 15px; margin-top: 45px;'>
					<form method='post' action='#'>
						<input class='form-control' type='text' id='Remise' name='code_promo' value=""
							placeholder="<?php echo "CODE PROMOTION"; ?>"
							style='width: 200px; margin-right: 5px; display: inline-block;' />
						<button id='Remise_bouton' class="btn btn-default" style="text-transform : uppercase;"
							onclick="return false;"> ENREGISTRER </button>
					</form>
				</div>
	<?php
			}
		}
	}
	/////////////////////////////////////////SI AUCUNE REMISE ATTRIBUEE - ON AFFICHE LE CHAMP CODE PROMOTION

	?>

	<table class='tableau_height' style='width: 100%;'>

		<tr>
			<td class='titre_paiement' style='text-transform : uppercase; padding: 10px;'><?php echo "Libellé"; ?></td>
			<td class='titre_paiement' style='text-transform : uppercase; padding: 10px; width: 80px;'>
				<?php echo "Prix HT"; ?>
			</td>
			<td class='titre_paiement' style='text-transform : uppercase; padding: 10px;'><?php echo "Quantité"; ?></td>
			<td class='titre_paiement' style='text-transform : uppercase; padding: 10px; width: 90px;'>
				<?php echo "TOTAL HT"; ?>
			</td>
		</tr>

		<?php
		///////////////////////////////SELECT BOUCLE
		$req_boucle = $bdd->prepare("SELECT * FROM membres_panier_details WHERE pseudo=? ORDER BY id ASC");
		$req_boucle->execute(array($user));
		while ($ligne_boucle = $req_boucle->fetch()) {
			$id_facture_panier_d = $ligne_boucle['id'];
			$id_panier_facture_details_id = $ligne_boucle['id'];
			$libelle = $ligne_boucle['libelle'];
			$TVA = sprintf('%.2f', $ligne_boucle['TVA']);
			$TVA_TAUX = $ligne_boucle['TVA_TAUX'];
			$action_module_service_produit = $ligne_boucle['action_module_service_produit'];
			$Duree_service = $ligne_boucle['Duree_service'];

			$PU_HT = sprintf('%.2f', $ligne_boucle['PU_HT']);
			$_SESSION['total_unitaire_HT'] = "$PU_HT";

			$quantite = $ligne_boucle['quantite'];
			$_SESSION['quantite'] = $quantite;

			$PU_HT_total = sprintf('%.2f', (($PU_HT * $quantite) + $PU_HT_total));
			$PU_HT_total_panier = sprintf('%.2f', ($PU_HT_total_panier + $PU_HT_total));
			$PU_TTC_total = sprintf('%.2f', ($PU_HT_total + $TVA));
			$PU_TTC_totald_panierd = ($PU_TTC_totald_panierd + $PU_TTC_total);

			if ($ligne_boucle['TVA_TAUX'] == "1.20") {
				$PU_TVA_TOTAUX = ($PU_TVA_TOTAUX + ($TVA * $quantite));
				$Taux_tva = "1.20";
			}
			if ($ligne_boucle['TVA_TAUX'] == "1.055") {
				$PU_TVA2_TOTAUX = ($PU_TVA2_TOTAUX + ($TVA * $quantite));
				$Taux2_tva = "1.055";
			}

		?>

			<tr>
				<td class='ligne_paiement' style='padding: 15px;'><?php echo strtoupper($libelle); ?></td>
				<td class='ligne_paiement' style='padding: 15px;'><?php echo "$PU_HT"; ?> &euro;</td>
				<td class='ligne_paiement' style='padding: 15px; text-align:center;'><?php echo $quantite; ?> </td>
				<td class='ligne_paiement' style='padding: 15px;'><?php echo $PU_HT_total; ?> &euro;</td>
			</tr>

		<?php
			unset($PU_HT_total);
		}
		$req_boucle->closeCursor();

		//Si pas de résultat 
		if (empty($id_facture_panier_d)) {
		?>
			<tr>
				<td class='ligne_paiement' colspan='4'><?php echo "Aucune information disponible pour le moment !"; ?></td>
			</tr>
		<?php
		}
		//Si pas de résultat 

		//////////////////////////////////////////TOTAUX

		$PU_HT_total_panier = sprintf('%.2f', ($PU_HT_total_panier));
		$PU_TVA_total_panier = sprintf('%.2f', ($PU_TTC_totald_panierd - $PU_HT_total_panier));

		$_SESSION['total_HT'] = $PU_HT_total_panier;
		$_SESSION['total_HT_net'] = $_SESSION['total_HT'];
		$_SESSION['total_TTC'] = sprintf('%.2f', ($_SESSION['total_HT_net'] + $PU_TVA_TOTAUX + $PU_TVA2_TOTAUX));
		$_SESSION['total_TVA'] = "$PU_TVA_total_panier";

		// Obtenir la valeur de Taux_tva depuis la base de données
		$taux_tva_query = $bdd->prepare("SELECT Taux_tva FROM configurations_pdf_devis_factures LIMIT 1");
		$taux_tva_query->execute();
		$taux_tva_result = $taux_tva_query->fetch();
		$taux_tva = $taux_tva_result['Taux_tva'] / 100; // Convertir en pourcentage

		$_SESSION['total_TTC'] = sprintf('%.2f', ($_SESSION['total_HT_net'] + $PU_TVA_TOTAUX + $PU_TVA2_TOTAUX));
		$_SESSION['total_TVA'] = "$PU_TVA_total_panier";

		// Ajouter la valeur de Taux_tva au total TTC
		$_SESSION['total_TTC'] = sprintf('%.2f', ($_SESSION['total_TTC'] * (1 + $taux_tva)));

		//////////////////////////////////////////TOTAUX

		if ($Taux_tva == "1.20") {
			$Taux_tva = "20";
		}
		if ($Taux2_tva == "1.055") {
			$Taux2_tva = "5.5";
		}

		?>

		<tr>
			<td class='titre_paiement totaux_paiement' colspan='2'><?php echo "TOTAL HT"; ?></td>
			<td class='titre_paiement totaux_paiement' colspan='2'><?php echo $_SESSION['total_HT']; ?> &euro;</td>
		</tr>
		<?php if (!empty($PU_TVA_TOTAUX)) { ?>
			<tr>
				<td class='titre_paiement totaux_paiement' colspan='2'><?php echo "TVA " . $Taux_tva . "%"; ?></td>
				<td class='titre_paiement totaux_paiement' colspan='2'><?php echo $PU_TVA_TOTAUX; ?> &euro;</td>
			</tr>
		<?php } ?>
		<?php if (!empty($PU_TVA2_TOTAUX)) { ?>
			<tr>
				<td class='titre_paiement totaux_paiement' colspan='2'><?php echo "TVA " . $Taux2_tva . "%"; ?></td>
				<td class='titre_paiement totaux_paiement' colspan='2'><?php echo $PU_TVA2_TOTAUX; ?> &euro;</td>
			</tr>
		<?php } ?>
		<tr>
			<td class='titre_paiement totaux_paiement' colspan='2'><?php echo "TTC"; ?></td>
			<td class='titre_paiement totaux_paiement' colspan='2'><?php echo $_SESSION['total_TTC']; ?> &euro;</td>
		</tr>

	</table>

	<div style="margin-top: 20px;">
		<?php echo "En cliquant sur confirmer vous acceptez nos <a href='/CGU'>Cgu</a> et <a href='/CGV'>Cgv</a>, ainsi que le <a href='/Traitements-de-mes-donnees'>traitements de vos données.</a>"; ?></a>
	</div>

	<?php

	if (empty($user) && $_SESSION['total_HT'] == 0) {
	?>
		<div class="alert alert-danger" style="text-align: left; margin-top: 20px;">
			<span class="uk-icon-warning"></span> Vous devez vous identifiez afin de pouvoir procédé au paiement sécurisé en
			ligne.
		</div>
	<?php
	}

	//////SI ABONNEMENT PAYANT
	/* 	if ($_SESSION['total_HT'] > 0 && !empty($user)) {
		include('Panier-paypal-include.php');
		?>

		<?php
	}  */
	if (empty($user) && $_SESSION['total_HT'] > 0) {


	?>
		<div class="alert alert-danger" style="text-align: left; margin-top: 20px;">
			<span class="uk-icon-warning"></span> Vous devez vous identifiez afin de pouvoir procédé au paiement sécurisé en
			ligne.
		</div>
	<?php
	}

	//////PAIEMENT STRIPE
	/* 	if (isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && isset($user)) { */
	?>
	<div style="text-align: center; margin-top: 20px;">
		<a id='payerCB' class='btn btn-defaut'
			style='text-transform : uppercase;'> PAYER </a>
	</div>
	<?php
	/* } */

	//////SI ADMIN VALIDATION ABONNEMENT PAYANT
	/* if (isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && isset($user)) {
		   ?>
		   <div style="text-align: center; margin-top: 20px;">
			   <a href="/index.php?page=Traitements-admin" id='valider-admin' class='btn btn-defaut'
				   style='text-transform : uppercase;'> VALIDER EN ADMIN </a>
		   </div>
		   <?php
	   } */
	?>

</div>