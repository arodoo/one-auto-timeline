/**
 * Enhanced Vehicle Management JavaScript
 * Part of the Vehicle Management System Enhancement Plan (VEHCRUD-2025)
 */
$(document).ready(function() {
    // Initialize DataTable for vehicles list
    if ($('#vehicles-table').length) {
        $('#vehicles-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
            },
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": -1 } // Disable sorting on last column (actions)
            ]
        });
    }

    // Delete vehicle confirmation
    $(document).on("click", ".delete-vehicle", function() {
        const vehicleId = $(this).data("id");
        const vehicleImmat = $(this).data("immat");
        
        $("#delete-vehicle-id").val(vehicleId);
        $("#delete-vehicle-immat").text(vehicleImmat);
        $("#deleteVehicleModal").modal("show");
    });

    // Delete vehicle action
    $("#confirm-delete-vehicle").click(function() {
        const vehicleId = $("#delete-vehicle-id").val();
        
        $.ajax({
            url: '/Profil-automobile?action=delete_vehicle',
            type: 'POST',
            data: { vehicle_id: vehicleId },
            dataType: "json",
            success: function(res) {
                if (res.status === 200) {
                    popup_alert(res.message, "green filledlight", "#009900", "uk-icon-check");
                    setTimeout(function() {
                        window.location.href = '/vehicles';
                    }, 1500);
                } else {
                    popup_alert(res.message || "Erreur lors de la suppression", "red filledlight", "#ff0000", "uk-icon-close");
                }
            },
            error: function() {
                popup_alert("Erreur de communication avec le serveur", "red filledlight", "#ff0000", "uk-icon-close");
            }
        });
    });

    // Brand selection for model population
    $('#marque').on('change', function() {
        const brand = $(this).val();
        if (brand) {
            $.ajax({
                url: '/Profil-automobile?action=get_models',
                type: 'POST',
                data: { marque: brand },
                success: function(response) {
                    $('#modeles-list').html(response);
                }
            });
        }
    });
});