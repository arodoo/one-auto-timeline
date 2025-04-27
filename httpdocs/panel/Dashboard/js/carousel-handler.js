$(document).ready(function() {
    // Initialize each carousel type with a small delay to fix initial loading issue
    setTimeout(() => {
        loadCarouselData('mechanics', '.mechanics-carousel');
        loadCarouselData('services', '.services-carousel');
        loadCarouselData('control', '.control-carousel');
    }, 100);
    
    // Function to load data and initialize carousel
    function loadCarouselData(type, selector) {
        $.ajax({
            url: '/panel/Dashboard/ajax/get-carousel-data.php',
            method: 'POST',
            data: { type: type },
            dataType: 'json',
            success: function(response) {
                try {
                    const carousel = $(selector);
                    
                    // Clear loading spinner
                    carousel.empty();
                    
                    if (response.status === 'success' && response.items && response.items.length > 0) {
                        // Add items to carousel
                        for (const item of response.items) {
                            // Ensure data is valid
                            if (!item || typeof item !== 'object') continue;
                            
                            // Default values for missing data
                            const imageUrl = item.image || '/images/no-avatar.png';
                            const title = item.title || 'Sans titre';
                            const description = item.description || 'Aucune description disponible';
                            
                            // Fix URL format to match https://mon-espace-auto.com/Fiche/{user-type}/{user-id}
                            let detailUrl = '#';
                            if (item.id && item.type_account) {
                                // Format the user type for URL (lowercase, replace spaces with hyphens)
                                const formattedType = formatUserTypeForUrl(item.type_account);
                                detailUrl = `https://mon-espace-auto.com/Fiche/${formattedType}/${item.id}`;
                            } else if (item.id_membre) {
                                // If we have a member ID but not type, fetch from default URL with ID
                                detailUrl = `https://mon-espace-auto.com/Fiche/utilisateur/${item.id_membre}`;
                            } else if (item.url) {
                                detailUrl = item.url;
                            }
                            
                            const carouselItem = `
                                <div class="item">
                                    <div class="card mb-0">
                                        <img src="${imageUrl}" class="card-img-top" alt="${title}" style="height: 180px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title">${title}</h5>
                                            <p class="card-text">${description.substring(0, 80)}${description.length > 80 ? '...' : ''}</p>
                                        </div>
                                        <div class="card-footer">
                                            <a href="${detailUrl}" class="btn btn-sm btn-primary">Voir détails</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            carousel.append(carouselItem);
                        }
                        
                        // Initialize Owl Carousel after adding items
                        setTimeout(() => {
                            if (carousel.find('.item').length > 0) {
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
                        }, 50);
                    } else {
                        carousel.html('<div class="alert alert-info">Aucune donnée disponible</div>');
                    }
                } catch (parseError) {
                    console.error('Error parsing carousel data:', parseError);
                    $(selector).html('<div class="alert alert-danger">Erreur de format</div>');
                }
            },
            error: function(xhr, status, error) {
                $(selector).html('<div class="alert alert-danger">Erreur de chargement des données</div>');
                console.error('Error loading carousel data:', error);
            }
        });
    }
    
    // Helper function to format user type for URL
    function formatUserTypeForUrl(userType) {
        if (!userType) return 'utilisateur';
        
        // Map of French user types to URL-friendly format
        const typeMapping = {
            'Utilisateur': 'utilisateur',
            'Dépanneur': 'd-panneur',
            'Professionnel de la mécanique': 'mecanicien',
            'Professionnel de la carrosserie': 'carrosserie',
            'Professionnel de la vente': 'vendeur',
            'Professionnel du service': 'service',
            'Centre contrôle technique': 'controle-technique'
        };
        
        return typeMapping[userType] || userType.toLowerCase().replace(/\s+/g, '-').replace(/[éèê]/g, 'e');
    }
});