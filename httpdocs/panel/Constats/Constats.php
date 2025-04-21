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
        $.post({
          url: '/panel/Constats/Constats-action-ajouter-modifier-ajax.php',
          type: 'POST',
          <?php if ($_GET['action'] == "modifier") { ?>
                    data: new FormData($("#formulaire-modifier")[0]),
          <?php } else { ?>
                    data: new FormData($("#formulaire-ajouter")[0]),
          <?php } ?>
              processData: false,
          contentType: false,
          dataType: "json",
          success: function (res) {
            if (res.retour_validation == "ok") {
              popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
              <?php if ($_GET['action'] != "modifier") { ?>
                $("#formulaire-gestion-des-pages-ajouter")[0].reset();
              <?php } ?>
            } else {
              popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            }
            liste();
          }
        });
        $("html, body").animate({ scrollTop: 0 }, "slow");
      });

      //AJAX - SUPPRIMER
      $(document).on("click", ".lien-supprimer", function () {
        $.post({
          url: '/panel/Constats/Constats-action-supprimer-ajax.php',
          type: 'POST',
          data: {
            idaction: $(this).attr("data-id")
          },
          dataType: "json",
          success: function (res) {
            if (res.retour_validation == "ok") {
              popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
            } else {
              popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            }
            liste();
          }
        });
      });

      $(document).on("click", "#image-bouton", function () {
        //ON SOUMET LE TEXTAREA TINYMCE
        $.post({
          url: '/panel/Constats/Constats-action-images-ajax.php',
          type: 'POST',
          data: new FormData($("#formulaire-images")[0]),
          processData: false,
          contentType: false,
          dataType: "json",
          success: function (res) {
            if (res.retour_validation == "ok") {
              popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
                // Reload the page to reflect changes
                location.reload();
            } else {
              popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            }
          }
        });
      });


      //FUNCTION AJAX - LISTE
      function liste() {
        $.post({
          url: '/panel/Constats/Constats-liste-ajax.php',
          type: 'POST',
          dataType: "html",
          success: function (res) {
            $("#liste").html(res);
          }
        });
      }
      liste();

      $(document).on('click', '#btnSupprModal', function () {
        $.post({
          url: '/panel/Constats/modal-supprimer-ajax.php',
          type: 'POST',
          data: {
            idaction: $(this).attr("data-id")
          },
          dataType: "html",
          success: function (res) {
            $("body").append(res)
            $("#modalSuppr").modal('show')
          }
        })
      });

      $(document).on("click", "#btnSuppr", function () {
        // $(".modal").show();
        $.post({
          url: '/panel/Constats/Constats-action-supprimer-ajax.php',
          type: 'POST',
          data: {
            idaction: $(this).attr("data-id")
          },
          dataType: "json",
          success: function (res) {
            if (res.retour_validation == "ok") {
              popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
            } else {
              popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            }
            liste();
            $("#modalSuppr").modal('hide')
          }
        });
      });

      $(document).on("click", "#btnNon", function () {
        $("#modalSuppr").modal('hide')
      });

      $(document).on('hidden.bs.modal', "#modalSuppr", function () {
        $(this).remove()
      })
    });
  </script>

  <?php
  $current_page = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
  if ($current_page != 'Generate' && $action != "Images") {
    /* echo "<a href='/Constats/ajouter'><button type='button' class='btn btn-success' style='margin-right: 5px;' ><span class='uk-icon-plus-circle'></span> Ajouter</button></a>"; */
    echo "<a href='Constat-amiable-accident'><button type='button' class='btn btn-primary' style='margin-right: 5px;'><span class='uk-icon-plus-circle'></span>Ajouter</button></a>";
  }
  ?>
  <div style='clear: both;'></div><br />
  <div style='padding: 5px; text-align: center;'>

    <?php

    ////////////////////////////FORMULAIRE AJOUTER / MODIFIER
    if ($action == "ajouter" || $action == "modifier") {

      if ($action == "modifier") {

        ///////////////////////////////SELECT
        $req_select = $bdd->prepare("SELECT * FROM membres_constats WHERE id=?");
        $req_select->execute(array($idaction));
        $ligne_select = $req_select->fetch();
        $req_select->closeCursor();
        $idoneinfos = $ligne_select['id'];

        if ($ligne_select['statut_devis'] == "Traité") {
          $selectedstatut1 = "selected='selected'";
        } elseif ($ligne_select['statut_devis'] == "Non traité") {
          $selectedstatut2 = "selected='selected'";
        } elseif ($ligne_select['statut_devis'] == "Accepté") {
          $selectedstatut3 = "selected='selected'";
        }

        ///////////////////////////////SELECT
        $req_select_constat = $bdd->prepare("SELECT * FROM membres WHERE id=?");
        $req_select_constat->execute(array($ligne_select['id_membre_constat']));
        $ligne_select_constat = $req_select_constat->fetch();
        $req_select_constat->closeCursor();

        ///////////////////////////////SELECT
        $req_select_constat_jumele = $bdd->prepare("SELECT * FROM membres WHERE id=?");
        $req_select_constat_jumele->execute(array($ligne_select['id_membre_jumelage']));
        $ligne_select_constat_jumele = $req_select_constat_jumele->fetch();
        $req_select_constat_jumele->closeCursor();
        $idoneinfos = $ligne_select_depanneur['id'];

        ?>

        <div align='left'>
          <h2>Modifier</h2>
        </div><br />
        <div style='clear: both;'></div>

        <form id='formulaire-modifier' method="post"
          action="?page=Constats&amp;action=modifier-action&amp;idaction=<?php echo "$idaction"; ?>">
          <input id="action" type="hidden" name="action" value="modifier-action">
          <input id="idaction" type="hidden" name="idaction" value="<?php echo $_GET['idaction']; ?>">

          <?php
      } else {
        ?>

          <div align='left'>
            <h2>Ajouter</h2>
          </div><br />
          <div style='clear: both;'></div>

          <form id='formulaire-ajouter' method="post"
            action="?page=Constats&amp;action=ajouter-action&amp;idaction=<?php echo "$idaction"; ?>">
            <input id="action" type="hidden" name="action" value="ajouter-action">

            <?php
      }
      ?>

          <table style="text-align: left; width: 100%; text-align: center;" cellpadding="2" cellspacing="2">
            <tbody>

              <tr>
                <td colspan="2">
                  <h2>Utilisateur</h2>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Nom &amp; prénom</td>
                <td style="text-align: left;"> <?php echo $ligne_select_constat['prenom']; ?>
                  <?php echo $ligne_select_constat['nom']; ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Adresse &amp; ville</td>
                <td style="text-align: left;"> <?php echo $ligne_select_constat['adresse']; ?>
                  <?php echo $ligne_select_constat['ville']; ?>     <?php echo $ligne_select_constat['cp']; ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Coordonnées</td>
                <td style="text-align: left;"> <?php echo $ligne_select_constat['mail']; ?>
                  <?php echo $ligne_select_constat['Telephone_portable']; ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>

              <?php if (!empty($ligne_select['id_membre_jumelage'])) { ?>

                <tr>
                  <td colspan="2">
                    <h2>Utilisateur jumerlé</h2>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td style="text-align: left; width: 190px;">Date jumelage</td>
                  <td style="text-align: left;">
                    <?php if (!empty($ligne_select['date_jumelage'])) {
                      echo date('d-m-Y', $ligne_select['date_jumelage']);
                    } else {
                      echo "--";
                    } ?>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td style="text-align: left; width: 190px;">Nom &amp; prénom</td>
                  <td style="text-align: left;"> <?php echo $ligne_select_constat_jumele['prenom']; ?>
                    <?php echo $ligne_select_constat_jumele['nom']; ?>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td style="text-align: left; width: 190px;">Adresse &amp; ville</td>
                  <td style="text-align: left;"> <?php echo $ligne_select_constat_jumele['adresse']; ?>
                    <?php echo $ligne_select_constat_jumele['ville']; ?>       <?php echo $ligne_select_constat_jumele['cp']; ?>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td style="text-align: left; width: 190px;">Coordonnées</td>
                  <td style="text-align: left;"> <?php echo $ligne_select_constat_jumele['mail']; ?>
                    <?php echo $ligne_select_constat_jumele['Telephone_portable']; ?>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>

              <?php } ?>

              <tr>
                <td colspan="2">
                  <h2>Constats</h2>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Date du constat</td>
                <td style="text-align: left;">
                  <?php if (!empty($ligne_select['date_constat'])) {
                    echo date('d-m-Y', $ligne_select['date_constat']);
                  } else {
                    echo "--";
                  } ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Visualiser le constat</td>
                <td style="text-align: left;">
                  <a href="" class="btn btn-danger" target="blank_"> <?php echo "Visualiser le constat"; ?></a>
                </td>
              </tr>

              <?php if (!empty($ligne_select['id_membre_jumelage'])) { ?>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td style="text-align: left; width: 190px;">Visualiser le constat opposant</td>
                  <td style="text-align: left;">
                    <a href="" class="btn btn-danger" target="blank_"> <?php echo "Constat opposant"; ?></a>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td style="text-align: left; width: 190px;">Visualiser le constat jumelé</td>
                  <td style="text-align: left;">
                    <a href="" class="btn btn-danger" target="blank_"> <?php echo "Constat jumelé"; ?></a>
                  </td>
                </tr>
              <?php } ?>

            </tbody>
          </table>

          <table style="text-align: left; width: 100%; text-align: center;" border="0" cellpadding="2" cellspacing="2">
            <tbody>
              <tr>
                <td colspan="2" style="text-align: center;">
                  <button id='bouton' type='button' class='btn btn-success' onclick="return false;"
                    style='width: 150px;'>ENREGISTRER</button>
                </td>
              </tr>
            </tbody>
          </table>

        </form>
    </div><br /><br />
    <br /><br />

    <?php
    }elseif($action == "Images"){

      ?>

      <h1>Images</h1>

        <div>
          <form id="formulaire-images" enctype="multipart/form-data" style="display: flex; flex-direction: column; align-items: flex-start;">
            <label for="imageUpload">Ajouter une image :</label>
            <input type="hidden" name="idaction" value="<?php echo $idaction; ?>">
            <input type="file" id="image" name="image" accept="image/*" style="margin-bottom: 10px;">
            <button onclick="return false;" id="image-bouton" class="btn btn-primary">Enregistrer</button>
          </form>
        </div>

        <div class="container mt-4">
          <h3></h3>
          <div class="row">
          <?php
          $req_images = $bdd->prepare("SELECT * FROM constats_images WHERE id_constat = ?");
          $req_images->execute(array($idaction));

          while ($image = $req_images->fetch()) {
            echo "<div class='col-md-6 mb-3'><img src='/images/constats/{$image['id_constat']}/{$image['img']}' alt='Image' class='img-fluid'></div>";
          }

          $req_images->closeCursor();
          ?>
          </div>
        </div>

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
?>