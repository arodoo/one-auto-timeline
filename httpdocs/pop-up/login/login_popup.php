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
?>


<script>
  $(document).ready(function () {

    //AJAX SOUMISSION DU FORMULAIRE
    $(document).on("click", "#login_post", function () {
      $.post({
        url: "<?php echo "/pop-up/login/login_popup-ajax.php"; ?>",
        type: 'POST',
        data: {
          <?php
          ////////////Type de connexion utilisateur ou administrateur 
          if ($_GET['a'] == "admin") {
            ?>
                admin: "admin",
            <?php
          }
          ////////////Type de connexion utilisateur ou administrateur 
          ?>
          login: $('#login').val(),
          password: $('#password_login').val(),
          remember_me: $('#customCheckBox1').is(':checked') ? '1' : '0',
          login_post: $('#login_post').val()
        },
        dataType: "json",
        success: function (res) {
          if (res.retour_validation == "Ok") {
            <?php if (!empty($_SERVER["HTTP_REFERER"]) && empty($_GET['a']) && strpos($_SERVER["HTTP_REFERER"], "Mot-de-passe") == false) { ?>
              $(location).attr("href", "");
            <?php } else { ?>
              $(location).attr("href", "");
            <?php } ?>
          } else {
            $('#retour_login').html("<div class='alert alert-danger' role='alert' style='text-align: left;' >" + res.Texte_rapport + "</div>");
          }
        }
      });

    });

  });
</script>

<div class="modal fade" id="pxp-signin-modal" tabindex="-1" role="dialog" aria-labelledby="pxpSigninModal"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header" style="text-align: left;">
        <h2 class="modal-title style_color" style="float: left;">Identification</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div style="clear: both;"></div>
      </div>
      <div class="modal-body" style="text-align: left;">
        <div id='retour_login'></div>
        <form class="mt-4" method='post' action='#'>
          <div class="form-group">
            <label for="pxp-signin-email">Email</label>
            <input type="text" class="form-control" id="login" name="login" placeholder="Entrer l'adresse mail">
          </div>
          <div class="form-group">
            <label for="pxp-signin-pass">Mot de passe</label>
            <input type="password" class="form-control" id="password_login" name="password"
              placeholder="Enter le password">
          </div>
          <div class="mb-4">
            <div class="form-check custom-checkbox mb-3">
              <input type="checkbox" class="form-check-input" id="customCheckBox1" name="remember_me" required="">
              <label class="form-check-label" for="customCheckBox1">Se souvenir de moi</label>
            </div>
          </div>

          <div class="form-group" style="display: inline-block; width: 100%;">
            <a href="#" id='login_post' class="pxp-agent-contact-modal-btn btn btn-white w-space btn-default"
              onclick="return false;">S'identifier</a>
            <a href="#"
              class="pxp-modal-link btn btn-white btn-white-inscription w-space btn-default pxp-header-inscription"
              onclick="return false;">Pas inscrit ?</a>
          </div>

          <a href="/mot-de-passe-oublie" style="text-decoration: underline;">Mot de passe perdu ?</a>

          <div style="clear: both;"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
ob_end_flush();
?>