<div class="deznav menu_lateral_left">
	<div class="deznav-scroll">
		<ul class="metismenu" id="menu">
			<li><a class="ai-icon" href="/" aria-expanded="false">
					<i class="flaticon-381-networking"></i>
					<span class="nav-text">Dashboard</span>
				</a>
			</li>

			<?php
			////////COMPTE CLIENT
			if($statut_compte_oo == 2 || $statut_compte_oo == 3 || $statut_compte_oo == 4 || $statut_compte_oo == 6 || $statut_compte_oo == 7){
			?>

				<li>
					<a class="ai-icon" href="/Abonnement" aria-expanded="false">
						<i class="fas fa-file-contract"></i>
						<span class="nav-text">Abonnement</span>
					</a>
				</li>

			<?php
			}
			?>

			<?php
			////////COMPTE CLIENT
			if($statut_compte_oo == 1){
			?>

				<li>
					<a class="ai-icon" href="/Plateforme/Depanneurs/1" aria-expanded="false">
						<i class="fas fa-search"></i>
						<span class="nav-text">Dépanneurs</span>
					</a>
				</li>

			<?php
			}
			?>

				<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
						<i class="fas fa-cog"></i>
						<span class="nav-text">Mon compte 
							<span class="badge" title="Messages" ><?php echo $total_message_non_lu; ?></span>
						</span>
					</a>
					<ul aria-expanded="false">
						<?php
							//////////////////////////////////SI ADMIN
							if($admin_oo > 0 ){
								echo "<li class='dropdown-item' ><a class='test' href='/administration/index-admin.php' ><span class='uk-icon-cogs'></span> Admin</a><li>";
							}									
							?>
								<li><a href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Gestion-de-votre-compte.html" title="<?php echo "Mes informations"; ?>"><?php echo "Mes informations"; ?> </a></li>
								<li><a href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/<?php echo "Avatar"; ?>" title="<?php echo "Logo"; ?>"><?php echo "Logo"; ?></a></li>
								<?php
								if($statut_compte_oo == 1){
								?>
									<li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Profil-automobile" title="<?php echo "Profil automobile"; ?>"><?php echo "Profil automobile"; ?></a></li>
								<?php
								}
								?>
								<li><a href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Messagerie.html" title="<?php echo "Messagerie"; ?>"><?php echo "Messagerie"; ?> <span class="badge badge-primary" ><?php echo $total_message_non_lu; ?></span></a></li>
								<li><a id='Deconnexion' class='Deconnexion' href='#'>Déconnexion</a></li>
							</ul>
						</li>

						<?php
						////////COMPTE CLIENT
						if($statut_compte_oo == 1){
						?>

						<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="fas fa-car"></i>
							<span class="nav-text">Mes services</span>
							</a>
							<ul aria-expanded="false">
									<li><a class="dropdown-item nav-link nav_item" href="/Mes-documents" title="<?php echo "Mes documents"; ?>"><?php echo "Mes documents"; ?></a></li>
									<!-- <li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Carte-grise" title="<?php echo "Carte grise"; ?>"><?php echo "Carte grise"; ?></a></li> -->
									<li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Constats" title="<?php echo "Constats"; ?>"><?php echo "Constats"; ?></a></li>
									<li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Mes-annonces-client" title="<?php echo "Mes annonces client"; ?>"><?php echo "Mes annonces client"; ?></a></li>
							</ul>
						</li>

						<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="fas fa-bullseye"></i>
							<span class="nav-text">Dépannages</span>
							</a>
							<ul aria-expanded="false">
								<li><a class="dropdown-item nav-link nav_item" href="/Plateforme/Depanneurs/1" title="<?php echo "Dépanneurs"; ?>"><?php echo "Dépanneurs"; ?></a></li>
									<li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Mes-annonces-client" title="<?php echo "Dépannage express"; ?>"><?php echo "Dépannage express"; ?></a></li>
							</ul>
						</li>

						<?php
						}
						?>

						<!-- Constats -->
						<li>
							<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
								<i class="fa fa-car-crash"></i>
								<span class="nav-text">Constats</span>
							</a>
							<ul aria-expanded="false">
								<li><a href="/Constats">Mes constats</a></li>
								<?php 
								// Only show "Constats clients" if user has pending constats
								if (!empty($mail_oo)) {
									if (!function_exists('get_pending_agency_constats')) {
										require_once('includes/utils/constat_invitation_utils.php');
									}
									$clientConstats = get_pending_agency_constats($mail_oo);
									if (!empty($clientConstats)): 
								?>
									<li><a href="/Constats-clients">Constats clients</a></li>
								<?php 
									endif;
								}
								?>
							</ul>
						</li>

						<?php
						////////COMPTE CLIENT
						if($statut_compte_oo == 3 || $statut_compte_oo == 4 || $statut_compte_oo == 5){
						?>

						<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="fas fa-shopping-cart"></i>
							<span class="nav-text">Marketplace vendeur</span>
							</a>
							<ul aria-expanded="false">
									<li><a class="dropdown-item nav-link nav_item" href="/Mon-profil-vendeur" title="<?php echo "Mon profil vendeur"; ?>"><?php echo "Mon profil vendeur"; ?></a></li>
									<li><a class="dropdown-item nav-link nav_item" href="/Mes-produits" title="<?php echo "Mes offres"; ?>"><?php echo "Mes offres"; ?></a></li>
									<li><a class="dropdown-item nav-link nav_item" href="/Mes-ventes" title="<?php echo "Mes ventes"; ?>"><?php echo "Mes ventes"; ?></a></li>
									<li><a class="dropdown-item nav-link nav_item" href="/Mes-paiements" title="<?php echo "Mes paiements"; ?>"><?php echo "Mes paiements"; ?></a></li>
							</ul>
						</li>

						<?php
						}
						?>

						<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="fas fa-cart-plus"></i>
							<span class="nav-text">Boutique client</span>
							</a>
							<ul aria-expanded="false">
									<li><a class="dropdown-item nav-link nav_item" href="/Marketplace" title="<?php echo "Marketplace"; ?>"><?php echo "Boutique"; ?></a></li>
									<li><a class="dropdown-item nav-link nav_item" href="/Mes-produits-favoris" title="<?php echo "Mes produits favoris"; ?>"><?php echo "Mes produits favoris"; ?></a></li>
									<li><a class="dropdown-item nav-link nav_item" href="/Mes-commandes" title="<?php echo "Mes commandes"; ?>"><?php echo "Mes commandes"; ?></a></li>
									<li><a class="dropdown-item nav-link nav_item" href="/Paiements" title="<?php echo "Paiements"; ?>"><?php echo "Paiements"; ?></a></li>
							</ul>
						</li>

						<?php
						////////COMPTE Professionnel de la mécanique / Professionnel de la carrosserie
						if($statut_compte_oo == 3 || $statut_compte_oo == 4){
						?>

						<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="fas fa-file-alt"></i>
							<span class="nav-text">Mes annonces</span>
							</a>
							<ul aria-expanded="false">
									<li><a class="dropdown-item nav-link nav_item" href="/Mes-annonces" title="<?php echo "Mes annonces"; ?>"><?php echo "Mes annonces"; ?></a></li>
							</ul>
						</li>

						<?php
						}
						?>

						<?php
						////////COMPTE Professionnel Centre contrôle technique
						if($statut_compte_oo == 7){
						?>

						<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="fas fa-file-alt"></i>
							<span class="nav-text">Mes annonces CT</span>
							</a>
							<ul aria-expanded="false">
									<li><a class="dropdown-item nav-link nav_item" href="/Mes-annonces-ct" title="<?php echo "Mes annonces CT"; ?>"><?php echo "Mes annonces CT"; ?></a></li>
							</ul>
						</li>

						<?php
						}
						?>

						<?php
						////////COMPTE Professionnel du service
						if($statut_compte_oo == 6){
						?>

						<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="fas fa-cog"></i>
							<span class="nav-text">Mes services</span>
							</a>
							<ul aria-expanded="false">
									<li><a class="dropdown-item nav-link nav_item" href="/Mes-services" title="<?php echo "Mes services"; ?>"><?php echo "Mes services"; ?></a></li>
							</ul>
						</li>

				
						<?php
						}
						?>

						<?php
						////////COMPTE Dépanneur / Professionnel de la mécanique / Professionnel de la carrosserie / Professionnel du service
						if($statut_compte_oo == 2 || $statut_compte_oo == 3 || $statut_compte_oo == 4 || $statut_compte_oo == 6){
						?>

						<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="fas fa-bullseye"></i>
							<span class="nav-text">Devis</span>
							</a>
							<ul aria-expanded="false">
									<li><a class="dropdown-item nav-link nav_item" href="/Devis" title="<?php echo "Devis"; ?>"><?php echo "Devis"; ?></a></li>
							</ul>
						</li>

				
						<?php
						}
						?>

						<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="fas fa-bullhorn"></i>
							<span class="nav-text">Annonces</span>
							</a>
							<ul aria-expanded="false">
									<li><a class="dropdown-item nav-link nav_item" href="/Annonces" title="<?php echo "Mécanique/Carrosserie"; ?>"><?php echo "Mécanique/Carrosserie"; ?></a></li>
									<li><a class="dropdown-item nav-link nav_item" href="/Services" title="<?php echo "Annonces de services"; ?>"><?php echo "Annonces de services"; ?></a></li>
									<li><a class="dropdown-item nav-link nav_item" href="/Centres-controles-techniques" title="<?php echo "Contrôles techniques"; ?>"><?php echo "Contrôles techniques"; ?></a></li>
							</ul>
						</li>

						<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="fas fa-file-pdf"></i>
							<span class="nav-text">Mes devis</span>
							</a>
							<ul aria-expanded="false">
									<li><a class="dropdown-item nav-link nav_item" href="/Mes-devis" title="<?php echo "Mes devis"; ?>"><?php echo "Mes devis"; ?></a></li>
									<li><a class="dropdown-item nav-link nav_item" href="<?php echo "$http"; ?><?php echo "$nomsiteweb"; ?>/Mes-annonces-client" title="<?php echo "Mes annonces client"; ?>"><?php echo "Mes annonces client"; ?></a></li>
							</ul>
						</li>

					</ul>

		<div class="copyright" style="text-align: center;">
			<p>Copyright ©</p>
		</div>

	</div>
</div>
