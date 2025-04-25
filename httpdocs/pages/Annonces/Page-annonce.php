<?php

$req_select_annonce = $bdd->prepare("SELECT * FROM membres_annonces WHERE id=?");
$req_select_annonce->execute(array($_GET['idaction']));
$ligne_select_annonce = $req_select_annonce->fetch();
$req_select_annonce->closeCursor();


//Id de la catégorie du produit
$id_categorie = $ligne_select_annonce['id_categorie'];


//Requête pour obtenir la catégorie du produit
$req_select_categorie = $bdd->prepare("SELECT * FROM configurations_categories WHERE id=?");
$req_select_categorie->execute(array($id_categorie));
$ligne_categorie = $req_select_categorie->fetch();
$req_select_categorie->closeCursor();


$req_select_produit_img = $bdd->prepare("SELECT * FROM membres_annonces_images WHERE id_annonce_service=?");
$req_select_produit_img->execute(array($_GET['idaction']));
$select_produit_img = $req_select_produit_img->fetch();
$req_select_produit_img->closeCursor();

$nom_image = $select_produit_img['nom_image'];
$pseudo = $select_produit_img['pseudo'];


//évaluation des annonces
$req_select_avis_annonce = $bdd->prepare("SELECT COUNT(*) as total_avis, AVG(note) as avg_note FROM membres_avis WHERE id_page=? AND type='annonce'");
$req_select_avis_annonce->execute(array($_GET['idaction']));
$ligne_avis_annonce = $req_select_avis_annonce->fetch();
$req_select_avis_annonce->closeCursor();

$total_avis = $ligne_avis_annonce['total_avis'];
$avg_note = round($ligne_avis_annonce['avg_note']);

$date_creation = date("d-m-Y", $ligne_select_annonce['date|']);

// Récupère les notes des utilisateurs
$req_select_avis = $bdd->prepare("SELECT id, id_auteur, note, commentaire, date FROM membres_avis WHERE id_page=? AND type='annonce'");
$req_select_avis->execute(array($_GET['idaction']));
$avis = $req_select_avis->fetchAll();
$req_select_avis->closeCursor();

// Récupère les noms des auteurs
$authors = [];
foreach ($avis as $avi) {
	$req_select_author = $bdd->prepare("SELECT nom, prenom FROM membres WHERE id=?");
	$req_select_author->execute(array($avi['id_auteur']));
	$author = $req_select_author->fetch();
	$req_select_author->closeCursor();
	$authors[$avi['id_auteur']] = $author;
}

// Fonction pour tronquer le commentaire
function truncate_comment($comment, $length = 250)
{
	if (strlen($comment) > $length) {
		return substr($comment, 0, $length) . '...';
	}
	return $comment;
}

?>

<style>
	ul#stars {
		list-style-type: none;
		padding: 0;
		margin: 0;
	}

	ul#stars li.star {
		display: inline-block;
		font-size: 25px;
		color: #d8d8d8;
		cursor: pointer;
	}

	ul#stars li.star.selected {
		color: #ffc107;
	}

	.star-selected {
		color: #ffeb3b;
	}

	ul#stars li.star.hover {
		color: #ffeb3b;
	}
</style>
<script>
	$(document).ready(function() {
		$(document).on("click", "[data-target='#ajouterModal']", function(event) {
			event.preventDefault();
			$("#ajouterModal").modal("show");
		});
	});


	$(document).ready(function() {

		$(document).on("click", "#envoyer_devis button[type='submit']", function(event) {
			event.preventDefault(); // Prevent the default form submission

			$.post({
				url: '/pages/Plateforme/pop-up-selection-ajax-action.php',
				data: {
					type: "annonce",
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
				error: function(xhr, status, error) {
					console.error('Error:', error);
					alert('Une erreur s\'est produite. Veuillez réessayer.');
				}
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
				error: function(xhr, status, error) {
					console.error('Error:', error);
					alert('Une erreur s\'est produite. Veuillez réessayer.');
				}
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
				url: '/pages/Annonces/Annonces-avis.php',
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

<script>
	// Script pour envoyer un message de demande
	$(document).ready(function() {
		$(document).on("click", "#bouton", function(event) {
			event.preventDefault();
			tinyMCE.triggerSave();
			var formData = new FormData($("#formulaire-ajouter")[0]);
			formData.append('id_type_compte_categorie', <?= json_encode($id_categorie) ?>);
			formData.append('action', <?= json_encode($_GET['action'] == 'modifier' ? 'modifier-action' : 'ajouter-action') ?>);
			formData.append('id_service', <?= json_encode($_GET['idaction']) ?>);
			formData.append('objet_de_la_demande', $("#nom").val());
			formData.append('description_de_la_demande', $("#description").val());
			formData.append('type', 'annonce');
			if (<?= json_encode($_GET['action'] == 'modifier') ?>) {
				formData.append('idaction', <?= json_encode($_GET['idaction']) ?>);
			}
			$.post({
				url: '/pages/Annonces/Annonces-popup-ajax.php',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				dataType: "json",
				success: function(response) {
					if (response.retour_validation == "ok") {
						popup_alert(response.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
						$("#formulaire-ajouter")[0].reset();
						handleTimer(100);
					} else {
						popup_alert(response.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
					}
				},
				/* error: function(jqXHR, textStatus, errorThrown) {
					let errorMsg = "Une erreur est survenue : " + textStatus + " - " + errorThrown + " - " + jqXHR.responseText;
					popup_alert(errorMsg, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
					console.log("Erreur:", errorMsg);
				} */
			});
			$("html, body").animate({
				scrollTop: 0
			}, "slow");
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
							<div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel"
								aria-labelledby="home-tab" tabindex="0">
								<img class="img-fluid rounded  " src="/images/membres/<?= htmlspecialchars($ligne_select_annonce['pseudo']) ?>/<?= htmlspecialchars($nom_image) ?>" alt="<?= htmlspecialchars($ligne_select_annonce['title']) ?>">
							</div>
						</div>
					</div>

					<div class="col-xl-6 col-lg-6  col-md-6 col-xxl-7 col-sm-12">
						<div class="product-detail-content">
							<!--Product details-->
							<div class="new-arrival-content pr mt-md-0 mt-3">
								<h2><?= htmlspecialchars($ligne_select_annonce['title']) ?></h2>
								<div class="comment-review star-rating">
									<ul>
										<?php for ($i = 1; $i <= 5; $i++): ?>
											<li><i class="fa fa-star <?= $i <= $avg_note ? 'selected' : '' ?>" style="color: <?= $i <= $avg_note ? '#ffc107' : '#d8d8d8' ?>"></i></li>
										<?php endfor; ?>
									</ul>
									<span class="review-text avis-review">(<?= htmlspecialchars($total_avis) ?> avis) </span>
									<?php
									if (empty($user)) {
									?>
										<a class="product-review pxp-header-user" href="#" onclick="return false;">Déposer un avis ?</a>
									<?php
									} else {
									?>
										<a class="product-review" href="#" data-bs-toggle="modal" data-bs-target="#reviewModal">Déposer un avis ?</a>
									<?php
									}
									?>
								</div>
								<p>Date de création : <span class="item"><?= htmlspecialchars($date_creation) ?></span> </p>
								<p>Spécialité : <span class="item"><?= htmlspecialchars($ligne_select_annonce['specialite']) ?></span> </p>
								<p>Catégorie : <span class="item"><?= htmlspecialchars($ligne_categorie['title']) ?></span> </p>
								<p>Professionnel : <span class="item"><?= htmlspecialchars($ligne_select_annonce['pseudo']) ?></span></p>
								<p>Secteur géographique : <span class="item"><?= htmlspecialchars($ligne_select_annonce['ville']) ?></span></p>
								<?php for ($i = 1; $i <= 5; $i++): ?>
									<?php if (!empty($ligne_select_annonce["mot_cle_$i"])): ?>
										<span class="badge badge-success light"><?= htmlspecialchars($ligne_select_annonce["mot_cle_$i"]) ?></span>
									<?php endif; ?>
								<?php endfor; ?>
								<p class="text-content"><?= htmlspecialchars($ligne_select_annonce['description']) ?></p>
								<div class="shopping-cart" style="width: 100%; margin-top: 20px;">
									<a class="btn btn-primary <?php if (empty($user)) {
																	echo 'pxp-header-user';
																} ?>"
										data-id="<?= htmlspecialchars($ligne_select_service['id']) ?>"
										href="javascript:void(0);"
										data-toggle="modal" data-target="#ajouterModal"
										style="padding: 20px;">
										<i class="fa fa-file-pdf me-2"></i> Demande de devis
									</a>
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
																<li><i class="fa fa-star <?= $i <= $avi['note'] ? 'selected' : '' ?>" style="color: <?= $i <= $avi['note'] ? '#ffc107' : '#d8d8d8' ?>"></i></li>
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
														<a href="#" class="delete-avis" data-id="<?= htmlspecialchars($avi['id']) ?>"><i class="fa fa-trash" style="color: red;"></i></a>
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

<!-- Modal demande -->
<div class="modal fade" id="ajouterModal" tabindex="-1" aria-labelledby="ajouterModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ajouterModalLabel">
					<?php if ($_GET['action'] == 'modifier') { ?>
						Modifier une demande
					<?php } else { ?>
						Ajouter une demande
					<?php } ?>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id='formulaire-ajouter' method="post" action="#">
					<input type="hidden" name="action" value="<?php echo ($_GET['action'] == 'modifier') ? 'modifier-action' : 'ajouter-action'; ?>">
					<?php if ($_GET['action'] == 'modifier') { ?>
						<input type="hidden" name="idaction" value="<?php echo $_GET['idaction']; ?>">
					<?php } ?>
					<div class="form-group">
						<label for="nom">*Nom de la demande:</label>
						<input type="text" name="nom" id="nom" class="form-control" required value="<?php echo ($_GET['action'] == 'modifier') ? htmlspecialchars($ligne_select['nom']) : ''; ?>">
					</div>
					<div class="form-group">
						<label for="description">*Description complète:</label>
						<textarea name="description" id="description" class="form-control" required><?php echo ($_GET['action'] == 'modifier') ? htmlspecialchars($ligne_select['description']) : ''; ?></textarea>
					</div>
					<div class="form-group">
						<button type="submit" name="bouton" id="bouton" class="btn btn-primary"><?php echo ($_GET['action'] == 'modifier') ? 'Mise à jour' : 'Valider'; ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>