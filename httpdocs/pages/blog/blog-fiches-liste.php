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

$limitation_texte_liste_blog_cfg = "200";

if($action != "Categorie"){

////////////////////////////////////////////ACTION SELECT RESPONSIVE
if(!empty($_POST['post_selection_categorie'])){

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM codi_one_Creation_web_categories WHERE id=?");
$req_select->execute(array($_POST['post_selection_categorie']));
$ligne_select = $req_select->fetch();
$idoneinfos_ccr = $ligne_select['id'];
$nom_categorie_ccr = $ligne_select['nom_categorie'];
$nom_url_categorie_ccr = $ligne_select['nom_url_categorie'];
header("location: /$nom_url_categorie_ccr");
}
////////////////////////////////////////////ACTION SELECT RESPONSIVE

/////////////////TEXTE HOME CATEGORIE
if(!empty($Contenu_cfg_blog)){
?>
<div class="contact-form-wrapper background-white p30" style="margin-bottom: 20px;" >
<?php
echo "<div style='text-align: left; margin-bottom: 35px;'>
$Contenu_cfg_blog
</div>";
?>
</div>
<?php
}
/////////////////TEXTE HOME CATEGORIE

}else{
///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM codi_one_blog_categories WHERE id=?");
$req_select->execute(array($idaction));
$ligne_select = $req_select->fetch();
$idoneinfos = $ligne_select['id'];
$nom_categorie = $ligne_select['nom_categorie'];
$nom_url_categorie = $ligne_select['nom_url_categorie'];
$text_categorie = $ligne_select['text_categorie'];
$nbr_consultation_blog = $ligne_select['nbr_consultation_blog'];
$Title = $ligne_select['Title'];
$Metas_description = $ligne_select['Metas_description'];
$Metas_mots_cles = $ligne_select['Metas_mots_cles'];
$activer_categorie_blog = $ligne_select['activer'];
$date_categorie_blog = $ligne_select['date'];

if(!empty($date_categorie_blog)){
$date_categorie_blog_date = date('d-m-Y', $date_categorie_blog);
}else{
$date_categorie_blog_date = "- -";
}

//////////Si pas de résultat - Redirection 301 page accueil
if(empty($idoneinfos)){
header("HTTP/1.0 301 Moved Permanently");     
header("Location: /");      
exit();
}
//////////Si pas de résultat - Redirection 301 page accueil

if(!empty($text_categorie)){
echo "<div style='text-align: left;'>
<p style='text-align: left;'> $text_categorie </p>
</div>
<br />";
}

}
?>

<div class='col-md-9 ' style='text-align: left;'>
<div class=" contact-form-wrapper background-white p30" >

<?php

$nbrpage = 15;
/////////////////////////////////////////////////////////////////PAGINATION BLOG OU CATEGORIES - SQL
if($action == "Categorie"){
///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM codi_one_blog WHERE id_categorie=? AND activer=? ORDER BY date_blog DESC LIMIT ".(($_GET['n']-1)*$nbrpage).",".$nbrpage." ");
$req_boucle->execute(array($idaction,'oui'));
}else{
///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM codi_one_blog WHERE activer='oui' ORDER BY date_blog DESC LIMIT ".(($_GET['n']-1)*$nbrpage).",".$nbrpage." ");
$req_boucle->execute();
}
/////////////////////////////////////////////////////////////////PAGINATION BLOG OU CATEGORIES - SQL
while($ligne_boucle = $req_boucle->fetch()){
$idoneinfos_artciles_blog = $ligne_boucle['id'];
$id_categorie_artciles_blog = $ligne_boucle['id_categorie'];
$titre_blog_1_artciles_blog = $ligne_boucle['titre_blog_1'];
$titre_blog_2_artciles_blog = $ligne_boucle['titre_blog_2'];

$video_artciles_blog = $ligne_boucle['video'];
$url_fiche_blog_artciles_blog = $ligne_boucle['url_fiche_blog'];
$mot_cle_blog_1_artciles_blog = $ligne_boucle['mot_cle_blog_1'];
$mot_cle_blog_1_lien_artciles_blog = $ligne_boucle['mot_cle_blog_1_lien'];
$mot_cle_blog_2_artciles_blog = $ligne_boucle['mot_cle_blog_2'];
$mot_cle_blog_2_lien_artciles_blog = $ligne_boucle['mot_cle_blog_2_lien'];
$mot_cle_blog_3_artciles_blog = $ligne_boucle['mot_cle_blog_3'];
$mot_cle_blog_3_lien_artciles_blog = $ligne_boucle['mot_cle_blog_3_lien'];
$mot_cle_blog_4_artciles_blog = $ligne_boucle['mot_cle_blog_4'];
$mot_cle_blog_4_lien_artciles_blog = $ligne_boucle['mot_cle_blog_4_lien'];
$ID_IMAGE_BLOG_artciles_blog = $ligne_boucle['ID_IMAGE_BLOG'];
$nbr_consultation_blog_artciles_blog = $ligne_boucle['nbr_consultation_blog'];
$Title_artciles_blog = $ligne_boucle['Title'];
$Metas_description_artciles_blog = $ligne_boucle['Metas_description'];
$Metas_mots_cles_artciles_blog = $ligne_boucle['Metas_mots_cles'];
$activer_commentaire_artciles_blog = $ligne_boucle['activer_commentaire'];
$activer_artciles_blog = $ligne_boucle['activer'];
$date_blog_artciles_blog = $ligne_boucle['date_blog'];

$type_blog_artciles_blog = $ligne_boucle['type_blog_artciles'];

$texte_article_blog_source = strip_tags($ligne_boucle['texte_article']);
$texte_article_blog_len = strlen($texte_article_blog_source);
$texte_article_blog = substr($texte_article_blog_source ,"0","$limitation_texte_liste_blog_cfg");

$texte_article_blog_texte = mb_substr($texte_article_blog_source,"0",($limitation_texte_liste_blog_cfg*2));

//TYPE D'ARTICLE CONDITIONS
if($texte_article_blog_len > $limitation_texte_liste_blog_cfg && $type_blog_artciles_blog != "texte"){
$texte_article_blog = "$texte_article_blog ...";
}elseif($texte_article_blog_len > ($limitation_texte_liste_blog_cfg*2) && $type_blog_artciles_blog == "texte"){
$texte_article_blog = "$texte_article_blog_texte ...";
}

if(!empty($date_blog_artciles_blog)){
$date_blog_artciles_blog_d_mm = date('d', $date_blog_artciles_blog);
$date_blog_artciles_blog_m_mm = date('m', $date_blog_artciles_blog);
$date_blog_artciles_blog_y_mm = date('Y', $date_blog_artciles_blog);
if($date_blog_artciles_blog_m_mm < 10){
$date_blog_artciles_blog_m_mm_0 = substr($date_blog_artciles_blog_m_mm,1);
}else{
$date_blog_artciles_blog_m_mm_0 = $date_blog_artciles_blog_m_mm;
}
$date_blog_artciles_blog_m = $mois_annee[$date_blog_artciles_blog_m_mm_0];
}

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT COUNT(*) AS nbr_commentaire FROM codi_one_blog_commentaires WHERE id_article=?");
$req_select->execute(array($id_categorie_artciles_blog));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$nbr_commentaire = $ligne_select['nbr_commentaire'];

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM codi_one_blog_a_b_image WHERE id_page=? AND defaut=? OR id_page=? ");
$req_select->execute(array($idoneinfos_artciles_blog,'oui',$idoneinfos_artciles_blog));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idimmmagee_image_ii = $ligne_select['id'];
$img_lien_image_ii = $ligne_select['img_lien'];
$img_lien2_image_ii = $ligne_select['img_lien2'];
$img_title_image_ii = $ligne_select['img_title'];

$calcul_page = ($n*$nbrpage);
$calcul_pageoo = ($n*$nbrpage-$nbrpage);
$ww++;
?>

<div class="row">

<?php
if($calcul_page >= $ww && $ww > $nbrpage && $nbrpage >= $nbr_passage && $calcul_pageoo < $ww || !isset($n) && $nbrpage >= $ww){
$nbr_passage++;

//TYPE D'ARTICLE CONDITIONS
///////////////////////////si colone grille 12

//SI ARTICLE MODE TEXTE ET VIDEO VIDE
if($type_blog_artciles_blog == "texte" && !empty($ligne_boucle['texte_article']) && empty($video_artciles_blog)){
?>

<div class='col-md-12' style='text-align: left;'>
<h2 style='margin-top:0px;'><a href='<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "$url_fiche_blog_artciles_blog"; ?>'><?php echo "$titre_blog_1_artciles_blog"; ?></a></h2>
<div style='text-align: left;'>
<?php echo "$texte_article_blog"; ?>
</div>
<hr />
<?php

//SI ARTICLE MODE IMAGE ET EXISTE
}elseif($type_blog_artciles_blog == "image" && !empty($idimmmagee_image_ii) ){
?>
<div class='col-md-12' style='text-align: left;'>
<h2 style='margin-top:0px;'><a href='<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "$url_fiche_blog_artciles_blog"; ?>'><?php echo "$titre_blog_1_artciles_blog"; ?></a></h2>
<a href='<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "$url_fiche_blog_artciles_blog"; ?>'><img src="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/images/blog/<?php echo "$img_lien2_image_ii"; ?>" alt="<?php echo "$titre_blog_1_artciles_blog"; ?>" title="<?php echo "$titre_blog_1_artciles_blog"; ?>" style="width: 100%;"/></a>
<hr />
<?php

//SI ARTICLE MODE VIDEO ET SI ELLE EXISTE
}elseif($type_blog_artciles_blog == "vidéo" && !empty($video_artciles_blog) ){
?>
<div class='col-md-12' style='text-align: left;'>
<h2 style='margin-top:0px;'><a href='<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "$url_fiche_blog_artciles_blog"; ?>'><?php echo "$titre_blog_1_artciles_blog"; ?></a></h2>
<?php
$video_artciles_blog_explode = explode('"', $video_artciles_blog);
$video_artciles_blog_explode_nouvelle_chaine = "".$video_artciles_blog_explode[5]."?wmode=opaque";
$video_artciles_blog_explode_replace = str_replace("$video_artciles_blog_explode[5]","$video_artciles_blog_explode_nouvelle_chaine", $video_artciles_blog);
?>
<div class='video_fiche' style='z-index: 0;'>
<?php echo "$video_artciles_blog_explode_replace"; ?>
</div>
<hr />
<?php
///////////////////////////si colone grille 12

///////////////////////////si colone grille 6
}else{
?>
<div class='col-md-6' style='text-align: left;'>
<?php
//TYPE ARTICLE CONDITIONS

//SI ARTICLE MODE STANDARD
if($type_blog_artciles_blog == "standard"){
?>
<a href='<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "$url_fiche_blog_artciles_blog"; ?>'><img src="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/images/blog/<?php echo "$img_lien2_image_ii"; ?>" alt="<?php echo "$titre_blog_1_artciles_blog"; ?>" title="<?php echo "$titre_blog_1_artciles_blog"; ?>" style="width: 100%;"/></a>
<?php

//SI ARTICLE MODE TEXTE ET VIDEO EXISTE
}elseif($type_blog_artciles_blog == "texte" && !empty($video_artciles_blog)){
$video_artciles_blog_explode = explode('"', $video_artciles_blog);
$video_artciles_blog_explode_nouvelle_chaine = "".$video_artciles_blog_explode[5]."?wmode=opaque";
$video_artciles_blog_explode_replace = str_replace("$video_artciles_blog_explode[5]","$video_artciles_blog_explode_nouvelle_chaine", $video_artciles_blog);
?>
<div class='video_fiche' style='z-index: 0;'>
<?php echo "$video_artciles_blog_explode_replace"; ?>
</div>
<?php

}
///////////////////////////si colone grille 6
//TYPE D'ARTICLE CONDITIONS

}

//TYPE D'ARTICLE CONDITIONS
?>

</div>

<?php
//TYPE D'ARTICLE CONDITIONS
if($type_blog_artciles_blog == "texte" && !empty($video_artciles_blog)  || $type_blog_artciles_blog == "standard"  ){
?>
<div class='col-md-6' style='text-align: left;'>

<h2 style='margin-top:0px;'><a href='<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "$url_fiche_blog_artciles_blog"; ?>'><?php echo "$titre_blog_1_artciles_blog"; ?></a></h2>
<div style='text-align: left;'>
<?php echo "$texte_article_blog"; ?>
</div>

<?php
if($texte_article_blog_len > $limitation_texte_liste_blog_cfg){
?>
<p style='text-align: left;'>
<span style='display: inline-block; cursor: pointer;' onclick="document.location.replace('/<?php echo $url_fiche_blog_artciles_blog; ?>');" ><i class='uk-icon-arrow-circle-right'></i> <?php echo "Lire la suite"; ?></span>
</p>
<?php
}
?>

<p style='font-size: 12px; text-align: left;'>
<span style='display: inline-block;'><i class='uk-icon-clock-o'></i> <?php echo "$date_blog_artciles_blog_d_mm $date_blog_artciles_blog_m $date_blog_artciles_blog_y_mm"; ?></span>  
<?php
if($nbr_commentaire > 0){
?>
<span style='display: inline-block;'><i class='uk-icon-comments-o' style='margin-left: 20px;'></i> <?php echo "$nbr_commentaire"; ?> <?php echo "commentaires"; ?> </span> 
<?php
}
?>
<span style='display: inline-block;'><i class='uk-icon-search-plus' style='margin-left: 20px;'></i> <?php echo "$nbr_consultation_blog_artciles_blog"; ?> <?php echo "Consultations"; ?> </span>
</p>

</div>

</div>

<?php
}
//TYPE D'ARTICLE CONDITIONS
?>
<div style='clear: both; margin-bottom: 20px;'></div>

<?php
}

}
$req_boucle->closeCursor();

if(empty($nbr_passage)){
?>
	<div class="alert alert-warning" style="text-align: left;" >Il n'y a pas de guide !</div>
<?php
}

/////////////////////////////////////////////////////////////////PAGINATION BLOG OU CATEGORIES - SQL
if($action == "Categorie"){
///////////////////////PAGINATION
pagination("codi_one_blog WHERE id_categorie='".$id_categorie_artciles_blog."' ","$nbrpage","$nomsiteweb/$nom_url_categorie","$nom_url_categorie","Blog");
///////////////////////PAGINATION
}else{
///////////////////////PAGINATION
pagination("codi_one_blog","$nbrpage","$nomsiteweb/Blog","Blog","Blog");
///////////////////////PAGINATION
}
/////////////////////////////////////////////////////////////////PAGINATION BLOG OU CATEGORIES - SQL

?>

</div>
</div>
