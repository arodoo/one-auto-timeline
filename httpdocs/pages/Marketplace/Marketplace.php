<?php
session_start();


$req_favoris = $bdd->prepare("
    SELECT id_produit 
    FROM membres_produits_favoris 
    WHERE id_membre = ?
");
$req_favoris->execute([$id_oo]);
$favoris = $req_favoris->fetchAll(PDO::FETCH_COLUMN);
$req_favoris->closeCursor();

// Invertimos el array para facilitar la comprobación en JS (clave => true)
$favoris_map = array_flip($favoris);

/* if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$min_prix = isset($_POST['min_prix']) ? $_POST['min_prix'] : 0;
	$max_prix = isset($_POST['max_prix']) ? $_POST['max_prix'] : PHP_INT_MAX;
	$mots_cles = isset($_POST['mots_cles']) ? $_POST['mots_cles'] : '';

	$req_select_produits = $bdd->prepare("
        SELECT 
            mpi.id AS image_id,
            mpi.nom_image AS image_name,
            mp.id AS produit_id,
            mp.pseudo as pseudo,
            mp.nom_produit AS produit_name,
            mp.description_produit AS produit_description,
            mpi.id_membre AS membre_id,
            mp.montant_unite AS montant_unite
        FROM 
            membres_produits_images mpi
        INNER JOIN 
            membres_produits mp
        ON 
            mpi.id_produit = mp.id
        AND 
            mpi.id_membre = mp.id_membre
        WHERE 
            mp.montant_unite BETWEEN ? AND ?
        AND 
            mp.description_produit LIKE ?
    ");
	$req_select_produits->execute([$min_prix, $max_prix, '%' . $mots_cles . '%']);
	$produits = $req_select_produits->fetchAll();
	$req_select_produits->closeCursor();
} else {
	$req_select_produits = $bdd->prepare("
        SELECT 
            mpi.id AS image_id,
            mpi.nom_image AS image_name,
            mp.id AS produit_id,
            mp.pseudo as pseudo,
            mp.nom_produit AS produit_name,
            mp.description_produit AS produit_description,
            mpi.id_membre AS membre_id,
            mp.montant_unite AS montant_unite
        FROM 
            membres_produits_images mpi
        INNER JOIN 
            membres_produits mp
        ON 
            mpi.id_produit = mp.id
        AND 
            mpi.id_membre = mp.id_membre
    ");
	$req_select_produits->execute();
	$produits = $req_select_produits->fetchAll();
	$req_select_produits->closeCursor();
} */

// Function pour supprimer les accents
function lettre_sans_accent($chaine)
{
	$normalized = \Normalizer::normalize($chaine, \Normalizer::FORM_D);
	$sans_accent = preg_replace('/[\p{Mn}]/u', '', $normalized);
	$sans_accent = str_replace(' ', '-', $sans_accent); // Sustituir espacios por guiones
	$sans_accent = str_replace("'", '-', $sans_accent); // Sustituir apóstrofes por guiones
	$sans_accent = str_replace(',', '-', $sans_accent); // Sustituir comas por guiones
	return $sans_accent;
}

?>

<style>
	.heart-icon {
		cursor: pointer;
		color: gray;
	}

	.heart-icon.text-danger {
		color: red;
	}

	.text-secondary {
		color: #6c757d !important;
	}
</style>



<div class="filter cm-content-box box-primary">
	<div class="content-title SlideToolHeader">
		<div class="cpa">
			<i class="fa-sharp fa-solid fa-filter me-2"></i>Formulaire de recherche
		</div>
	</div>
	<div class="cm-content-body form excerpt">
		<div class="card-body">
			<div class="row">
				<!-- <div class="col-xl-4 col-sm-6">
					<label class="form-label">Mots clés</label>
					<input type="text" class="form-control mb-xl-0 mb-3" id="mots_cles_marketplace"
						name="mots_cles_marketplace" id="exampleFormControlInput1" placeholder="Mots clés">
				</div> -->
				<!--
										<div class="col-xl-4  col-sm-6 mb-3 mb-xl-0">
											<label class="form-label">Catégories</label>
											<select id="id_categorie_marketplace" name="id_categorie_marketplace" class="form-control selectpicker" data-live-search="true">
												<option selected="all">Toutes catégories</option>
												<?php
												$stmt = $bdd->query('SELECT id, nom_categorie FROM configurations_categories WHERE type="marketplace" ORDER BY nom_categorie ASC');
												$categories = $stmt->fetchAll();
												foreach ($categories as $cat): ?>
													<option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="col-xl-4 col-sm-6">
											<label class="form-label">Marque</label>
											<div class="input-hasicon mb-sm-0 mb-3">
												<select class="form-control selectpicker" id="marque" name="marque" data-live-search="true">
													<option value="" > Sélection </option>
													<?php
													$sql = "SELECT DISTINCT rappel_marque FROM configurations_modeles";
													$stmt = $bdd->prepare($sql);
													$stmt->execute();
													while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
														$selected = ($row['rappel_marque'] == $marque) ? 'selected' : '';
														echo '<option value="' . htmlspecialchars($row['rappel_marque']) . '" ' . $selected . '>' . htmlspecialchars($row['rappel_marque']) . '</option>';
													}
													?>
												</select>
											</div>
											</div>
										<div class="col-xl-3 col-sm-6">
											<label class="form-label">Modèle</label>
											<div class="input-hasicon mb-sm-0 mb-3">
												<select class="form-control" id="model_select" name="model">
													<option value="">Sélectionnez une marque</option>
												</select>
											</div>
											</div>
											-->

				<!-- <div class="col-xl-3 col-sm-6">
					<label class="form-label">Prix Minimum</label>
					<div class="input-hasicon mb-sm-0 mb-3">
						<input type="text" id="min-price" name="min_prix" class="form-control" placeholder="Min">
						<div class="icon"><i class="fas fa-euro-sign"></i></div>
					</div>
				</div>
				<div class="col-xl-3 col-sm-6">
					<label class="form-label">Prix Maximum</label>
					<div class="input-hasicon mb-sm-0 mb-3">
						<input type="text" id="max-price" name="max_prix" class="form-control" placeholder="Max">
						<div class="icon"><i class="fas fa-euro-sign"></i></div>
					</div>
				</div> -->
				<div class="col-xl-6 col-sm-6">
					<label class="form-label">Type de véhicule</label>
					<select id="type_vehicule" name="type_vehicule">
						<option value="" selected disabled>Sélectionnez une option</option>
						<option value="P">Passenger Car/LCV (w/o Motorcycle)</option>
						<option value="B">Motorcycle</option>
						<option value="O">Commercial Vehicle</option>
						<option value="M">Engine</option>
						<option value="A">Axle</option>
						<option value="U">Universal</option>
					</select>
				</div>
				<div class="col-xl-6 col-sm-6">
					<label class="form-label">categorie</label>
					<select id="selectAssemblyGroup" class="selectpicker" data-live-search="true" disabled>
						<option value="" disabled selected>Sélectionnez une option</option>
					</select>
				</div>




				<!-- <div class="col-xl-2 col-sm-6 align-self-end">
					<button class="btn btn-primary me-2" title="Rechercher" type="button"
						style="padding: 12px;">Rechercher</button>
				</div> -->
			</div>
		</div>
	</div>
</div>


<div class="row" id="product-container">
	<?php

	if (!empty($jsonData['articles'])) {
		foreach ($jsonData['articles'] as $article) {
			// Mapeo de datos:
			$produit_id = $article['articleNumber'] ?? '';
			$produit_name = "Article " . $produit_id;
			// Para la descripción, obtenemos el valor de genericArticleDescription del primer elemento de genericArticles
			$produit_description = "";
			if (!empty($article['genericArticles'])) {
				$produit_description = $article['genericArticles'][0]['genericArticleDescription'] ?? '';
			}
			// Se toma la primera imagen disponible
			$image_url = "";
			if (!empty($article['images'])) {
				$image_url = $article['images'][0]['imageURL200'] ??
					$article['images'][0]['imageURL400'] ??
					$article['images'][0]['imageURL800'] ??
					$article['images'][0]['imageURL1600'] ??
					$article['images'][0]['imageURL100'] ??
					$article['images'][0]['imageURL50'] ?? "";
			}

			$id_categorie = $produit_description;
			$article_status_description = $article['misc']['articleStatusDescription'] ?? '';

			$is_favorite = false;

			$first_word = explode(' ', trim($produit_name))[0];
			$first_word_sans_accent = lettre_sans_accent($produit_description);
	?>
			<div class="col-lg-12 col-xl-6 col-xxl-4">
				<div class="card">
					<div class="card-body">
						<div class="row m-b-30">
							<div class="col-md-5 col-xxl-12">
								<div class="new-arrival-product mb-4 mb-xxl-4 mb-md-0">
									<div class="new-arrivals-img-contnent">
										<a href="/Page-marketplace/<?php echo $first_word_sans_accent . '/' . $produit_id . '/' . $articleNumber . '/' . $selectedId; ?>"
											title="<?php echo htmlspecialchars($produit_name); ?>">
											<img class="img-fluid"
												src="<?php echo htmlspecialchars($image_url); ?>"
												alt="<?php echo htmlspecialchars($produit_name); ?>">
										</a>
									</div>
								</div>
							</div>
							<div class="col-md-7 col-xxl-12">
								<div class="new-arrival-content d-flex justify-content-end align-items-center">
									<i class="fa fa-heart heart-icon <?php echo $is_favorite ? 'text-danger' : 'text-secondary'; ?>"
										data-produit-id="<?php echo $produit_id; ?>"></i>
								</div>
								<div class="new-arrival-content position-relative">
									<h4>
										<a href="/Page-marketplace/<?php echo $first_word_sans_accent . '/' . $produit_id . '/' . $articleNumber . '/' . $selectedId; ?>"
											title="<?php echo htmlspecialchars($produit_name); ?>">
											<?php echo htmlspecialchars($produit_name); ?>
										</a>
									</h4>
									<div class="comment-review star-rating">
										<ul>
											<li><i class="fa fa-star"></i></li>
											<li><i class="fa fa-star"></i></li>
											<li><i class="fa fa-star"></i></li>
											<li><i class="fa fa-star"></i></li>
											<li><i class="fa fa-star"></i></li>
										</ul>
										<span class="review-text">(34 avis)</span>
									</div>
									<p>Disponibilité: <span class="item"> En stock <i class="fa fa-check-circle text-success"></i></span></p>
									<p>Catégorie: <?php echo htmlspecialchars($id_categorie); ?></p>
									<p>Offres : 0</p>
									<p class="text-content"><?php echo nl2br(htmlspecialchars($produit_description)); ?></p>
									<p class="text-content">Status: <?php echo htmlspecialchars($article_status_description); ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	<?php
		}
	} else {
		echo "<p>Aucun article trouvé.</p>";
	}
	?>
</div>





<script>
	var favoritesMap = <?php echo json_encode($favoris_map); ?>;


	function lettre_sans_accent(chaine) {
		let normalized = chaine.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
		normalized = normalized.replace(/ /g, '-');
		normalized = normalized.replace(/'/g, '-');
		normalized = normalized.replace(/,/g, '');
		return normalized;
	}

	document.getElementById("type_vehicule").addEventListener("change", function() {
		var typeVehicule = this.value;

		fetch("/pages/Marketplace/Marketplace-API-ajax.php", {
				method: "POST",
				headers: {
					"Content-Type": "application/json"
				},
				body: JSON.stringify({
					type_vehicule: typeVehicule
				})
			})
			.then(response => response.json())
			.then(data => {
				/* 	console.log("Respuesta del servidor:", data); */

				if (
					data.response &&
					data.response.assemblyGroupFacets &&
					data.response.assemblyGroupFacets.counts
				) {
					const countsArray = data.response.assemblyGroupFacets.counts;

					let opciones = '<option value="" disabled selected>Sélectionnez une option</option>';
					countsArray.forEach(item => {
						const nodeId = item.assemblyGroupNodeId;
						const name = item.assemblyGroupName;

						// Imprime en consola los valores
						/* 	console.log("assemblyGroupNodeId:", nodeId, "| assemblyGroupName:", name); */


						opciones += `<option value="${nodeId}">${name}</option>`;
					});

					// Inject the options into the <select>
					document.getElementById("selectAssemblyGroup").innerHTML = opciones;

					// refresh the select
					if (typeof $('#selectAssemblyGroup').selectpicker === "function") {
						$('#selectAssemblyGroup').selectpicker('refresh');
					}

					// Enable the select using jQuery and refresh bootstrap-select
					$('#selectAssemblyGroup').prop('disabled', false);
					$('#selectAssemblyGroup').selectpicker('refresh');
				} else {
					console.error("Aucune donnée trouvée dans la réponse");
				}
			})
			.catch(error => console.error("Error:"));
	});

	const articleDataMap = {};

	// Listener for the second request when changing the option in the select
	document.getElementById("selectAssemblyGroup").addEventListener("change", function() {
		var selectedId = this.value;
		var payload = {
			assemblyGroupNodeIds: selectedId
		};

		fetch("/pages/Marketplace/Marketplace-API-ajax.php", {
				method: "POST",
				headers: {
					"Content-Type": "application/json"
				},
				body: JSON.stringify(payload)
			})
			.then(response => response.json())
			.then(data => {
				if (data.response && data.response.articles && data.response.articles.length > 0) {
					document.getElementById("product-container").innerHTML = '';

					data.response.articles.forEach(article => {
						var produit_id = article.genericArticles[0].legacyArticleId ?? '';
						var produit_name = "Article " + produit_id;
						var articleNumber = article.articleNumber ?? '';

						var produit_description = "";
						if (article.genericArticles && article.genericArticles.length > 0) {
							produit_description = article.genericArticles[0].genericArticleDescription ?? '';
						}

						var image_url = "";
						if (article.images && article.images.length > 0) {
							image_url = article.images[0]['imageURL200'] ??
								article.images[0]['imageURL400'] ??
								article.images[0]['imageURL800'] ??
								article.images[0]['imageURL1600'] ??
								article.images[0]['imageURL100'] ??
								article.images[0]['imageURL50'] ?? "";
						} else {
							image_url = "/images/erreurBad.webp";
						}

						var id_categorie = "";
						if (article.genericArticles && article.genericArticles.length > 0) {
							id_categorie = article.genericArticles[0].assemblyGroupName ?? '';
						}
						var article_status_description = article['misc']['articleStatusDescription'] ?? '';

						var heartClass = (favoritesMap.hasOwnProperty(produit_id)) ? 'text-success' : 'text-secondary';

						var first_word = produit_name.split(' ')[0];
						var first_word_sans_accent = lettre_sans_accent(produit_description);


						var uniqueOffresId = "offres-count-" + articleNumber;

						var lienProduit = `/Page-marketplace/${first_word_sans_accent}/${produit_id}/${articleNumber}/${selectedId}`;


						var productHtml = `
                    <div class="col-lg-12 col-xl-6 col-xxl-4 product-card" data-article-number="${articleNumber}" data-selected-id="${selectedId}">
                        <div class="card">
                            <div class="card-body">
                                <div class="row m-b-30">
                                    <div class="col-md-5 col-xxl-12">
                                        <div class="new-arrival-product mb-4 mb-xxl-4 mb-md-0">
                                            <div class="new-arrivals-img-contnent">
                                                <a href="/Page-marketplace/${first_word_sans_accent}/${produit_id}/${articleNumber}/${selectedId}" title="${produit_name}">
                                                    <img class="img-fluid" src="${image_url}" alt="${produit_name}">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7 col-xxl-12">
                                        <div class="new-arrival-content d-flex justify-content-end align-items-center">
                                            <!-- Ícono del corazón con la clase determinada -->
                                            <i class="fa fa-heart heart-icon ${heartClass}" data-produit-id="${produit_id}"></i>
                                        </div>
                                        <div class="new-arrival-content position-relative">
                                            <h4>
                                                <a href="/Page-marketplace/${first_word_sans_accent}/${produit_id}/${articleNumber}/${selectedId}" title="${produit_name}">
                                                    ${produit_description}
                                                </a>
                                            </h4>
                                            <p>Catégorie: ${id_categorie}</p>
                                            <p id="${uniqueOffresId}">Offres : 0</p>
                                            <p class="text-content">${produit_name}</p>
                                            <p class="text-content">Status: ${article_status_description}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
						document.getElementById("product-container").insertAdjacentHTML('beforeend', productHtml);
					});

					// Lazy loading para ofertas (se mantiene igual)
					const observer = new IntersectionObserver((entries, observer) => {
						entries.forEach(entry => {
							if (entry.isIntersecting) {
								const card = entry.target;
								const articleNumber = card.dataset.articleNumber;
								const selectedIdFromCard = card.dataset.selectedId;
								const uniqueOffresId = "offres-count-" + articleNumber;

								fetch("/pages/Marketplace/Marketplace-list-offre.php", {
										method: 'POST',
										headers: {
											'Content-Type': 'application/x-www-form-urlencoded'
										},
										body: new URLSearchParams({
											selectedId: selectedIdFromCard,
											articleNumber: articleNumber
										})
									})
									.then(response => response.json())
									.then(data => {
										const offresCountElement = document.getElementById(uniqueOffresId);
										if (offresCountElement) {
											offresCountElement.textContent = `Offres : ${data}`;
										}
									})
									.catch(error => console.error('Error en lazy loading:', error));

								observer.unobserve(card);
							}
						});
					});

					document.querySelectorAll('.product-card').forEach(card => {
						observer.observe(card);
					});
				} else {
					console.log("Aucun article trouvé dans la réponse");
				}
			})
			.catch(error => {
				console.error("Erreur lors de la première requête:");
				console.error("Payload:");
			});
	});



	document.addEventListener("click", function(event) {
		// Vérifie si l'élément cliqué est une icône de cœur
		if (event.target && event.target.classList.contains("heart-icon")) {
			// Récupère l'ID du produit à partir de l'attribut data (data-produit-id)
			var produitId = event.target.getAttribute("data-produit-id");
			if (!produitId) return;

			// Trouver l'élément parent le plus proche de la carte produit
			var cardElement = event.target.closest(".product-card");

			// Vérifie si l'élément de la carte a un lien <a> et récupère son href
			var lienProduit = "";
			if (cardElement) {
				var lienElement = cardElement.querySelector("a");
				if (lienElement) {
					lienProduit = lienElement.getAttribute("href");
				}
			}

			// Envoie la requête AJAX pour ajouter ou supprimer le favori avec le lien du produit
			fetch("/pages/Marketplace/Marketplace-favoris-ajax.php", {
					method: "POST",
					headers: {
						"Content-Type": "application/x-www-form-urlencoded"
					},
					body: new URLSearchParams({
						produit_id: produitId,
						lien_produit: lienProduit // Envoi du lien du produit au backend
					})
				})
				.then(response => response.json())
				.then(data => {
					// console.log("Réponse du serveur:", data);

					// Supprime les classes de couleur précédentes pour éviter les conflits
					event.target.classList.remove("text-danger", "text-secondary", "text-success");

					if (data.retour_action === "added") {
						popup_alert(data.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
						event.target.classList.add("text-success");
					} else if (data.retour_action === "removed") {
						popup_alert(data.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
						event.target.classList.add("text-danger");
					}
				})
				.catch(error => console.error("Erreur lors de la mise à jour du favori:", error));
		}
	});
</script>