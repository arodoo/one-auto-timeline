<?php
REQUIRE_ONCE($_SERVER['DOCUMENT_ROOT'].'/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');
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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)){

    $now = time();

        //////////////////////////////CREATION FACTURE
        ///////////////////////////////SELECT
        $req_select = $bdd->prepare("SELECT * FROM membres_panier WHERE pseudo=? AND id=?");
        $req_select->execute(array(
            $user,
            $_SESSION['idaction']
        ));
        $ligne_select = $req_select->fetch();
        $req_select->closeCursor();
        $id_facture_panier = $ligne_select['id'];
        $numero_panier = $ligne_select['numero_panier'];
        $Titre_facture = $ligne_select['Titre_panier'];
        $Contenu = $ligne_select['Contenu'];
        $code_promotion = $ligne_select['code_promotion'];
        $type_panier = $ligne_select['type_panier'];

        $condition_livraison = "Immédiat";

        ///////////////////////////////SELECT
        $req_select = $bdd->prepare("SELECT * FROM configurations_pdf_devis_factures WHERE id=1");
        $req_select->execute();
        $ligne_select = $req_select->fetch();
        $req_select->closeCursor();
        $LAST_REFERENCE_FACTURE = $ligne_select['LAST_REFERENCE_FACTURE'];
        $LAST_REFERENCE_FACTURE = ($LAST_REFERENCE_FACTURE + 1);

        $sql_update = $bdd->prepare("UPDATE configurations_pdf_devis_factures SET 
			LAST_REFERENCE_FACTURE=?
			WHERE id=?");
        $sql_update->execute(array(
            $LAST_REFERENCE_FACTURE,
            '1'
        ));
        $sql_update->closeCursor();

        $LAST_REFERENCE_FACTURE = "FA-" . $LAST_REFERENCE_FACTURE . "";

        if ($_SESSION['remise_panier_facture'] < 1){
            $_SESSION['remise_panier_facture'] = 0;
        }

	if(isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && isset($user) ){
		$Titre_facture = "Test";
 		$Contenu = "Test";
	}

        //CREATION FACTURE
        ///////////////////////////////INSERT
        $sql_insert = $bdd->prepare("INSERT INTO membres_prestataire_facture 
			(id_membre,
			pseudo,
			REFERENCE_NUMERO,
			numero_facture,
			Titre_facture,
			Contenu,
			Suivi,
			date_edition,
			departement,
			jour_edition,
			mois_edition,
			annee_edition,
			mod_paiement,
			Tarif_HT,
			Remise,
			Tarif_HT_net,
			Tarif_TTC,
			Total_Tva,
			taux_tva,
			condition_reglement,
			delai_livraison,
			code_promotion,
			Type_compte_F,
			id_devis,
			statut
			)
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $sql_insert->execute(array(
            $id_oo,
            $user,
            $LAST_REFERENCE_FACTURE,
            $LAST_REFERENCE_FACTURE,
            $Titre_facture,
            $Contenu,
            'payer',
            $now,
            '',
            '',
            '',
            '',
            $modepaiements,
            $_SESSION['total_HT'],
            $_SESSION['remise_panier_facture'],
            $_SESSION['total_HT_net'],
            $_SESSION['total_TTC'],
            $_SESSION['total_TVA'],
            $Tva_coef,
            'Immédiat',
            $condition_livraison,
            '',
            '',
            '',
            'Activée'
        ));
        $sql_insert->closeCursor();

        ///////////////////////////////SELECT
        $req_select = $bdd->prepare("SELECT * FROM membres_prestataire_facture WHERE date_edition=?");
        $req_select->execute(array(
            $now
        ));
        $ligne_select = $req_select->fetch();
        $req_select->closeCursor();
        $id_url = $ligne_select['id'];
        $_SESSION['LAST_REFERENCE_FACTURE'] = $LAST_REFERENCE_FACTURE;

        //////////////////////////////CREATION FACTURE
        //////////////////////////////CREATION DETAILS FACTURE
        ///////////////////////////////SELECT BOUCLE
        $req_boucle = $bdd->prepare("SELECT * FROM  membres_panier_details WHERE numero_panier=? ORDER BY id ASC");
        $req_boucle->execute(array($numero_panier));
        while ($ligne_boucle = $req_boucle->fetch()){
            $id_facture_panier_dd = $ligne_boucle['id'];
            $id_panier_facture_details_idd = $ligne_boucle['id'];
            $id_panier_SERVICE_PRODUIT_idd = $ligne_boucle['id_panier_SERVICE_PRODUIT'];
            $libelled = $ligne_boucle['libelle'];
            $PU_HTd = sprintf('%.2f', $ligne_boucle['PU_HT']);
            $quantited = $ligne_boucle['quantite'];
            $action_module_service_produit = $ligne_boucle['action_module_service_produit'];
            $Duree_service = $ligne_boucle['Duree_service'];

		if(isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && isset($user) ){
			$libelled = "Test : $libelled";
		}

                ///////////////////////////////INSERT
                $sql_insert = $bdd->prepare("INSERT INTO membres_prestataire_facture_details
			(id_membre,
			pseudo,
			numero_facture,
			libelle,
			PU_HT,
			quantite,
			REFERENCE_DETAIL,
			Type_detail)
		VALUES (?,?,?,?,?,?,?,?)");
                $sql_insert->execute(array(
                    	$id_oo,
                    	$user,
                   	$LAST_REFERENCE_FACTURE,
                    	$libelled,
                    	$PU_HTd,
                    	$quantited,
                    	'',
                    	$type_detail));
        	$sql_insert->closeCursor();


///////////Crédits
if($action_module_service_produit == "Crédits"){

///////////////////////////////UPDATE
$nbr_prestation = ($nbr_prestation+$Duree_service);
$sql_update = $bdd->prepare("UPDATE membres SET nbr_prestation=? WHERE id=?");
$sql_update->execute(array($nbr_prestation,$id_oo));                     
$sql_update->closeCursor();

///////////////////////////////INSERT
$sql_insert = $bdd->prepare("INSERT INTO membres_credits
	(id_membre,
	pseudo,
	societe,
	credits,
	date_commande
	)
	VALUES (?,?,?,?,?)");
$sql_insert->execute(array(
	$id_oo,
	$user,
	$nom_professionnel,
	$Duree_service,
	time()));                     
$sql_insert->closeCursor();

}

///////////Devis
if($action_module_service_produit == "Devis"){
    $sql_update = $bdd->prepare("UPDATE membres_demande_de_devis SET 
    statut_payer=?,
    date_paiement=?
    WHERE id_membre=? AND intitule=? AND statut_payer=?");
    $sql_update->execute(array(
    "Payé",
    time(),
    $id_oo,
    $Duree_service,
    "Non payé"
    ));
    $sql_update->closeCursor();
}

///////////Formation
if($action_module_service_produit == "Formation" ){
    $sql_update = $bdd->prepare("UPDATE membres_commandes_de_formation SET 
    statut_paye=?,
    date_paiement=?
    WHERE id_membre=? AND intitule=? AND statut_paye=?");
    $sql_update->execute(array(
    "Payé",
    time(),
    $id_oo,
    $Duree_service,
    "Non payé"
    ));
    $sql_update->closeCursor();
}

///////////DUE
if($action_module_service_produit == "DUE" ){
    $sql_update = $bdd->prepare("UPDATE membres_commandes_de_due SET 
    statut_paye=?,
    date_paiement=?
    WHERE id_membre=? AND intitule=? AND statut_paye=?");
    $sql_update->execute(array(
    "Payé",
    time(),
    $id_oo,
    $Duree_service,
    "Non payé"
    ));
    $sql_update->closeCursor();
}

///////////ABONNEMENT
if($action_module_service_produit == "Abonnement Régulier" || $action_module_service_produit == "Abonnement Premium" ){

	$Duree_service_mois = $Duree_service;

	if($action_module_service_produit == "Abonnement Régulier"){
		$platine_abo = "oui";
		$premium_abo = "non";
		$vip_abo = "non";
		$Duree_service = (time()+($Duree_service*(30*86400)));
	}

	if($action_module_service_produit == "Abonnement Premium"){
		$platine_abo = "non";
		$premium_abo = "oui";
		$vip_abo = "non";
		$Duree_service = (time()+($Duree_service*(30*86400)));
	}

	///////////////////////////////UPDATE
	$sql_update = $bdd->prepare("UPDATE membres SET 
		platine=?,
		vip=?,
		premium=?,
		date_commande=?,
		date_commande_fin=?,
		paye=?,
		duree_abonnement_mois=?
		WHERE id=?");
	$sql_update->execute(array(
		$platine_abo,
		$vip_abo,
		$premium_abo,
		time(),
		$Duree_service,
		'oui',
		$Duree_service_mois,
		$id_oo));                     
	$sql_update->closeCursor();

        ///////////////////////////////UPDATE
        $sql_update = $bdd->prepare("UPDATE membres_etablissements SET platine=?, vip=?, premium=? WHERE id_membre=?");
        $sql_update->execute(array(
            $platine_abo,
	    $vip_abo,
	    $premium_abo,	
            $id_oo
        ));
        $sql_update->closeCursor();

///////////////////////////////INSERT
$sql_insert = $bdd->prepare("INSERT INTO membres_abonnements_achats
	(id_membre,
	pseudo,
	societe,
	abonnement,
	date_commande
	)
	VALUES (?,?,?,?,?)");
$sql_insert->execute(array(
	$id_oo,
	$user,
	$nom_professionnel,
	$action_module_service_produit,
	time()));                     
$sql_insert->closeCursor();

}

}

if(!empty($code_promotion)){
    ///////////////////////////////SELECT
			$req_select = $bdd->prepare("SELECT * FROM codes_promotion WHERE numero_code=?");
			$req_select->execute(array($code_promotion));
			$ligne_select_c = $req_select->fetch();
			$req_select->closeCursor();
            
            $new_quant = $ligne_select_c['nbr_utilisation_en_cours'] + 1;

            ///////////////////////////////UPDATE
        $sql_update = $bdd->prepare("UPDATE codes_promotion SET nbr_utilisation_en_cours=? WHERE numero_code=?");
        $sql_update->execute(array(
            $new_quant,
            $code_promotion
        ));
        $sql_update->closeCursor();

        /*///////////////////////////////INSERT
        $sql_insert = $bdd->prepare("INSERT INTO membres_codes_promo
        (id_membre,
        pseudo,
        code_promo,
        date
        )
        VALUES (?,?,?,?)");
        $sql_insert->execute(array(
        $id_oo,
        $user,
        $code_promotion,
        time()));                     
        $sql_insert->closeCursor();*/

}

        ///////////////////////////////DELETE
        $sql_delete = $bdd->prepare("DELETE FROM membres_panier WHERE pseudo=?");
        $sql_delete->execute(array($user));
        $sql_delete->closeCursor();

        ///////////////////////////////DELETE
        $sql_delete = $bdd->prepare("DELETE FROM membres_panier_details WHERE pseudo=?");
        $sql_delete->execute(array($user));
        $sql_delete->closeCursor();


    ////////////////MAIL CLIENT
    $de_nom = "$nomsiteweb"; //Nom de l'envoyeur
    $de_mail = "$emaildefault"; //Email de l'envoyeur
    $vers_nom = "$user"; //Nom du receveur
    $vers_mail = "$mail_oo"; //Email du receveur
    $sujet = "Paiement sur $nomsiteweb"; //Sujet du mail
    $message_principalone = "
		<b>Bonjour,</b><br /><br />
		Vous avez effectué un paiement sur $nomsiteweb. <br /><br />
		Nous vous en remercions. <br /><br />
		La somme du paiement : " . $_SESSION['total_TTC'] . " euros<br /><br />
		Mode de paiement : " . $modepaiements . " <br /><br />
		Cordialement, la team PEP'S<br /><br />";
    mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);
    ////////////////MAIL CLIENT

    ////////////////MAIL ADMINISTRATEUR
    $de_nom = "$user"; //Nom de l'envoyeur
    $de_mail = "$mail_oo"; //Email de l'envoyeur
    $vers_nom = "$nomsiteweb"; //Nom du receveur
    $vers_mail = "$emaildefault"; //Email du receveur
    $sujet = "MAIL ADMINISTRATEUR : Vous avez reçut un paiement sur $nomsiteweb"; //Sujet du mail
    $message_principalone = "<b>Objet:</b> $sujet<br /><br />
		<b>Bonjour,</b><br /><br />
		Vous avez reçut une nouveau paiement.<br /><br />
		<b>Voici les informations du client concerné :</b><br /><br />
		Date d'édition : ".time()." <br /><br />
		Pseudo client : $user <br />
		Nom : " . $nom_oo . " <br />
		Prénom : " . $prenom_oo . " <br /><br />
		La somme du paiement : " . $_SESSION['total_TTC'] . " euros<br /><br />
		Mode de paiement : " . $modepaiements . " <br /><br />

		$information_mail

		Pour accéder à là facture en PDF : <a href='" . $http . "" . $nomsiteweb . "/facture/" . $LAST_REFERENCE_FACTURE . "/" . $nomsiteweb . "' target='blank_' >FACTURE PDF</a><br />
		Pour accéder à la modification de la facture : <a href='" . $http . "" . $nomsiteweb . "/administration/index-admin.php?page=Facturations&action=Facture&idaction=" . $id_url . "' target='blank_'>VISUALISER LA FACTURE</a><br />
		Pour accéder à la fiche du client : <a href='" . $http . "" . $nomsiteweb . "/administration/index-admin.php?page=Membres&action=modifier&amp;idaction=" . $id_oo . "' target='blank_'>FICHE DU MEMBRE</a><br /><br />

		Cordialement, la team PEP'S<br /><br />";
    mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);

    ///////////////////////////////INCLUDE RETOUR AFFICHAGE
    header('location: /Traitements-informations');

    /////////////////////////////////////////ACTIONS SI PAIEMENT VALIDE
    /////////////////////ON CASSE LES SESSIONS
    unset($_SESSION['code_promo']);
    unset($_SESSION['remise_panier_facture']);
    unset($_SESSION['total_TTC']);
    unset($_SESSION['code_promotion_montant']);
    unset($_SESSION['prix_abonnement']);
    unset($_SESSION['duree_jour_abonnement']);
   unset($_SESSION['duree_jour_abonnement_seconde']);

unset($_SESSION['etape2']);
unset($_SESSION['etape3']);
unset($_SESSION['etape4']);

unset($_SESSION['nom']);
unset($_SESSION['date_voyage_seconde']);
unset($_SESSION['date_voyage_seconde_r']);
unset($_SESSION['date_voyage_fin_seconde']);
unset($_SESSION['id_type_de_voyage']);
unset($_SESSION['id_periode']);
unset($_SESSION['id_surveillance']);
unset($_SESSION['id_pays']);
unset($_SESSION['id_ville']);
unset($_SESSION['adresse']);
unset($_SESSION['abonnement']);
unset($_SESSION['modif']);
unset($_SESSION['duree']);
unset($_SESSION['modif2']);

    /////////////////////ON CASSE LES SESSIONS DES PRODUITS OU SERVICES
    
}
?>
