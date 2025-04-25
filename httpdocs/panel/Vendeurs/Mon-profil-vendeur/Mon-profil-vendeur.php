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

  $action = isset($_GET['action']) ? $_GET['action'] : '';
  $idaction = isset($_GET['idaction']) ? $_GET['idaction'] : '';

  // Get fresh profile data
  $req_select = $bdd->prepare("SELECT * FROM membres_profil_paiement WHERE id_membre = ? ");
  $req_select->execute(array($id_oo));
  $profile_data = $req_select->fetch();
  $req_select->closeCursor();
  $profil_complet = isset($profile_data['profil_complet']) ? $profile_data['profil_complet'] : 'non';
  $stripe_account_id = isset($profile_data['id_account']) ? $profile_data['id_account'] : '';
  
  error_log("Profile data - ID: $id_oo, Account: $stripe_account_id, Status: $profil_complet, Action: $action");
  
  // When returning from Stripe, ALWAYS update the status to completed
  if ($action == 'return' && !empty($stripe_account_id) && $profil_complet != 'oui') {
    $sql_update = $bdd->prepare("UPDATE membres_profil_paiement SET profil_complet = 'oui' WHERE id_membre = ?");
    $sql_update->execute([$id_oo]);
    $profil_complet = 'oui'; // Update local variable too
    error_log("Updated profile status to 'oui' from within Mon-profil-vendeur.php");
  }

  // Rest of your code remains the same...
?>

  <script>
    $(document).ready(function() {
      $(document).on('click', '#configurer_compte', function() {
        $.post({
          url: '/panel/Vendeurs/Mon-profil-vendeur/Mon-profil-vendeur-ajax.php',
          type: 'POST',
          dataType: 'json',
          success: function(res) {
            if (res.retour_validation === 'ok') {
              window.location.href = res.retour_lien;
            } else {
              popup_alert(res.Texte_rapport, "red filledlight", "#CC0000", "uk-icon-times");
            }
          },
          error: function(xhr, status, error) {
            popup_alert("Une erreur est survenue.. Veuillez réessayer.", "red filledlight", "#CC0000", "uk-icon-times");
          }
        });
      });

      $(document).on('click', '#dashboard_compte', function() {
        $.post({
          url: '/panel/Vendeurs/Mon-profil-vendeur/Mon-profil-vendeur-dashboard-ajax.php',
          type: 'POST',
          dataType: "json",
          success: function(res) {
            if (res.retour_validation == "ok") {
              //popup_alert(res.Texte_rapport,"green filledlight","#009900","uk-icon-check");
              $(location).attr("href", res.retour_lien);
            } else {
              popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            }
          }
        });
      });

    });
  </script>
  <div style='padding: 5px; text-align: center;'>

    <div style="margin-top: 80px;" class="text-center">
      <h2>Profil Paiement</h2>
    </div>
    <br>
    <?php if (empty($stripe_account_id)) { ?>
      <!-- No Stripe account exists yet -->
      <p>Veuillez configurer votre compte pour finaliser l'abonnement professionnel. Cette action permettra d'activer les paiements via Stripe et de garantir un processus sécurisé.</p>
      <div class="mt-3">
        <a class="btn btn-success btn-lg" href="#" id="configurer_compte" style="font-size: 14px;">Configurer le compte avec Stripe</a>
      </div>
    <?php } elseif ($profil_complet != 'oui') { ?>
      <!-- Stripe account exists but onboarding is incomplete -->
      <div class="alert alert-warning">
        <p><strong>Configuration incomplète!</strong> Vous avez commencé le processus d'inscription mais ne l'avez pas terminé.</p>
        <p>Veuillez poursuivre la configuration de votre compte pour activer les paiements.</p>
      </div>
      <div class="mt-3">
        <a class="btn btn-warning btn-lg" href="#" id="configurer_compte" style="font-size: 14px;">Reprendre la configuration</a>
      </div>
    <?php } else { ?>
      <!-- Account is fully configured -->
      <div>
        <?php if (empty($_GET['action'])) { ?>
          <p>Vous avez déjà configuré votre compte.</p>
          <hr>
          <div id="dashboard_compte" class="btn btn-success " style="border-radius: 25px;">
            Suivre mes paiements <i class="uk-icon-chevron-right"></i>
          </div>
        <?php } elseif ($_GET['action'] == 'return') { ?>
          <div class="alert alert-success">Compte configuré correctement</div>
          <hr>
          <div id="dashboard_compte" class="btn btn-success " style="border-radius: 25px;">
            Suivre mes paiements <i class="uk-icon-chevron-right"></i>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </div>


<?php
} else {
  header('location: /');
}
?>