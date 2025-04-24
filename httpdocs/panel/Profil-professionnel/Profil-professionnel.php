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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {

  $action = $_GET['action'];
  $idaction = $_GET['idaction'];

  ?>

  <script>
    $(document).ready(function () {

      //AJAX SOUMISSION DU FORMULAIRE - MODIFIER - AJOUTER
      $(document).on("click", "#bouton", function () {
        //ON SOUMET LE TEXTAREA TINYMCE
        tinyMCE.triggerSave();
        
        // Log form data for debugging
        console.log("Form data:", new FormData($("#profil_auto_form")[0]));

        $.ajax({
          url: '/panel/Profil-professionnel/Profil-professionnel-ajouter-modifier-ajax.php',
          type: 'POST',
          data: new FormData($("#profil_auto_form")[0]),
          processData: false,
          contentType: false,
          dataType: "json",
          success: function (res) {
            console.log("Response:", res);
            if (res.retour_validation == "ok") {
              popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
              // Auto reload page after 1 second
              setTimeout(function() {
                window.location.reload();
              }, 1000);
            } else {
              popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            }
            //liste();
          },
          error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
          }
        });
        $("html, body").animate({ scrollTop: 0 }, "slow");
      });

    });
  </script>

  <?php
  ///////////////////////////////SELECT
  $req_select = $bdd->prepare("SELECT * FROM membres_profils WHERE id_membre=?");
  $req_select->execute(array($id_oo));
  $ligne_select = $req_select->fetch();
  $req_select->closeCursor();
  $titre_profil = $ligne_select['titre_profil'];
  $url_profil = $ligne_select['url_profil'];
  $description = $ligne_select['description'];
  $title = $ligne_select['title'];
  $meta_description = $ligne_select['meta_description'];
  $meta_keyword = $ligne_select['meta_keyword'];
  $activer = $ligne_select['activer'];

  // Fetch photos information if exists
  $req_photos = $bdd->prepare("SELECT * FROM membres_profil_professionnel_imgs WHERE id_membre = ?");
  $req_photos->execute(array($id_oo));
  $photos_data = $req_photos->fetch();
  $req_photos->closeCursor();
  ?>

  <div style='padding: 5px;'>

    <form id="profil_auto_form" method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="form-group">
          <label for="immatriculation">*Titre profil</label>
          <input type="text" class="form-control" id="titre_profil" name="titre_profil"
            value="<?php echo $titre_profil; ?>">
        </div>
        <div class="form-group">
          <label for="description">*Description</label>
          <textarea class="form-control" id="description" name="description"
            style="height: 200px !important;"><?php echo $description; ?></textarea>
        </div>
        
        <!-- Photos row -->
        <div class="row">
          <!-- Photo Field 1 -->
          <div class="col-md-6">
            <div class="form-group">
              <label for="photo1">Photo 1</label>
              <input type="file" class="form-control" id="photo1" name="photo1" accept="image/*">
              <?php if ($photos_data && !empty($photos_data['photo1'])): ?>
                <div class="mt-2">
                  <img src="/images/profil-professionnel/<?php echo $id_oo; ?>/<?php echo $photos_data['photo1']; ?>" 
                       width="150" class="img-thumbnail">
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Photo Field 2 -->
          <div class="col-md-6">
            <div class="form-group">
              <label for="photo2">Photo 2</label>
              <input type="file" class="form-control" id="photo2" name="photo2" accept="image/*">
              <?php if ($photos_data && !empty($photos_data['photo2'])): ?>
                <div class="mt-2">
                  <img src="/images/profil-professionnel/<?php echo $id_oo; ?>/<?php echo $photos_data['photo2']; ?>" 
                       width="150" class="img-thumbnail">
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <button id="bouton" type="submit" class="btn btn-primary" onclick="return false;"
          style="width: 200px;">Enregistrer</button>
    </form>
  </div>

  </div>

  <?php
} else {
  header("location: /");
}

?>