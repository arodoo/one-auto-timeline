<?php
ob_start();





	$req_select_annonce_ct = $bdd->prepare("SELECT * FROM membres_annonces_ct WHERE id=?");
	$req_select_annonce_ct->execute(array($_GET['idaction']));
	$ligne_select_annonce_ct = $req_select_annonce_ct->fetch();
	$req_select_annonce_ct->closeCursor();


	$req_select_annonce_ct = $bdd->prepare("SELECT * FROM membres_annonces_ct_images WHERE id_annonce_service=?");
	$req_select_annonce_ct->execute(array($_GET['idaction']));
	$select_annonces_ct_images = $req_select_annonce_ct->fetchAll();
	$req_select_annonce_ct->closeCursor();


	$req_bouclem = $bdd->prepare("SELECT * FROM membres WHERE id=?");
	$req_bouclem->execute(array($ligne_select_annonce_ct['id_membre']));
	$ligne_bouclem = $req_bouclem->fetch();
	$pseudo = $ligne_bouclem['pseudo'];
	$prenom = $ligne_bouclem['prenom'];
	$mail = $ligne_bouclem['mail'];
	$image_profil = $ligne_bouclem['image_profil'];
	$statut_compte = $ligne_bouclem['statut_compte'];
	$adresse = $ligne_bouclem['adresse'];
	$ville = $ligne_bouclem['ville'];
	$cp = $ligne_bouclem['cp'];

	$req_select_avis_annonce = $bdd->prepare("SELECT COUNT(*) as total_avis, AVG(note) as avg_note FROM membres_avis WHERE id_page=? AND type='annonce'");
	$req_select_avis_annonce->execute(array($_GET['idaction']));
	$ligne_avis_annonce = $req_select_avis_annonce->fetch();
	$req_select_avis_annonce->closeCursor();

	$total_avis = $ligne_avis_annonce['total_avis'];
	$avg_note = round($ligne_avis_annonce['avg_note']);

	$stmt = $bdd->prepare("
	SELECT 
		i.id AS image_id,
		i.nom_image AS image_name,
		a.id AS annonce_id,
		a.nom AS annonce_nom,
		a.description AS annonce_description,
		a.prix AS annonce_prix,
		a.pseudo,
		COALESCE(AVG(n.note), 0) AS stmt_avg_note, 
		COALESCE(COUNT(n.id), 0) AS stmt_total_reviews
	FROM 
		membres_annonces_ct_images i
	INNER JOIN 
		membres_annonces_ct a ON i.id_annonce_service = a.id 
	LEFT JOIN 
		membres_avis n ON a.id = n.id_page
	WHERE 
		a.statut = 'activé' AND a.id = :idaction AND n.type = 'service'
	GROUP BY 
		a.id, i.id
	ORDER BY 
		a.id DESC;
");

	$stmt->bindParam(':idaction', $_GET['idaction'], PDO::PARAM_INT);
	$stmt->execute();

	$stmt_result = $stmt->fetch();
	$stmt_avg_note = $stmt_result['stmt_avg_note'];
	$stmt_total_reviews = $stmt_result['stmt_total_reviews'];

/* 	var_dump($stmt_total_reviews);
	var_dump($stmt_avg_note);
	var_dump($_GET['idaction']); */

	$req_select_avis = $bdd->prepare("SELECT id, id_auteur, note, commentaire, date FROM membres_avis WHERE id_page=? AND type='service'");
	$req_select_avis->execute(array($_GET['idaction']));
	$avis = $req_select_avis->fetchAll();
	$req_select_avis->closeCursor();

	$authors = [];
	foreach ($avis as $avi) {
		$req_select_author = $bdd->prepare("SELECT nom, prenom FROM membres WHERE id=?");
		$req_select_author->execute(array($avi['id_auteur']));
		$author = $req_select_author->fetch();
		$req_select_author->closeCursor();
		$authors[$avi['id_auteur']] = $author;
	}

	function truncate_comment($comment, $length = 250)
	{
		if (strlen($comment) > $length) {
			return substr($comment, 0, $length) . '...';
		}
		return $comment;
	}
?>

	<script>
		$(document).ready(function() {

			$(document).on("click", "#envoyer_devis button[type='submit']", function(event) {
				event.preventDefault(); // Prevent the default form submission

				$.post({
					url: '/pages/Plateforme/pop-up-selection-ajax-action.php',
					data: {
						type: "service",
						idaction: $("input[name='idaction']").val(),
						objet_de_la_demande: $("#objet_de_la_demande").val(),
						description_de_la_demande: $("#description_de_la_demande").val()
					},
					type: 'POST',
					dataType: "json",
					success: function(response) {
						if (response.retour_validation === 'ok') {
							popup_alert(response.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
							$('#modalNom1').modal('hide');
						} else {
							popup_alert(response.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
						}
					},
					/* 	error: function(xhr, status, error) {
							console.error('Error:', error);
							alert('Une erreur s\'est produite. Veuillez réessayer.');
						} */
				});
			});

			// Script para eliminar un avis
			$(document).on('click', '.delete-avis', function(event) {
				event.preventDefault(); // Prevent the default link behavior

				var avisId = $(this).data('id');

				$.ajax({
					url: '/pages/Services/Services-avis-supprimer.php',
					type: 'POST',
					data: {
						id: avisId
					},
					dataType: "json",
					success: function(response) {
						if (response.retour_validation === 'ok') {
							popup_alert(response.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
							location.href = "";
						} else {
							popup_alert(response.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
						}
					},
					/* error: function(xhr, status, error) {
						console.error('Error:', error);
						alert('Une erreur s\'est produite. Veuillez réessayer.');
					} */
				});
			});
		});

		// Script pour sélectionner des étoiles et envoyer une évaluation
		$(document).ready(function() {
			let selectedStars = 0;

			// Événement de clic sur les étoiles
			$('#stars li').on('click', function() {
				var clickedStar = parseInt($(this).data('value'), 10); // Obtenir la valeur de l'étoile sélectionnée
				var stars = $(this).parent().children('li.star');

				// Si on clique sur la même étoile déjà sélectionnée, désélectionner
				if (clickedStar === selectedStars) {
					stars.removeClass('selected star-selected');
					stars.find('i').css('color', '#d8d8d8'); // Couleur pour les étoiles non sélectionnées
					selectedStars = 0; // Réinitialiser la sélection
				} else {
					// Mettre à jour la sélection et colorer jusqu'à l'étoile sélectionnée
					selectedStars = clickedStar;
					stars.removeClass('selected star-selected');
					stars.find('i').css('color', '#d8d8d8');
					for (var i = 0; i < clickedStar; i++) {
						$(stars[i]).addClass('selected star-selected');
						$(stars[i]).find('i').css('color', 'rgb(227, 213, 0)'); // Couleur jaune pour les étoiles sélectionnées
					}
				}

				/* console.log("Étoiles sélectionnées : " + selectedStars);  */
			});


			// Envoyer la note sélectionnée
			$('#btn-deposer-avis').on('click', function(event) {
				event.preventDefault(); // Prevent the default form submission

				var data = {
					id_annonce: <?= json_encode($_GET['idaction']) ?>,
					valoracion: selectedStars,
					review_text: $('#review-text').val()
				};

				/* console.log("Datos enviados:", data);  */

				$.post({
					url: '/pages/Centres-controles-techniques/Centres-controles-techniques-avis.php',
					data: data,
					type: 'POST',
					dataType: "json",
					success: function(response) {
						if (response.status === "success") {
							popup_alert("Valorisation envoyée avec succès.", "green filledlight", "#009900", "uk-icon-check");

						} else {
							popup_alert("Erreur lors de l'envoi de la valorisation.", "#CC0000 filledlight", "#CC0000", "uk-icon-times");
						}
					},

				});
			});
		});
	</script>

	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-xl-6 col-lg-6  col-md-6 col-xxl-5 ">
							<!-- Tab panes -->
							<div class="tab-content" id="myTabContent">
								<?php foreach ($select_annonces_ct_images as $index => $image): ?>
									<div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="tab-pane-<?= $index ?>" role="tabpanel" aria-labelledby="tab-<?= $index ?>" tabindex="0">
										<img class="img-fluid rounded" src="/images/membres/<?= htmlspecialchars($pseudo) ?>/<?= htmlspecialchars($image['nom_image']) ?>" alt="<?= htmlspecialchars($ligne_select_annonce_ct['title']) ?>">
									</div>
								<?php endforeach; ?>
							</div>
							<ul class="nav nav-tabs slide-item-list mt-3" id="myTab" role="tablist">
								<?php foreach ($select_annonces_ct_images as $index => $image): ?>
									<li class="nav-item" role="presentation">
										<a href="#tab-pane-<?= $index ?>" class="nav-link <?= $index === 0 ? 'active' : '' ?>" id="tab-<?= $index ?>" data-bs-toggle="tab" data-bs-target="#tab-pane-<?= $index ?>" role="tab" aria-controls="tab-pane-<?= $index ?>" aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">
											<img class="img-fluid me-2 rounded" src="/images/membres/<?= htmlspecialchars($pseudo) ?>/<?= htmlspecialchars($image['nom_image']) ?>" alt="<?= htmlspecialchars($ligne_select_annonce_ct['title']) ?>" width="80">
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

						<div class="col-xl-6 col-lg-6  col-md-6 col-xxl-7 col-sm-12">
							<div class="product-detail-content">
								<!--Product details-->
								<div class="new-arrival-content pr mt-md-0 mt-3">
									<h2><?= htmlspecialchars($ligne_select_annonce_ct['nom']) ?></h2>
									<div class="comment-review star-rating">
										<ul>
											<?php for ($i = 1; $i <= 5; $i++): ?>
												<li><i class="fa fa-star <?= $i <= $stmt_avg_note ? 'selected' : '' ?>" style="color: <?= $i <= $stmt_avg_note ? '#ffc107' : '#d8d8d8' ?>"></i></li>
											<?php endfor; ?>
										</ul>
										<span class="review-text">(<?= htmlspecialchars($stmt_total_reviews) ?> avis) </span>
										<?php if (empty($user)): ?>
											<a class="product-review pxp-header-user" href="#" onclick="return false;">Déposer un avis ?</a>
										<?php else: ?>
											<a class="product-review" href="#" data-bs-toggle="modal" data-bs-target="#reviewModal">Déposer un avis ?</a>
										<?php endif; ?>
									</div>
									<div class="d-table mb-2">
										<p class="price" style="font-size: 30px; text-align: left;">€<?= htmlspecialchars($ligne_select_annonce_ct['prix']) ?></p>
									</div>
									<!-- <div class="d-table mb-2" style="margin-top: 10px;">
										<p class="price" style="font-size: 24px; text-align: left;">02 38 00 00 00</p>
									</div> -->
									<!-- <p>Catégorie : <span class="item">Centre de contrôle technique</span> </p> -->
									<p>Centre : <span class="item"><?php echo $prenom; ?></span></p>
									<p class="text-content">
										<?php echo $ligne_select_annonce_ct['description']; ?>
									</p>
									<div class="shopping-cart" style="width: 100%; margin-top: 20px;">
										<a class="btn btn-primary <?= !empty($user) ? "btn-message" : "pxp-header-user" ?> "
											data-id="<?php echo $ligne_select_annonce_ct['id_membre']; ?>"
											data-nom="<?php echo $pseudo; ?>"
											href="javascript:void(0);">
											<i class="fa fa-envelope me-2"></i> Message
										</a>

									</div>
									<div id="mapDiv" style="height: 272px; width: 100%;"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="filter cm-content-box box-primary">
						<div class="content-title SlideToolHeader">
							<div class="cpa">
								<i class="fa-solid fa-envelope me-1"></i> Liste des avis
							</div>
							<div class="tools">
								<a href="javascript:void(0);" class="expand handle"><i
										class="fal fa-angle-down"></i></a>
							</div>
						</div>
						<div class="cm-content-body form excerpt">
							<div class="card-body pb-4">
								<div class="container">
									<?php if (empty($avis)): ?>
										<p>Aucun avis n'a été trouvé.</p>
									<?php else: ?>
										<?php foreach ($avis as $avi): ?>
											<div class="row section-avis" style="border-bottom: 1px solid;">
												<div class="col-sm-12 col-md-2 d-flex flex-column align-items-start">
													<strong>Auteur:</strong>
													<span><?= htmlspecialchars($authors[$avi['id_auteur']]['nom'] . ' ' . $authors[$avi['id_auteur']]['prenom']) ?></span>
												</div>
												<div class="col-sm-12 col-md-3 d-flex flex-column align-items-start">
													<strong>Note:</strong>
													<div class="comment-review star-rating" style="display: block;">
														<ul>
															<?php for ($i = 1; $i <= 5; $i++): ?>
																<li>
																	<i class="fa fa-star <?= $i <= $avi['note'] ? 'selected' : '' ?>"
																		style="color: <?= $i <= $avi['note'] ? '#ffc107' : '#d8d8d8' ?>"></i>
																</li>
															<?php endfor; ?>
														</ul>
													</div>
												</div>
												<div class="col-sm-12 col-md-4 d-flex flex-column align-items-start">
													<strong>Commentaire:</strong>
													<span><?= htmlspecialchars(truncate_comment($avi['commentaire'])) ?></span>
												</div>
												<div class="col-sm-12 col-md-2 d-flex flex-column align-items-start">
													<strong>Date:</strong>
													<span><?= date("d-m-Y", $avi['date']) ?></span>
												</div>
												<?php if ($admin_oo == 1): ?>
													<div class="col-sm-12 col-md-1 d-flex flex-column align-items-start">
														<a href="#" class="delete-avis" data-id="<?= htmlspecialchars($avi['id']) ?>">
															<i class="fa fa-trash" style="color: red;"></i>
														</a>
													</div>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>

							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<div id="selectioninfos"> </div>

	<!-- Modal avis -->
	<div class="modal fade" id="reviewModal">
		<div class="modal-dialog modal-dialog-center" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Déposer une note</h4>
					<button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
				</div>
				<div class="modal-body">
					<form method="post" action="#">
						<div class="form-group">
							<div class="rating-widget mb-4 text-center">
								<div class="rating-stars">
									<ul id="stars">
										<li class="star" title="Poor" data-value="1">
											<i class="fa fa-star fa-fw"></i>
										</li>
										<li class="star" title="Fair" data-value="2">
											<i class="fa fa-star fa-fw"></i>
										</li>
										<li class="star" title="Good" data-value="3">
											<i class="fa fa-star fa-fw"></i>
										</li>
										<li class="star" title="Excellent" data-value="4">
											<i class="fa fa-star fa-fw"></i>
										</li>
										<li class="star" title="WOW!!!" data-value="5">
											<i class="fa fa-star fa-fw"></i>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="review-text">Votre avis</label>
							<textarea id="review-text" class="form-control" rows="4" placeholder="Écrivez votre avis ici..."></textarea>
						</div>
						<button id="btn-deposer-avis" class="btn btn-success btn-block">Enregistrer</button>
					</form>
				</div>
			</div>
		</div>
	</div>



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

	<script>
		$(document).ready(function() {

			$(".btn-message").on("click", function() {
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

<?php

?>