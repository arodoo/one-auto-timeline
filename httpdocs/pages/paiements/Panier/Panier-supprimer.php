<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

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

$id_detail_panier = $_POST['id_detail_panier'];

if(!empty($user) && !empty($id_detail_panier) ){

///////////////////////////////DELETE
$sql_delete = $bdd->prepare("DELETE FROM membres_panier_details WHERE id=?");
$sql_delete->execute(array($id_detail_panier));                     
$sql_delete->closeCursor();

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

$result = array("Texte_rapport"=>"Produit supprimé !","retour_validation"=>"ok","retour_lien"=>"");

}

$result = json_encode($result);
echo $result;

ob_end_flush();
?>