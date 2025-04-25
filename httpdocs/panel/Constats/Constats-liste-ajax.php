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

  $nom_fichier = "Constats";
  $nom_fichier_datatable = "Constats-" . date('d-m-Y', time()) . "-$nomsiteweb";
  ?>
  <script>
    $(document).ready(function () {
      // Force clear any saved DataTables state
      if (localStorage) {
        for (var key in localStorage) {
          if (key.indexOf('DataTables') !== -1) {
            localStorage.removeItem(key);
          }
        }
      }

      // Completely replace DataTables sorting icons with FontAwesome
      $('head').append('<style>' +
        'table.dataTable thead .sorting,' +
        'table.dataTable thead .sorting_asc,' +
        'table.dataTable thead .sorting_desc,' +
        'table.dataTable thead .sorting_asc_disabled,' +
        'table.dataTable thead .sorting_desc_disabled {' +
        '    background-image: none !important;' +
        '    position: relative;' +
        '}' +
        'table.dataTable thead .sorting:after,' +
        'table.dataTable thead .sorting_asc:after,' +
        'table.dataTable thead .sorting_desc:after,' +
        'table.dataTable thead .sorting_asc_disabled:after,' +
        'table.dataTable thead .sorting_desc_disabled:after {' +
        '    font-family: FontAwesome;' +
        '    position: absolute;' +
        '    top: 8px;' +
        '    right: 8px;' +
        '    display: block;' +
        '}' +
        'table.dataTable thead .sorting:after { content: "\\f0dc"; opacity: 0.5; }' +
        'table.dataTable thead .sorting_asc:after { content: "\\f0de"; }' +
        'table.dataTable thead .sorting_desc:after { content: "\\f0dd"; }' +
        'table.dataTable thead .sorting_asc_disabled:after { content: "\\f0de"; opacity: 0.3; }' +
        'table.dataTable thead .sorting_desc_disabled:after { content: "\\f0dd"; opacity: 0.3; }' +
        '</style>'
      );

      var dataTable = $('#Tableau_7').DataTable({
        destroy: true,
        stateSave: false,
        responsive: true,
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
          {
            targets: 1,
            render: function (data, type, row) {
              if (type === 'sort') {
                // Extract number for sorting
                var match = String(data).match(/#(\d+)$/);
                return match ? parseInt(match[1], 10) : 0;
              }
              return data;
            }
          },
          { "type": "date-fr", "targets": 2 },
          { "orderable": false, "targets": 4 }
        ],
        order: [[1, 'desc']], // Default sort by constat number column descending
        "language": {
          "sProcessing": "Traitement en cours...",
          "sSearch": "Rechercher :",
          "sLengthMenu": "Afficher _MENU_ éléments",
          "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
          "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
          "sInfoFiltered": "(filtré de _MAX_ éléments au total)",
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
        },
        // Add this to fix the sorting icons
        "aoColumnDefs": [
          { "bSortable": false, "aTargets": [4] }
        ]
      });

      // Add custom styling to solve the icons issue
      $('head').append('<style>.sorting::before, .sorting_asc::before, .sorting_desc::before { font-family: "FontAwesome"; content: "\\f0dc"; padding-right: 5px; }\n.sorting_asc::before { content: "\\f0de"; }\n.sorting_desc::before { content: "\\f0dd"; }</style>');

      // CHAMPS DE RECHERCHE SUR COLONNE
      $('#Tableau_7 tfoot .search_table').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" class="form-control" placeholder="' + title + '" style="width:100%; font-weight: normal;"/>');
      });

      // Apply search to columns
      dataTable.columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
          if (that.search() !== this.value) {
            that
              .search(this.value)
              .draw();
          }
        });
      });

      // Handle send to agency button clicks with improved validation
      $(document).on('click', '.btn-send-to-agency', function (e) {
        e.preventDefault();

        var constatId = $(this).data('id');
        var isJumelage = $(this).data('jumelage') === 'yes';
        var uniqueId = $(this).data('unique-id');

        // Client-side validation
        if (!constatId || constatId <= 0) {
          alert('ID de constat invalide.');
          return;
        }

        if (!uniqueId) {
          alert('Ce constat ne peut pas être envoyé à l\'agence (format ancien ou identifiant manquant).');
          return;
        }

        // Confirm before sending
        if (!confirm('Voulez-vous envoyer ce constat à votre agence d\'assurance? Assurez-vous que toutes les informations sont correctes et que l\'email de l\'agence est configuré dans le constat.')) {
          return;
        }

        // Show loading state
        var $button = $(this);
        var originalText = $button.html();
        $button.html('<i class="fa fa-spinner fa-spin"></i> Envoi...');
        $button.prop('disabled', true);

        // Send AJAX request
        $.ajax({
          url: '/panel/Constats/Constats-send-agency-email.php',
          type: 'POST',
          data: {
            constat_id: constatId,
            is_jumelage: isJumelage ? 'yes' : 'no',
            unique_id: uniqueId
          },
          dataType: 'json',
          success: function (response) {
            console.log("Server response:", response);

            if (response.success) {
              if (response.invitation_sent) {
                // Show invitation message
                alert('Une invitation a été envoyée à l\'agence ' + response.email + ' pour créer un compte et accéder à votre constat.');
              } else {
                // Agency is already registered
                if (response.is_subscribed === false) {
                  // Agency is registered but not subscribed
                  alert('Email envoyé avec succès à l\'agence ' + response.email + '.\n\nNous avons détecté que cette agence n\'a pas d\'abonnement actif. Elle devra s\'abonner pour pouvoir consulter ce constat.');
                } else {
                  // Normal success message
                  alert('Email envoyé avec succès à l\'agence ' + response.email + '!');
                }
              }
            } else {
              // Show detailed error message
              alert('Erreur: ' + (response.message || 'Une erreur inconnue est survenue.'));
            }
          },
          error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
            console.error('Response text:', xhr.responseText);
            // Show more informative error message
            alert('Une erreur est survenue lors de l\'envoi de l\'email. Vérifiez que toutes les informations requises sont renseignées dans le constat, notamment l\'email de l\'agence.');
          },
          complete: function () {
            // Restore button state
            $button.html(originalText);
            $button.prop('disabled', false);
          }
        });
      });
    });
  </script>

  <div id="dt-debug" style="font-size:12px; color:#666; margin-bottom:10px; display:none;">
    <strong>Debug:</strong> <span id="dt-order-info"></span>
  </div>

  <table id='Tableau_7' class='display' style='text-align: center; width: 100%; margin-top: 15px;' cellpadding='2'
    cellspacing='2'>
    <thead>
      <tr scope='col'>
        <th style='text-align: center;'>UTILISATEUR</th>
        <th style='text-align: center;'>NUMÉRO DE CONSTAT</th>
        <th style='text-align: center;'>DATE DU CONSTAT</th>
        <th style='text-align: center;'>JUMELE</th>
        <th style='text-align: center; width: 200px;'>ACTIONS</th>
        <!-- <th style='text-align: center; width: 90px;'>SUPPRIMER</th> -->
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th class='search_table' style='text-align: center;'>UTILISATEUR</th>
        <th class='search_table' style='text-align: center;'>NUMÉRO DE CONSTAT</th>
        <th class='search_table' style='text-align: center;'>DATE DU CONSTAT</th>
        <th class='search_table' style='text-align: center;'>JUMELE</th>
        <th style='text-align: center; width: 200px;'>ACTIONS</th>
        <!-- <th style='text-align: center; width: 90px;'>SUPPRIMER</th> -->
      </tr>
    </tfoot>
    <tbody>
      <?php
      // First query the new constats_main table with user filter
      $req_new = $bdd->prepare("SELECT cm.*, m.prenom, m.nom, m.id as membre_id 
                               FROM constats_main cm 
                               LEFT JOIN membres m ON m.id = cm.id_membre 
                               WHERE cm.id_membre = ?
                               ORDER BY cm.created_at DESC");
      $req_new->execute(array($id_oo));

      // Then get the legacy records for the same user
      $req_old = $bdd->prepare("SELECT * FROM membres_constats 
                               WHERE id_membre = ? 
                               ORDER BY id DESC");
      $req_old->execute(array($id_oo));

      // Display new format records
      while ($ligne = $req_new->fetch()) {
        $isJumelage = (!empty($ligne['shared_with_user_id']) || !empty($ligne['is_shared'])) ? "yes" : "no";
        ?>
        <tr class='odd'>
          <td class='dtr-control' style='text-align: center;'>
            <?php echo $ligne['prenom'] . ' ' . $ligne['nom']; ?>
          </td>
          <td class='dtr-control' style='text-align: center;'>
            Constat #<?php echo $ligne['id']; ?>
          </td>
          <td style='text-align: center;'>
            <?php echo !empty($ligne['created_at']) ? date('d-m-Y', strtotime($ligne['created_at'])) : "--"; ?>
          </td>
          <td style='text-align: center;'>
            <?php echo $isJumelage === "yes" ? "oui" : "non"; ?>
          </td>
          <td style='text-align: center;'>
            <a href='/panel/Constats/constant-form/PDFGenerator/index.php?id=<?php echo $ligne['unique_id']; ?>'
              class='btn btn-info btn-sm' target='_blank' title="Visualiser le constat">
              <i class="fa fa-eye"></i>
            </a>
            <button class='btn btn-info btn-sm btn-send-to-agency' data-id='<?php echo $ligne['id']; ?>'
              data-unique-id='<?php echo $ligne['unique_id']; ?>' data-jumelage='<?php echo $isJumelage; ?>'
              title="Envoyer à l'agence d'assurance">
              <i class="fa fa-paper-plane"></i>
            </button>
            <a href="/Constats/Images/<?php echo $ligne['id']; ?>" class='btn btn-info btn-sm' title="Ajouter des images">
              <i class="uk-icon-photo"></i>
            </a>
          </td>
        </tr>
        <?php
      }
      $req_new->closeCursor();

      // Display legacy format records
      while ($ligne_boucle = $req_old->fetch()) {
        $idoneinfos = $ligne_boucle['id'];

        ///////////////////////////////SELECT
        $req_select_utilisateur = $bdd->prepare("SELECT * FROM membres WHERE id=?");
        $req_select_utilisateur->execute(array($ligne_boucle['id_membre']));
        $ligne_select_utilisateur = $req_select_utilisateur->fetch();
        $req_select_utilisateur->closeCursor();

        ///////////////////////////////SELECT
        $req_select_jumelage = $bdd->prepare("SELECT * FROM membres WHERE id=?");
        $req_select_jumelage->execute(array($ligne_boucle['id_membre_jumelage']));
        $ligne_select_jumelage = $req_select_jumelage->fetch();
        $req_select_jumelage->closeCursor();

        $isJumelage = !empty($ligne_boucle['id_membre_jumelage']) ? "yes" : "no";

        ?>
        <tr class='odd legacy-record'>
          <td class='dtr-control' style='text-align: center;'><?php echo $ligne_select_utilisateur['prenom']; ?>
            <?php echo $ligne_select_utilisateur['nom']; ?>
          </td>
          <td class='dtr-control' style='text-align: center;'> Constat #<?php echo $ligne_boucle['id']; ?></td>
          <td style='text-align: center;'>
            <?php echo !empty($ligne_boucle['date_statut']) ? date('d-m-Y', $ligne_boucle['date_statut']) : "--"; ?>
          </td>
          <td style='text-align: center;'>
            <?php echo $isJumelage === "yes" ? "oui" : "non"; ?>
          </td>
          <td style='text-align: center;'>
            <a href='/panel/Constats/constant-form/PDFGenerator/index.php?legacy_id=<?php echo $idoneinfos; ?>'
              class='btn btn-info btn-sm' target='_blank' title="Visualiser le constat">
              <i class="fa fa-eye"></i>
            </a>
            <button class='btn btn-info btn-sm btn-send-to-agency' data-id='<?php echo $idoneinfos; ?>'
              data-jumelage='<?php echo $isJumelage; ?>' title="Envoyer à l'agence d'assurance">
              <i class="fa fa-paper-plane"></i>
            </button>
            <a class='btn btn-info btn-sm' title="Ajouter des images">
              <i class="uk-icon-photo"></i>
            </a>
          </td>
        </tr>
        <?php
      }
      $req_old->closeCursor();
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