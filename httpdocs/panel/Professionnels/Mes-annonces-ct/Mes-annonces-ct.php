<?php
ob_start();
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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

  <script>
    $(document).ready(function() {

      //AJAX SOUMISSION DU FORMULAIRE - MODIFIER - AJOUTER
      $(document).on("click", "#bouton", function(e) {
        e.preventDefault();
        tinyMCE.triggerSave();

        var form = $("#formulaire-ajouter")[0];
        if ("<?php echo $_GET['action']; ?>" == "modifier") {
          form = $("#formulaire-modifier")[0];
        }

        var formData = new FormData(form);
       /*  console.log("Datos enviados al backend:", Object.fromEntries(formData.entries())); */
        $.post({
          url: '/panel/Professionnels/Mes-annonces-ct/Mes-annonces-ct-action-ajouter-modifier-ajax.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          dataType: "json",
          success: function(res) {
            if (res.retour_validation == "ok") {
            /*   console.log("Respuesta del servidor:", res); */
              popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
              if ("<?php echo $_GET['action']; ?>" != "modifier") {
                $("#formulaire-ajouter")[0].reset();
              }
            } else {
              popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            }
            liste();
          },
        /*   error: function(jqXHR, textStatus, errorThrown) {
            console.error('Erreur lors de la soumission du formulaire:', textStatus, errorThrown);
            popup_alert('Erreur lors de la soumission du formulaire: ' + textStatus + ' - ' + errorThrown, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
          } */
        });

        $("html, body").animate({
          scrollTop: 0
        }, "slow");
      });

      //AJAX - SUPPRIMER
      $(document).on("click", ".lien-supprimer", function() {
        $.post({
          url: '/panel/Professionnels/Mes-annonces-ct/Mes-annonces-ct-action-supprimer-ajax.php',
          type: 'POST',
          data: {
            idaction: $(this).attr("data-id")
          },
          dataType: "json",
          success: function(res) {
            if (res.retour_validation == "ok") {
              popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
            } else {
              popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            }
            liste();
          }
        });
      });

      //FUNCTION AJAX - LISTE
      function liste() {
        $.post({
          url: '/panel/Professionnels/Mes-annonces-ct/Mes-annonces-ct-liste-ajax.php',
          type: 'POST',
          dataType: "html",
          success: function(res) {
            $("#liste").html(res);
          }
        });
      }
      liste();

      var photoCount = 0;
      var formData = new FormData();

      function handleFileSelect(event, previewId, photoNum) {
        var files = event.target.files;

        $.each(files, function(index, file) {
          var reader = new FileReader();
          reader.onload = function(e) {
            var img = $('<img>').attr('src', e.target.result).css('max-width', '100%');
            var cropContainer = $('<div>').addClass('crop-container').append(img);
            $('#' + previewId).empty().append(cropContainer); // Vider le conteneur avant d'ajouter la nouvelle image

            img.on('load', function() {
              if (img[0].naturalWidth < 545 || img[0].naturalHeight < 545) {
                alert('L\'image doit faire au moins 545 pixels de largeur et de hauteur.');
                cropContainer.remove();
                return;
              }

              var cropper = new Cropper(img[0], {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1,
                ready: function() {
                  var cropButton = $('<button>').text('Valider le recadrage').addClass('btn btn-success crop-button');
                  cropContainer.append(cropButton);

                  cropButton.on('click', function(event) {
                    event.preventDefault(); // Empêcher le comportement par défaut du bouton
                    var canvas = cropper.getCroppedCanvas({
                      width: 545,
                      height: 545
                    });
                    canvas.toBlob(function(blob) {
                      var timestamp = Date.now();
                      var slug = convertToSlug(file.name) + '-' + timestamp;
                      var newFile = new File([blob], slug + '.jpg', {
                        type: 'image/jpeg'
                      });
                      formData.append('photos[]', newFile);
                      formData.append('photo_num[]', photoNum); // Ajouter le numéro de champ
                      cropContainer.remove();
                      photoCount++;

                      // Afficher la prévisualisation de l'image recadrée
                      var croppedImg = $('<img>').attr('src', URL.createObjectURL(blob)).css('max-width', '100%');
                      $('#' + previewId).empty().append(croppedImg); // Vider le conteneur avant d'ajouter la nouvelle image

                      // Envoyer l'image recadrée via AJAX
                      $.ajax({
                        url: '/panel/Professionnels/Mes-annonces-ct/Mes-annonces-ct-upload.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                          var jsonResponse = JSON.parse(response);
                          jsonResponse.forEach(function(res) {
                            if (res.retour_validation === "ok") {
                              popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
                            } else {
                              popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                            }
                          });
                          formData = new FormData(); // Réinitialiser formData après chaque envoi
                        },
                        error: function() {
                          alert('Erreur lors de la soumission du formulaire.');
                        }
                      });
                    });
                  });
                }
              });
            });
          };
          reader.readAsDataURL(file);
        });
      }

      $('#photos1').on('change', function(event) {
        handleFileSelect(event, 'preview1', 1);
      });

      $('#photos2').on('change', function(event) {
        handleFileSelect(event, 'preview2', 2);
      });

      $('#photos3').on('change', function(event) {
        handleFileSelect(event, 'preview3', 3);
      });

      $('#photos4').on('change', function(event) {
        handleFileSelect(event, 'preview4', 4);
      });

      function convertToSlug(text) {
        return text.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
      }

    });
  </script>

  <div style='padding: 5px; text-align: center;'>

    <?php
    if ($action != "ajouter" && $action != "modifier") {
    ?>
      <a href="/Mes-annonces-ct/ajouter" class="btn btn-primary"
        style="float: right;  margin-right: 5px; margin-bottom: 20px;">Ajouter une annonce</a>
      <?php
    }

    ////////////////////////////FORMULAIRE AJOUTER / MODIFIER
    if ($action == "ajouter" || $action == "modifier") {

      if ($action == "modifier") {

        ///////////////////////////////SELECT
        $req_select = $bdd->prepare("SELECT * FROM membres_annonces_ct WHERE id=? AND id_membre=?");
        $req_select->execute(array($idaction, $id_oo));
        $ligne_select = $req_select->fetch();
        $req_select->closeCursor();

        $_SESSION['id_temporaire_image_annonce'] = $idaction;

        $id_departement_selected = $ligne_select['id_departement'];
      ?>

        <div align='left'>
          <h2 style="float: left;">Modifier</h2>
          <a href="/Mes-annonces-ct" class="btn btn-primary" style="float: right;">Liste</a>
          <a href="/Mes-annonces-ct/ajouter" class="btn btn-primary" style="float: right;  margin-right: 5px;">Ajouter une
            annonce</a>
        </div><br />
        <div style='clear: both;'></div>

        <?php
        $images = [];
        $id_membre = $id_oo;
        $id_produit = $_SESSION['id_temporaire_image_annonce'];
        $sql = "SELECT numero, nom_image FROM membres_annonces_ct_images WHERE id_membre = ? AND id_annonce_service = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$id_membre, $id_produit]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="container mt-5 text-left" style="text-align: left;">
          <h2>*Téléchargement photos</h2>
          <form id="commandeForm" enctype="multipart/form-data">
            <div class="row mb-3">
              <?php for ($i = 1; $i <= 1; $i++): ?>
                <div class="col-md-12 text-left" style="text-align: left;">
                  <input type="file" name="photos[]" id="photos<?= $i ?>" class="form-control" accept="image/*">
                  <input type="hidden" name="photo_num[]" value="<?= $i ?>">
                  <div id="preview<?= $i ?>"></div>
                  <?php
                  if ($action == 'modifier') {
                    foreach ($images as $image) {
                      if ($image['numero'] == $i) {
                        echo '<a href="/images/membres/' . $user . '/' . $image['nom_image'] . '" target="_blank">' . $image['nom_image'] . '</a>';
                      }
                    }
                  }
                  ?>
                </div>
              <?php endfor; ?>
            </div>
          </form>
        </div>
        <?php ob_end_flush(); ?>

        <form id='formulaire-modifier' method="post" enctype="multipart/form-data">
          <input id="action" type="hidden" name="action" value="modifier-action">
          <input id="idaction" type="hidden" name="idaction" value="<?php echo $_GET['idaction']; ?>">

        <?php
      } else {

        $_SESSION['id_temporaire_image_annonce'] = time();

        ?>

          <div align='left'>
            <h2 style="float: left;">Ajouter</h2>
            <a href="/Mes-annonces-ct" class="btn btn-primary" style="float: right;">Liste</a>
            <a href="/Mes-annonces-ct/ajouter" class="btn btn-primary" style="float: right; margin-right: 5px;">Ajouter une
              annonce</a>
          </div><br />
          <div style='clear: both;'></div>

          <?php
          $images = [];
          $id_membre = $id_oo;
          $id_produit = $_SESSION['id_temporaire_image_annonce'];
          $sql = "SELECT numero, nom_image FROM membres_annonces_ct_images WHERE id_membre = ? AND id_annonce_service = ?";
          $stmt = $bdd->prepare($sql);
          $stmt->execute([$id_membre, $id_produit]);
          $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
          ?>
          <div class="container mt-5 text-left" style="text-align: left;">
            <h2>*Téléchargement photos</h2>
            <form id="commandeForm" enctype="multipart/form-data">
              <div class="row mb-3">
                <?php for ($i = 1; $i <= 1; $i++): ?>
                  <div class="col-md-12 text-left" style="text-align: left;">
                    <input type="file" name="photos[]" id="photos<?= $i ?>" class="form-control" accept="image/*">
                    <input type="hidden" name="photo_num[]" value="<?= $i ?>">
                    <div id="preview<?= $i ?>"></div>
                    <?php
                    if ($action == 'modifier') {
                      foreach ($images as $image) {
                        if ($image['numero'] == $i) {
                          echo '<a href="/images/membres/' . $user . '/' . $image['nom_image'] . '" target="_blank">' . $image['nom_image'] . '</a>';
                        }
                      }
                    }
                    ?>
                  </div>
                <?php endfor; ?>
              </div>
            </form>
          </div>
          <?php ob_end_flush(); ?>

          <form id='formulaire-ajouter' method="post" enctype="multipart/form-data">
            <input id="action" type="hidden" name="action" value="ajouter-action">

          <?php
        }
          ?>

          <div class="container mt-5 text-left" style="text-align: left;">
            <h2>Informations annonce</h2>

            <div class="row mb-3">
              <div class="col-md-12 text-left">
                <label for="nom">*Nom annonce:</label>
                <input type="text" name="nom" id="nom" class="form-control" value="<?php echo $ligne_select['nom']; ?>">
              </div>
              <div class="col-md-12 text-left">
                <label for="description">*Description:</label>
                <textarea name="description" id="description"
                  class="form-control"><?php echo nl2br($ligne_select['description']); ?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4 text-left">
                <label for="ville">*Ville:</label>
                <input type="text" name="ville" id="ville" class="form-control"
                  value="<?php echo $ligne_select['ville']; ?>">
              </div>
              <div class="col-md-4 text-left">
                <label for="departement">*Département:</label> <br>
                <select name="departement" id="departement" class="form-control">
                  <option value=''>Sélection</option>
                  <?php
                  $sql = "SELECT id, name, code FROM dpts ORDER BY name";
                  $stmt = $bdd->prepare($sql);
                  $stmt->execute();
                  $departements = $stmt->fetchAll(PDO::FETCH_ASSOC);


                  foreach ($departements as $departement) {
                    $selected = ($departement['id'] == $id_departement_selected) ? 'selected' : '';
                    echo '<option value="' . $departement['id'] . '" ' . $selected . '>'
                      . $departement['code'] . ' - ' . $departement['name']
                      . '</option>';
                  }
                  ?>
                </select>
          

              </div>
              <div class="col-md-4 text-left">
                <label for="specialite">Prix:</label>
                <div class="input-hasicon mb-sm-0 mb-3">
                  <input type="text" name="prix" id="prix" class="form-control" value="<?php echo $ligne_select['prix']; ?>">
                  <div class="icon"><i class="fas fa-euro-sign"></i></div>
                </div>
              </div>
              <div class="col-md-4 text-left">
                <label for="statut">*Statut:</label>
                <select name="statut" id="statut" class="form-control">
                  <option value="brouillon" <?php if ($ligne_select['statut'] == "brouillon")
                                              echo 'selected'; ?>>Brouillon
                  </option>
                  <option value="activé" <?php if ($ligne_select['statut'] == "activé")
                                            echo 'selected'; ?>>Activée</option>
                </select>
              </div>
              <?php if ($action == "modifier") { ?>
                <div class="col-md-4 text-left">
                  <label for="date_statut">Date statut:</label> <br>
                  <div style="margin-top: 10px;">
                    <?php echo date('d-m-Y', $ligne_select['date']); ?>
                  </div>
                </div>
              <?php } ?>
              <div class="col-md-4 text-left">
                <label for="mot_cle_1">Mot clé 1:</label>
                <input type="text" name="mot_cle_1" id="mot_cle_1" class="form-control"
                  value="<?php echo $ligne_select['mot_cle_1']; ?>">
              </div>
              <div class="col-md-4 text-left">
                <label for="mot_cle_2">Mot clé 1:</label>
                <input type="text" name="mot_cle_2" id="mot_cle_2" class="form-control"
                  value="<?php echo $ligne_select['mot_cle_2']; ?>">
              </div>
              <div class="col-md-4 text-left">
                <label for="mot_cle_1">Mot clé 3:</label>
                <input type="text" name="mot_cle_3" id="mot_cle_3" class="form-control"
                  value="<?php echo $ligne_select['mot_cle_3']; ?>">
              </div>
              <div class="col-md-4 text-left">
                <label for="mot_cle_4">Mot clé 1:</label>
                <input type="text" name="mot_cle_4" id="mot_cle_4" class="form-control"
                  value="<?php echo $ligne_select['mot_cle_4']; ?>">
              </div>
              <div class="col-md-4 text-left">
                <label for="mot_cle_1">Mot clé 5:</label>
                <input type="text" name="mot_cle_5" id="mot_cle_5" class="form-control"
                  value="<?php echo $ligne_select['mot_cle_5']; ?>">
              </div>
            </div>
            <div class="col-md-4 text-left">
              <label for="valider"> </label> <br>
              <input type="submit" name="bouton" id="bouton" class="btn btn-primary" value="Valider">
            </div>
          </form>
  </div>
  <br /><br />

<?php

    }
    ////////////////////////////FORMULAIRE AJOUTER / MODIFIER

    /////////////////////////////////////////Si aucune action
    if (!isset($action)) {
?>

  <div id='liste'></div>

<?php
    }
    /////////////////////////////////////////Si aucune action

    echo "</div>";
  } else {
    header('location: /');
  }
  ob_end_flush();
?>