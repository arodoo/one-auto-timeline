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


$namepage = $_GET['namepage'];

/////////////////////////Si aucune page existe, on fait une redirection 301
if(empty($id_page_categorie)){
header("Status: 301 Moved Permanently", false, 301); 
header("location: ".$http."".$nomsiteweb."");
exit();
}
?>

<div class="col-md-12 list-grid-view">

      <div class="cws_divider mb-30"></div>
      <div class="row products">

<?php
///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM pages WHERE id_categorie=? AND Statut_page=? order by position_menu DESC ");
$req_boucle->execute(array($id_page_categorie,'oui'));
while($ligne_boucle = $req_boucle->fetch()){
$idoneinfos = $ligne_boucle['id'];
$id_resultat_liste = $ligne_boucle['id'];
$id_categorie = $ligne_boucle['id_categorie'];
$id_image_parallaxe_banniere = $ligne_boucle['id_image_parallaxe_banniere'];
$PagePage = $ligne_boucle['Page'];
$Ancre_lien_menu = $ligne_boucle['Ancre_lien_menu'];
$categorie_menu = $ligne_boucle['categorie_menu'];
$presence_footer = $ligne_boucle['presence_footer'];
$position_footer = $ligne_boucle['position_footer'];
$Ancre_lien_footer = $ligne_boucle['Ancre_lien_footer'];
$Titre_h1 = $ligne_boucle['Titre_h1'];
$Ancre_fil_ariane = $ligne_boucle['Ancre_fil_ariane'];
$TitreTitre = $ligne_boucle['Title'];
$Metas_description = $ligne_boucle['Metas_description'];
$Metas_mots_cles = $ligne_boucle['Metas_mots_cles'];
$Site_map_xml_date_mise_a_jour = $ligne_boucle['Site_map_xml_date_mise_a_jour'];
$Site_map_xml_propriete = $ligne_boucle['Site_map_xml_propriete'];
$Site_map_xml_frequence_mise_a_jour = $ligne_boucle['Site_map_xml_frequence_mise_a_jour'];
$Declaree_dans_site_map_xml = $ligne_boucle['Declaree_dans_site_map_xml'];
$Statut_page = $ligne_boucle['Statut_page'];

$Page_inscription = $ligne_boucle['Page_inscription'];
$Page_portefolio = $ligne_boucle['Page_portefolio'];
$Page_blog_actualite = $ligne_boucle['Page_blog_actualite'];
$Page_livre_d_or = $ligne_boucle['Page_livre_d_or'];

$Page_index = $ligne_boucle['Page_index'];
$Page_admin = $ligne_boucle['Page_admin'];
$Page_fixe = $ligne_boucle['Page_fixe'];
$date_upadte_p = $ligne_boucle['date'];
$Page_type_module_ou_page = $ligne_boucle['Page_type_module_ou_page'];

$Prix_services_produits = $ligne_boucle['Prix_services_produits'];
$Libelle_services_produits = $ligne_boucle['Libelle_services_produits'];
$Stock_services_produits = $ligne_boucle['Stock_services_produits'];
$Destination_services_produits = $ligne_boucle['Destination_services_produits'];
$Gestion_des_stocks_services_produits = $ligne_boucle['Gestion_des_stocks_services_produits'];
$Stocks_services_produits = $ligne_boucle['Stocks_services_produits'];
$Type_de_quantite = $ligne_boucle['Type_de_quantite'];

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM pages_a_b_image WHERE id_page=? AND defaut=? || id_page=? ");
$req_select->execute(array($idoneinfos,'oui',$idoneinfos));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idimmmagee = $ligne_select['id'];
$img_lien = $ligne_select['img_lien2'];
$img_title = $ligne_select['img_title'];

?>

              <div class="col-md-4" >
                <div class="shop-item" >
                  <!-- Shop Image-->
                  <div class="shop-media" style="max-height: 300px;" >
                    <div class="pic"><img src="/images/pages/<?php echo "$img_lien"; ?>" data-at2x="/images/pages/<?php echo "$img_lien"; ?>" alt="<?php echo "$Titre_h1"; ?>"></div>
                    <div class="location"><?php echo "$Titre_h1"; ?></div>
                  </div>
                  <!-- Shop Content-->
                  <div class="shop-item-body">
			<a href="/<?php echo "$PagePage"; ?>">
                      		<h6 class="shop-title"><?php echo "$Titre_h1"; ?></h6>
			</a>
			<?php
			if($type_categorie == "Page boutique"){
			?>
                    <div class="shop-price"><?php echo "$Prix_services_produits"; ?>€ <?php if(!empty($Type_de_quantite)){ echo "$Type_de_quantite"; } ?></div>
			<?php
			}
			?>
                    <a href="/<?php echo "$PagePage"; ?>" class="shop-button"><?php echo "$Ancre_lien_menu"; ?></a>
                    <div class="price-review">
			<?php
			if($type_categorie == "Page boutique" && $Destination_services_produits == "Panier et paiement" ){
				if(!empty($user)){
				?>
					<a href="#" style="margin-bottom: 20px; width: 100%;" class="cws-button small alt ajouter-panier" data-id="<?php echo "$idoneinfos"; ?>" onclick="return false;" ><span class="uk-icon-shopping-cart"></span> PANIER</a>
				<?php
				}else{
				?>
					<a href="#" style="margin-bottom: 20px; width: 100%;" class="cws-button small alt login_jspanel" data-id="<?php echo "$idoneinfos"; ?>" onclick="return false;" ><span class="uk-icon-shopping-cart"></span> PANIER</a>
				<?php
				}
			}elseif($type_categorie == "Page boutique" && $Destination_services_produits == "Page contact" ){
			?>
			<a href="/Contact" class="cws-button small alt ajouter-panier" >Contact</a>
			<?php
			}elseif($type_categorie == "Page boutique" && $Destination_services_produits == "Devis en ligne" ){
			?>
			<a href="/Demande-de-devis-gratuit" class="cws-button small alt ajouter-panier" >Devis</a>
			<?php
			}
			?>
		    </div>
			<?php
			if($type_categorie == "Page boutique" && $Destination_services_produits == "Panier et paiement"  ){
			?>
                    		<div class="action font-2"><?php echo "$Prix_services_produits"; ?>€ <?php if(!empty($Type_de_quantite)){ echo "$Type_de_quantite"; } ?> </div>
			<?php
			}
			?>
                  </div>
                  <div class="link"> <a href="/images/pages/<?php echo "$img_lien"; ?>" class="fancy"><i class="fa fa-expand"></i></a></div>
                </div>
              </div>

<?php
}
if(empty($id_resultat_liste)){
?>
<div class="alert alert-danger" role="alert" style="text-align: left;" ><span class="uk-icon-warning"></span> Il n'y a aucun résultat pour le moment !</div>
<?php
}

?>

          </div>
</div>
 
<div style='clear: both;'></div>
