<?php

$idaction = intval($_GET['idaction']); // Convert $idaction to integer

// Fetch profile details
$req_selectc = $bdd->prepare("SELECT * FROM membres_profils WHERE id_membre=?");
$req_selectc->execute(array($idaction));
$ligne_selectc = $req_selectc->fetch();
$req_selectc->closeCursor();

$id_profil = $ligne_selectc['titre_profil'];
$titre_profil = $ligne_selectc['titre_profil'];
$url_profil = $ligne_selectc['url_profil'];
$description = $ligne_selectc['description'];
$title = $ligne_selectc['title'];
$meta_description = $ligne_selectc['meta_description'];
$meta_keyword = $ligne_selectc['meta_keyword'];
$activer = $ligne_selectc['activer'];

// Fetch member details
$req_bouclem = $bdd->prepare("SELECT * FROM membres WHERE id=?");
$req_bouclem->execute(array($ligne_selectc['id_membre']));
$ligne_bouclem = $req_bouclem->fetch();
$pseudo = $ligne_bouclem['pseudo'];
$mail = $ligne_bouclem['mail'];
$image_profil = $ligne_bouclem['image_profil'];
$statut_compte = $ligne_bouclem['statut_compte'];
$adresse = $ligne_bouclem['adresse'];
$ville = $ligne_bouclem['ville'];
$cp = $ligne_bouclem['cp'];

// Fetch account type
$req_bouclemt = $bdd->prepare("SELECT * FROM membres_type_de_compte WHERE id=?");
$req_bouclemt->execute(array($statut_compte));
$ligne_bouclemt = $req_bouclemt->fetch();
$statut_compte = $ligne_bouclemt['Nom_type'];

// Fetch number of reviews
$req_avis = $bdd->prepare("SELECT COUNT(*) as nbr_avis FROM membres_avis WHERE id_membre = ?");
$req_avis->execute(array($idaction));
$ligne_avis = $req_avis->fetch();
$nbr_avis = $ligne_avis ? $ligne_avis['nbr_avis'] : 0;
$req_avis->closeCursor();

// Check if user is logged in and has rated
$userLoggedIn = !empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user);
$userHasRated = false;

if ($userLoggedIn) {
	$req_user_avis = $bdd->prepare("SELECT COUNT(*) as user_avis FROM membres_avis WHERE id_membre = ? AND id_auteur = ?");
	$req_user_avis->execute(array($idaction, $id_oo));
	$ligne_user_avis = $req_user_avis->fetch();
	$userHasRated = $ligne_user_avis ? $ligne_user_avis['user_avis'] > 0 : false;
	$req_user_avis->closeCursor();
}

// Calculate average rating
$req_avg_rating = $bdd->prepare("SELECT AVG(note) as avg_rating FROM membres_avis WHERE id_membre = ?");
$req_avg_rating->execute(array($idaction));
$ligne_avg_rating = $req_avg_rating->fetch();
$avg_rating = $ligne_avg_rating ? round($ligne_avg_rating['avg_rating'], 1) : 0;
$req_avg_rating->closeCursor();

// Function to generate star HTML
function generateStars($avg_rating)
{
	$fullStars = floor($avg_rating);
	$halfStar = ($avg_rating - $fullStars) >= 0.5 ? 1 : 0;
	$emptyStars = 5 - $fullStars - $halfStar;

	$starsHtml = str_repeat('<i class="fa fa-star text-warning"></i>', $fullStars);
	$starsHtml .= str_repeat('<i class="fa fa-star-half-alt text-warning"></i>', $halfStar);
	$starsHtml .= str_repeat('<i class="fa fa-star text-muted"></i>', $emptyStars);

	return $starsHtml;
}
?>

<script>
	$(document).ready(() => {
		// Handle geolocation for itinerary
		$('#itineraire').click(() => {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition((position) => {
					const latHere = position.coords.latitude;
					const lngHere = position.coords.longitude;
					$(location).attr("href", "https://www.google.com/maps/dir/" + latHere + "," + lngHere + "/<?php echo htmlspecialchars($adresse) ?>", "_blank");
				});
			} else {
				/* console.log("Geoloc is not supported by this browser."); */
				popup_alert("Geoloc n'est pas pris en charge par ce navigateur", "green filledlight", "#009900", "uk-icon-check");
			}
		});

		// Disable review button if user is not logged in or has already rated
		var userLoggedIn = <?php echo json_encode($userLoggedIn); ?>;
		var userHasRated = <?php echo json_encode($userHasRated); ?>;

		if (!userLoggedIn) {
			$('#avisButton').prop('disabled', true);
			$('#avisButtonMessage').text('Vous devez être connecté pour évaluer un utilisateur');
		}
		if (userHasRated) {
			$('#avisButton').prop('disabled', true);
			$('#avisButtonMessage').text('Vous avez déjà noté ce profil');
		}
	});
</script>

<section class="container" style="padding-top: 20px; padding-bottom: 20px;">
	<div class="row">
		<div class="col-lg-12">
			<div class="profile card card-body px-3 pt-3 pb-0">
				<div class="profile-head">
					<div class="profile-info">
						<div id="pp-avatar-container" class="profile-photo">
							<?php if (!empty($image_profil)) { ?>
								<img id="fi-avatar-img" class="img-fluid rounded-circle"
									src="/images/membres/<?php echo $pseudo; ?>/<?php echo $image_profil; ?>"
									alt="<?php echo $image_profil; ?>">
							<?php } else { ?>
								<img class="imageRadius2" src="/images/extra.jpg" alt="pro"
									class="img-fluid rounded-circle">
							<?php } ?>
							<div class="profile-rating" data-toggle="tooltip">
								<?php echo generateStars($avg_rating); ?>
							</div>
						</div>
						<div class="profile-details">
							<div class="profile-name px-3 pt-2">
								<h4 class="text-primary mb-0">Professionnel</h4><br>
								<p><?php echo $titre_profil; ?></p>
							</div>
							<div class="profile-email px-2 pt-2">
								<h4 class="text-muted mb-0">Type compte</h4> <br>
								<p><?php echo $statut_compte; ?></p>
							</div>
							<div class="dropdown-container">
								<div class="dropdown">
									<button class="dropbtn"></button>
									<div class="dropdown-content">
										<?php if (empty($user)) { ?>

											<a onclick="return false;" class="btn btn-primary mb-1 nav-link pxp-header-user" href="#">Message</a>

										<?php } else { ?>
											<a href="javascript:void(0);" data-bs-toggle="modal"
												data-bs-target="#addCloseFriendModal">
												<i class="fa fa-users me-2" style="color: #e3e151;"></i> Message
											</a>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-12">
			<div class="card">
				<div class="card-body">
					<div class="profile-statistics">
						<div class="text-center">
							<div class="row">
								<div class="col">
									<h3 class="m-b-0">Code postal</h3><span><?php echo $cp; ?></span>
								</div>
								<div class="col">
									<h3 class="m-b-0">Ville du professionnel</h3><span><?php echo $ville; ?></span>
									<div class="mt-4" style="display: flex;">
										<?php if (empty($user)) { ?>

											<a onclick="return false;" class=" btn btn-primary mb-1  nav-link pxp-header-user" style="margin: 0 auto;" href="#">Message</a>

										<?php } else { ?>
											<a href="javascript:void(0);" class="btn btn-primary mb-1 open-message-modal" data-id="123" data-nom="Jean Dupont">
												Message
											</a>
										<?php } ?>
									</div>
								</div>
								<div class="col">
									<h3 class="m-b-0">Nombre d'avis</h3><span><?php echo $nbr_avis; ?></span>
									<div class="mt-4">
										<button id="avisButton" class="btn btn-primary mb-1" data-bs-toggle="modal"
											data-bs-target="#avisAnnonceModal">Déposer un avis</button>
										<p id="avisButtonMessage" class="text-danger mt-2"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function() {
				$(".open-message-modal").on("click", function() {
					var idMembreDestinataire = $(this).attr("data-id");
					var nomUtilisateur = $(this).attr("data-nom");

					$.get("/pop-up/message/modal-envoyer-message.php", function(data) {
						$("body").append(data);
						$("#id_membre_destinataire").val(idMembreDestinataire);
						$("#nom_utilisateur").text(nomUtilisateur);
						$("#envoyerMessageModal").modal("show");
					});
				});
			});
		</script>



		<div class="col-xl-12">
			<div class="card">
				<div class="card-body">
					<div class="profile-news">
						<?php echo nl2br($description); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-12">
			<div class="card">
				<div class="card-body">
					<div class="profile-news">
						<div id="mapDiv" style="height: 272px; width: 100%;"></div>
					</div>
				</div>
			</div>
		</div>

	</div>

</section>

<script>
	function callbackfunc() {
		console.log('map instanciated')
	}
</script>

<script
	src="https://maps.google.com/maps/api/js?key=<?php echo $cle_api_google_i; ?>&libraries=places&callback=callbackfunc"
	type="text/javascript"></script>

<script async type="text/javascript">
	$(document).ready(() => {
		var styles = [{
				"featureType": "landscape.natural",
				"elementType": "geometry",
				"stylers": [{
						"color": "#ffffff"
					} // Light green color for forests
				]
			},
			{
				"featureType": "road",
				"elementType": "geometry",
				"stylers": [{
						"color": "#dea2ad"
					} // Light color for streets
				]
			}
		];

		var map = new google.maps.Map(document.getElementById('mapDiv'), {
			center: new google.maps.LatLng("<?php echo $ligne_bouclem['latitude']; ?>", "<?php echo $ligne_bouclem['longitude']; ?>"), // Initial coordinates
			zoom: 11, // Default zoom level
			mapTypeId: google.maps.MapTypeId.ROADMAP, // Map type
			mapTypeControl: true, // Enable map type controls
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR // Placement of map type controls
			},
			navigationControl: true, // Enable navigation controls
			navigationControlOptions: {
				style: google.maps.NavigationControlStyle.ZOOM_PAN // Placement of navigation controls
			},
			scrollwheel: true, // Enable scroll wheel
		});

		map.setOptions({
			styles: styles
		});

		//var icon_profil = "/images/";

		<?php if (!empty($ligne_bouclem['latitude']) && !empty($ligne_bouclem['longitude'])) { ?>
			var lat = parseFloat("<?php echo $ligne_bouclem['latitude']; ?>");
			var lng = parseFloat("<?php echo $ligne_bouclem['longitude']; ?>");
			var marker = new google.maps.Marker({
				map: map,
				position: {
					lat: lat,
					lng: lng
				},
				title: "<?php echo $ligne_bouclem['titre_profil']; ?>",
				icon: {
					<?php if (!empty($image_profil)) { ?>
						url: "/images/membres/<?php echo $pseudo; ?>/<?php echo $image_profil; ?>",
					<?php
					} else {
					?>
						url: "/images/extra.jpg",
					<?php } ?>
					scaledSize: new google.maps.Size(30, 30),
					origin: new google.maps.Point(0, 0)
				}
			});

		<?php } ?>

	});
</script>

<link rel="stylesheet" type="text/css" href="/pages/Plateforme/Fiche/fiche.css">
<script src="/pages/Plateforme/Fiche/fiche.js"></script>