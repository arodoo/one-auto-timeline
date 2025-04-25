<?php
// Function pour supprimer les accents
function lettre_sans_accent($chaine)
{
	$normalized = \Normalizer::normalize($chaine, \Normalizer::FORM_D);
	$sans_accent = preg_replace('/[\p{Mn}]/u', '', $normalized);
	return $sans_accent;
}
?>

<script>
	$(document).ready(function() {
		let page = 1;
		const limit = 9;
		let canLoadMore = true; // Contrôle si plus de données peuvent être chargées
		let selectedCategoryId = null; // Stocke la catégorie sélectionnée

		function loadServices(page, append = false, categoryId = null) {
			if (!canLoadMore && append) return; // Si plus de données ne peuvent pas être chargées, arrêter la fonction

			let formData = $('#filterForm').serializeArray();
			formData.push({
				name: 'page',
				value: page
			});
			formData.push({
				name: 'limit',
				value: limit
			});

			if (categoryId) {
				formData.push({
					name: 'id_catgeorie_service',
					value: categoryId
				});
				$("#id_catgeorie_service").val(categoryId).change();
			}

			// Assurer des valeurs vides pour la catégorie et le département si non sélectionnés
			if ($('#id_catgeorie_service').val() === 'Toutes catégories') {
				formData.push({
					name: 'id_catgeorie_service',
					value: ''
				});
			}
			if ($('#id_departement_service').val() === 'Tous les départements') {
				formData.push({
					name: 'id_departement_service',
					value: ''
				});
			}

			$.ajax({
				type: 'POST',
				url: '/pages/Services/Services-filtre-ajax.php',
				data: $.param(formData),
				dataType: 'json',
				/* beforeSend: function() {
					$("#loadingIndicator").show(); // Afficher un indicateur de chargement optionnel
				}, */
				success: function(data) {
					/* 					$("#loadingIndicator").hide(); // Masquer l'indicateur de chargement */

					if (data.status === 'success' && data.data.length > 0) {
						if (!append) {
							$('#results').empty(); // Nettoyer uniquement si c'est une nouvelle recherche
						}
						data.data.forEach(function(service) {
							let cleanTitle = service.service_title.normalize("NFD").replace(/[\u0300-\u036f]/g, "").split(' ')[0].toLowerCase();

							let serviceHtml = `
                            <div class="col-xl-4 col-xxl-4 col-lg-4 col-sm-6">
                                <div class="card">
                                    <div class="card-body product-grid-card">
                                        <div class="new-arrival-product">
                                            <div class="new-arrivals-img-contnent">
                                                <a href="/Page-service/${cleanTitle}/${service.service_id}" 
                                                   title="${service.service_title}">
                                                    <img class="img-fluid" src="/images/membres/${service.pseudo}/${service.image_name}" 
                                                         alt="${service.service_title}">
                                                </a>
                                            </div>
                                            <div class="new-arrival-content text-center mt-3">
                                                <h4><a href="/Page-service/${cleanTitle}/${service.service_id}" 
                                                       title="${service.service_title}">
                                                       ${service.service_title}
                                                   </a></h4>
                                                <p>Spécialité : <span class="item">${service.service_specialite}</span></p>
                                                <p class="text-content">${service.service_description}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
							$('#results').append(serviceHtml);
						});

						canLoadMore = data.data.length >= limit; // Permettre plus de chargement uniquement si plus de résultats ont été obtenus
					} else {
						if (!append) {
							$('#results').html('<p class="text-center" style="font-size: 18px; color: #888;">Aucun service trouvé.</p>');
						}
						canLoadMore = false; // Plus de données à charger
					}
				},
				error: function(xhr, status, error) {
					$("#loadingIndicator").hide();
					console.error("Erreur lors du chargement des services:", error);
				}
			});
		}

		// Détecter si l'utilisateur accède à une URL avec catégorie
		let pathArray = window.location.pathname.split('/');
		if (pathArray.length === 4 && pathArray[1] === "Services") {
			let categoryId = pathArray[3];
			selectedCategoryId = categoryId;
			loadServices(1, false, categoryId);
			$("#id_catgeorie_service").val(categoryId).change();
		} else {
			loadServices(page);
		}

		// Événement lorsque l'utilisateur clique sur une catégorie de la liste
		$(".category-link").on("click", function(e) {
			e.preventDefault();
			let categoryId = $(this).data("id");
			let categorySlug = $(this).data("category");
			let newUrl = "/Services/" + categorySlug + "/" + categoryId;

			window.history.pushState({}, '', newUrl);
			selectedCategoryId = categoryId;
			page = 1; // Réinitialiser la pagination
			canLoadMore = true; // Permettre le chargement de plus de données
			loadServices(1, false, categoryId);
		});

		// Filtrer les services lors de l'utilisation du formulaire de recherche
		$('#filterForm').on('submit', function(event) {
			event.preventDefault();
			page = 1;
			selectedCategoryId = $("#id_catgeorie_service").val();
			canLoadMore = true;
			loadServices(page, false, selectedCategoryId);
		});

		// Charger plus de services lors du défilement
		$(window).scroll(function() {
			if (canLoadMore && $(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
				page++;
				loadServices(page, true, selectedCategoryId);
			}
		});
	});
</script>

<div class="filter cm-content-box box-primary">
	<div class="content-title SlideToolHeader">
		<div class="cpa">
			<i class="fa-sharp fa-solid fa-filter me-2"></i>Formulaire de recherche
		</div>
	</div>
	<div class="cm-content-body form excerpt">
		<div class="card-body">
			<form id="filterForm" method="POST">
				<div class="row">
					<div class="col-xl-3 col-sm-6">
						<label class="form-label">Mots clés</label>
						<input name="mot_cle_service" id="mot_cle_service" type="text" class="form-control mb-xl-0 mb-3" id="exampleFormControlInput1" placeholder="Mots clés">
					</div>
					<div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
						<label class="form-label">Catégories</label>
						<select name="id_catgeorie_service" id="id_catgeorie_service" class="form-control selectpicker" data-live-search="true">
							<option selected="">Toutes catégories</option>
							<?php
							$stmt = $bdd->query('SELECT id, nom_categorie FROM configurations_categories WHERE type="service" ORDER BY nom_categorie ASC');
							$categories = $stmt->fetchAll();
							foreach ($categories as $cat): ?>
								<option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-xl-3 col-sm-6">
						<label class="form-label">Département</label>
						<select name="id_departement_service" id="id_departement_service" class="form-control selectpicker" data-live-search="true">
							<option selected="">Tous les départements</option>
							<?php
							$stmt = $bdd->query('SELECT id, code, name FROM dpts');
							$departments = $stmt->fetchAll();
							foreach ($departments as $dept): ?>
								<option value="<?= htmlspecialchars($dept['id']) ?>"><?= htmlspecialchars($dept['code']) ?> - <?= htmlspecialchars($dept['name']) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-xl-3 col-sm-6 align-self-end">
						<button class="btn btn-primary" title="Rechercher" type="submit" style="padding: 12px;">Rechercher</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>



<div id="results" class="row">
	<?php
	$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
	$limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 9;
	$offset = ($page - 1) * $limit;

	$stmt = $bdd->prepare("
		SELECT 
			i.id AS image_id,
			i.nom_image AS image_name,
			s.id AS service_id,
			s.title AS service_title,
			s.description AS service_description,
			s.specialite AS service_specialite,
			s.pseudo
		FROM 
			membres_services_images i
		INNER JOIN 
			membres_services s
		ON 
			i.id_annonce_service = s.id 
		INNER JOIN 
			membres m
		ON 
			s.id_membre = m.id
		WHERE 
			s.statut = 'activé' AND m.abonnement = 'oui'
		GROUP BY 
			s.id 
		ORDER BY 
			s.id DESC 
		LIMIT :limit OFFSET :offset
	");
	$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
	$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
	$stmt->execute();

	$services = $stmt->fetchAll();
	foreach ($services as $service):
		$cleanTitle = lettre_sans_accent($service['service_title']);

		$cleanTitle = explode(' ', trim($cleanTitle))[0];

		$cleanTitle = strtolower($cleanTitle);
	?>
		<div class="col-xl-4 col-xxl-4 col-lg-4 col-sm-6">
			<div class="card">
				<div class="card-body product-grid-card">
					<div class="new-arrival-product">
						<div class="new-arrivals-img-contnent">
							<a href="/Page-service/<?= htmlspecialchars($cleanTitle) ?>/<?= htmlspecialchars($service['service_id']) ?>"
								title="<?= htmlspecialchars($service['service_title']) ?>">
								<img class="img-fluid" src="/images/membres/<?= htmlspecialchars($service['pseudo']) ?>/<?= htmlspecialchars($service['image_name']) ?>"
									alt="<?= htmlspecialchars($service['service_title']) ?>">
							</a>
						</div>
						<div class="new-arrival-content text-center mt-3">
							<h4><a href="/Page-service/<?= htmlspecialchars($cleanTitle) ?>/<?= htmlspecialchars($service['service_id']) ?>"
									title="<?= htmlspecialchars($service['service_title']) ?>">
									<?= htmlspecialchars($service['service_title']) ?>
								</a></h4>
							<ul class="star-rating">
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa-solid fa-star-half-stroke"></i></li>
								<li><i class="fa-solid fa-star-half-stroke"></i></li>
							</ul>
							<p>Spécialité : <span class="item"><?= htmlspecialchars($service['service_specialite']) ?></span></p>
							<!-- <p>Professionnel : <span class="item"><a href="/Fiche/<?= htmlspecialchars($service['service_professionnel']) ?>/8"><?= htmlspecialchars($service['service_professionnel']) ?></a></span></p> -->
							<p class="text-content"><?= htmlspecialchars($service['service_description']) ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php endforeach; ?>

</div>

<div class="col-12">
	<h4>Liste des catégories de services</h4>
	<div class="row">
		<?php
		$stmt = $bdd->query('SELECT id, nom_categorie, nom_categorie_url FROM configurations_categories WHERE type="service"');
		$categories = $stmt->fetchAll();
		foreach ($categories as $cat):
		?>
			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
				<a href="#" class="category-link" data-category="<?= htmlspecialchars($cat['nom_categorie_url']) ?>" data-id="<?= htmlspecialchars($cat['id']) ?>">
					<?= htmlspecialchars($cat['nom_categorie']) ?>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
</div>