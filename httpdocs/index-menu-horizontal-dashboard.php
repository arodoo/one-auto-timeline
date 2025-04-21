<script>
	document.addEventListener("DOMContentLoaded", function() {
		let cartData = null; // Variable para almacenar los datos del carrito una sola vez

		var cartIcon = document.getElementById("cart-icon");
		var cartContainer = document.getElementById("cart-container");

		// Al hacer hover sobre el ícono, cargar la ventana emergente o mostrarla
		cartIcon.addEventListener("mouseenter", function() {
			// Si la ventana emergente no está ya cargada
			if (!document.getElementById("cart-dropdown")) {
				fetch("/pages/paiements/Panier/cart-dropdown.php")
					.then(response => response.text())
					.then(html => {
						cartContainer.insertAdjacentHTML("beforeend", html);
						var dropdown = document.getElementById("cart-dropdown");
						dropdown.style.display = "block";
						loadCartItems(); // Cargamos los datos (1 sola vez) y los mostramos
					})
					.catch(error => console.error("Error al cargar la ventana emergente:", error));
			} else {
				// Si ya se cargó el HTML, solo se muestra
				var dropdown = document.getElementById("cart-dropdown");
				dropdown.style.display = "block";
				loadCartItems(); // Intentamos mostrar los datos (sin nueva petición si ya se guardaron)
			}
		});

		// Ocultar la ventana emergente cuando se salga del área del ícono o del dropdown
		cartIcon.addEventListener("mouseleave", function() {
			setTimeout(function() {
				var cartDropdown = document.getElementById("cart-dropdown");
				if (cartDropdown && !cartDropdown.matches(':hover')) {
					cartDropdown.style.display = "none";
				}
			}, 300);
		});

		// Mantener la ventana emergente visible si el mouse está sobre ella
		document.addEventListener("mousemove", function(event) {
			var cartDropdown = document.getElementById("cart-dropdown");
			if (cartDropdown && !cartIcon.contains(event.target) && !cartDropdown.contains(event.target)) {
				cartDropdown.style.display = "none";
			}
		});

		// Función principal que se llama al hacer hover
		function loadCartItems() {
			// Si ya tenemos datos guardados en cartData, NO volvemos a pedirlos
			if (cartData) {
				displayCartItems(cartData);
			} else {
				// Hacemos la petición solo la primera vez
				fetch("/pages/paiements/Panier/get-cart-items.php")
					.then(response => response.json())
					.then(data => {
						cartData = data; // Guardamos los datos en memoria
						displayCartItems(data); // Mostramos los datos en el dropdown
					})
					.catch(error => {
						console.error("Error al cargar los productos:", error);
						const cartItemsList = document.getElementById("cart-items");
						if (cartItemsList) {
							cartItemsList.innerHTML = "<li>Error al cargar los productos</li>";
						}
					});
			}
		}

		// Función para generar el HTML de cada producto y mostrarlo
		function displayCartItems(data) {
			const cartItemsList = document.getElementById("cart-items");
			if (!cartItemsList) return; // Si no existe, salimos

			cartItemsList.innerHTML = ""; // Limpia la lista

			if (data.length > 0) {
				data.forEach(item => {
					let li = document.createElement("li");
					li.classList.add("cart-item");

					let precioUnitario = parseFloat(item.PU_HT) || 0;
					let cantidad = parseInt(item.quantite) || 0;
					let tvaTaux = parseFloat(item.TVA_TAUX) || 0;

					let subTotal = precioUnitario * cantidad;
					let valorIVA = subTotal * (tvaTaux / 100);
					let total = subTotal + valorIVA;

					li.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: bold;">Mon panier</span>
                        <button class="btn-close" data-id="${item.id}" 
                                style="background: none; border: none; font-size: 16px; cursor: pointer;">
                        </button>
                    </div>

                    <h6 style="text-align: center; font-weight: bold; margin-top: 10px;">
                        ${item.libelle}
                    </h6>

                    <p style="text-align: center;">
                        ${cantidad} x ${precioUnitario} F CFA
                    </p>


                    <div style="margin-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>Sous-total articles</span>
                            <span>${subTotal.toLocaleString()} F CFA</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>TVA ${tvaTaux}%</span>
                            <span>${valorIVA.toLocaleString()} F CFA</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: bold;">
                            <span>Total</span>
                            <span>${total.toLocaleString()} F CFA</span>
                        </div>
                    </div>
                `;

					// Insertamos el <li> en la lista
					cartItemsList.appendChild(li);

					// Agregamos un listener para el botón "x" que muestre el ID en la consola
					const closeBtn = li.querySelector(".btn-close");
					closeBtn.addEventListener("click", function() {
						const productId = this.getAttribute("data-id");
						console.log("Se hizo clic en la 'x' con ID:", productId);

						// Petición AJAX con jQuery para eliminar el producto en el backend
						$.ajax({
							url: "/pages/paiements/Panier/delete-cart-item.php",
							type: "POST",
							data: {
								productId: productId
							},
							dataType: "json",
							success: function(data) {
								console.log("Respuesta del servidor:", data);
								if (data.retour_validation === "ok") {
									alert("Producto eliminado correctamente");
									// Opcional: eliminar el <li> del DOM, por ejemplo:
									// $(closeBtn).closest("li").remove();
								} else {
									console.error("Error al eliminar el producto:", data.Texte_rapport);
									alert("No se pudo eliminar el producto");
								}
							},
							error: function(jqXHR, textStatus, errorThrown) {
								console.error("Error en la petición de eliminación:", errorThrown);
								alert("Ocurrió un error al eliminar el producto");
							}
						});
					});

				});
			} else {
				cartItemsList.innerHTML = "<li>El carrito está vacío</li>";
			}
		}
	});
</script>





<div class="header">
	<style>

	</style>
	<div class="header-content">
		<nav class="navbar navbar-expand">
			<div class="collapse navbar-collapse justify-content-between">

				<div class="header-left">
					<!--
					<div class="dashboard_bar">
						<div class="input-group search-area d-lg-inline-flex d-none">
							<div class="input-group-append">
								<button class="input-group-text search_icon search_icon" title="Chercher un trajet de covoiturage"><i class="flaticon-381-search-2"></i></button>
							</div>
							<input type="text" id="date_trajet" name="date_trajet" class="form-control" value="" placeholder="Chercher un trajet par date" data-default-date="">
						</div>
					</div>
					-->
				</div>

				<ul class="navbar-nav header-right">

					<!-- <li class="nav-item dropdown notification_dropdown">
						<a class="nav-link bell bell-link" href="/Paiement">
							<svg class="svg-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
								xmlns="http://www.w3.org/2000/svg">
								<path
									d="M7 4H3V6H5L8.68 14.39L7.24 17.04C7.09 17.32 7 17.65 7 18C7 18.55 7.45 19 8 19H19V17H8.42C8.28 17 8.17 16.89 8.17 16.75L8.2 16.65L9.1 14H16.55C17.3 14 17.96 13.55 18.3 12.87L21.88 6.48C21.96 6.34 22 6.17 22 6C22 5.45 21.55 5 21 5H8.1L7.34 3H3V5H7L7 4ZM9.16 12L11.55 7H19.04L16.55 12H9.16ZM7 20C6.45 20 6 20.45 6 21C6 21.55 6.45 22 7 22C7.55 22 8 21.55 8 21C8 20.45 7.55 20 7 20ZM17 20C16.45 20 16 20.45 16 21C16 21.55 16.45 22 17 22C17.55 22 18 21.55 18 21C18 20.45 17.55 20 17 20Z"
									fill="currentColor" />
							</svg>
							<span class="badge light text-white bg-primary rounded-circle"><?php echo $total_item_panier; ?></span>
						</a>
					</li> -->
					<li class="nav-item dropdown notification_dropdown" id="cart-container">
						<a class="nav-link bell bell-link" href="/Paiement" id="cart-icon">
							<svg class="svg-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
								xmlns="http://www.w3.org/2000/svg">
								<path d="M7 4H3V6H5L8.68 14.39L7.24 17.04C7.09 17.32 7 17.65 7 18C7 18.55 7.45 19 8 19H19V17H8.42C8.28 17 8.17 16.89 8.17 16.75L8.2 16.65L9.1 14H16.55C17.3 14 17.96 13.55 18.3 12.87L21.88 6.48C21.96 6.34 22 6.17 22 6C22 5.45 21.55 5 21 5H8.1L7.34 3H3V5H7L7 4ZM9.16 12L11.55 7H19.04L16.55 12H9.16ZM7 20C6.45 20 6 20.45 6 21C6 21.55 6.45 22 7 22C7.55 22 8 21.55 8 21C8 20.45 7.55 20 7 20ZM17 20C16.45 20 16 20.45 16 21C16 21.55 16.45 22 17 22C17.55 22 18 21.55 18 21C18 20.45 17.55 20 17 20Z"
									fill="currentColor" />
							</svg>
							<span class="badge light text-white bg-primary rounded-circle"><?php echo $total_item_panier; ?></span>
						</a>
					</li>



					<li class="nav-item dropdown notification_dropdown">
						<a class="nav-link bell bell-link" href="/Messagerie.html" title="Messagerie">
							<svg class="svg-icon" width="20" height="20" viewBox="0 0 28 28" fill="none"
								xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd"
									d="M25.6666 8.16666C25.6666 5.5895 23.5771 3.5 21 3.5C17.1161 3.5 10.8838 3.5 6.99998 3.5C4.42281 3.5 2.33331 5.5895 2.33331 8.16666V23.3333C2.33331 23.8058 2.61798 24.2305 3.05315 24.4113C3.48948 24.5922 3.99115 24.4918 4.32481 24.1582C4.32481 24.1582 6.59281 21.8902 7.96714 20.517C8.40464 20.0795 8.99733 19.8333 9.61683 19.8333H21C23.5771 19.8333 25.6666 17.7438 25.6666 15.1667V8.16666ZM23.3333 8.16666C23.3333 6.87866 22.2891 5.83333 21 5.83333C17.1161 5.83333 10.8838 5.83333 6.99998 5.83333C5.71198 5.83333 4.66665 6.87866 4.66665 8.16666V20.517L6.31631 18.8673C7.19132 17.9923 8.37899 17.5 9.61683 17.5H21C22.2891 17.5 23.3333 16.4558 23.3333 15.1667V8.16666ZM8.16665 15.1667H17.5C18.144 15.1667 18.6666 14.644 18.6666 14C18.6666 13.356 18.144 12.8333 17.5 12.8333H8.16665C7.52265 12.8333 6.99998 13.356 6.99998 14C6.99998 14.644 7.52265 15.1667 8.16665 15.1667ZM8.16665 10.5H19.8333C20.4773 10.5 21 9.97733 21 9.33333C21 8.68933 20.4773 8.16666 19.8333 8.16666H8.16665C7.52265 8.16666 6.99998 8.68933 6.99998 9.33333C6.99998 9.97733 7.52265 10.5 8.16665 10.5Z" />
							</svg>
							<span
								class="badge light text-white bg-primary rounded-circle"><?php echo $total_message_non_lu; ?></span>
						</a>
					</li>
					<li class="nav-item dropdown header-profile">
						<a class="nav-link" href="javascript:void(0)" role="button" data-bs-toggle="dropdown">
							<div class="header-info">
								<span class="text-black">Bonjour, <strong><?php echo $prenom_oo; ?>
										<b><?php echo strtoupper(substr($nom_oo, 0, 1)); ?>.</b> </strong></span>
								<p class="fs-12 mb-0"><?php if ($admin_oo > 0) {
															echo "Administrateur";
														} else { ?> <?php echo $id_statut_compte_membre; ?> <?php } ?> </p>
							</div>
							<?php
							if (!empty($image_profil_oo)) {
							?>
								<div class="<?php echo ($abonnement_oo == 'oui') ? 'golden-border' : ''; ?>">
									<img alt="image"
										src="/images/membres/<?php echo $pseudo_oo; ?>/<?php echo $image_profil_oo; ?>"
										style="width:20;"
										alt="<?php echo $image_profil_oo; ?>"
										class="img-fluid">
								</div>
							<?php
							} else {
							?>
								<div class="<?php echo ($abonnement_oo == 'oui') ? 'golden-border' : ''; ?>">
									<img src="/images/profile/1.jpg"
										class="img-fluid"
										style="width:20;"
										alt="Photo de profil">
								</div>
							<?php
							}
							?>
						</a>
						<div class="dropdown-menu dropdown-menu-end dropdown-menu2">
							<a href="/Gestion-de-votre-compte.html" class="dropdown-item ai-icon">
								<svg id="icon-info" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18"
									height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<circle cx="12" cy="12" r="10"></circle>
									<line x1="12" y1="16" x2="12" y2="12"></line>
									<line x1="12" y1="8" x2="12.01" y2="8"></line>
								</svg>
								<span class="ms-2">Informations</span>
							</a>

							<?php if ($statut_compte_oo == 1) { ?>

								<a href="/Profil-automobile" class="dropdown-item ai-icon">
									<svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18"
										height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
										stroke-linecap="round" stroke-linejoin="round">
										<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
										<circle cx="12" cy="7" r="4"></circle>
									</svg>
									<span class="ms-2">Profil automobile</span>
								</a>

							<?php } ?>

							<?php
							////////COMPTE Dépanneur / Professionnel de la mécanique / Professionnel de la carrosserie / Professionnel de la vente / Professionnel du service
							if ($statut_compte_oo == 2 || $statut_compte_oo == 3 || $statut_compte_oo == 4 || $statut_compte_oo == 5 || $statut_compte_oo == 6) {
							?>

								<a href="<?php echo "$url_profil_oo"; ?>" class="dropdown-item ai-icon">
									<svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18"
										height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
										stroke-linecap="round" stroke-linejoin="round">
										<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
										<path d="M16 16c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
									</svg>
									<span class="ms-2">Profil public</span>
								</a>

								<a href="/Profil-professionnel" class="dropdown-item ai-icon">
									<svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18"
										height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
										stroke-linecap="round" stroke-linejoin="round">
										<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
										<circle cx="12" cy="7" r="4"></circle>
									</svg>
									<span class="ms-2">Profil professionnel</span>
								</a>

							<?php } ?>

							<a class="dropdown-item ai-icon" href="javascript:void(0);" title="Télécharger une photo"
								data-bs-toggle="modal" data-bs-target="#cameraModal">
								<svg id="icon-avatar" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18"
									height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<path d="M20 21v-2a4 4 0 0 0-3-3.87"></path>
									<path d="M4 21v-2a4 4 0 0 1 3-3.87"></path>
									<path d="M9 8.35a4 4 0 1 1 6 0"></path>
									<line x1="12" y1="20" x2="12" y2="14"></line>
								</svg>
								<span class="ms-2">Photo de profil</span>
							</a>

							<a class="dropdown-item ai-icon" href="/Liste-avatar" title="Choisir un avatar">
								<svg id="icon-avatar" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18"
									height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
									<path d="M12 14c-4.41 0-8 1.79-8 4v2h16v-2c0-2.21-3.59-4-8-4z"></path>
								</svg>
								<span class="ms-2">Choisir un avatar</span>
							</a>


							<?php if ($statut_compte_oo == 1) { ?>
								<a href="/Mes-documents" class="dropdown-item ai-icon">
									<svg id="icon-documents" xmlns="http://www.w3.org/2000/svg" class="text-primary"
										width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
										stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8z"></path>
										<polyline points="14 2 14 8 20 8"></polyline>
										<line x1="16" y1="13" x2="8" y2="13"></line>
										<line x1="16" y1="17" x2="8" y2="17"></line>
										<polyline points="10 9 9 9 8 9"></polyline>
									</svg>
									<span class="ms-2">Documents</span>
								</a>

							<?php } ?>

							<a href="/Messagerie.html" class="dropdown-item ai-icon">
								<svg id="icon-inbox" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18"
									height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<path
										d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
									</path>
									<polyline points="22,6 12,13 2,6"></polyline>
								</svg>
								<span class="ms-2">Messages </span>
							</a>
							
							<?php 
							// Determine if the user is an insurance agency by checking if they have client constats
							$hasClientConstats = false;
							if (!empty($mail_oo)) {
								require_once('includes/utils/constat_invitation_utils.php');
								$clientConstats = get_pending_agency_constats($mail_oo);
								$hasClientConstats = !empty($clientConstats);
							}

							if ($hasClientConstats): 
							?>
								<a href="/panel/Constats/constats-client.php" class="dropdown-item ai-icon">
									<svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8z"></path>
										<polyline points="14 2 14 8 20 8"></polyline>
										<line x1="16" y1="13" x2="8" y2="13"></line>
										<line x1="16" y1="17" x2="8" y2="17"></line>
										<polyline points="10 9 9 9 8 9"></polyline>
									</svg>
									<span class="ms-2">Constats clients</span>
								</a>
							<?php endif; ?>

							<?php
							////////COMPTE Dépanneur / Professionnel de la mécanique / Professionnel de la carrosserie / Professionnel du service
							if ($statut_compte_oo == 2 || $statut_compte_oo == 3 || $statut_compte_oo == 4 || $statut_compte_oo == 6) {
							?>

								<a href="/Abonnement" class="dropdown-item ai-icon">
									<svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18"
										viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
										stroke-linecap="round" stroke-linejoin="round">
										<path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8z"></path>
										<polyline points="14 2 14 8 20 8"></polyline>
										<polyline points="10 9 9 9 8 9"></polyline>
									</svg>
									<span class="ms-2">Abonnement</span>
								</a>

							<?php } ?>

							<a href="/Factures" class="dropdown-item ai-icon">
								<svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18"
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8z"></path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="16" y1="13" x2="8" y2="13"></line>
									<line x1="16" y1="17" x2="8" y2="17"></line>
									<polyline points="10 9 9 9 8 9"></polyline>
								</svg>
								<span class="ms-2">Factures</span>
							</a>

							<a href="#" class="dropdown-item ai-icon Deconnexion" onclick="return false;">
								<svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18"
									height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round">
									<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
									<polyline points="16 17 21 12 16 7"></polyline>
									<line x1="21" y1="12" x2="9" y2="12"></line>
								</svg>
								<span class="ms-2">Déconnection </span>
							</a>
						</div>
					</li>
				</ul>
			</div>
		</nav>
	</div>
</div>