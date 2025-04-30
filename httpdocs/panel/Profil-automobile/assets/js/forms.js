$(document).ready(function() {
    // API search form
    if ($('#api-search-form').length > 0) {
        $('#api-search-form').on('submit', function(e) {
            e.preventDefault();
            
            const immatriculation = $('#voir_immatriculation').val();
            
            if (!immatriculation) {
                popup_alert("Veuillez renseigner l'immatriculation", "red filledlight", "#ff0000", "uk-icon-close");
                return;
            }
            
            $.ajax({
                url: '/Profil-automobile?action=get_api_info',
                type: 'POST',
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('#api-search-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Recherche en cours...');
                },
                success: function(res) {
                    $('#api-search-btn').prop('disabled', false).html('Rechercher');
                    
                    if (res.status === 200) {
                        // Populate the form
                        populateVehicleForm(res.data.data);
                        
                        // Show the form
                        $('#vehicle-form-container').slideDown();
                        
                        popup_alert("Informations trouvées", "green filledlight", "#009900", "uk-icon-check");
                        
                        // Scroll to form
                        $('html, body').animate({
                            scrollTop: $("#vehicle-form-container").offset().top - 50
                        }, 500);
                    } else {
                        popup_alert(res.message || "Erreur lors de la recherche", "red filledlight", "#ff0000", "uk-icon-close");
                    }
                },
                error: function() {
                    $('#api-search-btn').prop('disabled', false).html('Rechercher');
                    popup_alert("Erreur lors de la recherche", "red filledlight", "#ff0000", "uk-icon-close");
                }
            });
        });
    }
    
    // Manual vehicle form
    if ($('#manual-vehicle-form').length > 0) {
        // Update US date when FR date changes
        $('#date1erCir_fr').on('change', function() {
            const frDate = $(this).val();
            if (frDate) {
                $('#date1erCir_us').val(frDate);
            }
        });
        
        // Fetch models when brand changes
        $('#marque').on('change', function() {
            const marque = $(this).val();
            if (marque) {
                $.ajax({
                    url: '/Profil-automobile?action=get_models',
                    type: 'POST',
                    data: { marque: marque },
                    success: function(data) {
                        $('#modeles-list').html(data);
                    }
                });
            }
        });
        
        // Trigger change if brand is already selected (editing mode)
        if ($('#marque').val()) {
            $('#marque').trigger('change');
        }
        
        // Form submission
        $('#manual-vehicle-form').on('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = $(this).serializeArray();
            const jsonData = {};
            
            $.each(formData, function(idx, field) {
                jsonData[field.name] = field.value;
            });
            
            // Add source flag
            jsonData.source = 'manual';
            
            // Submit the data
            $.ajax({
                url: '/Profil-automobile?action=save_vehicle',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(jsonData),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 200) {
                        popup_alert(response.message, "green filledlight", "#009900", "uk-icon-check");
                        setTimeout(function() {
                            window.location.href = '/Profil-automobile?action=list';
                        }, 1500);
                    } else {
                        let errorMsg = response.message;
                        if (response.missingFields) {
                            errorMsg += ': ' + response.missingFields.join(', ');
                            
                            // Highlight missing fields
                            $.each(response.missingFields, function(idx, field) {
                                $('#' + field).addClass('is-invalid');
                            });
                        }
                        
                        popup_alert(errorMsg, "red filledlight", "#ff0000", "uk-icon-close");
                    }
                },
                error: function() {
                    popup_alert('Erreur lors de l\'enregistrement des données', "red filledlight", "#ff0000", "uk-icon-close");
                }
            });
        });
        
        // Clear validation errors on input
        $('#manual-vehicle-form input, #manual-vehicle-form select').on('input change', function() {
            $(this).removeClass('is-invalid');
        });
    }
    
    // API form submission
    $('#vehicle-api-form').on('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = {};
        
        // Get all inputs
        $(this).find('input, select').each(function() {
            const field = $(this).attr('id').replace('aim_', '');
            formData[field] = $(this).val();
        });
        
        // Submit the data
        $.ajax({
            url: '/Profil-automobile?action=save_vehicle',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    popup_alert(response.message, "green filledlight", "#009900", "uk-icon-check");
                    setTimeout(function() {
                        window.location.href = '/Profil-automobile?action=list';
                    }, 1500);
                } else {
                    let errorMsg = response.message;
                    if (response.missingFields) {
                        errorMsg += ': ' + response.missingFields.join(', ');
                        
                        // Highlight missing fields
                        $.each(response.missingFields, function(idx, field) {
                            $('#aim_' + field).addClass('is-invalid');
                        });
                    }
                    
                    popup_alert(errorMsg, "red filledlight", "#ff0000", "uk-icon-close");
                }
            },
            error: function() {
                popup_alert('Erreur lors de l\'enregistrement des données', "red filledlight", "#ff0000", "uk-icon-close");
            }
        });
    });
    
    // Function to populate vehicle form from API response
    function populateVehicleForm(data) {
        const fields = [
            "immat", "co2", "energie", "energieNGC", "genreVCG", "genreVCGNGC",
            "puisFisc", "carrosserieCG", "marque", "modele", "date1erCir_us", "date1erCir_fr",
            "collection", "date30", "vin", "boite_vitesse", "puisFiscReel", "nr_passagers",
            "nb_portes", "type_mine", "couleur", "poids", "cylindres", "sra_id", "sra_group",
            "sra_commercial", "code_moteur", "k_type", "db_c", "date_dernier_control_tecnique",
            "erreur", "nbr_req_restants", "logo_marque"
        ];
        
        // Populate each field
        fields.forEach(field => {
            let value = data[field] || "";
            
            // Special handling for date fields
            if (field === "date_dernier_control_tecnique" && value) {
                value = new Date(value * 1000).toISOString().split('T')[0];
            }
            
            $('#aim_' + field).val(value);
        });
    }
});