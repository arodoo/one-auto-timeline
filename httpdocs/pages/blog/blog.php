<script>
  $(document).ready(function (){
    ListeDesCommentaires();

  //AJAX SOUMISSION DU FORMULAIRE
  $(document).on("click", "#envoyer_commentaire_post", function (){
    $.post({
      url : '/pages/blog/blog-commentaires-ajax.php',
      type : 'POST',
      data : {
        idaction:"<?php echo $_GET['idaction']; ?>",
        note_post:$('#note_post').val(),
        nom_post_commentaire:$('#nom_post_commentaire').val(),
        titre_post_commentaire:$('#titre_post_commentaire').val(),
        post_commentaire:$('#post_commentaire').val(),
        envoyer_commentaire_post:$('#envoyer_commentaire_post').val()
      },
      dataType: "json",
      success: function (res) {
        if(res.retour_validation == "ok"){
          deletenote1('etoile11');
          ListeDesCommentaires();
          $('#deposer-un-avis-en-ligne').modal('toggle');
          popup_alert(res.Texte_rapport,"green filledlight","#009900","uk-icon-check");
          $('#titre_post_commentaire').val("");
          $('#post_commentaire').val("");
          $('#note_post').val("");
        }else{
          popup_alert(res.Texte_rapport,"#CC0000 filledlight","#CC0000","uk-icon-times");
        }
      }
    });

  });

  <?php
  //////////////SI ADMIN
  if(!empty($_SESSION['7A5d8M9i4N9']) && !empty($_SESSION['4M8e7M5b1R2e8s'])){
    ?>
  //AJAX ON SUPPRIME EN BOUCLE LES COMMENTAIRES SELECTIONNES
  $(document).on("click", "#Commentaire_selection_supprimer", function (){
    $.post({
      url : '/pages/blog/blog-commentaires-supprimer-ajax.php',
      type : 'POST',
      data: new FormData($("#commentairesforma")[0]),
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (res) {
        if(res.retour_validation == "ok"){
          ListeDesCommentaires();
          popup_alert(res.Texte_rapport,"green filledlight","#009900","uk-icon-check");
        }else{
          popup_alert(res.Texte_rapport,"#CC0000 filledlight","#CC0000","uk-icon-times");
        }
      }
    });
  });
  <?php
  }
  //////////////SI ADMIN
  ?>

  function ListeDesCommentaires(){
    $.post({
      url : '/pages/blog/blog-commentaires-liste-ajax.php',
      type : 'POST',
      data:{
        idaction:"<?php echo $_GET['idaction']; ?>"
      },
      dataType: "html",
      success: function (res) {
        $("#liste-des-commentaires").html(res);
      }
    });
  }

  $(document).on("click" , "#button-deposer-un-avis-en-ligne", function (){
    $('#deposer-un-avis-en-ligne').modal({ backdrop: 'static', keyboard: false, show: true });
  });

  });

</script>


<?php

//////////////////CONFIGURATIONS DU BLOG
///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM codi_one_blog_a_cfg WHERE id=?");
$req_select->execute(array("1"));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$id_blog_cfg = $ligne_select['id'];
$Contenu_cfg_blog = $ligne_select['Contenu_cfg_blog'];
$limitation_texte_liste_blog_cfg = $ligne_select['limitation_texte_liste_cfg_blog'];
if(!empty($ligne_select['nbr_article_page_blog'])){
  $nbrpage = $ligne_select['nbr_article_page_blog'];
}
$nbr_liste_menu_cfg_blog = $ligne_select['nbr_liste_menu_cfg_blog'];
//////////////////CONFIGURATIONS DU BLOG

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Variables
$name = $_GET['name'];
$action = $_GET['action'];
$idaction = $_GET['idaction'];
$fiche = $_GET['fiche'];
$now = time();
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Variables

?>

<div class="row class_blog">

<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////SI LISTE GLOBALE OU CATEGORIE
if(empty($fiche)){
	////////////FICHES LISTE
  	include('pages/blog/blog-fiches-liste.php');

    ?>

<div class='col-md-3 contact-form-wrapper background-white p30' style='text-align: left;'>
	<?php include('pages/blog/blog-menu.php'); ?>
	<div style="clear: both;" ></div>
</div>
<?php

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////SI FICHE
if(!empty($fiche)){

  	$activer_bouton_avis_fiche = "oui";
	////////////FICHE
 	include('pages/blog/blog-fiche.php');
	////////////FICHE

///////////////////////////////////////////////////////////////VIDEO
?>

<div class='col-md-3 contact-form-wrapper background-white p30' style='text-align: left;'>
	<?php include('pages/blog/blog-menu.php'); ?>
	<div style="clear: both;" ></div>
</div>
<?php
//<iframe width="560" height="315" src="//www.youtube.com/embed/B5tD-vTEIkc" wmode="opaque" frameborder="0" allowfullscreen></iframe>
  if(!empty($video_artciles_blog)){

    $video_artciles_blog_explode = explode('"', $video_artciles_blog);
    $video_artciles_blog_explode_nouvelle_chaine = "".$video_artciles_blog_explode[5]."?wmode=opaque";
    $video_artciles_blog_explode_replace = str_replace("$video_artciles_blog_explode[5]","$video_artciles_blog_explode_nouvelle_chaine", $video_artciles_blog);
    ?>
    <div class='video_fiche' style='z-index: 0; margin-bottom: 15px;'>
      <?php echo "$video_artciles_blog_explode_replace"; ?>
    </div>
    <?php
  }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////SI FICHE
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Menu
?>

<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Menu
?>

</div>
