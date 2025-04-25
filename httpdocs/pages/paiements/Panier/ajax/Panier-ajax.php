<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../../Configurations_bdd.php');
require_once('../../../../Configurations.php');
require_once('../../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../../../";
require_once('../../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

include('../../../../pages/paiements/Api-Paypal/paypal.php');

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

$idaction = $_POST['idaction'];

////////////////////////////////////////////////////////////////////////////////REQUÊTE PANIER OU FACTURE
if ($_POST['type_paiement'] == "Facture") {
  ///////////////////////////////SELECT
  $req_select = $bdd->prepare("SELECT * FROM membres_prestataire_facture where id=?");
  $req_select->execute(array($_POST['idaction']));
  $ligne_select = $req_select->fetch();
  $req_select->closeCursor();
  $_SESSION['type_paiement'] = "Facture";

} elseif ($_POST['type_paiement'] == "Panier" && !empty($_POST['idaction'])) {
  ///////////////////////////////SELECT
  $req_select = $bdd->prepare("SELECT * FROM membres_panier WHERE id=?");
  $req_select->execute(array($_POST['idaction']));
  $ligne_select = $req_select->fetch();
  $req_select->closeCursor();
  $_SESSION['type_paiement'] = "Panier";

} else {
  ///////////////////////////////SELECT
  $req_select = $bdd->prepare("SELECT * FROM membres_panier WHERE pseudo=?");
  $req_select->execute(array($user));
  $ligne_select = $req_select->fetch();
  $req_select->closeCursor();

}

$id_facture_panier = $ligne_select['id'];
//On renomme la session pour la page de retour des paiements
$_SESSION['idaction'] = $id_facture_panier;

//Factures
$numero_facture = $ligne_select['numero_facture'];
$Titre_facture = $ligne_select['Titre_facture'];

//Panier
$numero_panier = $ligne_select['numero_panier'];
$id_facture = $ligne_select['id_facture'];
$Titre_panier = $ligne_select['Titre_panier'];

$Contenu = $ligne_select['Contenu'];
$Suivi = $ligne_select['Suivi'];
$date_edition = $ligne_select['date_edition'];
$mod_paiement = $ligne_select['mod_paiement'];
$Tarif_HT = $ligne_select['Tarif_HT'];
$Remise = $ligne_select['Remise'];
$Tarif_HT_net = $ligne_select['Tarif_HT_net'];
$Tarif_TTC = $ligne_select['Tarif_TTC'];
$Total_Tva = $ligne_select['Total_Tva'];
$taux_tva = $ligne_select['taux_tva'];
$condition_reglement = $ligne_select['condition_reglement'];
$delai_livraison = $ligne_select['delai_livraison'];
$Type_compte_F = $ligne_select['Type_compte_F'];
$code_promotion = $ligne_select['code_promotion'];

if ($_POST['type_paiement'] == "Facture") {
  $table_liste_details = "membres_panier_details WHERE numero_facture=?";
  $table_liste_details_valeur = "$numero_facture";
  $titre_h1_page = "Paiement facture N°$numero_facture";

} elseif ($_POST['type_paiement'] == "Panier" && !empty($_POST['idaction'])) {
  $table_liste_details = "membres_panier_details WHERE numero_panier=?";
  $table_liste_details_valeur = "$id_facture_panier";
  $titre_h1_page = "Paiement panier";

} else {
  $table_liste_details = "membres_panier_details WHERE pseudo=?";
  $table_liste_details_valeur = "$user";

  $titre_h1_page = "Paiement panier";
}
////////////////////////////////////////////////////////////////////////////////REQUÊTE PANIER OU FACTURE

//////////////////////////////////////////////////////////////////////////////////////////BLOC IDENTIFICATION
if (empty($user)) {
  include('../../../../pages/paiements/Panier/includes/Panier-identification-include.php');
  //////////////////////////////////////////////////////////////////////////////////////////BLOC IDENTIFICATION
} else {
  //////////////////////////////////////////////////////////////////////////////////////////BLOC INFORMATIONS
  include('../../../../pages/paiements/Panier/includes/Panier-informations-include.php');
}
//////////////////////////////////////////////////////////////////////////////////////////BLOC INFORMATIONS

//////////////////////////////////////////////////////////////////////////////////////////BLOC RECAPITULATIF
include('../../../../pages/paiements/Panier/includes/Panier-recapitulatif-paiement-include.php');
//////////////////////////////////////////////////////////////////////////////////////////BLOC RECAPITULATIF


ob_end_flush();
?>