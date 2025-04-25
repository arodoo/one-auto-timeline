$(document).ready(function() {
    // Initialize document form submission
    $('#documentForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append('action', 'addDocument');
        
        $.ajax({
            url: '/panel/Dashboard/ajax/document-actions.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        $('#addDocumentModal').modal('hide');
                        popup_alert(data.message, "green filledlight", "#009900", "uk-icon-check");
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        popup_alert(data.message, "red filledlight", "#ff0000", "uk-icon-close");
                    }
                } catch (e) {
                    popup_alert("Une erreur est survenue", "red filledlight", "#ff0000", "uk-icon-close");
                }
            },
            error: function() {
                popup_alert("Une erreur de connexion est survenue", "red filledlight", "#ff0000", "uk-icon-close");
            }
        });
    });
    
    // Delete document action
    $(document).on('click', '.delete-document', function(e) {
        e.preventDefault();
        
        const documentId = $(this).data('id');
        
        if (confirm('Êtes-vous sûr de vouloir supprimer ce document ?')) {
            $.ajax({
                url: '/panel/Dashboard/ajax/document-actions.php',
                type: 'POST',
                data: {
                    action: 'deleteDocument',
                    document_id: documentId
                },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.status === 'success') {
                            popup_alert(data.message, "green filledlight", "#009900", "uk-icon-check");
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            popup_alert(data.message, "red filledlight", "#ff0000", "uk-icon-close");
                        }
                    } catch (e) {
                        popup_alert("Une erreur est survenue", "red filledlight", "#ff0000", "uk-icon-close");
                    }
                },
                error: function() {
                    popup_alert("Une erreur de connexion est survenue", "red filledlight", "#ff0000", "uk-icon-close");
                }
            });
        }
    });
    
    // Load carousel data
    function loadCarouselData(carouselType, selector) {
        $.ajax({
            url: '/panel/Dashboard/ajax/get-carousel-data.php',
            type: 'POST',
            data: { type: carouselType },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        $(selector).html('');
                        
                        if (data.items.length === 0) {
                            $(selector).html('<div class="alert alert-info">Aucune annonce disponible</div>');
                            return;
                        }
                        
                        data.items.forEach(function(item) {
                            const card = `
                                <div class="item">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h5 class="card-title">${item.title}</h5>
                                            <p class="card-text">${item.description.substring(0, 50)}...</p>
                                            <a href="${item.url}" class="btn btn-sm btn-primary">Voir plus</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            $(selector).append(card);
                        });
                        
                        // Initialize Owl Carousel
                        $(selector).owlCarousel({
                            items: 1,
                            loop: data.items.length > 1,
                            margin: 10,
                            autoplay: true,
                            autoplayTimeout: 3000,
                            autoplayHoverPause: true,
                            dots: true,
                            nav: false
                        });
                    } else {
                        $(selector).html('<div class="alert alert-warning">Erreur de chargement</div>');
                    }
                } catch (e) {
                    $(selector).html('<div class="alert alert-danger">Erreur de format</div>');
                }
            },
            error: function() {
                $(selector).html('<div class="alert alert-danger">Erreur de connexion</div>');
            }
        });
    }
    
    // Load carousel data for each type
    loadCarouselData('mechanics', '.mechanics-carousel');
    loadCarouselData('services', '.services-carousel');
    loadCarouselData('control', '.control-carousel');
});