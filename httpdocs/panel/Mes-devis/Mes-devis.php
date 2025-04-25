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


  function lettre_sans_accent($chaine)
  {
    $normalized = \Normalizer::normalize($chaine, \Normalizer::FORM_D);
    $sans_accent = preg_replace('/[\p{Mn}]/u', '', $normalized);
    return $sans_accent;
  }
?>
  <link rel="stylesheet" type="text/css" href="/panel/Mes-devis/mes-devis.css">
  <script>
    $(document).ready(function() {

      // Function to get URL parameter
      function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? null : decodeURIComponent(results[1].replace(/\+/g, ' '));
      }

      // Get 'id' parameter from URL
      const id = getUrlParameter('id');

      //AJAX SOUMISSION DU FORMULAIRE - MODIFIER - AJOUTER
      $(document).on("click", "#bouton", function() {
        //ON SOUMET LE TEXTAREA TINYMCE
        tinyMCE.triggerSave();
        $.post({
          url: '/panel/Mes-devis/Mes-devis-action-ajouter-modifier-ajax.php',
          type: 'POST',
          <?php if ($_GET['action'] == "modifier") { ?>
            data: new FormData($("#formulaire-modifier")[0]),
          <?php } else { ?>
            data: new FormData($("#formulaire-ajouter")[0]),
          <?php } ?>
          processData: false,
          contentType: false,
          dataType: "json",
          success: function(res) {
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
        $("html, body").animate({
          scrollTop: 0
        }, "slow");
      });

      //AJAX - SUPPRIMER
      $(document).on("click", ".lien-supprimer", function() {
        $.post({
          url: '/panel/Mes-devis/Mes-devis-action-supprimer-ajax.php',
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
          url: '/panel/Mes-devis/Mes-devis-liste-ajax.php',
          type: 'POST',
          data: {
            id: id
          },
          dataType: "html",
          success: function(res) {
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
        $req_select = $bdd->prepare("SELECT * FROM membres_devis WHERE id=? AND id_membre_utilisateur=?");
        $req_select->execute(array($idaction, $id_oo));
        $ligne_select = $req_select->fetch();
        $req_select->closeCursor();
        $idoneinfos = $ligne_select['id'];


        $id_membre_utilisateur = $ligne_select['id_membre_utilisateur'];
        $id_membre_depanneur = $ligne_select['id_membre_depanneur'];
        $membres_annonces_clients_id = $ligne_select['membres_annonces_clients_id'];
        $type = $ligne_select['type'];


        if ($type == 'annonce') {
          $req_annonces = $bdd->prepare("SELECT * FROM membres_annonces WHERE id=?");
          $req_annonces->execute(array($membres_annonces_clients_id));
          $ligne_annonces = $req_annonces->fetch();
          $req_annonces->closeCursor();
          $titre = "Titre du annonce : ";
          $nom = $ligne_annonces['nom'];
          $cleanTitle = strtolower(explode(' ', lettre_sans_accent($nom))[0]);
          $url = "/Page-annonce/{$cleanTitle}/{$membres_annonces_clients_id}";
        } elseif ($type == 'service') {
          $req_services = $bdd->prepare("SELECT * FROM membres_services WHERE id=?");
          $req_services->execute(array($membres_annonces_clients_id));
          $ligne_services = $req_services->fetch();
          $req_services->closeCursor();
          $titre = "Titre du service : ";
          $nom = $ligne_services['nom'];
          $cleanTitle = strtolower(explode(' ', lettre_sans_accent($nom))[0]);
          $url = "/Page-service/{$cleanTitle}/{$membres_annonces_clients_id}";
        }

        /*         var_dump($id_membre_utilisateur, $id_membre_depanneur, $membres_annonces_clients_id, $type, $ligne_annonces ?? null, $ligne_services ?? null); */

        if ($ligne_select['statut_devis'] == "Traité") {
          $selectedstatut1 = "selected='selected'";
        } elseif ($ligne_select['statut_devis'] == "Non traité") {
          $selectedstatut2 = "selected='selected'";
        } elseif ($ligne_select['statut_devis'] == "Accepté") {
          $selectedstatut3 = "selected='selected'";
        } elseif ($ligne_select['statut_devis'] == "Refusé") {
          $selectedstatut3 = "selected='selected'";
        }

        ///////////////////////////////SELECT
        $req_select_utilisateur = $bdd->prepare("SELECT * FROM membres WHERE id=?");
        $req_select_utilisateur->execute(array($ligne_select['id_membre_utilisateur']));
        $ligne_select_utilisateur = $req_select_utilisateur->fetch();
        $req_select_utilisateur->closeCursor();

        ///////////////////////////////SELECT
        $req_select_depanneur = $bdd->prepare("SELECT * FROM membres WHERE id=?");
        $req_select_depanneur->execute(array($ligne_select['id_membre_depanneur']));
        $ligne_select_depanneur = $req_select_depanneur->fetch();
        $req_select_depanneur->closeCursor();
        $idoneinfos = $ligne_select_depanneur['id'];

        /*  var_dump($ligne_select_depanneur); */
    ?>

        <div align='left'>
          <h2>Visualiser</h2>
        </div><br />
        <div style='clear: both;'></div>


        <?php if ($type == 'annonce' || $type == 'service') { ?>
          <div align='left'>
            <h3><?php echo $titre; ?> <?php echo $nom; ?></h3>
            <a href="<?php echo $url; ?>" target="_blank">Voir les détails</a>
          </div>
          <br />
        <?php } ?>


        <form id='formulaire-modifier' method="post" action="?page=Mes-devis&amp;action=modifier-action&amp;idaction=<?php echo "$idaction"; ?>">
          <input id="action" type="hidden" name="action" value="modifier-action">
          <input id="idaction" type="hidden" name="idaction" value="<?php echo $_GET['idaction']; ?>">

        <?php
      } else {
        ?>

          <div align='left'>
            <h2>Ajouter</h2>
          </div><br />
          <div style='clear: both;'></div>

          <form id='formulaire-ajouter' method="post" action="?page=Mes-devis&amp;action=ajouter-action&amp;idaction=<?php echo "$idaction"; ?>">
            <input id="action" type="hidden" name="action" value="ajouter-action">

          <?php
        }
          ?>

          <table style="text-align: left; width: 100%; text-align: left;" cellpadding="2" cellspacing="2">
            <tbody>


              <?php ?>
              <tr>
                <td colspan="2">

                  <?php if ($type == 'annonce' || $type == 'service') { ?>
                    <h2>
                      Le professionnel correspondant
                    </h2>

                  <?php } else { ?>
                    <h2>Dépanneur</h2>
                  <?php }  ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Nom &amp; prénom</td>
                <td style="text-align: left;"> <?php echo $ligne_select_depanneur['prenom']; ?> <?php echo $ligne_select_depanneur['nom']; ?> </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>

              <tr>
                <td style="text-align: left; width: 190px;">Adresse &amp; ville</td>
                <td style="text-align: left;"> <?php echo $ligne_select_depanneur['adresse']; ?> <?php echo $ligne_select_depanneur['ville']; ?> <?php echo $ligne_select_depanneur['cp']; ?> </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>

              <tr>
                <td style="text-align: left; width: 190px;">Coordonnées</td>
                <td style="text-align: left;"> <?php echo $ligne_select_depanneur['mail']; ?> <?php echo $ligne_select_depanneur['Telephone_portable']; ?> </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>

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
                <td style="text-align: left;"><?php if (!empty($ligne_select['date_demande'])) {
                                                echo date('d-m-Y', $ligne_select['date_demande']);
                                              } else {
                                                echo "--";
                                              } ?> </td>
              </tr>

              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left;" colspan="2">Objet</td>
              </tr>
              <tr>
                <td style="text-align: left;"><?php echo $ligne_select['objet_de_la_demande']; ?> </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left;" colspan="2">Description de la demande</td>
              </tr>
              <tr>
                <td style="text-align: left;"><?php echo $ligne_select['description_de_la_demande']; ?> </td>
              </tr>

              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Date statut</td>
                <td style="text-align: left;"><?php if (!empty($ligne_select['date_statut'])) {
                                                echo date('d-m-Y', $ligne_select['date_statut']);
                                              } else {
                                                echo "--";
                                              } ?> </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Télécharger le devis</td>
                <td style="text-align: left;">
                  <?php if (!empty($ligne_select['lien_devis'])) { ?>
                    <a href="<?php echo $ligne_select['lien_devis']; ?>" class="btn-danger" target="blank_"> <?php echo "Devis"; ?></a>
                  <?php } else { ?>
                    Aucun lien disponible
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td style="text-align: left; width: 190px;">Statut du devis</td>
                <td style="text-align: left;">
                  <?php if ($ligne_select['statut_devis'] == "Traité") { ?>
                    <select name="statut_devis" class="form-control">
                      <option <?php echo ""; ?> value=''> Sélection &nbsp; &nbsp;</option>
                      <option <?php echo "$selectedstatut3"; ?> value='Accepté'> Accepté &nbsp; &nbsp;</option>
                      <option <?php echo "$selectedstatut4"; ?> value='Refusé'> Ne pas accepter &nbsp; &nbsp;</option>
                    </select>
                  <?php } elseif ($ligne_select['statut_devis'] == "Accepté") { ?>
                    <span class="label label-success">Devis accepté par vous</span>
                  <?php } elseif ($ligne_select['statut_devis'] == "Refusé") { ?>
                    <span class="label label-danger">Devis refusé par vous</span>
                  <?php } else { ?>
                    <span class="label label-danger">En attente de traitement</span>
                  <?php } ?>

                </td>
              </tr>

            </tbody>
          </table>

          <?php if ($ligne_select['statut_devis'] == "Traité") { ?>

            <table style="text-align: left; width: 100%; text-align: center;" border="0" cellpadding="2" cellspacing="2">
              <tbody>
                <tr>
                  <td colspan="2" style="text-align: center;">
                    <button id='bouton' type='button' class='btn btn-success' onclick="return false;" style='width: 150px;'>ENREGISTRER</button>
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