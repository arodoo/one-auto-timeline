<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

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

  $nom_fichier = "Mes-annonces-client";
  $nom_fichier_datatable = "Mes-annonces-client-" . date('d-m-Y', time()) . "-$nomsiteweb";
  ?>
  <script>
    $(document).ready(function () {
      $('#Tableau_7').DataTable({
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
          },
          {
            extend: 'csv',
            filename: "<?php echo "$nom_fichier_datatable"; ?>",
            exportOptions: {
              columns: ':visible'
            }
          },
          {
            extend: 'colvis',
            text: "Colonnes visibles",
          }
        ],
        columnDefs: [
          { visible: false },
          { orderable: false, targets: 4 }
        ],
        "language": {
          "sProcessing": "Traitement en cours...",
          "sSearch": "Rechercher :",
          "sLengthMenu": "Afficher MENU éléments",
          "sInfo": "Affichage de l'élément START à END sur TOTAL éléments",
          "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 éléments",
          "sInfoFiltered": "(filtré de MAX éléments au total)",
          "sLoadingRecords": "Chargement en cours...",
          "sZeroRecords": "Aucun élément à afficher",
          "sEmptyTable": "Aucune donnée disponible dans le tableau",
          "oPaginate": {
            "sFirst": "Premier",
            "sPrevious": "Précédent",
            "sNext": "Suivant",
            "sLast": "Dernier"
          },
          "oAria": {
            "sSortAscending": ": activer pour trier la colonne par ordre croissant",
            "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
          }
        }
      });

      // Champs de recherche sur colonne
      $('#Tableau_7 tfoot .search_table').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" class="form-control" placeholder="' + title + '" style="width:100%; font-weight: normal;"/>');
      });
      var table = $('#Tableau_7').DataTable();
      table.columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
          if (that.search() !== this.value) {
            that.search(this.value).draw();
          }
        });
      });
    });
  </script>

  <div
    style="background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px; margin-top: 60px; margin-bottom: 60px;">
    Pour consulter les demandes de devis reçues, vous devez accéder à la page des devis en cliquant <a href="/Mes-devis"
      style="color: #721c24; text-decoration: underline;">ici</a>.
  </div>

  <table id='Tableau_7' class='display' style='text-align: center; width: 100%; margin-top: 15px;' cellpadding='2'
    cellspacing='2'>
    <thead>
      <tr scope='col'>
        <th style='text-align: center;'>DEMANDE</th>
        <th style='text-align: center;'>CATEGORIE</th>
        <th style='text-align: center;'>DEPARTEMENT</th>
        <th style='text-align: center;'>DATE</th>
        <th style='text-align: center;'>STATUT</th>
        <th style='text-align: center; width: 90px;'>GESTION</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th class='search_table' style='text-align: center;'>DEMANDE</th>
        <th class='search_table' style='text-align: center;'>CATEGORIE</th>
        <th class='search_table' style='text-align: center;'>DEPARTEMENT</th>
        <th class='search_table' style='text-align: center;'>DATE</th>
        <th class='search_table' style='text-align: center;'>STATUT</th>
        <th style='text-align: center; width: 90px;'>GESTION</th>
      </tr>
    </tfoot>
    <tbody>
      <?php
      // Boucle de sélection
      $req_boucle = $bdd->prepare("SELECT * FROM membres_annonces_clients WHERE id_membre=? ORDER BY id DESC");
      $req_boucle->execute(array($id_oo));
      while ($ligne_boucle = $req_boucle->fetch()) {
        $idoneinfos = $ligne_boucle['id'];

        ?>
        <tr class='odd'>
          <td class='dtr-control' style='text-align: center;'><?php echo $ligne_boucle['nom']; ?></td>
          <td style='text-align: center;'>
            <?php
            if ($ligne_boucle['id_type_compte_categorie'] == 2) {
              echo 'Dépannage';
            } elseif ($ligne_boucle['id_type_compte_categorie'] == 3) {
              echo 'Devis carrosserie';
            } elseif ($ligne_boucle['id_type_compte_categorie'] == 4) {
              echo 'Devis mécanique';
            } elseif ($ligne_boucle['id_type_compte_categorie'] == 6) {
              echo 'Devis service';
            } else {
              echo 'Option inconnue';
            }
            ?>
          </td>
          <td style='text-align: center;'><?php echo $ligne_boucle['departement']; ?></td>
          <td style='text-align: center;'>
            <?php if (!empty($ligne_boucle['date'])) {
              echo date('d-m-Y', $ligne_boucle['date']);
            } else {
              echo "--";
            } ?>
          </td>
          <td style='text-align: center;'>
            <?php
            if ($ligne_boucle['statut'] == "brouillon") {
              echo "<span class='label label-warning'>Brouillon</span>";
            } elseif ($ligne_boucle['statut'] == "activé") {
              echo "<span class='label label-success'>Activé</span>";
            }
            ?>
          </td>
          <td style='text-align: center; width: 90px;'>
            <?php echo "<a href='/Mes-annonces-client/modifier/" . $idoneinfos . "' title='Modifier' style='margin-right: 10px;'><span class='uk-icon-file-text'></span></a>"; ?>
            <?php echo "<a class='lien-supprimer' href='#' data-id='" . $idoneinfos . "' onclick='return false;' title='Supprimer' style='margin-right: 10px;'><span class='uk-icon-times'></span></a>"; ?>
            <?php echo "<a href='https://mon-espace-auto.com/Mes-devis/" . $idoneinfos . "' title='Voir devis'><span class='uk-icon-eye'></span></a>"; ?>
          </td>
        </tr>
        <?php
      }
      $req_boucle->closeCursor();
      ?>
    </tbody>
  </table>
  <br /><br />

  <?php
} else {
  header('location: /');
}
ob_end_flush();
?>