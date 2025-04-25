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

if (!empty($user)) { //$_SESSION['4M8e7M5b1R2e8s']) &&

  $id_liaison = $_GET['id_liaison'];
  $action = $_GET['action'];
  $idaction = $_GET['idaction'];
  $now = time();

  ?>

  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="row">

            <?php
            include('panel/Avatar/Avatar-profil-photo-recadrage.php');
            ?>

          </div>
        </div>
      </div>
    </div>
  </div>

  <?php

} else {
  header('location: /');
}
?>