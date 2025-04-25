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
          url: '/panel/Vendeurs/Mes-ventes/Mes-ventes-action-ajouter-modifier-ajax.php',
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
                $("#formulaire-ajouter")[0].reset();
              <?php } ?>
            } else {
              popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            }
            location.reload();
          }
        });
        $("html, body").animate({ scrollTop: 0 }, "slow");
      });

      //AJAX - SUPPRIMER
      $(document).on("click", ".lien-supprimer", function () {
        $.post({
          url: '/panel/Vendeurs/Mes-ventes/Mes-ventes-action-supprimer-ajax.php',
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
          url: '/panel/Vendeurs/Mes-ventes/Mes-ventes-liste-ajax.php',
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
        $req_select = $bdd->prepare("SELECT * FROM membres_commandes WHERE id=? AND id_membre=?");
        $req_select->execute(array($idaction, $id_oo));
        $ligne_select = $req_select->fetch();
        $req_select->closeCursor();

	// Sélection client
	$req_select_client = $bdd->prepare("SELECT * FROM membres WHERE id=?");
	$req_select_client->execute(array($ligne_select['id_membre_client']));
	$ligne_select_client = $req_select_client->fetch();
	$req_select_client->closeCursor();

	// Sélection produit
	$req_select_produit = $bdd->prepare("SELECT * FROM membres_produits WHERE id=?");
	$req_select_produit->execute(array($ligne_select['id_produit']));
	$ligne_select_produit = $req_select_produit->fetch();
	$req_select_produit->closeCursor();

        ?>

        <div align='left'>
          <h2 style="float: left;" >Modifier</h2>
	    <a href="/Mes-ventes" class="btn btn-primary" style="float: right;" >Liste</a>
        </div><br />
        <div style='clear: both;'></div>

        <form id='formulaire-modifier' method="post"
          action="#">
          <input id="action" type="hidden" name="action" value="modifier-action">
          <input id="idaction" type="hidden" name="idaction" value="<?php echo $_GET['idaction']; ?>">

          <?php
      } else {

        ?>

          <div align='left'>
            <h2 style="float: left;" >Ajouter</h2>
	    <a href="/Mes-ventes" class="btn btn-primary" style="float: right;" >Liste</a>
          </div><br />
          <div style='clear: both;'></div>

          <form id='formulaire-ajouter' method="post"
            action="#">
            <input id="action" type="hidden" name="action" value="ajouter-action">

            <?php
      }
      ?>

<div class="container mt-5 text-left" style="text-align: left;" >
<h2>Informations du client</h2>
<div class="row mb-3">
<div class="col-md-6 text-left">
<label for="nom">Nom:</label>
<p><?php echo $ligne_select_client['nom']; ?></p>
</div>
<div class="col-md-6 text-left">
<label for="prenom">Prénom:</label>
<p><?php echo $ligne_select_client['prenom']; ?></p>
</div>
</div>
<div class="row mb-3">
<div class="col-md-6 text-left">
<label for="mail">Email:</label>
<p><?php echo $ligne_select_client['mail']; ?></p>
</div>
<div class="col-md-6 text-left">
<label for="telephone">Téléphone:</label>
<p><?php echo $ligne_select_client['Telephone_portable']; ?></p>
</div>
</div>
<div class="row mb-3">
<div class="col-md-6 text-left">
<label for="ville">Ville:</label>
<p><?php echo $ligne_select_client['ville']; ?></p>
</div>
<div class="col-md-6 text-left">
<label for="cp">Code Postal:</label>
<p><?php echo $ligne_select_client['cp']; ?></p>
</div>
</div>
<div class="row mb-3">
<div class="col-md-12 text-left">
<a href='#' class='btn btn-success btn-envoyer-message' data-id='<?php echo $ligne_select_client['id']; ?>' data-nom='<?php echo $ligne_select_client['nom']; ?>' onclick='return false;'>Envoyer un message</a>
</div>
</div>
</div>

<div class="container mt-5 text-left" style="text-align: left;">
<h2>Informations de la commande</h2>
<div class="row mb-3">
<div class="col-md-6 text-left">
<label for="lien_produit">Nom produit:</label>
<p><a href="/<?php echo $ligne_select_produit['lien_produit']; ?>"><?php echo $ligne_select_produit['nom_produit']; ?></a></p>
</div>
<div class="col-md-3 text-left">
<label for="id_commande">ID Commande:</label>
<p>#<?php echo $ligne_select['id_commande']; ?></p>
</div>
<div class="col-md-3 text-left">
<label for="id_produit">ID Produit:</label>
<p>#<?php echo $ligne_select['id_produit']; ?></p>
</div>
</div>
<div class="row mb-3">
<div class="col-md-4 text-left">
<label for="quantite">Quantité:</label>
<p><?php echo $ligne_select['quantite']; ?></p>
</div>
<div class="col-md-4 text-left">
<label for="montant">Montant:</label>
<p><?php echo $ligne_select['montant']; ?>€</p>
</div>
<div class="col-md-4 text-left">
<label for="montant_livraison">Montant livraison:</label>
<p><?php echo $ligne_select['montant_livraison']; ?>€</p>
</div>
<div class="col-md-4 text-left">
<label for="statut">Statut:</label>
<?php 
if($ligne_select['statut'] == "Traité" ){
echo "<span class='label label-warning'>Traité</span>";
} elseif($ligne_select['statut'] == "Non traité" ){
echo "<span class='label label-danger'>Non traité</span>";        
} elseif($ligne_select['statut'] == "Livré" ){
echo "<span class='label label-success'>Livré</span>";
} elseif($ligne_select['statut'] == "Non livré" ){
echo "<span class='label label-danger'>Non livré</span>";
}
?>
</div>
<div class="col-md-4 text-left">
<label for="date_statut">Date statut:</label>
<p><?php echo date('d-m-Y', $ligne_select['date_statut']); ?></p>
</div>
<div class="col-md-4 text-left">
<label for="date_statut">Date commande:</label>
<p><?php echo date('d-m-Y', $ligne_select['date_commande']); ?></p>
</div>
<div class="col-md-12 text-left">
<label for="commentaire">Commentaire</label>
<textarea class="form-control" name="commentaire" id="commentaire" style="width: 100%; height: 100px;" ><?php echo date('d-m-Y', $ligne_select['date_commande']); ?></textarea>
</div>
<div class="col-md-4 text-left">
<label for="statut">Statut:</label>
<select name="statut" id="statut" class="form-control">
<option value="Traité" <?php if($ligne_select['statut'] == "Traité") echo 'selected'; ?>>Traité</option>
<option value="Non traité" <?php if($ligne_select['statut'] == "Non traité") echo 'selected'; ?>>Non traité</option>
<option value="Livré" <?php if($ligne_select['statut'] == "Livré") echo 'selected'; ?>>Livré</option>
<option value="Non livré" <?php if($ligne_select['statut'] == "Non livré") echo 'selected'; ?>>Non livré</option>
</select>
</div>
<div class="col-md-4 text-left">
<label for="statut">&nbsp;</label><br>
<button type="submit" name="bouton" id="bouton" class="btn btn-success" style="onlick: return false;" >Valider</button>
</div>
</div>
</div>

        </form>
    </div><br /><br />
    <br /><br />

    <?php

	include('../../../pop-up/message/modal-envoyer-message.php');

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