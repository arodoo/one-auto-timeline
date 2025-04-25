<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 1); */

$assemblyGroupNodeIds = $_GET['assemblyGroupNodeIds'];
$nom = $_GET['nom'];
$idaction = $_GET['idaction'];
$articleNumber = $_GET['articlenumber'];
$selectedId = $_GET['selectedid'];
/* var_dump($_GET); */



$data = [
	"getArticles" => [
		"articleCountry" => "FR",
		"provider" => $provider_oo,
		"searchQuery" => $articleNumber,
		"searchType" => 0,
		"assemblyGroupNodeIds" => $selectedId,
		"lang" => "fr",
		"perPage" => 100,
		"page" => 1,
		"includeAll" => true
	]
];

$dataJson = json_encode($data);

$ch = curl_init($urlTecalliance);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	"Content-Type: application/json",
	"X-Api-Key: $apiKeyTech",
	"Content-Length: " . strlen($dataJson)
]);

$response = curl_exec($ch);
$error = curl_errno($ch) ? curl_error($ch) : null;
curl_close($ch);

$responseData = json_decode($response, true);

if ($error || !$responseData) {
	die("La requête API a échoué  ");
}

$articles = $responseData['articles'] ?? [];

foreach ($articles as $article) {
	$produit_id = $article['genericArticles'][0]['legacyArticleId'] ?? '';
	$produit_name = "Article " . $produit_id;
	$articleNumber = $article['articleNumber'] ?? '';
	$produit_description = $article['genericArticles'][0]['genericArticleDescription'] ?? '';
	$image_url = $article['images'][0]['imageURL200'] ?? "/images/erreurBad.webp";
	$id_categorie = $article['genericArticles'][0]['assemblyGroupName'] ?? '';
	$article_status_description = $article['misc']['articleStatusDescription'] ?? '';
	$quantityPerPackage = $article['misc']['quantityPerPackage'] ?? 0;
	$oemNumbers = $article['oemNumbers'] ?? [];
	$articleCriteria = $article['articleCriteria'] ?? [];


	// Mostrar los datos extraídos en formato JSON
	/* echo json_encode([
        'produit_id' => $produit_id,
        'produit_name' => $produit_name,
        'articleNumber' => $articleNumber,
        'produit_description' => $produit_description,
        'image_url' => $image_url,
        'id_categorie' => $id_categorie,
        'article_status_description' => $article_status_description,
        'quantityPerPackage' => $quantityPerPackage
    ], JSON_PRETTY_PRINT); */
}


////EXTRACT THE DATA OF THE PRODUCT ASSOCIATED WITH THE OFFER

////EXTRACT THE DATA OF THE PRODUCT ASSOCIATED WITH THE OFFER
$req_membre_produit = $bdd->prepare("
    SELECT * 
    FROM membres_produits 
    WHERE id_produit_api = ? AND node_ids_api = ? AND statut = 'activé'	
");
$req_membre_produit->execute([$articleNumber, $selectedId]);
$produit_offre = $req_membre_produit->fetchAll(PDO::FETCH_ASSOC);
$count_Offre = $req_membre_produit->rowCount(); // Conteo de filas
$req_membre_produit->closeCursor();




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
				url: '/pages/Marketplace/Marketplace-avis.php',
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

		// Click event for the shopping cart button
		$('.btn-success').on('click', function() {
			var produitId = $(this).data('produit-id');
			var produitDescription = $(this).data('produit-description');
			var produitQuantite = $(this).data('produit-quantite');

			$('#quantityModal').data('produit-id', produitId);
			$('#quantityModalLabel').text(produitDescription);
			$('#quantityAvailable').text(produitQuantite);
			$('#quantityInput').attr('max', produitQuantite); // Set the max attribute
			$('#quantityModal').modal('show');
		});


		$('#confirmQuantity').on('click', function() {
			var produitId = $('#quantityModal').data('produit-id');
			var quantity = $('#quantityInput').val();

			$.post({
				url: '/pages/Marketplace/Marketplace-ajout-panier.php',
				data: {
					produit_id: produitId,
					quantity: quantity
				},
				type: 'POST',
				dataType: "json",
				success: function(response) {
					if (response.status === "success") {
						popup_alert("Produit ajouté au panier.", "green filledlight", "#009900", "uk-icon-check");
						window.location.href = "/Paiement";
					} else {
						popup_alert(response.Texte_rapport || "Erreur lors de l'ajout du produit au panier.", "#CC0000 filledlight", "#CC0000", "uk-icon-times");
					}
				},
				error: function() {
					popup_alert("Erreur lors de l'ajout du produit au panier.", "#CC0000 filledlight", "#CC0000", "uk-icon-times");
				}
			});
		});

		//Validate the amount entered
		$('#quantityInput').on('input', function() {
			var maxQuantity = parseInt($(this).attr('max'), 10);
			var currentQuantity = parseInt($(this).val(), 10);

			if (currentQuantity > maxQuantity) {
				$(this).val(maxQuantity);
			}
		});
	});
</script>


<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-xl-6 col-lg-6 col-md-6 col-xxl-5">
						<!-- Tab panes -->
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel"
								aria-labelledby="home-tab" tabindex="0">
								<img class="img-fluid rounded" src="<?php echo htmlspecialchars($image_url); ?>" alt="">
							</div>
							<div class="tab-pane fade" id="profile-tab-pane" role="tabpanel"
								aria-labelledby="profile-tab" tabindex="0">
								<img class="img-fluid rounded " src="/images/membres/<?php echo htmlspecialchars($pseudo); ?>/<?php echo htmlspecialchars($nom_image); ?>" alt="">
							</div>
							<div class="tab-pane fade" id="contact-tab-pane" role="tabpanel"
								aria-labelledby="contact-tab" tabindex="0">
								<img class="img-fluid rounded" src="/images/membres/<?php echo htmlspecialchars($pseudo); ?>/<?php echo htmlspecialchars($nom_image); ?>" alt="">
							</div>
							<div class="tab-pane fade" id="end-tab-pane" role="tabpanel"
								aria-labelledby="end-tab" tabindex="0">
								<img class="img-fluid rounded" src="/images/membres/<?php echo htmlspecialchars($pseudo); ?>/<?php echo htmlspecialchars($nom_image); ?>" alt="">
							</div>

						</div>
						<ul class="nav nav-tabs slide-item-list mt-3" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a href="#first" class="nav-link active" id="home-tab"
									data-bs-toggle="tab" data-bs-target="#home-tab-pane" role="tab"
									aria-controls="home-tab-pane" aria-selected="true"><img
										class="img-fluid me-2 rounded" src="/images/membres/<?php echo htmlspecialchars($pseudo); ?>/<?php echo htmlspecialchars($nom_image); ?>" alt=""
										width="80"></a>
							</li>
							<li class="nav-item" role="presentation">
								<a href="#second" class="nav-link" id="profile-tab" data-bs-toggle="tab"
									data-bs-target="#profile-tab-pane" role="tab"
									aria-controls="profile-tab-pane" aria-selected="false"><img
										class="img-fluid me-2 rounded" src="/images/membres/<?php echo htmlspecialchars($pseudo); ?>/<?php echo htmlspecialchars($nom_image); ?>" alt=""
										width="80"></a>
							</li>
							<li class="nav-item" role="presentation">
								<a href="#third" class="nav-link" id="contact-tab" data-bs-toggle="tab"
									data-bs-target="#contact-tab-pane" role="tab"
									aria-controls="contact-tab-pane" aria-selected="false"><img
										class="img-fluid me-2 rounded" src="/images/membres/<?php echo htmlspecialchars($pseudo); ?>/<?php echo htmlspecialchars($nom_image); ?>" alt=""
										width="80"></a>
							</li>
							<li class="nav-item" role="presentation">
								<a href="#for" class="nav-link" id="end-tab" data-bs-toggle="tab"
									data-bs-target="#end-tab-pane" role="tab"
									aria-controls="end-tab-pane" aria-selected="false"><img
										class="img-fluid  rounded" src="/images/membres/<?php echo htmlspecialchars($pseudo); ?>/<?php echo htmlspecialchars($nom_image); ?>" alt=""
										width="80"></a>
							</li>

						</ul>
					</div>

					<div class="col-xl-6 col-lg-6 col-md-6 col-xxl-7 col-sm-12">
						<div class="product-detail-content">
							<!--Product details-->
							<div class="new-arrival-content pr mt-md-0 mt-3">
								<h2><?php echo htmlspecialchars($produit_description); ?></h2>
								<?php if ($statut_compte_oo == 5): ?>
									<a href="/Mes-produits/ajouter/<?php echo urlencode($articleNumber); ?>/<?php echo urlencode($selectedId); ?>" class="btn btn-primary">Ajouter une offre</a>
								<?php endif; ?>
								<!-- 	<div class="comment-review star-rating">
									<ul>
										<li><i class="fa fa-star"></i></li>
										<li><i class="fa fa-star"></i></li>
										<li><i class="fa fa-star"></i></li>
										<li><i class="fa fa-star"></i></li>
										<li><i class="fa fa-star"></i></li>
									</ul>
									<span class="review-text">(34 avis) </span>
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
								</div> -->
								<p>Nombre d'offre : <span class="item"><?php echo $count_Offre ?></span> </p>
								<p>Disponibilité: <span class="item">
										<?php if ($count_Offre >= 1): ?>
											En stock
										<?php else: ?>
											Non disponible
										<?php endif; ?>
										<i class="fa fa-shopping-basket"></i>
									</span></p>
								<p>Catégorie : <span class="item"><?php echo htmlspecialchars($id_categorie); ?></span> </p>
								<!-- <p class="text-content">
									<?php echo htmlspecialchars($produit_description); ?>
								</p> -->
								<p class="text-content">Status: <?php echo htmlspecialchars($article_status_description); ?></p>
								<p class="text-content">Quantity per package: <?php echo htmlspecialchars($quantityPerPackage); ?></p>
								<p class="text-content">OEM Numbers:</p>
								<ul>
									<?php foreach ($oemNumbers as $oem): ?>
										<li>
											Numéro de pièce OEM : <?php echo htmlspecialchars($oem['articleNumber']); ?>,
											Nom du fabricant : <?php echo htmlspecialchars($oem['mfrName']); ?>
										</li>
									<?php endforeach; ?>
								</ul>

								<p class="text-content">Caractéristiques de l'article :</p>
								<ul>
									<?php foreach ($articleCriteria as $criteria): ?>
										<li>
											<strong><?php echo htmlspecialchars($criteria['criteriaDescription']); ?></strong>:
											<?php echo htmlspecialchars($criteria['formattedValue'] ?? 'N/A'); ?>
										</li>
									<?php endforeach; ?>
								</ul>



							</div>
						</div>
					</div>



					<div class="filter cm-content-box box-primary">
						<div class="content-title SlideToolHeader">
							<div class="cpa">
								<i class="fa-solid fa-envelope me-1"></i> Liste des offres
							</div>
							<div class="tools">
								<a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a>
							</div>
						</div>
						<div class="cm-content-body form excerpt">
							<div class="card-body pb-4">
								<div class="container">
									<?php if (empty($produit_offre)): ?>
										<p>Ce produit n'a pas encore d'offres de vente.</p>
									<?php else: ?>
										<?php foreach ($produit_offre as $produit): ?>
											<?php
											$req_image = $bdd->prepare("
												SELECT * 
												FROM membres_produits_images 
												WHERE id_membre = ? AND id_produit = ? 
												ORDER BY id DESC LIMIT 1
											");
											$req_image->execute([$produit['id_membre'], $produit['id']]);
											$image = $req_image->fetch(PDO::FETCH_ASSOC);
											$req_image->closeCursor();
											?>
											<div class="row" style="border-bottom: 1px solid;">
												<div class="col-3 col-md-3 d-flex align-items-center mb-2 mb-md-0">
													<a href="javascript:void(0);" class="btn btn-success btn-sm me-1" style="padding: 5px;" data-produit-id="<?= htmlspecialchars($produit['id']) ?>" data-produit-description="<?= htmlspecialchars($produit['nom_produit']) ?>" data-produit-quantite="<?= htmlspecialchars($produit['quantite']) ?>">
														<i class="fa-solid fa-cart-shopping"></i>
													</a>
													<!-- <a href="javascript:void(0);" class="btn btn-danger btn-sm me-1" style="padding: 5px;">
														<i class="fa-solid fa-file"></i>
													</a>
													<a href="javascript:void(0);" class="btn btn-danger btn-sm me-1" style="padding: 5px;">
														<i class="fa-solid fa-truck"></i>
													</a> -->
													<?php if ($image): ?>
														<a href="/images/membres/<?= htmlspecialchars($image['pseudo']) ?>/<?= htmlspecialchars($image['nom_image']) ?>" target="_blank" class="btn btn-primary btn-sm" style="padding: 5px;">
															<i class="fa-solid fa-expand"></i>
														</a>
													<?php endif; ?>
												</div>

												<div class="col-3 col-md-3 d-flex align-items-center mb-2 mb-md-0">
													<span><?= htmlspecialchars($produit['montant_unite']) ?>€</span> &nbsp;
													<?= htmlspecialchars($produit['nom_produit']) ?>
												</div>

												<div class="col-3 col-md-3 d-flex align-items-center mb-2 mb-md-0">
													<span class="badge badge-success light">Quantité disponible: <?= htmlspecialchars($produit['quantite']) ?></span>
												</div>

												<div class="col-3 col-md-3">
													<p><?= htmlspecialchars($produit['pseudo']) ?></p>
												</div>
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
<!-- Modal select quantity-->
<div class="modal fade" id="quantityModal" tabindex="-1" role="dialog" aria-labelledby="quantityModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="quantityModalLabel">Sélectionner la quantité</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Quantité disponible: <span id="quantityAvailable"></span></p>
				<input type="number" id="quantityInput" class="form-control" min="1" max="">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="confirmQuantity">Confirmer</button>
			</div>
		</div>
	</div>
</div>



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

<!-- <script>
	$(document).ready(function() {
		$('#sendDataButton').on('click', function() {
			var articles = <?php echo json_encode($articles); ?>;
			console.log("Datos enviados:", articles); // Mostrar datos por consola
			$.ajax({
				url: '/panel/Vendeurs/Mes-produits/Mes-produits.php',
				type: 'POST',
				data: { articles: articles },
				success: function(response) {
					var newTab = window.open('/Mes-produits/ajouter', '_blank');
					if (newTab) {
						newTab.focus();
					} else {
						alert('Por favor, permita las ventanas emergentes para este sitio.');
					}
				},
				error: function() {
					alert('Error al enviar los datos.');
				}
			});
		});
	});
</script> -->