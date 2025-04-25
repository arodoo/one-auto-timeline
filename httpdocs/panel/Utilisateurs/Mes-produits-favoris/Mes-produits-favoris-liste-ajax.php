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

  $nom_fichier = "Mes-produits-favoris";
  $nom_fichier_datatable = "Mes-produits-favoris-" . date('d-m-Y', time()) . "-$nomsiteweb";

  function lettre_sans_accent($chaine)
  {
    $normalized = \Normalizer::normalize($chaine, \Normalizer::FORM_D);
    $sans_accent = preg_replace('/[\p{Mn}]/u', '', $normalized);
    return $sans_accent;
  }

?>
  <script>
    $(document).ready(function() {
      $('#Tableau_7').DataTable({
        "order": [],
        responsive: true,
        stateSave: true,
        dom: 'Bftipr',
        buttons: [{
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
        columnDefs: [{
            visible: false
          },
          {
            orderable: false,
            targets: 3
          }
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
      $('#Tableau_7 tfoot .search_table').each(function() {
        var title = $(this).text();
        $(this).html('<input type="text" class="form-control" placeholder="' + title + '" style="width:100%; font-weight: normal;"/>');
      });
      var table = $('#Tableau_7').DataTable();
      table.columns().every(function() {
        var that = this;
        $('input', this.footer()).on('keyup change', function() {
          if (that.search() !== this.value) {
            that.search(this.value).draw();
          }
        });
      });


    });
  </script>

  <table id='Tableau_7' class='display' style='text-align: center; width: 100%; margin-top: 15px;' cellpadding='2' cellspacing='2'>
    <thead>
      <tr scope='col'>
        <th style='text-align: center;'>VENDEUR</th>
        <th style='text-align: center;'>PRODUIT</th>
        <th style='text-align: center;'>DATE</th>
        <th style='text-align: center; width: 90px;'>GESTION</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th class='search_table' style='text-align: center;'>VENDEUR</th>
        <th class='search_table' style='text-align: center;'>PRODUIT</th>
        <th class='search_table' style='text-align: center;'>DATE</th>
        <th style='text-align: center; width: 90px;'>GESTION</th>
      </tr>
    </tfoot>
    <tbody>
      <?php
      // Boucle de sélection
      $req_boucle = $bdd->prepare("SELECT * FROM membres_produits_favoris WHERE id_membre=? ORDER BY id DESC");
      $req_boucle->execute(array($id_oo));
      while ($ligne_boucle = $req_boucle->fetch()) {
        $idoneinfos = $ligne_boucle['id'];
        $lien_produit = $ligne_boucle['lien_produit']; // Récupère le lien du produit depuis la base de données

        if (!empty($lien_produit)) {
          $lien_produit = trim($lien_produit, '/');

          $segments = explode('/', $lien_produit);
          if (count($segments) >= 2) {
            $nom_produit_extrait = str_replace('-', ' ', $segments[1]);
          } else {
            $nom_produit_extrait = "Nom inconnu";
          }
        } else {
          $nom_produit_extrait = "Nom inconnu";
        }



        // Sélection des vendeurs
        $req_select_vendeur = $bdd->prepare("SELECT * FROM membres WHERE id=?");
        $req_select_vendeur->execute(array($ligne_boucle['id_membre']));
        $ligne_select_vendeur = $req_select_vendeur->fetch();
        $req_select_vendeur->closeCursor();

        $req_select_pro = $bdd->prepare("SELECT * FROM membres_professionnel WHERE id=?");
        $req_select_pro->execute(array($ligne_boucle['id_membre_vendeur']));
        $ligne_select_pro = $req_select_pro->fetch();
        $req_select_pro->closeCursor();
        if (!empty($ligne_select_pro['Nom_societe'])) {
          $nom_pro = "(Société " . $ligne_select_pro['Nom_societe'] . ")";
        }

        // Sélection des vendeurs
        $req_select_produit = $bdd->prepare("SELECT * FROM membres_produits WHERE id=?");
        $req_select_produit->execute(array($ligne_boucle['id_produit']));
        $ligne_select_produit = $req_select_produit->fetch();
        $req_select_produit->closeCursor();


      ?>
        <tr class='odd'>
          <!--  <td class='dtr-control' style='text-align: center;'><?php echo $ligne_select_vendeur['prenom']; ?> <?php echo $ligne_select_vendeur['nom']; ?> <?php echo " $nom_pro"; ?> </td> -->
          <td class='dtr-control' style='text-align: center;'>Vendeur générique</td>

          <td style='text-align: center;'>
            <a href="<?php echo htmlspecialchars($lien_produit); ?>" target="_blank" title="<?php echo htmlspecialchars($nom_produit_extrait); ?>">
              <?php echo htmlspecialchars($nom_produit_extrait); ?>
            </a>
          </td>

          <td style='text-align: center;'>
            <?php
            if (!empty($ligne_boucle['date'])) {
              echo date('d-m-Y', $ligne_boucle['date']);
            } else {
              echo "--";
            } ?>
          </td>

          <td style='text-align: center; width: 90px;'>
            <?php echo "<a class='lien-supprimer' href='#' data-id='" . $idoneinfos . "' onclick='return false;' title='Supprimer'><span class='uk-icon-times'></span></a>"; ?>
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