<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

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

$idaction = $_POST['idaction'];

///////////////////////////////SELECT
$req_selectf = $bdd->prepare("SELECT * FROM membres WHERE id=?");
$req_selectf->execute(array($idaction));
$ligne_selectf = $req_selectf->fetch();
$req_selectf->closeCursor();

///////////////////////////////SELECT
$req_selectfd = $bdd->prepare("SELECT * FROM membres_devis WHERE id_membre_utilisateur=? AND id_membre_depanneur=? AND TIMESTAMPDIFF(SECOND, date_demande, NOW()) < 86400");
$req_selectfd->execute(array($id_oo, $ligne_selectf['id']));
$ligne_selectfd = $req_selectfd->fetch();
$req_selectfd->closeCursor();
$id_devis_existe = $ligne_selectfd['id'];

?>

<div class="modal" id="modalNom1" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="titleEtablissement" style="font-weight: bold; text-align: center;"> Profil
					du dépanneur</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body" style="overflow-y: auto; max-height: 550px;">
				<div style="text-align: center;">

					<div class="row" style="margin-top: 0px;">

						<?php if (!empty($user)) { ?>

							<div class="col-md-12 col-sm-12" style="text-align: center; margin-top: 0px;">

								<?php if (!empty($ligne_selectf['image_profil'])) { ?>
									<img class='imageRadius'
										src='/images/membres/<?php echo $ligne_selectf['pseudo']; ?>/<?php echo $ligne_selectf['image_profil']; ?>'
										alt="<?php echo $image_profil; ?>"> <br />

								<?php
								} else {
									?>
									<img class="imageRadius" src="/images/profile/1.jpg" alt="1.jpg"
										style="width: 100%; height: auto;"> <br />

								<?php } ?>

								<span> <?php echo $ligne_selectf['nom']; ?> 	<?php echo $ligne_selectf['prenom']; ?></span>
								<br />
								<span><?php echo $ligne_selectf['ville']; ?> 	<?php echo $ligne_selectf['cp']; ?></span>
								<br />
								<span><?php echo $ligne_selectf['mail']; ?>
									<?php echo $ligne_selectf['Telephone_portable']; ?></span> <br />

							</div>

							<?php if (empty($id_devis_existe)) { ?>

								<div class="col-md-12 col-sm-12" style="text-align: center;">
									<h3 style="color: #000000;">Demande de devis</h3>
									<form id="envoyer_devis" action="#" method="post">
										<input type="hidden" class="form-control" name="idaction"
											value="<?php echo $_POST['idaction']; ?>">
										<div class="form-group">
											<input type="text" placeholder="Objet" class="form-control" id="objet_de_la_demande"
												name="objet_de_la_demande" required>
										</div>
										<div class="form-group">
											<textarea class="form-control" placeholder="Message" id="description_de_la_demande"
												name="description_de_la_demande" rows="2" required></textarea>
										</div>
										<button type="submit" class="btn btn-primary" onclick="return false;">Envoyer</button>
									</form>
								</div>

							<?php } else { ?>

								<div class='alert alert-danger' style='text-align: left; margin-bottom: 10px;'>Vous avez déjà
									envoyé un devis à ce dépanneur.</div>

							<?php } ?>

						<?php } else { ?>

							<div class='alert alert-danger' style='text-align: left; margin-bottom: 10px;'>Vous devez être
								connecté pour voir les informations du dépanneur.</div>

						<?php } ?>

					</div>

				</div>

			</div>
		</div>
	</div>
</div>
</div>

<?php
ob_end_flush();
?>