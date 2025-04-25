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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {

	$action = $_GET['action'];
	$idaction = $_GET['idaction'];

	?>
	<script>
		$(document).ready(function () {

			var dropzone = $('#dropzone');
			var fileInput = $('#fileInput');
			var fileSelectBtn = $('#fileSelectBtn');
			var uploadForm = $('#uploadForm');

			dropzone.on('dragover', function (e) {
				e.preventDefault();
				e.stopPropagation();
				$(this).addClass('dragover');
			});

			dropzone.on('dragleave', function (e) {
				e.preventDefault();
				e.stopPropagation();
				$(this).removeClass('dragover');
			});

			dropzone.on('drop', function (e) {
				e.preventDefault();
				e.stopPropagation();
				$(this).removeClass('dragover');

				var files = e.originalEvent.dataTransfer.files;
				handleFiles(files);
			});

			fileSelectBtn.on('click', function () {
				fileInput.click();
			});

			fileInput.on('change', function () {
				var files = this.files;
				handleFiles(files);
			});

			function handleFiles(files) {
				for (var i = 0; i < files.length; i++) {
					var file = files[i];
					if (file.type.match('image.*')) {
						uploadFile(file);
					} else {
						alert('Seules les images sont autorisées.');
					}
				}
			}

			function uploadFile(file) {

				var formData = new FormData(uploadForm[0]); // Utilisez le formulaire complet
				formData.append('file', file);

				$.ajax({
					url: '/panel/Mes-documents/Mes-documents-action-uploads-ajax.php',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					dataType: "json",
					success: function (response) {
						if (response.retour_validation == "ok") {
							toastr.success(response.Texte_rapport, "Effectué");
						} else {
							toastr.error(response.Texte_rapport, "Erreur");
						}
						liste();
					},
					error: function (jqXHR, textStatus, errorMessage) {
						toastr.error('Erreur lors du téléchargement : ' + errorMessage, "Erreur");
					}
				});
			}

			// FUNCTION AJAX - LISTE
			function liste() {
				$.post({
					url: '/panel/Mes-documents/Mes-documents-liste-ajax.php',
					type: 'POST',
					dataType: "html",
					success: function (res) {
						$("#liste").html(res);
					}
				});
			}
			liste();

			$(document).on('click', '.supprimer', function () {
				$.post({
					url: '/panel/Mes-documents/Mes-documents-modal-supprimer.php',
					type: 'POST',
					data: {
						idaction: $(this).attr("data-id")
					},
					dataType: "html",
					success: function (res) {
						$("body").append(res)
						$("#modalSuppr").modal('show')
					}
				})
			});

			$(document).on("click", "#btnSuppr", function () {
				$.post({
					url: '/panel/Mes-documents/Mes-documents-action-supprimer-ajax.php',
					type: 'POST',
					data: {
						idaction: $(this).attr("data-id")
					},
					dataType: "json",
					success: function (res) {
						if (res.retour_validation == "ok") {
							toastr.success(res.Texte_rapport, "Effectué");
						} else {
							toastr.error(res.Texte_rapport, "Erreur");
						}
						liste();
						$("#modalSuppr").modal('hide')
					}
				});
			});

			$(document).on("click", "#btnNon", function () {
				$("#modalSuppr").modal('hide')
			});

			$(document).on('hidden.bs.modal', "#modalSuppr", function () {
				$(this).remove()
			})

		});
	</script>

	<div class="row">

		<div class="col-lg-12">

			<div class="card">
				<div class="card-body">
					<div class="row">

						<form id="uploadForm" method="POST" action="#">
							<div style="margin-bottom: 20px;">
								<div class="mb-3">
									<label class="form-label" for="Name">Sélectionner une catégorie</label>
									<select name="id_projet" class="form-control" id="id_projet" data-live-search="true"
										style='margin-bottom: 15px;'>
										<option value="" disabled>Sélection</option>
										<?php
										$req_bouclem = $bdd->prepare("SELECT * FROM configurations_categorie_documents ORDER BY position ASC");
										$req_bouclem->execute();
										while ($ligne_bouclem = $req_bouclem->fetch()) {
											?>
											<option <?php if ($_SESSION['id_categorie_document'] == $ligne_bouclem['id']) {
												echo "selected";
											} ?> value="<?php echo $ligne_bouclem['id']; ?>">
												<?php echo $ligne_bouclem['nom']; ?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>

							<div id="dropzone" style="margin-bottom: 40px;">
								<p>Glissez et déposez vos images ici</p>
								<p>ou</p>
								<button id="fileSelectBtn" type="button">Sélectionner des fichiers</button>
								<input type="file" id="fileInput" name="file" multiple style="display: none;">
							</div>
						</form>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">

		<div class="col-lg-12">

			<div class="card">
				<div class="card-body">
					<div class="row">

						<?php

						////////////////////////////////////////////////////////////////////////////////////////////PAS D'ACTION
						if (!isset($action)) {
							?>

							<div id='liste' style='clear: both;'></div>

							<?php
						}
						?>

					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
} else {
	header('location: /');
}
?>