<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');
require_once('../../includes/utils/constat_invitation_utils.php');

$lasturl = $_SERVER['HTTP_REFERER'];

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    $user_id = $id_oo;
    $user_email = $mail_oo;
    
    // Check if user is subscribed
    $is_subscribed = is_user_subscribed($user_id);
    
    // Get constats where this user's email is listed as the agency
    $client_constats = get_pending_agency_constats($user_email);
    
    $nom_fichier = "Constats Clients";
    $nom_fichier_datatable = "Constats-Clients-" . date('d-m-Y', time()) . "-$nomsiteweb";
    ?>
    <script>
        $(document).ready(function() {
            // Force clear any saved DataTables state
            if (localStorage) {
                for (var key in localStorage) {
                    if (key.indexOf('DataTables') !== -1) {
                        localStorage.removeItem(key);
                    }
                }
            }
            
            // Completely replace DataTables sorting icons with FontAwesome - same as in Constats-liste-ajax.php
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
            
            var dataTable = $('#Tableau_client_constats').DataTable({
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
                        render: function(data, type, row) {
                            if (type === 'sort') {
                                // Extract date for sorting
                                return new Date(data.split('/').reverse().join('-')).getTime();
                            }
                            return data;
                        }
                    },
                    { "type": "date-fr", "targets": 1 },
                    { "orderable": false, "targets": 4 }
                ],
                order: [[1, 'desc']], // Default sort by date column descending
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
            $('#Tableau_client_constats tfoot .search_table').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" style="width:100%; font-weight: normal;"/>');
            });
            
            // Apply search to columns
            dataTable.columns().every(function() {
                var that = this;
                $('input', this.footer()).on('keyup change', function() {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            });
        });
    </script>
    
    <?php if (empty($client_constats)): ?>
        <div class="alert alert-info">
            <i class="fa fa-info-circle mr-2"></i>
            Aucun constat client n'a été trouvé.
        </div>
    <?php elseif (!$is_subscribed): ?>
        <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle mr-2"></i>
            <strong>Accès limité</strong> - Pour accéder aux détails des constats, veuillez souscrire à un abonnement.
        </div>
        
        <div class="table-responsive">
            <table id="Tableau_client_constats" class="display" style="width: 100%; margin-top: 15px;" cellpadding="2" cellspacing="2">
                <thead>
                    <tr scope="col">
                        <th style="text-align: center;">RÉFÉRENCE</th>
                        <th style="text-align: center;">DATE DE L'ACCIDENT</th>
                        <th style="text-align: center;">CLIENT</th>
                        <th style="text-align: center;">LIEU</th>
                        <th style="text-align: center; width: 100px;">ACTIONS</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="search_table" style="text-align: center;">RÉFÉRENCE</th>
                        <th class="search_table" style="text-align: center;">DATE</th>
                        <th class="search_table" style="text-align: center;">CLIENT</th>
                        <th class="search_table" style="text-align: center;">LIEU</th>
                        <th style="text-align: center;">ACTIONS</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($client_constats as $constat): ?>
                    <tr class="odd">
                        <td class="dtr-control" style="text-align: center;">
                            <?php echo htmlspecialchars($constat['unique_id']); ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo htmlspecialchars($constat['formatted_date'] ?? date('d/m/Y', strtotime($constat['s1_accident_date']))); ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo htmlspecialchars($constat['prenom'] . ' ' . $constat['nom']); ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo htmlspecialchars($constat['s1_accident_place'] ?? 'Non spécifié'); ?>
                        </td>
                        <td style="text-align: center;">
                            <a href="/Abonnement" class="btn btn-primary btn-sm" title="Abonnement requis">
                                <i class="fas fa-lock"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table id="Tableau_client_constats" class="display" style="width: 100%; margin-top: 15px;" cellpadding="2" cellspacing="2">
                <thead>
                    <tr scope="col">
                        <th style="text-align: center;">RÉFÉRENCE</th>
                        <th style="text-align: center;">DATE DE L'ACCIDENT</th>
                        <th style="text-align: center;">CLIENT</th>
                        <th style="text-align: center;">LIEU</th>
                        <th style="text-align: center; width: 100px;">ACTIONS</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="search_table" style="text-align: center;">RÉFÉRENCE</th>
                        <th class="search_table" style="text-align: center;">DATE</th>
                        <th class="search_table" style="text-align: center;">CLIENT</th>
                        <th class="search_table" style="text-align: center;">LIEU</th>
                        <th style="text-align: center;">ACTIONS</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($client_constats as $constat): ?>
                    <tr class="odd">
                        <td class="dtr-control" style="text-align: center;">
                            <?php echo htmlspecialchars($constat['unique_id']); ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo htmlspecialchars($constat['formatted_date'] ?? date('d/m/Y', strtotime($constat['s1_accident_date']))); ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo htmlspecialchars($constat['prenom'] . ' ' . $constat['nom']); ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo htmlspecialchars($constat['s1_accident_place'] ?? 'Non spécifié'); ?>
                        </td>
                        <td style="text-align: center;">
                            <a href="/Constat-amiable-accident/pdf/<?php echo htmlspecialchars($constat['unique_id']); ?>" 
                               class="btn btn-info btn-sm" 
                               target="_blank" 
                               title="Visualiser le PDF">
                                <i class="fa fa-eye"></i>
                            </a>
                            <!-- <button class="btn btn-info btn-sm constat-details-btn" 
                                   data-id="<?php echo htmlspecialchars($constat['id']); ?>" 
                                   title="Voir les détails">
                                <i class="fa fa-info-circle"></i>
                            </button> -->
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <?php
} else {
    header('location: /');
}
ob_end_flush();
?>