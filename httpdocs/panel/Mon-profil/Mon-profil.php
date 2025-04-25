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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user) && ($statut_compte_oo != 1)){

    $id_type = 1;

    $action = $_GET['action'];
    $actionn = $_GET['actionn'];
    $idaction = $_GET['idaction'];
    $actionone = $_GET['actionone'];

    ?>

    <script>
        $(document).ready(function (){

            //AJAX SOUMISSION DU FORMULAIRE - MODIFIER - AJOUTER
            $(document).on("click", "#bouton_formulaire_article_categorie", function (){
                //ON SOUMET LE TEXTAREA TINYMCE
                tinyMCE.triggerSave();
                $.post({
                    url : '/panel/Mon-profil/Mon-profil-ajouter-modifier-ajax.php',
                    type : 'POST',
                    data: new FormData($("#formulaire_article_blog_modifier")[0]),
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (res) {
                        if(res.retour_validation == "ok"){
                            popup_alert(res.Texte_rapport,"green filledlight","#009900","uk-icon-check");
            			    $(location).attr("href", "/Mes-documents");
                            }else{
                                popup_alert(res.Texte_rapport,"#CC0000 filledlight","#CC0000","uk-icon-times");
                            }
                        }
                    });
                //listeArticleBlog();
            });

        $(document).on("click", ".categoriechecked", function() {
        souscategorie();
        //TypePubliciteChamp();
      });

      function souscategorie() {
       $.post({
          url: '/panel/Mon-profil/Mon-profil-action-sous-categories-liste.ajax.php',
          type: 'POST',
          data: new FormData($("#formulaire_article_blog_modifier")[0]),
          processData: false,
          contentType: false,
          dataType: "html",
          success: function(res) {
            $("#sous-categorie").html(res);
          }
        });
      }
      souscategorie();

});

    </script>

	<div class="alert alert-warning" style="text-lign: left; margin-bottom: 20px;" >
		<span class="uk-icon-warning" ></span> Pour être présent dans l'annuaire des Extras et pouvoir être sélectionné par des établissements, vous devez remplir votre profil.
	</div>

    <div style='padding: 5px;' align="center">

    <?php

	    ///////////////////////////////SELECT
	    $req_select = $bdd->prepare("SELECT * FROM membres_etablissements WHERE type_demande=? AND id_membre=?");
	    $req_select->execute(array("1",$id_oo));
	    $ligne_select = $req_select->fetch();
	    $req_select->closeCursor();
            $idoneinfos_artciles_blog_profil = $ligne_select['id'];
            $idaction = $ligne_select['id'];
            $mode_vacance = $ligne_select['mode_vacance'];
            $nom_etablissement = $ligne_select['nom_etablissement'];
            $nom_etablissement_url = $ligne_select['nom_etablissement_url'];
            $adresse = $ligne_select['adresse'];
            $cp = $ligne_select['cp'];
            $ville = $ligne_select['ville'];
            $slug_ville = $ligne_select['slug_ville'];
            $id_ville = $ligne_select['id_ville'];
            $longitude = $ligne_select['longitude'];
            $latitude = $ligne_select['latitude'];
            $mail = $ligne_select['mail'];
            $telephone = $ligne_select['telephone'];
            $site_web = $ligne_select['site_web'];
            $description = $ligne_select['description'];
            $photo_principale = $ligne_select['photo_principale'];
            $horaire_semaine = $ligne_select['horaire_semaine'];
            $horaire_samedi = $ligne_select['horaire_samedi'];
            $horaire_ferme = $ligne_select['horaire_ferme'];
            $avis = $ligne_select['avis'];
            $nbr_vue = $ligne_select['nbr_vue'];
            $activer = $ligne_select['activer'];
            $title = $ligne_select['title'];
            $meta_description = $ligne_select['meta_description'];
            $meta_keyword = $ligne_select['meta_keyword'];
            $carte_identite_r = $ligne_select['carte_identite_r'];
            $carte_identite_v = $ligne_select['carte_identite_v'];
            $carte_de_ss = $ligne_select['carte_de_ss'];
            $rib = $ligne_select['rib'];
            $date = $ligne_select['date'];
            $avec_promotion = $ligne_select['avec_promotion'];
            $activer_telephone = $ligne_select['activer_telephone'];
            $id_type = $ligne_select['type_demande'];

if($id_type == 1){
	$id_type_info = $nom_annuaire_1_titre;
}else{
	$id_type_info = $nom_annuaire_2_titre;
}
            ?>

            <div align='left'>
                <h2>Modifier mon profil</h2>
            </div><br />
            <div style='clear: both;'></div>

            <form id="formulaire_article_blog_modifier" method="post" action="#">
                <input id="action" type="hidden" name="action" value="modifier-action" >
                <input id="idaction" type="hidden" name="idaction" value="<?php echo "$idaction"; ?>" >

                <table style="text-align: left; width: 100%; text-align: center;" cellpadding="2" cellspacing="2"><tbody>

			<?php if($action != "ajouter"){ ?>
                    <tr><td style="text-align: left; width: 190px;">Date mise à jour</td>
                        <td style="text-align: left;">
                           <?php if(!empty($date)){ echo date('d-m-Y', $date); }else{ echo "--"; } ?>
                        </td></tr>
                    <tr><td colspan="2" >&nbsp;</td></tr>
			<?php } ?>

		</table>

	<div class="row" >

	  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 10px;" >
	  	<div class="single_menu_product" style="display: block; text-align: left;" >

                    <span class='labelt' >*Catégories</span> <br />
                    <select class="selectpicker categorie-accueil" multiple data-live-search="true" title="Choisissez le département" name="id_categorie[]" data-original-title="Choisissez le département" style="overflow: auto;z-index: 9999; height: 150px; max-width: 600px; " >
                    <?php
      ///////////////////////////////SELECT BOUCLE
      $req_boucle = $bdd->prepare("SELECT * FROM pages_categories WHERE id_type=? AND activer='oui' ORDER BY position ASC");
      $req_boucle->execute(array("1"));
      while($ligne_boucle = $req_boucle->fetch()){
        $idc2 = $ligne_boucle['id'];
        $nom_pays_url = $ligne_boucle['nom_pays_url'];
        $nom_categorie = $ligne_boucle['nom_categorie'];
        $activer = $ligne_boucle['activer'];
        $position = $ligne_boucle['position'];

      ///////////////////////////////SELECT BOUCLE
      $req_bouclecm = $bdd->prepare("SELECT * FROM membres_etablissements_categories WHERE id_etablissement=? AND id_categorie=?");
      $req_bouclecm->execute(array($idaction,$idc2));
      $ligne_bouclecm = $req_bouclecm->fetch();
        $idcs = $ligne_bouclecm['id'];
        ?>
        <option <?php if(!empty($idcs)){ echo "selected"; } ?> value="<?php echo "$idc2"; ?>"><?php echo $ligne_boucle['nom_categorie']; ?></option>

        <?php
      }
      $req_boucle->closeCursor();
      ?>
                    </select>
			<br />

			<!--div class="row" >
      <?php
      ///////////////////////////////SELECT BOUCLE
      $req_boucle = $bdd->prepare("SELECT * FROM pages_categories WHERE id_type=? AND activer='oui' ORDER BY position ASC");
      $req_boucle->execute(array("1"));
      while($ligne_boucle = $req_boucle->fetch()){
        $idc2 = $ligne_boucle['id'];
        $nom_pays_url = $ligne_boucle['nom_pays_url'];
        $nom_categorie = $ligne_boucle['nom_categorie'];
        $activer = $ligne_boucle['activer'];
        $position = $ligne_boucle['position'];

      ///////////////////////////////SELECT BOUCLE
      $req_bouclecm = $bdd->prepare("SELECT * FROM membres_etablissements_categories WHERE id_etablissement=? AND id_categorie=?");
      $req_bouclecm->execute(array($idaction,$idc2));
      $ligne_bouclecm = $req_bouclecm->fetch();
        $idcs = $ligne_bouclecm['id'];
        ?>
        <div class="col-md-4" style="text-align: left;" >
		<input class="form-control categoriechecked" name="id_categorie[]" type="checkbox" <?php if(!empty($idcs)){ echo "checked"; } ?> value='<?php echo "$idc2"; ?>' style="display: inline-block; height: 15px; width: 15px;" ><?php echo "$nom_categorie"; ?> 
	</div>
        <?php
      }
      $req_boucle->closeCursor();
      ?>
		</div-->

			<?php
			if(!empty($nom_etablissement)){
			?>
                            		<br /> <span class='labelt' > <?php echo "$nom_etablissement"; ?></span> <br /> <br />
			<?php
			}
			?>

                    <span class='labelt' >*Mode pause</span> <br />
                        <select name='mode_vacance' class="form-control" >
                            <option value="non" <?php if($mode_vacance == "non" ){ echo "selected"; } ?> > <?php echo "Non"; ?> &nbsp; &nbsp; </option>
                            <option value="oui" <?php if($mode_vacance == "oui" ){ echo "selected"; } ?> > <?php echo "Oui"; ?> &nbsp; &nbsp; </option>
                        </select> <br />

	  	</div>
	   </div>

	  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 10px;" >
	  	<div class="single_menu_product" style="display: block; text-align: left; min-height: 288px;" >

                    <span class='labelt' >Adresse</span> <br />
                    <input type='text' name="adresse" id="adresse" class="form-control" value="<?php if(empty($adresse)){ echo "$adresse_oo"; }else{ echo "$adresse"; } ?>" style='width: 100%;' />

                    <input type="hidden" name="lat" id="lat" />
                    <input type="hidden" name="lng" id="lng" />

                    <span class='labelt' >*Code postal</span> <br />
                    <input type='text' name="cp" id="cp" class="form-control" value="<?php if(empty($cp)){ echo "$cp_oo"; }else{  echo "$cp"; } ?>" style='width: 100%;' />

                    <span class='labelt' >*Ville</span> <br />
                    <input type='text' name="id_ville" id="id_ville" class="form-control" value="<?php if(empty($id_ville)){ echo "$ville_oo"; }else{  echo "$id_ville"; } ?>" style='width: 100%;' />

	  	</div>
	   </div>

	  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;" >
	  	<div class="single_menu_product" style="display: block; text-align: left;" >

                    <span class='labelt' >*Parle nous de toi</span> <br />
                    <textarea class='form-control' id='description' name='description' style='width: 100%; height: 200px;'><?php echo $description; ?></textarea>

	  	</div>
	   </div>

	 </div>

         <div style="text-align: center; margin-top: 20px;" >
		<button id='bouton_formulaire_article_categorie' type='button' class='btn btn-default' style='width: 150px;' onclick='return false;'>MODIFIER</button>
	</div>

                            </form>

</div>

<!-- GET COORDS WITH ADRESS -->
<script>
    if($('#id_ville').val() && $('#adresse').val()) {
        getCoords()
    }

    $('#id_ville').change(() => {
        if($('#id_ville').val() && $('#adresse').val()) {
            getCoords()
        }
    })
    $('#adresse').change(() => {
        if($('#id_ville').val() && $('#adresse').val()) {
            getCoords()
        }
    })

    function getCoords() {
        let ville = $("#id_ville").val()
        let googleAdressFormat = $('#adresse').val() + ", " + ville + ", France"
        console.log(googleAdressFormat)

        $.ajax({
            method: 'GET',
            url: 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDB8vOqn4NsaIIHSk4bP8hHMpdly2jzGEI&address='+googleAdressFormat,
            success: (res) => {
                $('#lat').val(res.results[0].geometry.location.lat)
                $('#lng').val(res.results[0].geometry.location.lng)
		console.log(res.results[0].geometry.location.lat);
		console.log(res.results[0].geometry.location.lng);
            }
        })
    }
</script>

<?php
}else{
header("location: /");
}

?>