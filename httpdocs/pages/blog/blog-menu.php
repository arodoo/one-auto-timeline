
<?php   
if(!empty($_GET['idaction']) && !empty($_GET['fiche']) ){
?>

<div style='clear: both; margin-bottom: 15px; '></div>

<div class="sidebar" style="margin-bottom: 40px;" >
	<h3>Informations</h3>
	<hr class="line-separator">

	<div class="information-layout">

		<div class="information-layout-item">
			<p class="text-header"><i class='uk-icon-folder-open'></i> Catégorie : <?php echo $nom_categorie; ?></p>
		</div>

		<div class="information-layout-item">
			<p class="text-header"><i class='uk-icon-clock-o'></i> Date : <?php echo "$date_blog_artciles_blog_d_mm $date_blog_artciles_blog_m_mm $date_blog_artciles_blog_y_mm"; ?> </p>
		</div>

		<div class="information-layout-item">
			<p class="text-header"><i class='uk-icon-search'></i> Consultations : <?php echo "$nbr_consultation_update"; ?> </p>
		</div>

		<?php
		$req_boucle = $bdd->prepare("SELECT * FROM codi_one_blog_liens WHERE id_article_blog = ?  ORDER BY position ASC");
		$req_boucle->execute(array($idaction));
		if ($req_boucle->rowCount() > 0){
		?>
			<div class="information-layout-item">
				<p class="text-header"><i class='uk-icon-link'></i> Liens : </p>
					<ul>
						<?php
						while ($ligne_boucle = $req_boucle -> fetch()){
							$idoneinfoslien_cc = $ligne_boucle['id'];
							$lien_page_cc = $ligne_boucle['lien_page'];
							$ancre_page_cc = $ligne_boucle['ancre_page'];
							$statut_cc = $_ligne_boucle['statut'];
							$position_cc = $_ligne_boucle['Position']
							?>
							<li style="padding-left : 30px; padding-bottom : 10px;">
								<a target="_blank" href="<?php // echo "$http"; ?><?php // echo "$nomsiteweb"; ?><?php echo "$lien_page_cc"; ?>"><?php echo "$ancre_page_cc"; ?></a>
										<!-- <p class="small m-0"><?php echo "".$date_blog_artciles_blog_d_mm."-".$date_blog_artciles_blog_m_mm."-".$date_blog_artciles_blog_y.""; ?></p> -->
							</li>
							<?php
						}
						?>
					</ul>
				</div>
		<?php
		}
		$req_boucle->closeCursor();
		?>
				
	</div>
</div>




<?php
}
?>

<div class="widget">
	<h3 class="widget_title">Catégories</h3>
	<ul class="list_none widget_categories border_bottom_dash">
		<?php
			///////////////////////////////SELECT BOUCLE
			$req_boucle = $bdd->prepare("SELECT * FROM codi_one_blog_categories WHERE activer='oui' ORDER BY nom_categorie ASC");
			$req_boucle->execute();
			while($ligne_boucle = $req_boucle->fetch()){
				$idoneinfos_cc = $ligne_boucle['id'];
				$nom_categorie_cc = $ligne_boucle['nom_categorie'];
				$nom_url_categorie_cc = $ligne_boucle['nom_url_categorie'];
				$nbr_consultation_blog_cc = $ligne_boucle['nbr_consultation_blog'];
				$Title_cc = $ligne_boucle['Title'];
				$Metas_description_cc = $ligne_boucle['Metas_description'];
				$Metas_mots_cles_cc = $ligne_boucle['Metas_mots_cles'];
				$activer_categorie_blog_cc = $ligne_boucle['activer'];
				$date_categorie_blog_cc = $ligne_boucle['date'];
		?>
				<li style="font-size: 14px;" ><a href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "$nom_url_categorie_cc"; ?>"><span class="categories_name"><?php echo "$nom_categorie_cc"; ?></span></a></li>
		<?php
			}
			$req_boucle->closeCursor();
		?>
	</ul>
</div>
<!-- </br></br> -->
			



<div style="clear: both;" ></div>

	<div class="widget" style="margin-top: 20px;" >
		<h3 class="widget_title">Les derniers articles</h3>
		<ul class="recent_post border_bottom_dash list_none">
<?php
///////////////////////////////////////////////////////////////LES DERNIERS ARTICLES AJOUTE
///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM codi_one_blog WHERE activer=? ORDER BY date_blog DESC LIMIT 0,7");
$req_boucle->execute(array('oui'));
while($ligne_boucle = $req_boucle->fetch()){
$idoneinfos_artciles_blog_mm = $ligne_boucle['id'];
$id_categorie_artciles_blog_mm = $ligne_boucle['id_categorie'];
$titre_blog_1_artciles_blog_mm = $ligne_boucle['titre_blog_1'];
$titre_blog_2_artciles_blog_mm = $ligne_boucle['titre_blog_2'];
$url_fiche_blog_artciles_blog_mm = $ligne_boucle['url_fiche_blog'];
$ID_IMAGE_blog_artciles_blog_mm = $ligne_boucle['ID_IMAGE_blog'];
$nbr_consultation_blog_artciles_blog_mm = $ligne_boucle['nbr_consultation_blog'];
$date_blog_artciles_blog_mm = $ligne_boucle['date_blog'];

if(!empty($date_blog_artciles_blog_mm)){
$date_blog_artciles_blog_d_mm = date('d', $date_blog_artciles_blog_mm);
$date_blog_artciles_blog_m_mm = date('m', $date_blog_artciles_blog_mm);
$date_blog_artciles_blog_y = date('Y', $date_blog_artciles_blog_mm);
$date_blog_artciles_blog_m_mm_0 = substr($date_blog_artciles_blog_m_mm,1);
if($date_blog_artciles_blog_m_mm < 10){
$date_blog_artciles_blog_m_mm_0 = substr($date_blog_artciles_blog_m_mm,1);
}else{
$date_blog_artciles_blog_m_mm_0 = $date_blog_artciles_blog_m_mm;
}
$date_blog_artciles_blog_m = $mois_annee[$date_blog_artciles_blog_m_mm_0];
}

///////////////////////////////SELECT
		$req_select = $bdd->prepare("SELECT * 
									FROM codi_one_blog_a_b_image 
									WHERE id_page=? 
									AND defaut=? 
									OR id_page=?");
		$req_select->execute(
			[
				$idoneinfos_artciles_blog_mm,
				'oui',
				$idoneinfos_artciles_blog_mm
			]
		);
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idimmmagee_image_ii_mm = $ligne_select['id'];
$img_lien_image_ii_mm = $ligne_select['img_lien'];
$img_lien2_image_ii_mm = $ligne_select['img_lien2'];
$img_title_image_ii_mm = $ligne_select['img_title'];
?>

                            <li>
                                <div class="post_footer">
                                    <div class="post_content">
                                        <h6><a href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "$url_fiche_blog_artciles_blog_mm"; ?>"><?php echo "$titre_blog_1_artciles_blog_mm"; ?></a></h6>
                                        <p class="small m-0"><?php echo "".$date_blog_artciles_blog_d_mm."-".$date_blog_artciles_blog_m_mm."-".$date_blog_artciles_blog_y.""; ?></p>
                                    </div>
                                </div>
                            </li>
<?php
}
$req_boucle->closeCursor();
?>

		</ul>
	</div>

<div style='margin-top: 20px; text-align: center;'>
<?php
/////////////////////////////////////PUBLICITES
//include('blog-menu-publicites.php');
/////////////////////////////////////PUBLICITES
?>
</div>

<?php
/////////////////////////////////////RESEAUX SOCIAUX BOUTTON
//include('function/reseaux-sociaux/reseaux-sociaux.php');
/////////////////////////////////////RESEAUX SOCIAUX BOUTTON
?>

</div>