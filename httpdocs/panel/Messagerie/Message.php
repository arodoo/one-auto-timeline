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
?>

<div class="contact-form-wrapper background-white p30">

  <?php

  if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {

    $now = time();
    $action = $_GET['action'];
    $actionn = $_GET['actionn'];
    $idaction = $_GET['idaction'];

    //On créer le dossier du membre si il n'existe pas
    if (!file_exists("" . $_SERVER['DOCUMENT_ROOT'] . "/images/membres/$user")) {
      mkdir("" . $_SERVER['DOCUMENT_ROOT'] . "/images/membres/$user");
    }

    ?>

    <script>
      $(document).ready(function () {

        //On, actualise la liste des messages
        ListeDesMessages();

        //AJAX SOUMISSION DU FORMULAIRE
        $(document).on("click", "#message_post_submit", function () {

          $.post({
            url: '/panel/Messagerie/Message-formulaire-ajax.php',
            type: 'POST',
            data: new FormData($("#message-form")[0]),
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (res) {
              if (res.retour_validation == "ok-ouverture" || res.retour_validation == "ok-reponse") {
                popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
                $('#message_prive_post_objet').val("");
                $('#message_prive_post').val("");
                $('#icon1').val("");
                if (res.retour_validation == "ok-ouverture") {
                  setTimeout(function () {
                    $(location).attr("href", res.retour_lien);
                  }, 50);
                }
              } else {
                popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
              }
              //On, actualise la liste des messages
              ListeDesMessages();
            }
          });
        });

        function ListeDesMessages() {
          $.post({
            url: '/panel/Messagerie/Message-liste-ajax.php',
            type: 'GET',
            data: {
              idaction: "<?php echo $_GET['idaction']; ?>",
              actionn: "<?php echo $_GET['actionn']; ?>",
            },
            dataType: "html",
            success: function (res) {
              $("#liste-des-messages").html(res);
            }
          });
        }

        <?php
        if (isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && $_GET['page'] != "Liste-des-messages") {
          ?>

          //AJAX - MESSAGE
          $(document).on("click", ".supprimer_message", function () {
            $.post({
              url: '/panel/Messagerie/Message-action-supprimer-ajax.php',
              type: 'POST',
              data: {
                idaction: $(this).attr("data-id"),
                table: "membres_messages",
                retour_lien: "Messagerie.html"
              },
              dataType: "json",
              success: function (res) {
                if (res.retour_validation == "ok") {
                  popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
                } else {
                  popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                }
                ListeDesMessages();
                $(location).attr("href", res.retour_lien);
              }
            });
          });

          //AJAX - SUPPRIMER MESSAGE REPONSE
          $(document).on("click", ".supprimer_commentaire", function () {
            $.post({
              url: '/panel/Messagerie/Message-action-supprimer-ajax.php',
              type: 'POST',
              data: {
                idaction: $(this).attr("data-id"),
                table: "membres_messages_reponse",
              },
              dataType: "json",
              success: function (res) {
                if (res.retour_validation == "ok") {
                  popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
                } else {
                  popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                }
                ListeDesMessages();
              }
            });
          });

          //AJAX - SUPPRIMER IMAGE
          $(document).on("click", ".supprimer_image", function () {
            $.post({
              url: '/panel/Messagerie/Message-action-supprimer-image-ajax.php',
              type: 'POST',
              data: {
                idaction: $(this).attr("data-id"),
                table: $(this).attr("data-table")
              },
              dataType: "json",
              success: function (res) {
                $("#fichier_message_table").css("display", "none");
                if (res.retour_validation == "ok") {
                  popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
                } else {
                  popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                }
                ListeDesMessages();
              }
            });
          });

          <?php
        }
        ?>

      });
    </script>

    <?php

    /////////////////////////////////////////MESSAGERIE MENU
    include('panel/Messagerie/Messagerie-menu.php');
    /////////////////////////////////////////MESSAGERIE MENU
  
    ///////////////////////////////////////////////////////////////////////////////Formulaire
    ?>

    <?php
    if ($actionn != "formulaire") {
      ?>
      <div id='liste-des-messages'></div>
      <?php
    }
    ?>

    <div id='formulaire'></div>

    <?php
    if ($actionn != "formulaire") {
      ?>
      <form id='message-form' method='post' action='#' enctype='multipart/form-data'>
        <input type='hidden' id='actionn' name='actionn' value='reponse' class='form-control' style='width: 100%;' />
        <?php
    } else {
      ?>
        <form id='message-form' method='post' action='#' enctype='multipart/form-data'>
          <input type='hidden' id='actionn' name='actionn' value='creation' class='form-control' style='width: 100%;' />
          <?php
    }
    if ($actionn == "formulaire") {
      ?>
          <div style='margin-top: 20px;'><span class='uk-icon-clipboard'></span> <?php echo "L'objet du message"; ?></div>
          <input type='text' id='message_prive_post_objet' name='message_prive_post_objet' class='form-control'
            style='width: 100%;' value="<?php echo $_SESSION['titre_projet']; ?>" /><br />
          <?php
    }
    ?>

        <input type='hidden' id='idaction' name='idaction' value='<?php echo "$idaction"; ?>' class='form-control'
          style='width: 100%;' />

        <div style=' margin-top: 20px;'><span class='uk-icon-comments-o'></span> <?php echo "Votre message"; ?></div>
        <textarea name='message_prive_post' id='message_prive_post' class='form-control'
          style='width: 100%; height: 150px;'></textarea>

        <div style=' margin-top: 20px;'><span class='uk-icon-download'></span> <?php echo "Joindre un fichier"; ?></div>
        <input type='file' class='form-control' id='icon1' name='icon1' style='max-width: 280px;' /><br />
        <span
          style='font-size: 14px;'><?php echo "<b>Les extensions autorisées sont</b> : .jpg, .gif, .png, .txt, .doc, .ppt, .xls, .pdf, .odt, .zip, .rar, .ace, .gz, .docx, .xlsx, .pptx."; ?><br />
          <b><?php echo "Taille maximum"; ?> :</b> <?php echo "2Mo"; ?>.</span><br />

        <div class="text-center">
          <button type='button' id='message_post_submit' class='btn btn-success' onclick='return false;'
            style='margin: 20px 0; width: 200px;'>ENVOYER</button>
        </div>
      </form>

      <?php
      ///////////////////////////////////////////////////////////////////////////////Formulaire
      ?>


      <?php
  } else {
    header("location: /");
  }
  ?>

</div>