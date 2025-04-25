<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../../Configurations_bdd.php');
require_once('../../../../Configurations.php');
require_once('../../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../../../";
require_once('../../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

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

$id_panier_detail = $_POST['id_panier_detail'];
$id_page_panier = $_POST['id_page_panier'];
$type_action = $_POST['type_action'];

//////////////////////////////////////////////REMISE / CODE PROMOTION
if(!empty($_POST['type_valeur'])){

if(is_numeric($_POST['type_valeur'])){

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM codes_promotion WHERE numero_code=?");
$req_select->execute(array($_SESSION['code_promo']));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$id_Code_promotion_idd = $ligne_select['id']; 
$numero_code = $ligne_select['numero_code']; 
$prix_offert = $ligne_select['prix_offert']; 
$nbr_utilisation_fin = $ligne_select['nbr_utilisation_fin']; 
$nbr_utilisation_en_cours = $ligne_select['nbr_utilisation_en_cours']; 
$date_debut = $ligne_select['date_debut']; 
$date_fin = $ligne_select['date_fin']; 
$destination = $ligne_select['destination']; 

$req_select = $bdd->prepare("SELECT COUNT(*) AS nbr_carte FROM pages_cartes_cadeaux WHERE id_page=? AND statut!='achetée' ");
$req_select->execute(array($id_page_panier));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$nbr_carte = $ligne_select['nbr_carte'];

if($_POST['type_valeur'] >= $nbr_carte && $type_action == "Achat" || $type_action != "Achat" ){

if($_POST['type_valeur'] > 0){

//UPDATE SQL
///////////////////////////////UPDATE
$sql_update = $bdd->prepare("UPDATE membres_panier_details SET 
	quantite=?
	WHERE id=? AND (pseudo=? || pseudo=?)");
$sql_update->execute(array(
	$_POST['type_valeur'],
	$id_panier_detail,$user,$_SESSION['pseudo_panier']));                     
$sql_update->closeCursor();

//////////////ON MET A JOUR LES TOTAUX DU PANIER
///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM membres_panier_details WHERE (pseudo=? || pseudo=? ) AND PU_HT > 0");
$req_boucle->execute(array($user,$_SESSION['pseudo_panier']));
while($ligne_boucle = $req_boucle->fetch()){
	$idoneinfos_artciles_fiche_panier = $ligne_boucle['id'];
	$PU_HT_artciles_fiche_panier = $ligne_boucle['PU_HT'];
	$TVA = $ligne_boucle['TVA'];
	$TVA_TAUX = $ligne_boucle['TVA_TAUX'];
	$quantite_artciles_fiche_panier = $ligne_boucle['quantite'];

	$PU_HT = ($PU_HT_artciles_fiche_panier);
	
	if(!empty($_SESSION['code_promo'])){	

		$PU_HT_REMISE = ($PU_HT-($PU_HT*$prix_offert/100));
		$TVA = (($PU_HT_REMISE*$TVA_TAUX)-$PU_HT_REMISE);
		$PU_TTC = ($PU_HT_REMISE+$TVA);
		$TOTAL_REMISE = ($TOTAL_REMISE+($PU_HT-$PU_HT_REMISE));

		$PU_HT_REMISE_TOTAUX = ($PU_HT_REMISE_TOTAUX+$PU_HT_REMISE);
		$TVA_TOTAUX = ($TVA_TOTAUX+$TVA);
		$PU_TTC_TOTAUX = ($PU_TTC_TOTAUX+($PU_HT_REMISE_TOTAUX+$TVA_TOTAUX));

		$PU_HT_TOTAUX = ($PU_HT_TOTAUX+($PU_HT*$quantite_artciles_fiche_panier));

	}else{

		$PU_HT_TOTAUX = ($PU_HT_TOTAUX+($PU_HT*$quantite_artciles_fiche_panier));

		$PU_HT_REMISE = $PU_HT;
		$TVA = (($PU_HT*$TVA_TAUX)-$PU_HT);
		$PU_TTC = ($PU_HT+$TVA);

		$PU_HT_REMISE_TOTAUX = "";
		$TVA_TOTAUX = ($TVA_TOTAUX+$TVA);
		$PU_TTC_TOTAUX = ($PU_TTC_TOTAUX+($PU_HT_TOTAUX+$TVA_TOTAUX));

	}

	if($ligne_boucle['TVA_TAUX'] == "1.20"){
		$PU_TVA_TOTAUX = ($PU_TVA_TOTAUX+($TVA*$quantite_artciles_fiche_panier));
	}
	if($ligne_boucle['TVA_TAUX'] == "1.055"){
		$PU_TVA2_TOTAUX = ($PU_TVA2_TOTAUX+($TVA*$quantite_artciles_fiche_panier));
	}
}

//UPDATE SQL
///////////////////////////////UPDATE
$sql_update = $bdd->prepare("UPDATE membres_panier SET 
	Tarif_HT=?, 
	Tarif_HT_net=?, 
	Tarif_TTC=?, 
	Total_Tva=?,
	Total_Tva2=?
	WHERE pseudo=? || pseudo=? ");
$sql_update->execute(array(
	$PU_HT_TOTAUX, 
	$PU_HT_REMISE_TOTAUX, 
	$PU_TTC_TOTAUX, 
	$PU_TVA_TOTAUX,
	$PU_TVA2_TOTAUX,
	$user,
	$_SESSION['pseudo_panier']));                     
$sql_update->closeCursor();

$result = array("Texte_rapport"=>"","retour_validation"=>"ok","retour_lien"=>"");

}elseif($_POST['type_valeur'] < 1){
$result = array("Texte_rapport"=>"La quantité doit être supérieur à 0 !","retour_validation"=>"","retour_lien"=>"");

}

}else{
$result = array("Texte_rapport"=>"Il n'y a pas assez de stock !","retour_validation"=>"","retour_lien"=>"");

}

}else{
$result = array("Texte_rapport"=>"La quantité doit être numérique !","retour_validation"=>"","retour_lien"=>"");

}

}else{
$result = array("Texte_rapport"=>"Vous devez indiquer une quantité !","retour_validation"=>"","retour_lien"=>"");

}

$result = json_encode($result);
echo $result;

ob_end_flush();
?>