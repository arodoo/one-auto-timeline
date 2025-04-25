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

      //FUNCTION AJAX - LISTE
      function liste() {
        $.post({
          url: '/panel/Vendeurs/Mes-paiements/Mes-paiements-liste-ajax.php',
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