	<?php
	if($Destination_services_produits == "Panier et paiement" ){
	if(!empty($user)){
	?>
	<a href='/Paiement' class='btn btn-default' style='width: 100%; margin-bottom: 20px;' ><span class="uk-icon-file-pdf-o"></span> VOTRE PANIER</a>
	<div style='margin-top: 10px;'></div>
	<a href="#" style="margin-bottom: 20px; width: 100%;" class="btn btn-success ajouter-panier" data-id="<?php echo "$id_page"; ?>" onclick="return false;" ><span class="uk-icon-plus-square"></span> AJOUTER AU PANIER</a>
	<?php
	}else{
	?>
	<a href="#" style="margin-bottom: 20px; width: 100%;" class="btn btn-success login_jspanel" data-id="<?php echo "$id_page"; ?>" onclick="return false;" ><span class="uk-icon-file-pdf-o"></span> AJOUTER AU PANIER</a>
	<?php
	}
	}elseif($Destination_services_produits == "Page contact" ){
	?>
	<a href="/Contact" style="margin-bottom: 20px; width: 100%;" class="btn btn-success" ><span class="uk-icon-envelope"></span> CONTACTER LE SERVICE CLIENT</a>
	<?php
	}elseif($Destination_services_produits == "Devis en ligne" ){
	?>
	<a href="/Demande-de-devis-gratuit" style="margin-bottom: 20px; width: 100%;" class="btn btn-success" ><span class="uk-icon-file-pdf-o"></span> DEMANDE DE DEVIS</a>
	<?php
	}
	?>
<?php
////////////////////////////////////////SI MODULE CATEGORIE ACTIVE
if($activer_option_menu_categorie_page == "oui"){
?>
<div class="panel panel-default" style="margin-top: 20px;">
  <div class="panel-heading" style="font-size: 16px; text-transform: uppercase;">Catégorie</div>
  <div class="panel-body">
          <ul>
		<li style="list-style-type: none;"><a href="/Categorie/<?php echo "$nom_url_categorie"; ?>"><span class="uk-icon-folder-open"></span> <?php echo "$titre_categorie"; ?></a></li>
         </ul>  
  </div>
</div>
<?php
}
////////////////////////////////////////SI MODULE CATEGORIE ACTIVE
?>

<?php
////////////////////////////////////////SI PRIX OU STOCK
if(!empty($Prix_services_produits) || $Gestion_des_stocks_services_produits == "oui"){
?>
<div style='clear: both; margin-bottom: 20px;' ></div>
<div class="panel panel-default">
  <div class="panel-heading" style="font-size: 16px; text-transform: uppercase;">
	Informations
  </div>
  <div style="padding: 5px; padding-top: 10px; text-align: center;">
	<?php
	if(!empty($Prix_services_produits)){
	?>
	<div style="margin-top: 10px; margin-bottom: 20px; width: 100%; font-weight: bold; font-size: 20px; text-align: center;" class="" > Prix <?php echo "$Prix_services_produits"; ?> <span class="uk-icon-euro"></span> TTC</div>
	<hr />
	<?php
	}
	?>
	<?php
	if($Gestion_des_stocks_services_produits == "oui" && $Stocks_services_produits > 5){
	?>
	<div class="alert alert-success" role="alert" style="padding: 5px;"><span class="uk-icon-thumbs-up"></span> <?php echo "EN STOCK"; ?> </div>
	<div style='clear: both; margin-top: 20px;' ></div>
	<?php
	}elseif($Gestion_des_stocks_services_produits == "oui" && $Stocks_services_produits > 1 ){
	?>
	<div class="alert alert-warning" role="alert" style="padding: 5px;" ><span class="uk-icon-thumbs-up"></span> <?php echo "EN STOCK"; ?> </div>
	<div style='clear: both; margin-top: 20px;' ></div>
	<?php
	}elseif($Gestion_des_stocks_services_produits == "oui" && ( $Stocks_services_produits == "" || $Stocks_services_produits == "0") ){
	?>
	<div class="alert alert-danger" role="alert" style="padding: 5px;"><span class="uk-icon-times"></span> <?php echo "RUPTURE"; ?> </div>
	<div style='clear: both; margin-top: 20px;' ></div>
	<?php
	}
	?>
</div>
</div>
<?php
}
////////////////////////////////////////SI PRIX OU STOCK

////////////////////////////////////////SI AFFICHER RESEAUX SOCIAUX
if($afficher_reseaux_sociaux_page == "oui" ){ 
?>
	<div style='margin-top: 20px; text-align: center;'>
		<?php
		/////////////////////////////////////RESEAUX SOCIAUX BOUTTON
		include('function/reseaux-sociaux/partage.php');
		/////////////////////////////////////RESEAUX SOCIAUX BOUTTON
		?>
	</div>
<?php
}
////////////////////////////////////////SI AFFICHER RESEAUX SOCIAUX

////////////////////////////////////////SI VIDEO YOUTUBE
if(!empty($contenu_video)){ 
$video_artciles_blog_explode = explode('"', $contenu_video);
$video_artciles_blog_explode_nouvelle_chaine = "".$video_artciles_blog_explode[5]."?wmode=opaque";
$video_artciles_blog_explode_replace = str_replace("$video_artciles_blog_explode[5]","$video_artciles_blog_explode_nouvelle_chaine", $contenu_video);
?>
	<div style='margin-top: 20px; text-align: center;'>
        <div class='video_fiche' style='z-index: 0;'>
		<?php echo "$video_artciles_blog_explode_replace"; ?>
	</div>
	</div>
<?php
}
////////////////////////////////////////SI VIDEO YOUTUBE

?>
