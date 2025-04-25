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

  <link rel="stylesheet" type="text/css" href="/panel/Devis/devis.css">

  <script>
    $(document).ready(function () {

      //AJAX SOUMISSION DU FORMULAIRE - MODIFIER - AJOUTER
      $(document).on("click", "#bouton", function () {
        //ON SOUMET LE TEXTAREA TINYMCE
        tinyMCE.triggerSave();
        $.post({
          url: '/panel/Devis/Devis-action-ajouter-modifier-ajax.php',
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
            console.log("Réponse du serveur:", res); // Log de la réponse du serveur
            if (res && res.retour_validation == "ok") {
              popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
              <?php if ($_GET['action'] != "modifier") { ?>
                $("#formulaire-gestion-des-pages-ajouter")[0].reset();
              <?php } ?>
            } else {
              popup_alert(res ? res.Texte_rapport : "Erreur inconnue", "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            }
            liste();
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error("Erreur AJAX: ", textStatus, errorThrown);
            popup_alert("Erreur de communication avec le serveur", "#CC0000 filledlight", "#CC0000", "uk-icon-times");
          }
        });
        $("html, body").animate({ scrollTop: 0 }, "slow");
      });

      //AJAX - SUPPRIMER
      $(document).on("click", ".lien-supprimer", function () {
        $.post({
          url: '/panel/Devis/Devis-action-supprimer-ajax.php',
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

      //FUNCTION AJAX - LISTE
      function liste() {
        $.post({
          url: '/panel/Devis/Devis-liste-ajax.php',
          type: 'POST',
          dataType: "html",
          success: function (res) {
            $("#liste").html(res);
          }
        });
      }
      liste();

    });
  </script>

  <div style='padding: 5px; text-align: center;'>

    <?php

    ////////////////////////////FORMULAIRE AJOUTER / MODIFIER
    if ($action == "ajouter" || $action == "modifier") {

      if ($action == "modifier") {

        ///////////////////////////////SELECT
        $req_select = $bdd->prepare("SELECT * FROM membres_devis WHERE id=? AND id_membre_depanneur=?");
        $req_select->execute(array($idaction, $id_oo));
        $ligne_select = $req_select->fetch();
        $req_select->closeCursor();
        $idoneinfos = $ligne_select['id'];

        if ($ligne_select['statut_devis'] == "Traité") {
          $selectedstatut1 = "selected='selected'";
        } elseif ($ligne_select['statut_devis'] == "Non traité") {
          $selectedstatut2 = "selected='selected'";
        } elseif ($ligne_select['statut_devis'] == "Accepté") {
          $selectedstatut3 = "selected='selected'";
        } elseif ($ligne_select['statut_devis'] == "Refusé") {
          $selectedstatut4 = "selected='selected'";
        }

        ///////////////////////////////SELECT
        $req_select_utilisateur = $bdd->prepare("SELECT * FROM membres WHERE id=?");
        $req_select_utilisateur->execute(array($ligne_select['id_membre_utilisateur']));
        $ligne_select_utilisateur = $req_select_utilisateur->fetch();
        $req_select_utilisateur->closeCursor();

        //getting the user's auto profile
        $req_select_auto = $bdd->prepare("SELECT * FROM membres_profil_auto WHERE id_membre=?");
        $req_select_auto->execute(array($ligne_select['id_membre_utilisateur']));
        $ligne_select_auto = $req_select_auto->fetch();
        $req_select_auto->closeCursor();

        ?>

        <div align='left'>
          <h2>Modifier &mp; Visualiser</h2>
        </div><br />
        <div style='clear: both;'></div>

        <form id='formulaire-modifier' method="post"
          action="?page=Devis&amp;action=modifier-action&amp;idaction=<?php echo "$idaction"; ?>">
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
            action="?page=Devis&amp;action=ajouter-action&amp;idaction=<?php echo "$idaction"; ?>">
            <input id="action" type="hidden" name="action" value="ajouter-action">

            <?php
      }
      ?>

          <table style="text-align: left; width: 100%; text-align: left;" cellpadding="2" cellspacing="2">
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
                <td style="text-align: left;"> <?php echo $ligne_select_utilisateur['prenom']; ?>
                  <?php echo $ligne_select_utilisateur['nom']; ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Adresse &amp; ville</td>
                <td style="text-align: left;"> <?php echo $ligne_select_utilisateur['adresse']; ?>
                  <?php echo $ligne_select_utilisateur['ville']; ?>     <?php echo $ligne_select_utilisateur['cp']; ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Coordonnées</td>
                <td style="text-align: left;"> <?php echo $ligne_select_utilisateur['mail']; ?>
                  <?php echo $ligne_select_utilisateur['Telephone_portable']; ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>

              <!--Starts devis info-->
              <tr>
                <td colspan="2">
                  <h2>Devis</h2>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Date de la demande</td>
                <td style="text-align: left;">
                  <?php
                  if (!empty($ligne_select['date_demande'])) {
                    $timestamp = intval($ligne_select['date_demande']);
                    echo date('d-m-Y', $timestamp);
                  } else {
                    echo "--";
                  }
                  ?>
                </td>
              </tr>

              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Objet</td>
                <td style="text-align: left;"><?php echo $ligne_select['objet_de_la_demande']; ?> </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Description de la demande</td>
                <td style="text-align: left;"><?php echo $ligne_select['description_de_la_demande']; ?> </td>
              </tr>

              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Date statut</td>
                <td style="text-align: left;">
                  <?php
                  if (!empty($ligne_select['date_statut'])) {
                    $timestamp = intval($ligne_select['date_statut']);
                    echo date('d-m-Y', $timestamp);
                  } else {
                    echo "--";
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <?php if ($statut_compte_oo == 2) { ?>
                <!-- auto information starts-->
                <tr>
                  <td colspan="6">
                    <h2>Informations sur l'auto</h2>
                  </td>
                </tr>
                <tr>
                  <td colspan="6">&nbsp;</td>
                </tr>
                <tr class="auto-info-table informations-auto">
                  <td class="desktop-label" style="text-align: left; width: 16.66%;">Marque</td>
                  <td data-label="Marque" style="text-align: left; width: 16.66%;"><span><?php echo !empty($ligne_select_auto['marque']) ? $ligne_select_auto['marque'] : 'NA'; ?></span></td>
                  <td class="desktop-label" style="text-align: left; width: 16.66%;">Modèle</td>
                  <td data-label="Modèle" style="text-align: left; width: 16.66%;"><span><?php echo !empty($ligne_select_auto['model']) ? $ligne_select_auto['model'] : 'NA'; ?></span></td>
                  <td class="desktop-label" style="text-align: left; width: 16.66%;">Année de mise en circulation</td>
                  <td data-label="Année de mise en circulation" style="text-align: left; width: 16.66%;"><span><?php echo !empty($ligne_select_auto['annee_mise_en_circulation']) ? $ligne_select_auto['annee_mise_en_circulation'] : 'NA'; ?></span></td>
                </tr>
                <tr class="auto-info-table informations-auto">
                  <td class="desktop-label" style="text-align: left; width: 16.66%;">Couleur</td>
                  <td data-label="Couleur" style="text-align: left; width: 16.66%;"><span><?php echo !empty($ligne_select_auto['couleur']) ? $ligne_select_auto['couleur'] : 'NA'; ?></span></td>
                  <td class="desktop-label" style="text-align: left; width: 16.66%;">Type de carburant</td>
                  <td data-label="Type de carburant" style="text-align: left; width: 16.66%;"><span><?php echo !empty($ligne_select_auto['energieNGC']) ? $ligne_select_auto['energieNGC'] : 'NA'; ?></span></td>
                  <td class="desktop-label" style="text-align: left; width: 16.66%;">Type de boîte de vitesses</td>
                  <td data-label="Type de boîte de vitesses" style="text-align: left; width: 16.66%;"><span><?php echo !empty($ligne_select_auto['boite']) ? $ligne_select_auto['boite'] : 'NA'; ?></span></td>
                </tr>
                <tr class="auto-info-table informations-auto">
                  <td class="desktop-label" style="text-align: left; width: 16.66%;">Puissance fiscale</td>
                  <td data-label="Puissance fiscale" style="text-align: left; width: 16.66%;"><span><?php echo !empty($ligne_select_auto['puisFisc']) ? $ligne_select_auto['puisFisc'] : 'NA'; ?></span></td>
                  <td class="desktop-label" style="text-align: left; width: 16.66%;">Nombre de passagers</td>
                  <td data-label="Nombre de passagers" style="text-align: left; width: 16.66%;"><span><?php echo !empty($ligne_select_auto['nr_passagers']) ? $ligne_select_auto['nr_passagers'] : 'NA'; ?></span></td>
                  <td class="desktop-label" style="text-align: left; width: 16.66%;">Nombre de portes</td>
                  <td data-label="Nombre de portes" style="text-align: left; width: 16.66%;"><span><?php echo !empty($ligne_select_auto['nb_portes']) ? $ligne_select_auto['nb_portes'] : 'NA'; ?></span></td>
                </tr>
                <tr class="auto-info-table informations-auto">
                  <td class="desktop-label" style="text-align: left; width: 16.66%;">Poids</td>
                  <td data-label="Poids" style="text-align: left; width: 16.66%;"><span><?php echo !empty($ligne_select_auto['poids']) ? $ligne_select_auto['poids'] : 'NA'; ?></span></td>
                  <td class="desktop-label" style="text-align: left; width: 16.66%;">Nombre de cylindres</td>
                  <td data-label="Nombre de cylindres" style="text-align: left; width: 16.66%;"><span><?php echo !empty($ligne_select_auto['cylindres']) ? $ligne_select_auto['cylindres'] : 'NA'; ?></span></td>
                </tr>
                <tr>
                  <td colspan="6">&nbsp;</td>
                </tr>
              <?php } ?>
              <!-- auto information ends-->

              <tr>
                <td style="text-align: left; width: 190px;">Statut</td>
                <td style="text-align: left;">
                  <?php if ($ligne_select['statut_devis'] != "Accepté" && $ligne_select['statut_devis'] != "Refusé") { ?>
                    <select name="statut_devis" class="form-control">
                      <option <?php echo "$selectedstatut1"; ?> value='Traité'> Traité &nbsp; &nbsp;</option>
                      <option <?php echo "$selectedstatut2"; ?> value='Non traité'> Non traité &nbsp; &nbsp;</option>
                      <option <?php echo "$selectedstatut3"; ?> value='Accepté'> Accepté &nbsp; &nbsp;</option>
                      <option <?php echo "$selectedstatut4"; ?> value='Refusé'> Refusé &nbsp; &nbsp;</option>
                    </select>
                  <?php } else { ?>
                    <?php if ($ligne_select['statut_devis'] == "Accepté") {
                      echo "<span class='label label-success' >Accepté</span>";
                    } elseif ($ligne_select['statut_devis'] == "Refusé") {
                      echo "<span class='label label-danger' >Rrefusé</span>";
                    } ?>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">
                  <!-- Télécharger le devis -->
                </td>
                <td class="download-td" style="text-align: right; right: 0; position: absolute; margin-right: 10px;">
                  <?php if (!empty($ligne_select['lien_devis'])) { ?>
                    <a href="<?php echo $ligne_select['lien_devis']; ?>" style="font-weight: bold; color: red; margin: 0px 0px 2px 8px;" target="blank_">
                      <?php echo "Visualiser le devis"; ?></a>
                  <?php } else { ?>
                   <!--  <span style="font-weight: bold; color: red; margin: 0px 0px 2px 8px;">Aucun lien disponible</span> -->
                  <?php } ?>
                </td>
              </tr>
              <?php if ($ligne_select['statut_devis'] != "Accepté" && $ligne_select['statut_devis'] != "Refusé") { ?>
                <tr>
                  <td style="text-align: left;" colspan="2">
                    <div class="d-flex">
                      <div class="col-12">
                        <input type='file' id="lien_devis" name="lien_devis" class="form-control" value="<?php echo ""; ?>"
                          style='width: 100%;' accept=".pdf, .jpeg, .jpg, .png" />
                        <span
                          style='font-size: 12px;'><?php echo "<b>Les extensions autorisées sont</b> : .pdf, .jpeg, .jpg, .png"; ?><br /><br />
                          <?php if (!empty($lien_devis)) { ?>
                            <a class="btn btn-default"
                              href="../../images/membres/<?php echo $user; ?>/<?php echo $lien_devis; ?>" target="_blank"
                              style="padding: 1px 30px !important; background-color: #4bca6f !important; border-color: #4bca6f !important;">Carte
                              d'identité recto</a>
                          <?php } ?>
                      </div>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>

          <?php if ($ligne_select['statut_devis'] != "Accepté" && $ligne_select['statut_devis'] != "Refusé") { ?>

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

          <?php } ?>

        </form>
    </div><br /><br />
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
?>