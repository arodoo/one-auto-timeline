$(document).ready(function() {
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
                        location.reload();
                    }, 1500);
                } else {
                    popup_alert(res.message || "Erreur de suppression", "red filledlight", "#ff0000", "uk-icon-close");
                }
                $("#deleteVehicleModal").modal("hide");
            },
            error: function() {
                popup_alert("Erreur lors de la suppression du véhicule", "red filledlight", "#ff0000", "uk-icon-close");
                $("#deleteVehicleModal").modal("hide");
            }
        });
    });
    
    // Show loading animation
    function showLoadingScreen(seconds) {
        document.getElementById('loading-screen').style.display = 'flex';
        setTimeout(function () {
            document.getElementById('loading-screen').style.display = 'none';
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        }, seconds * 1000);
    }
});