$(document).ready(function() {
    // Initialize DataTables if table exists
    if ($('#vehicles-table').length > 0) {
        $('#vehicles-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
            },
            "responsive": true,
            "autoWidth": false,
            "order": [[0, 'asc']]
        });
    }
});