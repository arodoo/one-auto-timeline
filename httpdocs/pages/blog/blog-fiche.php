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

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM codi_one_blog WHERE activer=? AND id=?");
$req_select->execute(array('oui',$_GET['idaction']));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idoneinfos_artciles_blog = $ligne_select['id'];
$id_categorie_artciles_blog = $ligne_select['id_categorie'];

$req_select = $bdd->prepare("SELECT nom_categorie FROM codi_one_blog_categories WHERE id=?");
$req_select->execute(array($id_categorie_artciles_blog));
$categorie_select = $req_select->fetch();
$req_select->closeCursor();
$nom_categorie = $categorie_select['nom_categorie'];

$titre_blog_1_artciles_blog = $ligne_select['titre_blog_1'];
$titre_blog_2_artciles_blog = $ligne_select['titre_blog_2'];
$texte_article_blog = $ligne_select['texte_article'];
$video_artciles_blog = $ligne_select['video'];
$url_fiche_blog_artciles_blog = $ligne_select['url_fiche_blog'];
$mot_cle_blog_1_artciles_blog = $ligne_select['mot_cle_blog_1'];
$mot_cle_blog_1_lien_artciles_blog = $ligne_select['mot_cle_blog_1_lien'];
$mot_cle_blog_2_artciles_blog = $ligne_select['mot_cle_blog_2'];
$mot_cle_blog_2_lien_artciles_blog = $ligne_select['mot_cle_blog_2_lien'];
$mot_cle_blog_3_artciles_blog = $ligne_select['mot_cle_blog_3'];
$mot_cle_blog_3_lien_artciles_blog = $ligne_select['mot_cle_blog_3_lien'];
$mot_cle_blog_4_artciles_blog = $ligne_select['mot_cle_blog_4'];
$mot_cle_blog_4_lien_artciles_blog = $ligne_select['mot_cle_blog_4_lien'];
$ID_IMAGE_BLOG_artciles_blog = $ligne_select['ID_IMAGE_BLOG'];
$nbr_consultation_blog_artciles_blog = $ligne_select['nbr_consultation_blog'];
$Title_artciles_blog = $ligne_select['Title'];
$Metas_description_artciles_blog = $ligne_select['Metas_description'];
$Metas_mots_cles_artciles_blog = $ligne_select['Metas_mots_cles'];
$activer_commentaire_artciles_blog = $ligne_select['activer_commentaire'];
$activer_artciles_blog = $ligne_select['activer'];
$date_blog_artciles_blog = $ligne_select['date_blog'];
$type_blog_artciles_blog = $ligne_select['type_blog_artciles'];

if(!empty($date_blog_artciles_blog)){
$date_blog_artciles_blog_d_mm = date('d', $date_blog_artciles_blog);
$date_blog_artciles_blog_m_mm = date('m', $date_blog_artciles_blog);
$date_blog_artciles_blog_y_mm = date('Y', $date_blog_artciles_blog);
$date_blog_artciles_blog_m_mm_0 = substr($date_blog_artciles_blog_m_mm,1);
if($date_blog_artciles_blog_m_mm < 10){
$date_blog_artciles_blog_m_mm_0 = substr($date_blog_artciles_blog_m_mm,1);
}else{
$date_blog_artciles_blog_m_mm_0 = $date_blog_artciles_blog_m_mm;
}
$date_blog_artciles_blog_m = $mois_annee[$date_blog_artciles_blog_m_mm_0];
}

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT COUNT(*) AS nbr_commentaire FROM codi_one_blog_commentaires WHERE id_article=?");
$req_select->execute(array($idoneinfos_artciles_blog));
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


//////////Si pas de résultat - Redirection 301 page accueil
if(empty($idoneinfos_artciles_blog)){
header("HTTP/1.0 301 Moved Permanently");     
header("Location: /");      
exit();
}
//////////Si pas de résultat - Redirection 301 page accueil

//////////Redirection 301 si // url
$explodeuri = explode('/', $_SERVER['REQUEST_URI']);
//var_dump($explodeuri[2]);
if ($explodeuri[2] == "Blog") {
header("HTTP/1.0 301 Moved Permanently");   
header("Location: /$url_fiche_blog_artciles_blog");      
exit();
}
//////////Redirection 301 si // url

///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM codi_one_blog_commentaires WHERE plus1=?");
$req_boucle->execute(array('oui'));
while($ligne_boucle = $req_boucle->fetch()){
$NOTEnewonehh = $ligne_boucle['note'];
$newsnote = ($NOTEnewonehh+$newsnote);
}
$req_boucle->closeCursor();

if($nbravis > 0){
$newsnote = ($newsnote/$nbravis);
$newsnote = round($newsnote, 2);
}else{
$newsnote = round($newsnote, 2);
$newsnote = "$newsnote";
}

$newsnoteimg = round($newsnote);

///////////////////////////////////////////////////////UPDATE CONSULTATIONS
$nbr_consultation_update = ($nbr_consultation_blog_artciles_blog+1);
///////////////////////////////UPDATE
$sql_update = $bdd->prepare("UPDATE codi_one_blog SET 
	nbr_consultation_blog=?  
	WHERE id=?");
$sql_update->execute(array(
	$nbr_consultation_update, 
	$idaction));                     
$sql_update->closeCursor();
///////////////////////////////////////////////////////UPDATE CONSULTATIONS
?>

<div class='col-md-9' style='text-align: left;'>

<?php if(!empty($img_lien2_image_ii)){ ?>
	<img src="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/images/blog/<?php echo "$img_lien2_image_ii"; ?>" alt="<?php echo "$titre_blog_1_artciles_blog"; ?>" title="<?php echo "$titre_blog_1_artciles_blog"; ?>" style="width: 100%;"/>
<?php } ?>

<?php
////////////////Partage réseaux sociaux
$lasturrloo1114455infos = $_SERVER['REQUEST_URI'];
include('function/reseaux-sociaux/partage.php');
////////////////Partage réseaux sociaux
?>

<h2><?php echo "$titre_blog_2_artciles_blog"; ?></h2>
<div style='text-align: left;'> 
	<?php echo "$texte_article_blog"; ?> 
</div>

<?php
///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM codi_one_blog_a_b_image 
	WHERE id_page=? 
	AND defaut!=? 
	AND id!=? ");
$req_boucle->execute(array(
	$idaction, 
	'oui', 
	$idimmmagee_image_ii));
while($ligne_boucle = $req_boucle->fetch()){
$idoneinfos_artciles_blog_image_suppl = $ligne_boucle['id'];
$img_lien_artciles_blog_image_suppl = $ligne_boucle['img_lien'];
$img_lien2_artciles_blog_image_suppl = $ligne_boucle['img_lien2'];
$img_title_artciles_blog_image_suppl = $ligne_boucle['img_title'];
?>
<div style='margin-bottom: 20px;'>
	<img src="<?php echo "".$http.""; ?><?php echo "$nomsiteweb"; ?>/images/blog/<?php echo "$img_lien2_artciles_blog_image_suppl"; ?>" alt="<?php echo "$img_title_artciles_blog_image_suppl"; ?>" title="<?php echo "$img_title_artciles_blog_image_suppl"; ?>" style="width: 100%;"/>
</div>
<?php
}
$req_boucle->closeCursor();
?>

<div style="clear: both; margin-top: 20px;" ></div>
<h2>Suggestions : <?php echo "$titre_blog_2_artciles_blog"; ?> </h2>

        <div class="row blog_wrap justify-content-center data-animation-delay="0.4s" style="justify-content: left !important;" >
	<?php
	///////////////////////////////SELECT BOUCLE
	$req_boucle = $bdd->prepare("SELECT * FROM codi_one_blog WHERE activer=? AND type_blog_artciles=? AND id_categorie=? ORDER BY date_blog DESC LIMIT 0,3");
	$req_boucle->execute(array("oui","standard",$id_categorie_artciles_blog));
	while($ligne_boucle = $req_boucle->fetch()){
		///////////////////////////////SELECT
		$req_select = $bdd->prepare("SELECT * FROM codi_one_blog_a_b_image WHERE id_page=?");
		$req_select->execute(array($ligne_boucle['id']));
		$ligne_select = $req_select->fetch();
		$req_select->closeCursor();
            	$img_lienii = $ligne_select['img_lien2'];
             	//affichage date
            	$date_fiche = $ligne_boucle['date_blog'];
            	$jour = date('d', $date_fiche);
            	$mois = date('m', $date_fiche);
            	$annee = date('y', $date_fiche);
		$b++;
		$texte_article_blog_source = strip_tags($ligne_boucle['texte_article']);
		$texte_article_blog_len = strlen($texte_article_blog_source);
		$texte_article_blog = substr($texte_article_blog_source ,"0","100");
		$texte_article_blog_texte = mb_substr($texte_article_blog_source,"0",100*2);
		if($texte_article_blog_len > $limitation_texte_liste_blog_cfg && $type_blog_artciles_blog != "texte"){
			$texte_article_blog = "$texte_article_blog ...";
		}elseif($texte_article_blog_len > ($limitation_texte_liste_blog_cfg*2) && $type_blog_artciles_blog == "texte"){
			$texte_article_blog = "$texte_article_blog_texte ...";
		}
            	?>
        		<div class="col-lg-4 col-md-6 mb-md-4 mb-2 pb-2">
            			<div class="blog_post blog_style1">
                			<div class="blog_img">
                        			<a href="/<?= $ligne_boucle['url_fiche_blog']; ?>">
                            				<img src="/images/blog/<?= $img_lienii; ?>" alt="<?= $ligne_boucle['image_url']; ?>">
                        			</a>
                        			<span class="post_date bg_blue text-light"><?php echo "".$jour."-".$mois."-".$annee.""; ?></span>
                    			</div>
                    			<div class="blog_content bg-white">
                    				<div class="blog_text">
                           	 			<h6 class="blog_title"><a href="/<?= $ligne_boucle['url_fiche_blog']; ?>"><?= $ligne_boucle['titre_blog_1']; ?></a></h6>
                            				<p><?php echo $texte_article_blog; ?></p>
                            				<a href="/<?= $ligne_boucle['url_fiche_blog']; ?>" class="text-capitalize"><?= $ligne_boucle['titre_blog_1']; ?> <i class="ti-angle-double-right align-middle"></i></a>
                        			</div>
                    			</div>
                		</div>
            		</div>
	<?php
	}
	$req_boucle->closeCursor();
	?>  
</div>

</div>
