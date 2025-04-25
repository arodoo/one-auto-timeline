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

//REQUÊTE DE L'ARTICLE DU BLOG ASSOCIE
///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM codi_one_blog WHERE activer=? AND id=?");
$req_select->execute(array('oui',$_POST['idaction']));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idoneinfos_artciles_blog = $ligne_select['id'];
$id_categorie_artciles_blog = $ligne_select['id_categorie'];
$titre_blog_1_artciles_blog = $ligne_select['titre_blog_1'];
$titre_blog_2_artciles_blog = $ligne_select['titre_blog_2'];
$texte_article_blog = $ligne_select['texte_article'];
$url_fiche_blog_artciles_blog = $ligne_select['url_fiche_blog'];

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT COUNT(*) AS nbr_commentaire FROM codi_one_blog_commentaires WHERE id_article=?");
$req_select->execute(array($idoneinfos_artciles_blog));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$nbr_commentaire = $ligne_select['nbr_commentaire'];
//REQUÊTE DE L'ARTICLE DU BLOG ASSOCIE

?>
<div style='text-align: left; margin-bottom: 10px;'>
<h3 style='text-align: left;' >Commentaires de l'article : <?php echo "$titre_blog_1_artciles_blog"; ?></h3>
</div>
<?php

//SI AU MINIMUM UN COMMENTAIRE
if($nbr_commentaire > 0 ){

if(isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && isset($user)){
?>
<form id='commentairesforma' method='post' action='#'>
<?php
}
/////////////////////////////SI ADMIN ON DONNE LA POSSIBILITE DE SUPPRIMER LES COMMENTAIRES

///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM codi_one_blog_commentaires WHERE activer=? AND id_article=?");
$req_boucle->execute(array('oui',$_POST['idaction']));
while($ligne_boucle = $req_boucle->fetch()){
$idoneinfos_artciles_blog_commentaires_i = $ligne_boucle['id'];
$id_membre_artciles_blog_commentaires_i = $ligne_boucle['id_membre'];
$pseudo_artciles_blog_commentaires_i = $ligne_boucle['pseudo'];
$id_article_artciles_blog_commentaires_i = $ligne_boucle['id_article'];
$titre_commentaire_artciles_blog_commentaires_i = $ligne_boucle['titre_commentaire'];
$contenu_commentaire_artciles_blog_commentaires_i = $ligne_boucle['contenu_commentaire'];
$note_artciles_blog_commentaires_i = $ligne_boucle['note'];
$date_commentaire_artciles_blog_commentaires_i = $ligne_boucle['date_commentaire'];

if(!empty($date_commentaire_artciles_blog_commentaires_i)){
$date_blog_artciles_blog_d_mm_cc = date('d', $date_commentaire_artciles_blog_commentaires_i);
$date_blog_artciles_blog_m_mm_cc = date('m', $date_commentaire_artciles_blog_commentaires_i);
$date_blog_artciles_blog_y_mm_cc = date('Y', $date_commentaire_artciles_blog_commentaires_i);
$date_blog_artciles_blog_m_mm_0_cc = substr($date_blog_artciles_blog_m_mm_cc,1);
if($date_blog_artciles_blog_m_mm_cc < 10){
$date_blog_artciles_blog_m_mm_cc = substr($date_blog_artciles_blog_m_mm_cc,1);
}else{
$date_blog_artciles_blog_m_mm_0_cc = $date_blog_artciles_blog_m_mm_cc;
}
$date_blog_artciles_blog_m_cc = $mois_annee[$date_blog_artciles_blog_m_mm_0_cc];
}

?>
<div class="panel panel-default" style=" padding: 10px;">
  <div class="panel-body" style='text-align: left; font-size: 16px;'>
<?php
/////////////////////////////SI ADMIN ON DONNE LA POSSIBILITE DE SUPPRIMER LES COMMENTAIRES
if(isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && isset($user)){
?>
<input type='checkbox' name='supprimer_commentaire[]' value="<?php echo "$idoneinfos_artciles_blog_commentaires_i"; ?>" style='margin-right: 10px; height: 10px;' title="Supprimer le commentaire">
<?php
}
/////////////////////////////SI ADMIN ON DONNE LA POSSIBILITE DE SUPPRIMER LES COMMENTAIRES
?>
<i class='uk-icon-comments'></i> <?php echo "$titre_commentaire_artciles_blog_commentaires_i"; ?> <img src='/images/etoiles/etoiles<?php echo "$note_artciles_blog_commentaires_i"; ?>.png' alt='<?php echo "$Note_blog_traduction $newsnoteimg"; ?>' style='margin-left: 20px;'/></div>
<div style='clear: both;'></div>
<div style='text-align: left;'><?php echo "$contenu_commentaire_artciles_blog_commentaires_i"; ?></div>
<div style='font-size: 12px; text-align: left; padding-bottom: 10px; margin-top: 10px;'>
<span style='display: inline-block;'><i class='uk-icon-clock-o'></i> <?php echo "$date_blog_artciles_blog_d_mm_cc $date_blog_artciles_blog_m_cc $date_blog_artciles_blog_y_mm_cc"; ?></span>  
<span style='display: inline-block;'><i class='uk-icon-user' style='margin-left: 20px;'></i> <?php echo "$pseudo_artciles_blog_commentaires_i"; ?> </span> 
</div>

</div>
</div>

<?php
}
$req_boucle->closeCursor();

/////////////////////////////SI ADMIN ON DONNE LA POSSIBILITE DE SUPPRIMER LES COMMENTAIRES
if(isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && !empty($user) && !empty($idoneinfos_artciles_blog_commentaires_i) ){
?>
<div style='text-align: left; margin-bottom: 20px;'>
<button type='button' id='Commentaire_selection_supprimer' class='btn btn-success' style='width: 200px;' onclick='return false;' >SUPPRIMER</button>
</div>
</form>
<?php
}
/////////////////////////////SI ADMIN ON DONNE LA POSSIBILITE DE SUPPRIMER LES COMMENTAIRES

}else{
?>
<div class="alert alert-warning" role="alert" style="text-align :left;" >
Il y a aucun commentaire pour l'article : "<?php echo "$titre_blog_1_artciles_blog"; ?>" !
</div>
<?php
}
////////////////////////////////////SI IL Y A AU MINIMUM UN COMMENTAIRE

ob_end_flush();
?>