<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];


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

  $nom_fichier = "Devis";
  $nom_fichier_datatable = "Devis-" . date('d-m-Y', time()) . "-$nomsiteweb";
  ?>
  <script>
    $(document).ready(function () {
      $('#Tableau_7').DataTable(
        {
          "order": [],
          responsive: true,
          stateSave: true,
          dom: 'Bftipr',
          buttons: [
            {
              extend: 'print',
              text: "Imprimer",
              exportOptions: {
                columns: ':visible'
              }
            },
            {
              extend: 'pdf',
              filename: "<?php echo "$nom_fichier_datatable"; ?>",
              title: "<?php echo "$nom_fichier"; ?>",
              exportOptions: {
                columns: ':visible'
              }
            }, {
              extend: 'csv',
              filename: "<?php echo "$nom_fichier_datatable"; ?>",
              exportOptions: {
                columns: ':visible'
              }
            }, {
              extend: 'colvis',
              text: "Colonnes visibles",
            }
          ],
          columnDefs: [{
            visible: false
          }],
          "columnDefs": [
            { "orderable": false, "targets": 5, },
            { "orderable": false, "targets": 6, },
          ],
          "language": {
            "sProcessing": "Traitement en cours...",
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix": "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
            "oPaginate": {
              "sFirst": "Premier",
              "sPrevious": "Pr&eacute;c&eacute;dent",
              "sNext": "Suivant",
              "sLast": "Dernier"
            },
            "oAria": {
              "sSortAscending": ": activer pour trier la colonne par ordre croissant",
              "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            }
          }
        }
      );

      ///////////////CHAMPS DE RECHERCHE SUR COLONNE
      $('#Tableau_7 tfoot .search_table').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" class="form-control" placeholder="' + title + '" style="width:100%; font-weight: normal;"/>');
      });
      var table = $('#Tableau_7').DataTable();
      table.columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
          if (that.search() !== this.value) {
            that
              .search(this.value)
              .draw();
          }
        });
      });

    });
  </script>

  <table id='Tableau_7' class="display" style="text-align: center; width: 100%; margin-top: 15px;" cellpadding="2"
    cellspacing="2">

    <thead>
      <tr scope="col">
        <th style="text-align: center;">CLIENT</th>
	<th style="text-align: center;">TYPE</th>
        <th style="text-align: center;">OBJET</th>
        <th style="text-align: center;">DATE</th>
        <th style="text-align: center;">DEVIS</th>
        <th style="text-align: center;">STATUT</th>
        <th style="text-align: center; width: 90px;">MODIFIER</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th class="search_table" style="text-align: center;">CLIENT</th>
	<th class="search_table" style="text-align: center;">TYPE</th>
        <th class="search_table" style="text-align: center;">OBJET</th>
        <th class="search_table" style="text-align: center;">DATE</th>
        <th class="search_table" style="text-align: center;">DEVIS</th>
        <th class="search_table" style="text-align: center;">STATUT</th>
        <th style="text-align: center; width: 90px;">MODIFIER</th>
      </tr>
    </tfoot>
    <tbody>

      <?php
      ///////////////////////////////SELECT BOUCLE
      $req_boucle = $bdd->prepare("SELECT * FROM membres_devis WHERE id_membre_depanneur=? ORDER BY id DESC");
      $req_boucle->execute(array($id_oo));
      while ($ligne_boucle = $req_boucle->fetch()) {
        $idoneinfos = $ligne_boucle['id'];

        ///////////////////////////////SELECT
        $req_select_utilisateur = $bdd->prepare("SELECT * FROM membres WHERE id=?");
        $req_select_utilisateur->execute(array($ligne_boucle['id_membre_utilisateur']));
        $ligne_select_utilisateur = $req_select_utilisateur->fetch();
        $req_select_utilisateur->closeCursor();

        ?>
        <tr class="odd">
          <td class="dtr-control" style="text-align: center; <?php echo "$colorback"; ?>">
            <?php echo $ligne_select_utilisateur['prenom']; ?>     <?php echo $ligne_select_utilisateur['nom']; ?>
          </td>
	<td style="text-align: center; <?php echo "$colorback"; ?>"> <?php echo $ligne_boucle['type']; ?></td>
          <td style="text-align: center; <?php echo "$colorback"; ?>"> <?php echo $ligne_boucle['objet_de_la_demande']; ?>
          </td>
          <td style="text-align: center; <?php echo "$colorback"; ?>">
            <?php
            if (!empty($ligne_boucle['date_statut'])) {
              echo date('d-m-Y', ($ligne_boucle['date_statut']));
            } else {
              echo "--";
            }
            ?>
          </td>
          <td style="text-align: center; <?php echo "$colorback"; ?>">
            <?php if (!empty($ligne_boucle['lien_devis'])) { ?>
                <a href="<?php echo $ligne_boucle['lien_devis']; ?>" class="btn btn-danger" id="btn-visualizer-devis" style='background-color: red; border-color: red; color:white!important;' target="blank_">
                <?php echo "Devis"; ?></a>
            <?php } else { ?>
              - -
            <?php } ?>
          </td>
          <td style="text-align: center; <?php echo "$colorback"; ?>">
            <?php
            if ($ligne_boucle['statut_devis'] == "Traité") {
              echo "<span class='label label-warning' >Traité</span>";
            } elseif ($ligne_boucle['statut_devis'] == "Non traité") {
              echo "<span class='label label-danger' >Non traité</span>";
            } elseif ($ligne_boucle['statut_devis'] == "Accepté") {
              echo "<span class='label label-success' >Accepté</span>";
            } elseif ($ligne_boucle['statut_devis'] == "Refusé") {
              echo "<span class='label label-danger' >Refusé</span>";
            }
            ?>
          </td>
          <td style="text-align: center; width: 90px;">
            <a href='/Devis/modifier/<?php echo $ligne_boucle['id']; ?>'>
              <span class='uk-icon-file-text'></span>
            </a>
            <a href='#' class='btn-envoyer-message' data-id='<?php echo $ligne_boucle['id_membre_utilisateur']; ?>'
              data-nom='<?php echo $nom_utilisateur; ?>' onclick='return false;'>
              <span class='uk-icon-envelope'></span>
            </a>
          </td>
        </tr>
        <?php
      }
      $req_boucle->closeCursor();

      ?>
    </tbody>
  </table><br /><br />

  <?php

  include('../../pop-up/message/modal-envoyer-message.php');
} else {
  header('location: /');
}
ob_end_flush();
?>