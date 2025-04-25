<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

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

if(!empty($user)){

$idaction = $_POST['idaction'];
$quantite = $_POST['quantite'];

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM pages WHERE id=?");
$req_select->execute(array($idaction));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idoneinfos = $ligne_select['id'];
$id_categorie = $ligne_select['id_categorie'];
$id_image_parallaxe_banniere = $ligne_select['id_image_parallaxe_banniere'];
$PagePage = $ligne_select['Page'];
$Ancre_lien_menu = $ligne_select['Ancre_lien_menu'];
$categorie_menu = $ligne_select['categorie_menu'];
$presence_footer = $ligne_select['presence_footer'];
$position_footer = $ligne_select['position_footer'];
$Ancre_lien_footer = $ligne_select['Ancre_lien_footer'];
$Titre_h1 = $ligne_select['Titre_h1'];
$Ancre_fil_ariane = $ligne_select['Ancre_fil_ariane'];
$TitreTitre = $ligne_select['Title'];
$Metas_description = $ligne_select['Metas_description'];
$Metas_mots_cles = $ligne_select['Metas_mots_cles'];
$Site_map_xml_date_mise_a_jour = $ligne_select['Site_map_xml_date_mise_a_jour'];
$Site_map_xml_propriete = $ligne_select['Site_map_xml_propriete'];
$Site_map_xml_frequence_mise_a_jour = $ligne_select['Site_map_xml_frequence_mise_a_jour'];
$Declaree_dans_site_map_xml = $ligne_select['Declaree_dans_site_map_xml'];
$Statut_page = $ligne_select['Statut_page'];

$Page_inscription = $ligne_select['Page_inscription'];
$Page_portefolio = $ligne_select['Page_portefolio'];
$Page_blog_actualite = $ligne_select['Page_blog_actualite'];
$Page_livre_d_or = $ligne_select['Page_livre_d_or'];

$Page_index = $ligne_select['Page_index'];
$Page_admin = $ligne_select['Page_admin'];
$Page_fixe = $ligne_select['Page_fixe'];
$date_upadte_p = $ligne_select['date'];
$Page_type_module_ou_page = $ligne_select['Page_type_module_ou_page'];

$Prix_services_produits = $ligne_select['Prix_services_produits'];
$Libelle_services_produits = $ligne_select['Libelle_services_produits'];
$Stock_services_produits = $ligne_select['Stock_services_produits'];

$pseudo_panier = $user;
ajout_panier($Libelle_services_produits,$quantite,"$Prix_services_produits","e-commerce","$action_parametres_valeurs_explode",$Libelle_services_produits,$pseudo_panier);

$result = array("Texte_rapport"=>"Ajouté au panier avec succès !","retour_validation"=>"ok","retour_lien"=>"");

$result = json_encode($result);
echo $result;

}

ob_end_flush();
?>