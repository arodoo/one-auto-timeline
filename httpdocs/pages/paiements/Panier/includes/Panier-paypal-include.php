<?php

$_SESSION['port'] = 0.0;
$paypal = "#";
$paypal = new Paypal();
/////////////////////////////POUR PLUS DE DETAILS https://developer.paypal.com/docs/classic/api/merchant/GetExpressCheckoutDetails_API_Operation_NVP/
$params = array(
	'SOLUTIONTYPE' => "Sole",
	'LANDINGPAGE' => "Billing",
	'LOGOIMG' => "" . $http . "$nomsiteweb/images/Logo/logo-dark.png",
	'INVNUM' => "$numero_panier" . time() . "",
	'BRANDNAME' => "$nom_proprietaire",
	'PHONENUM' => "" . $Telephone_portable_oo . "",
	'FIRSTNAME' => "" . $prenom_oo . "",
	'MIDDLENAME' => "" . $prenom_oo . "",
	'LASTNAME' => "" . $nom_oo . "",
	'PAYER_EMAIL' => "" . $mail_oo . "",
	'H_PHONENUMBER' => "" . $Telephone_portable_oo . "",
	'EMAIL' => "" . $mail_oo . "",
	'PAYMENTREQUEST_0_PAYMENTACTION' => "Sale",
	'PAYMENTREQUEST_0_SHIPTOSTREET' => "" . $adresse_oo . "",
	'PAYMENTREQUEST_0_SHIPTOCITY' => "" . $ville_oo . "",
	'PAYMENTREQUEST_0_SHIPTOZIP' => "" . $cp_oo . "",
	'PAYMENTREQUEST_0_SHIPTOPHONENUMP' => "" . $Telephone_portable_oo . "",
	'RETURNURL' => "" . $http . "$nomsiteweb/index.php?page=Traitements",
	'CANCELURL' => "" . $http . "$nomsiteweb",
	'PAYMENTREQUEST_0_CURRENCYCODE' => 'EUR',
	'PAYMENTREQUEST_0_SHIPPINGAMT' => $_SESSION['port'],
	//PAYMENTREQUEST_n_ITEMAMT
);

$k = 0;
unset($PU_TTC_totald_panierd);
//////////////////////////////////////////////////////PRODUITS SERVICES
///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM $table_liste_details");
$req_boucle->execute(array($table_liste_details_valeur));
while ($ligne_boucle = $req_boucle->fetch()) {
	$id_facture_panier_dd = $ligne_boucle['id'];
	$id_panier_facture_details_idd = $ligne_boucle['id'];
	$libelled = $ligne_boucle['libelle'];
	//SI TABLE PANIER
	$id_panier_SERVICE_PRODUIT_idd = $ligne_boucle['id_panier_SERVICE_PRODUIT'];
	$PU_HTd = $ligne_boucle['PU_HT'];
	$quantited = $ligne_boucle['quantite'];
	$PU_HT_totald = (($PU_HTd * $quantited) + $PU_HT_total);
	$Tva_coef = $ligne_boucle['TVA_TAUX'];
	$PU_TTC_totald = sprintf('%.2f', ($PU_HT_totald * $Tva_coef));
	$Tva = $ligne_boucle['TVA'];


	$params["L_PAYMENTREQUEST_0_NAME$k"] = $libelled;
	$params["L_PAYMENTREQUEST_0_DESC$k"] = '';
	$params["L_PAYMENTREQUEST_0_AMT$k"] = sprintf('%.2f', ($PU_HTd + $Tva));
	$params["L_PAYMENTREQUEST_0_QTY$k"] = utf8_encode($quantited);

	$PU_TTC_totald_panierd = ($PU_TTC_totald_panierd + $PU_TTC_totald);
	$k++;
}
$req_boucle->closeCursor();
//////////////////////////////////////////////////////PRODUITS SERVICES

///////////////////////////////////////////////////////////////////REMISE
if (!empty($remise_panier_facture_montant)) {
	$params["L_PAYMENTREQUEST_0_NAME$k"] = utf8_encode($libelle_remise);
	$params["L_PAYMENTREQUEST_0_DESC$k"] = "";
	$params["L_PAYMENTREQUEST_0_AMT$k"] = utf8_encode($_SESSION['total_montant_REMISE_paypal']);
	$params["L_PAYMENTREQUEST_0_QTY$k"] = 1;
}
///////////////////////////////////////////////////////////////////REMISE

$params["PAYMENTREQUEST_0_AMT"] = $_SESSION['total_TTC'];
//var_dump($params["L_PAYMENTREQUEST_0_NAME$k"],$params["L_PAYMENTREQUEST_0_AMT$k"], $params["L_PAYMENTREQUEST_0_QTY$k"], $params["PAYMENTREQUEST_0_AMT"]);
$response = $paypal->request('SetExpressCheckout', $params);

//echo print_r($params);
//echo "<br /><br />";

if (!empty($response)) {
	$paypal = 'https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=' . $response['TOKEN'];
	//$paypal = 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token='.$response['TOKEN'];
} else {
	//var_dump($params);
	//var_dump($paypal);
	$erreur_paypal = "oui";
}

if (empty($erreur_paypal)) {
	?>

	<!-- <div style='text-align: center; margin-top: 20px;'>
	<a href='<?php echo "$paypal"; ?>' class="btn btn-default" style=' margin-top: 15px; text-decoration: none;'>
		Payer ma commande
	</a>
</div> -->

	<?php
	/*
	if(isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && isset($user)){
	?>
		<div style="text-align: center; margin-top: 20px;">
			<a href="/index.php?page=Traitements" id='valider-admin' class='btn btn-success btn-white w-space' style='color : white !important; background : #233ED5;' style='text-transform : uppercase;' > VALIDER EN ADMININISTRATEUR </a>
		</div>
	<?php
	}
	*/
	?>

	<?php
}
?>