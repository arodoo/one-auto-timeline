$(document).ready(function() {
    // Initialize each carousel type
    loadCarouselData('mechanics', '.mechanics-carousel');
    loadCarouselData('services', '.services-carousel');
    loadCarouselData('control', '.control-carousel');
    
    // Function to load data and initialize carousel
    function loadCarouselData(type, selector) {
        $.ajax({
            url: '/panel/Dashboard/ajax/get-carousel-data.php',
            method: 'POST',
            data: { type: type },
            dataType: 'json',
            success: function(response) {
                const carousel = $(selector);
                
                // Clear loading spinner
                carousel.empty();
                
                if (response.status === 'success' && response.items && response.items.length > 0) {
                    // Add items to carousel
                    for (const item of response.items) {
                        const imageUrl = item.image || '/images/avatars/mechanic.png';
                        
                        const carouselItem = `
                            <div class="item">
                                <div class="card mb-0">
                                    <img src="${imageUrl}" class="card-img-top" alt="${item.title}">
                                    <div class="card-body">
                                        <h5 class="card-title">${item.title}</h5>
                                        <p class="card-text">${item.description.substring(0, 80)}${item.description.length > 80 ? '...' : ''}</p>
                                    </div>
                                    <div class="card-footer">
                                        <a href="${item.url}" class="btn btn-sm btn-primary">Voir détails</a>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        carousel.append(carouselItem);
                    }
                    
                    // Initialize Owl Carousel after adding items
                    carousel.owlCarousel({
                        loop: true,
                        margin: 15,
                        nav: true,
                        dots: false,
                        responsive: {
                            0: { items: 1 },
                            768: { items: 1 },
                            992: { items: 1 }
                        },
                        navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>']
                    });
                } else {
                    carousel.html('<div class="alert alert-info">Aucune donnée disponible</div>');
                }
            },
            error: function(xhr, status, error) {
                $(selector).html('<div class="alert alert-danger">Erreur de chargement des données</div>');
                console.error('Error loading carousel data:', error);
            }
        });
    }
});