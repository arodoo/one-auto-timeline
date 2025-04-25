<?php

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

//////////////////////////////Si carte map activée

if(empty($google_map_accueil_hauteur_en_px)){
$google_map_accueil_hauteur_en_px = "300";
}

?>
		<script type="text/javascript">

			function initialisation(){

				var centreCarte = new google.maps.LatLng(<?php echo $latitude_ii; ?>, <?php echo $longitude_ii; ?>);
				var optionsCarte =
          {
					zoom: 14,
					center: centreCarte,
          mapTypeControl: true,
          mapTypeControlOptions: { style: google.maps.MapTypeControlStyle.DROPDOWN_MENU },
          zoomControl: true,
          zoomControlOptions: { style: google.maps.ZoomControlStyle.SMALL },
          panControl: true,
          scrollwheel: false, 
          streetViewControl: true,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
          overviewMapControl: false,
          
          draggable : true,
          scaleControl: true
          }
				var maCarte = new google.maps.Map(document.getElementById("EmplacementDeMaCarte"), optionsCarte);
				var optionsMarqueur = {
					position: centreCarte,
					map: maCarte
				}
				var marqueur = new google.maps.Marker(optionsMarqueur);

				var contenuInfoBulle = "<?php echo $nom_proprietaire; ?>&nbsp;-" + "&nbsp;<?php echo $cp_dpt_ii; ?>&nbsp;<?php echo $ville_ii; ?>";
				var infoBulle = new google.maps.InfoWindow({ content: contenuInfoBulle });

                            infoBulle.open(maCarte, marqueur);
				google.maps.event.addListener(marqueur, 'click', function() { infoBulle.open(maCarte, marqueur); });
 
        var adUnitDiv = document.createElement('div');
        var adUnitOptions = {
          format: google.maps.adsense.AdFormat.SMALL_HORIZONTAL_LINK_UNIT,
          position: google.maps.ControlPosition.TOP_CENTER,
          map: maCarte,
          visible: true
        };
        var adUnit = new google.maps.adsense.AdUnit(adUnitDiv, adUnitOptions);

        }
	google.maps.event.addDomListener(window, 'load', initialisation)
 		</script>


<div id="EmplacementDeMaCarte" style="width: 100%; height: <?php echo "$google_map_accueil_hauteur_en_px"; ?>px;"></div>

<?php
//////////////////////////////Si carte map activée
?>
