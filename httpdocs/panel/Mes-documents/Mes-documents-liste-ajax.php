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

    $nom_fichier = "Documents";
    $nom_fichier_datatable = "Documents-" . date('d-m-Y', time()) . "-$nomsiteweb";

    ?>

    <script>
        $(document).ready(function () {
            $('#Tableau').DataTable(
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
                        { "orderable": false, "targets": 3, },
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
            $('#Tableau tfoot .search_table').each(function () {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" style="width:100%; font-weight: normal;"/>');
            });
            var table = $('#Tableau').DataTable();
            table.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value)
                            .draw();
                    }
                });
            });

            // Handle delete button click
            $(document).on('click', '.supprimer', function () {
                var id = $(this).data('id');
                $('#btnSuppr').data('id', id);
                $('#modalSuppr').modal('show');
            });

            // Handle confirm delete button click
            $('#btnSuppr').on('click', function () {
                var id = $(this).data('id');
                $.ajax({
                    url: 'Mes-documents-action-supprimer-ajax.php',
                    type: 'POST',
                    data: { idaction: id },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.retour_validation === "ok") {
                            // Reload the table or remove the row
                            location.reload();
                        } else {
                            alert(result.Texte_rapport);
                        }
                    }
                });
                $('#modalSuppr').modal('hide');
            });

        });
    </script>

    <table id='Tableau' class="display" style="text-align: center; width: 100%; margin-top: 15px;" cellpadding="2"
        cellspacing="2">

        <thead>
            <tr scope="col">
                <th style="text-align: center;">Catégorie</th>
                <th style="text-align: center;">Image (nom)</th>
                <th style="text-align: center;">Date</th>
                <th style="text-align: center; width: 90px;">GESTION</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th class="search_table" style="text-align: center;">Catégorie</th>
                <th class="search_table" style="text-align: center;">Image (nom)</th>
                <th class="search_table" style="text-align: center;">Date</th>
                <th style="text-align: center; width: 90px;">GESTION</th>
            </tr>
        </tfoot>

        <tbody>

            <?php
            ///////////////////////////////SELECT BOUCLE
            $req_boucle = $bdd->prepare("SELECT * FROM membres_profil_auto_documents WHERE id_membre=? ORDER BY date DESC");
            $req_boucle->execute(array($id_oo));
            while ($ligne_boucle = $req_boucle->fetch()) {
                $idd = $ligne_boucle['id'];
                if (!empty($ligne_boucle['date'])) {
                    $date = date('d-m-Y', $ligne_boucle['date']);
                }

                ///////////////////////////////SELECT
                $req_select = $bdd->prepare("SELECT * FROM configurations_categorie_documents WHERE id=?");
                $req_select->execute(array($ligne_boucle['id_categorie']));
                $ligne_select = $req_select->fetch();
                $req_select->closeCursor();

                echo "<tr class='odd'>
                        <td style='text-align: center;'>" . $ligne_select['nom'] . "</td>
                        <td style='text-align: center;'>
                            <a href='" . $ligne_boucle['lien'] . "' target='_blank'>" . $ligne_boucle['nom'] . "</a>
                        </td>
                        <td style='text-align: center;'>$date</td>
                        <td style='text-align: center;'>
                            <a href='" . $ligne_boucle['lien'] . "' title='Consulter' data-id='" . $idd . "' target='_blank'>
                                <span class='uk-icon-search'></span>
                            </a> &nbsp;
                            <a class='supprimer' data-id='" . $idd . "' href='#' onclick='return false;'>
                                <span class='uk-icon-times'></span>
                            </a>
                        </td>
                    </tr>";
            }
            $req_boucle->closeCursor();

            echo '</tbody></table>';

} else {
    header('location: /');
}

ob_end_flush();
?>