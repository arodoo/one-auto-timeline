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
		let canLoadMore = true;
		let selectedCategoryId = null;

		// Détecter si l'utilisateur accède à une URL avec catégorie
		let pathArray = window.location.pathname.split('/');
		if (pathArray.length === 4 && pathArray[1] === "Annonces") {
			let categoryId = pathArray[3];
			selectedCategoryId = categoryId;
			loadAnnonces(1, false, categoryId);
			$("#id_catgeorie_annonce").val(categoryId).change();
		} else {
			loadAnnonces(page);
		}

		// Événement lorsque l'utilisateur clique sur une catégorie de la liste
		$(".category-link").on("click", function(e) {
			e.preventDefault();
			let categoryId = $(this).data("id");
			let categorySlug = $(this).data("category");
			let newUrl = "/Annonces/" + categorySlug + "/" + categoryId;

			window.history.pushState({}, '', newUrl);
			selectedCategoryId = categoryId;
			page = 1;
			canLoadMore = true;
			loadAnnonces(1, false, categoryId);
		});

		// Filtrer les annonces lors de l'utilisation du formulaire de recherche
		$('#filterForm').on('submit', function(event) {
			event.preventDefault();
			page = 1;
			selectedCategoryId = $("#id_catgeorie_annonce").val();
			canLoadMore = true;
			loadAnnonces(page, false, selectedCategoryId);
		});

		// Asignar evento al botón "Rechercher"
		$('#rechercherButton').on('click', function() {
			$('#filterForm').submit();
		});

		function loadAnnonces(page, append = false, categoryId = null) {
			if (!canLoadMore && append) return;

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
					name: 'id_catgeorie_annonce',
					value: categoryId
				});
				$("#id_catgeorie_annonce").val(categoryId).change();
			}

			if ($('#id_catgeorie_annonce').val() === 'Toutes catégories') {
				formData.push({
					name: 'id_catgeorie_annonce',
					value: ''
				});
			}
			if ($('#id_departement_annonce').val() === 'Tous les départements') {
				formData.push({
					name: 'id_departement_annonce',
					value: ''
				});
			}

			$.ajax({
				type: 'POST',
				url: '/pages/Annonces/Annonces-filtres-ajax.php',
				data: $.param(formData),
				dataType: 'json',
				success: function(data) {
					if (data.status === 'success' && data.data.length > 0) {
						if (!append) {
							$('#annonces-results').empty();
						}
						data.data.forEach(function(annonce) {
							let cleanTitle = annonce.annonce_title.normalize("NFD").replace(/[\u0300-\u036f]/g, "").split(' ')[0].toLowerCase();

							let annonceHtml = `
							<div class="col-xl-4 col-xxl-4 col-lg-4 col-sm-6">
								<div class="card">
									<div class="card-body product-grid-card">
										<div class="new-arrival-product">
											<div class="new-arrivals-img-contnent">
												<a href="/Page-annonce/${cleanTitle}/${annonce.annonce_id}" title="${annonce.annonce_title}">
													<img class="img-fluid" src="/images/membres/${annonce.pseudo}/${annonce.image_name}" alt="${annonce.annonce_title}">
												</a>
											</div>
											<div class="new-arrival-content text-center mt-3">
												<h4><a href="/Page-annonce/${cleanTitle}/${annonce.annonce_id}" title="${annonce.annonce_title}">${annonce.annonce_title}</a></h4>
												<p>Spécialité : <span class="item">${annonce.annonce_specialite}</span></p>
												<p class="text-content">${annonce.annonce_description}</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						`;
							$('#annonces-results').append(annonceHtml);
						});

						canLoadMore = data.data.length >= limit;
					} else {
						if (!append) {
							$('#annonces-results').html('<p class="text-center" style="font-size: 18px; color: #888;">Aucune annonce trouvée.</p>');
						}
						canLoadMore = false;
					}
				},
				error: function(xhr, status, error) {
					console.error("Erreur lors du chargement des annonces:", error);
				}
			});
		}

		$(window).scroll(function() {
			if (canLoadMore && $(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
				page++;
				loadAnnonces(page, true, selectedCategoryId);
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
			<form id="filterForm">
				<div class="row">
					<div class="col-xl-3 col-sm-6">
						<label class="form-label">Mots clés</label>
						<input name="mot_cle_annonce" id="mot_cle_annonce" type="text" class="form-control mb-xl-0 mb-3" id="exampleFormControlInput1" placeholder="Mots clés">
					</div>
					<div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
						<label class="form-label">Catégories</label>
						<select name="id_catgeorie_annonce" id="id_catgeorie_annonce" class="form-control selectpicker" data-live-search="true">
							<option selected="">Toutes catégories</option>
							<?php
							$stmt = $bdd->query('SELECT id, nom_categorie FROM configurations_categories WHERE type="annonce" ORDER BY nom_categorie ASC');
							$categories = $stmt->fetchAll();
							foreach ($categories as $cat): ?>
								<option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-xl-3 col-sm-6">
						<label class="form-label">Département</label>
						<select name="id_departement_annonce" id="id_departement_annonce" class="form-control selectpicker" data-live-search="true">
							<option selected="">Tous les départements</option>
							<?php
							$stmt = $bdd->query('SELECT id,code, name FROM dpts');
							$departments = $stmt->fetchAll();
							foreach ($departments as $dept): ?>
								<option value="<?= htmlspecialchars($dept['id']) ?>"><?= htmlspecialchars($dept['code']) ?> - <?= htmlspecialchars($dept['name']) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-xl-3 col-sm-6 align-self-end">
						<button id="rechercherButton" class="btn btn-default" title="Rechercher" type="button" style="padding: 12px;">Rechercher</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="row" id="annonces-results">
	<?php
	$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
	$limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 9;
	$offset = ($page - 1) * $limit;

	$stmt = $bdd->prepare("
		SELECT 
			i.id AS image_id,
			i.nom_image AS image_name,
			a.id AS annonce_id,
			a.title AS annonce_title,
			a.description AS annonce_description,
			a.specialite AS annonce_specialite,
			a.pseudo,
			AVG(n.note) AS avg_note
		FROM 
			membres_annonces_images i
		INNER JOIN 
			membres_annonces a
		ON 
			i.id_annonce_service = a.id 
		INNER JOIN 
			membres m
		ON 
			a.id_membre = m.id
		LEFT JOIN 
			membres_avis n
		ON 
			a.id = n.id_page
		WHERE 
			a.statut = 'activé' AND m.abonnement = 'oui'
		GROUP BY 
			a.id 
		ORDER BY 
			a.id DESC 
		LIMIT :limit OFFSET :offset
	");
	$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
	$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
	$stmt->execute();

	$annonces = $stmt->fetchAll();
	foreach ($annonces as $annonce):
		$cleanTitle = lettre_sans_accent($annonce['annonce_title']);

		$cleanTitle = explode(' ', trim($cleanTitle))[0];

		$cleanTitle = strtolower($cleanTitle);
	?>
		<div class="col-xl-4 col-xxl-4 col-lg-4 col-sm-6">
			<div class="card">
				<div class="card-body product-grid-card">
					<div class="new-arrival-product">
						<div class="new-arrivals-img-contnent">
							<a href="/Page-annonce/<?= htmlspecialchars($cleanTitle) ?>/<?= htmlspecialchars($annonce['annonce_id']) ?>"
								title="<?= htmlspecialchars($annonce['annonce_title']) ?>">
								<img class="img-fluid" src="/images/membres/<?= htmlspecialchars($annonce['pseudo']) ?>/<?= htmlspecialchars($annonce['image_name']) ?>"
									alt="<?= htmlspecialchars($annonce['annonce_title']) ?>">
							</a>
						</div>
						<div class="new-arrival-content text-center mt-3">
							<h4><a href="/Page-annonce/<?= htmlspecialchars($cleanTitle) ?>/<?= htmlspecialchars($annonce['annonce_id']) ?>"
									title="<?= htmlspecialchars($annonce['annonce_title']) ?>">
									<?= htmlspecialchars($annonce['annonce_title']) ?>
								</a></h4>
							<ul class="star-rating">
								<?php
								$avgNote = round($annonce['avg_note']);
								for ($i = 0; $i < 5; $i++): ?>
									<li><i class="fa fa-star" style="color: <?= $i < $avgNote ? '#ffc107' : '#d8d8d8' ?>"></i></li>
								<?php endfor; ?>
							</ul>
							<p>Spécialité : <span class="item"><?= htmlspecialchars($annonce['annonce_specialite']) ?></span></p>
							<p>Professionnel : <span class="item"><a href="/Fiche/<?= htmlspecialchars($annonce['pseudo']) ?>/8"><?= htmlspecialchars($annonce['pseudo']) ?></a></span></p>
							<p class="text-content"><?= htmlspecialchars($annonce['annonce_description']) ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<div class="col-12">
	<h4>Liste des catégories de annonces</h4>
	<div class="row">
		<?php
		$stmt = $bdd->query('SELECT id, nom_categorie, nom_categorie_url FROM configurations_categories WHERE type="annonce"');
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