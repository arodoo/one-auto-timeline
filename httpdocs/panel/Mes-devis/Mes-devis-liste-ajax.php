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
          { "orderable": false, "targets": 5 },
        ],
        "language": {
          "sProcessing": "Traitement en cours...",
          "sSearch": "Rechercher :",
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
        }
      });

      // Champs de recherche sur colonne
      $('#Tableau_7 tfoot .search_table').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" class="form-control" placeholder="' + title + '" style="width:100%; font-weight: normal;" />');
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
        <th style="text-align: center;">PROFESSIONNEL</th>
        <th style="text-align: center;">TYPE</th>
        <th style="text-align: center;">OBJET</th>
        <th style="text-align: center;">DATE</th>
        <th style="text-align: center;">DEVIS</th>
        <th style="text-align: center;">STATUT</th>
        <th style="text-align: center; width: 90px;">GESTION</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th class="search_table" style="text-align: center;">PROFESSIONNEL</th>
        <th class="search_table" style="text-align: center;">TYPE</th>
        <th class="search_table" style="text-align: center;">OBJET</th>
        <th class="search_table" style="text-align: center;">DATE</th>
        <th class="search_table" style="text-align: center;">DEVIS</th>
        <th class="search_table" style="text-align: center;">STATUT</th>
        <th style="text-align: center; width: 90px;">GESTION</th>
      </tr>
    </tfoot>
    <tbody>
      <?php
      ///////////////////////////////SELECT BOUCLE
      if (isset($_POST['id']) && !empty($_POST['id'])) {
        $req_boucle = $bdd->prepare("SELECT * FROM membres_devis WHERE id_membre_utilisateur=? AND membres_annonces_clients_id=? AND statut_devis='Accepté' ORDER BY id DESC");
        $req_boucle->execute(array($id_oo, $_POST['id']));
      } else {
        $req_boucle = $bdd->prepare("SELECT * FROM membres_devis WHERE id_membre_utilisateur=? ORDER BY id DESC");
        $req_boucle->execute(array($id_oo));
      }
      while ($ligne_boucle = $req_boucle->fetch()) {
        $idoneinfos = $ligne_boucle['id'];

        ///////////////////////////////SELECT
        $req_select_utilisateur = $bdd->prepare("SELECT * FROM membres WHERE id=?");
        $req_select_utilisateur->execute(array($ligne_boucle['id_membre_utilisateur']));
        $ligne_select_utilisateur = $req_select_utilisateur->fetch();
        $req_select_utilisateur->closeCursor();

        ///////////////////////////////SELECT
        $req_select_depanneur = $bdd->prepare("SELECT * FROM membres WHERE id=?");
        $req_select_depanneur->execute(array($ligne_boucle['id_membre_depanneur']));
        $ligne_select_depanneur = $req_select_depanneur->fetch();
        $req_select_depanneur->closeCursor();
        $idoneinfos = $ligne_select_depanneur['id'];

        // Fetch user profile URL
        $req_select_profil = $bdd->prepare("SELECT url_profil FROM membres_profils WHERE id_membre=?");
        $req_select_profil->execute(array($ligne_select_depanneur['id']));
        $ligne_select_profil = $req_select_profil->fetch();
        $req_select_profil->closeCursor();
        $url_profil = "https://mon-espace-auto.com" . $ligne_select_profil['url_profil'];
        ?>
        <tr class="odd">
          <td class="dtr-control" style="text-align: center;">
            <a href="<?php echo $url_profil; ?>" target="_blank" title="Visiter le profil">
              <?php echo $ligne_select_depanneur['prenom']; ?> <?php echo $ligne_select_depanneur['nom']; ?>
            </a>
          </td>
          <td style="text-align: center; <?php echo "$colorback"; ?>"> <?php echo $ligne_boucle['type']; ?></td>
          <td style="text-align: center; <?php echo "$colorback"; ?>"> <?php echo $ligne_boucle['objet_de_la_demande']; ?>
          </td>
          <td style="text-align: center; <?php echo "$colorback"; ?>">
            <?php
            if (!empty($ligne_boucle['date_statut'])) {
              echo date('d-m-Y', $ligne_boucle['date_statut']);
            } else {
              echo "--";
            }
            ?>
          </td>
          <td style="text-align: center;">
            <?php if (!empty($ligne_boucle['lien_devis'])) { ?>
              <a href='<?php echo $ligne_boucle['lien_devis']; ?>' class='btn btn-danger' target='blank_' style='background-color: red; border-color: red; color:white!important;'>Devis</a>
            <?php } else { ?>
              - -
            <?php } ?>
          </td>
          <td style="text-align: center;">
            <?php
            if ($ligne_boucle['statut_devis'] == "Traité") {
              echo "<span class='label label-warning'>Traité</span>";
            } elseif ($ligne_boucle['statut_devis'] == "Non traité") {
              echo "<span class='label label-danger'>Non traité</span>";
            } elseif ($ligne_boucle['statut_devis'] == "Accepté") {
              echo "<span class='label label-success'>Accepté</span>";
            } elseif ($ligne_boucle['statut_devis'] == "Refusé") {
              echo "<span class='label label-danger'>Refusé</span>";
            }
            ?>
          </td>
          <td style="text-align: center; width: 90px;"><a
              href='/Mes-devis/modifier/<?php echo $ligne_boucle['id']; ?>'><span class='uk-icon-file-text'></span></a> &nbsp; 
           <a class='lien-supprimer' data-id='<?php echo $idoneinfos; ?>'
              href='#'><span class='uk-icon-times'></span></a></td>
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