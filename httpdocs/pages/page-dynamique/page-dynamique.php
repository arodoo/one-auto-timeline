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
if(empty($id_page)){
header("Status: 301 Moved Permanently", false, 301); 
header("location: ".$http."".$nomsiteweb."");
exit();
}
?>

<div class="contact-form-wrapper background-white p30" style="margin-bottom: 20px;" >

	<?php
	if($Destination_services_produits == "Panier et paiement" ){
	?>


	<?php
	}
	?>

<?php
//////////////////Type colonne qi page boutique ou si image
if($type_categorie == "Page boutique" || !empty($idimagee_page) ){
	$col_page_1 = "col-md-9";
	$col_page_2 = "col-md-3";
}else{
	$col_page_1 = "col-md-12";
}
//////////////////Type colonne qi page boutique ou si image
?>

<div class="<?php echo "$col_page_1"; ?>" >
<?php
////////////////////////////////////////SI MODULE PHOTO ACTIVE
if(!empty($page_photos_module)){
	if(!empty($idimagee_page)){
	?>
		<img src="/images/pages/<?php echo "$img_lien_page"; ?>" alt="<?php echo "$img_lien_page"; ?>" style="width: 100%;" >
	<?php
	}
}
////////////////////////////////////////SI MODULE PHOTO ACTIVE
////////////////////////////////////////SI CONTENU PAGE
if(!empty($contenu_page)){ 

////////////////////////////////////////SI AFFICHER RESEAUX SOCIAUX
if($type_categorie == "Page web" || empty($idimagee_page) && $afficher_reseaux_sociaux_page == "oui" ){ 
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
?>
	<div class="col-md-12" >
		<div class='texte_page' style='text-align: left;'>
			<?php echo "$contenu_page"; ?>
		</div>
	</div>
<?php 
} 
////////////////////////////////////////SI CONTENU PAGE
?>
</div>

<?php
//////////////////Type colonne si page boutique ou si image
if($type_categorie == "Page boutique" || !empty($idimagee_page) ){
?>
	<div class="<?php echo "$col_page_2"; ?>" >
		<?php
		include('page-dynamique-menu.php');
		?>
	</div>
<?php
}
//////////////////Type colonne qi page boutique ou si image

if($type_categorie == "Page web" || empty($idimagee_page) ){ 

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
}

?>

<div style='clear: both;'></div>

</div>

<div style='clear: both;'></div>

