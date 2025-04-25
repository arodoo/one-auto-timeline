<?php
session_start();
?>
<script
    src="https://maps.google.com/maps/api/js?key=<?php echo $cle_api_google_i; ?>&libraries=places&callback=callbackfunc"
    type="text/javascript"></script>

<?php
if (empty($_GET['idactionn'] == 1)) {
    header("HTTP/1.0 301 Moved Permanently");
    header("Location: /");
    exit();
}
?>

<div id="mapDiv" style="width: 100%; height: 400px;"></div>

<?php
include 'Plateforme-liste-etablissements.php';
?>

<div id="selectioninfos"> </div>

<script>
    $(document).ready(() => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const id_oo = <?php echo json_encode($id_oo); ?>;

                let latHere = position.coords.latitude;
                let lngHere = position.coords.longitude;
                let zoom = 10;

                if (id_oo == 8) {
                    latHere = 47.9108329;
                    lngHere = 1.9157977;
                    zoom = 6;
                }

                if (!localStorage.getItem('coordsSent')) {
                    //funct to send cords to the session
                    $.post('/pages/Plateforme/save-cords.php', {
                        lat: latHere,
                        lng: lngHere
                    }).done(function (data) {
                        localStorage.setItem('coordsSent', true);
                        location.reload();
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                    /*     console.log('error saving cords', errorThrown, textStatus); */
                        popup_alert("Erreur lors de l'enregistrement", "green filledlight", "#009900", "uk-icon-check");
                    });
                }

                if (!localStorage.getItem('lat') && !localStorage.getItem('lng')) {
                    zoom = 6; // SI PAS DE GEOLOC, ON DEZOOM SUR LA FRANCE
                }

                var styles = [{
                    "featureType": "landscape.natural",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#ffffff"
                    }]
                },
                {
                    "featureType": "road",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#dea2ad"
                    }]
                }];

                var map = new google.maps.Map(document.getElementById('mapDiv'), {
                    center: new google.maps.LatLng(latHere, lngHere),
                    zoom: zoom,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: true,
                    mapTypeControlOptions: {
                        style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                        position: google.maps.ControlPosition.TOP_CENTER,
                    },
                    navigationControl: true,
                    navigationControlOptions: {
                        style: google.maps.NavigationControlStyle.ZOOM_PAN
                    },
                    scrollwheel: false,
                });

                const allMarkers = [];
                const etablissements = <?= json_encode($etablissements) ?>;
            /*     console.log(etablissements); */

                map.setOptions({
                    styles: styles
                });

                etablissements.forEach(elem => {
                    if (elem.longitude && elem.latitude) {
                        var icon_profil = "/images/depanneur.png";
                        var marker = new google.maps.Marker({
                            map: map,
                            position: {
                                lat: parseFloat(elem.latitude),
                                lng: parseFloat(elem.longitude)
                            },
                            title: "Déménageur : " + elem.prenom + " " + elem.nom,
                            icon: icon_profil,
                            scaledSize: new google.maps.Size(30, 30),
                            origin: new google.maps.Point(0, 0)
                        });
                        allMarkers.push({
                            marker: marker
                        });

                        marker.addListener("click", () => {
                            $.post({
                                url: '/pages/Plateforme/pop-up-selection-ajax.php',
                                data: {
                                    idaction: elem.id
                                },
                                type: 'POST',
                                dataType: "html",
                                success: (res) => {
                                    $('#selectioninfos').html(res);
                                    $('#modalNom1').modal('show');
                                }
                            });
                        });
                    }
                });

                var icon_mission = "/images/utilisateur.png";
                var marker = new google.maps.Marker({
                    map: map,
                    position: {
                        lat: parseFloat(latHere),
                        lng: parseFloat(lngHere)
                    },
                    title: "Vous",
                    icon: icon_mission,
                    scaledSize: new google.maps.Size(30, 30),
                    origin: new google.maps.Point(0, 0)
                });
                allMarkers.push({
                    marker: marker
                });

                // Ajouter un cercle pour représenter le rayon de recherche
                var circle = new google.maps.Circle({
                    map: map,
                    radius: <?= $radiusKm ?> * 1000, // Convertir en mètres
                    fillColor: '#AA0000'
                });
                circle.bindTo('center', marker, 'position');

                // Fonction pour ajuster la taille de l'icône en fonction du niveau de zoom
                function adjustIconSize(zoomLevel) {
                    var size = 30; // Taille de base de l'icône
                    var newSize = 20;
                    return new google.maps.Size(newSize, newSize);
                }

                google.maps.event.addListener(map, 'zoom_changed', function () {
                    var currentZoom = map.getZoom();
                    marker.setIcon({
                        url: icon_mission,
                        scaledSize: adjustIconSize(currentZoom),
                        origin: new google.maps.Point(0, 0)
                    });
                });

                $(document).ready(function () {
                    $(document).on("click", ".popupselectionextra", function () {
                        $.post({
                            url: '/pages/Plateforme/pop-up-selection-ajax.php',
                            data: {
                                idaction: $(this).attr("data-id")
                            },
                            type: 'POST',
                            dataType: "html",
                            success: (res) => {
                                $('#selectioninfos').html(res);
                                $('#modalNom1').modal('show');
                            }
                        });
                    });

                    $(document).on("click", ".btnFermerModal, .close", function () {
                        $('.modal').modal('hide');
                    });
                });
            });
        } else {
            /* console.log("Geoloc is not supported by this browser."); */
            popup_alert("Geoloc is not supported by this browser", "green filledlight", "#009900", "uk-icon-check");
        }

        $(document).on("click", "#envoyer_devis button[type='submit']", function (event) {
            event.preventDefault(); // Prevent the default form submission

            $.post({
                url: '/pages/Plateforme/pop-up-selection-ajax-action.php',
                data: {
                    type: "dépannage",
                    idaction: $("input[name='idaction']").val(),
                    objet_de_la_demande: $("#objet_de_la_demande").val(),
                    description_de_la_demande: $("#description_de_la_demande").val()
                },
                type: 'POST',
                dataType: "json",
                success: function (response) {
                    if (response.retour_validation === 'ok') {
                        popup_alert(response.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
                        $('#modalNom1').modal('hide');
                    } else {
                        popup_alert(response.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                    }
                },
                error: function (xhr, status, error) {
              /*       console.error('Error:', error); */
                    alert('Une erreur s\'est produite. Veuillez réessayer.');
                }
            });
        });

    });
</script>