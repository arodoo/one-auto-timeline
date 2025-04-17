<?php

$modif = "oui";

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

	//JSPANEL TYPE : http://jspanel.de/api/#option/paneltype

?>

	<script>
		$(document).ready(function() {

			// AJAX SOUMISSION DU FORMULAIRE - MODIFIER 
			$(document).on("click", "#modification_post", function() {
				var form = $("#formulaire_inscription")[0];
				var formData = new FormData(form);

				// Imprimir el formulario en la consola
				console.log("Formulario enviado:");
				for (let [key, value] of formData.entries()) {
					console.log(`${key}: ${value}`);
				}

				$.post({
					url: '/panel/Profil/Compte-modifications-ajax.php',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					dataType: "json",

					success: function(res) {
						if (res.retour_validation == "ok") {
							popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
							setTimeout(function() {
								<?php if ($statut_compte_oo == 1 && $platine != "oui" && $premium != "oui") { ?>
									$(location).attr("href", "/Formules");
								<?php } elseif ($statut_compte_oo == 1) { ?>
									$(location).attr("href", "/Missions");
								<?php } else { ?>
									$(location).attr("href", "/Gestion-de-votre-compte.html");
								<?php } ?>
							}, 800);
						} else {
							popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
						}
					}
				});
			});



			//AFFICHE INFORMATIONS MOT DE PASSE
			$(document).on("click", "#password", function() {
				$('#rappot_mot_de_passe_nouveau').css("display", "");
			});

		});
	</script>

<?php
	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT * FROM membres WHERE pseudo=?");
	$req_select->execute(array($user));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
	$idd2dddf = $ligne_select['id'];
	$pseudo_creation = $ligne_select['pseudo'];
	$Mail = $ligne_select['mail'];
	$Nom = $ligne_select['nom'];
	$Prenom = $ligne_select['prenom'];
	$prenom_autres = $ligne_select['prenom_autres'];
	$Pays = $ligne_select['Pays'];
	$Numero = $ligne_select['Numero'];
	$Type_extension = $ligne_select['Type_extension'];
	$type_voie = $ligne_select['type_voie'];
	$Adresse = $ligne_select['adresse'];
	$Code_postal = $ligne_select['cp'];
	$Ville = $ligne_select['ville'];
	$datenaissance = $ligne_select['datenaissance'];
	$Telephone = $ligne_select['Telephone'];
	$Telephone_portable = $ligne_select['Telephone_portable'];
	$faxpost = $ligne_select['Fax'];
	$cbaonepost = $ligne_select['newslettre'];
	$cbb = $ligne_select['reglement_accepte'];
	$FH = $ligne_select['femme_homme'];
	$date_enregistrement = $ligne_select['date_enregistrement'];
	$date_enregistrement = date("d-m-Y", $date_enregistrement);

	$ville_naissance = $ligne_select['ville_naissance'];
	$pays_naissance = $ligne_select['pays_naissance'];
	$datenaissance = $ligne_select['datenaissance'];

	$nom_commercial = $ligne_select['nom_commercial'];

	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT * FROM membres_professionnel WHERE pseudo=?");
	$req_select->execute(array($user));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
	$Nom_societe = $ligne_select['Nom_societe'];
	$Numero_identification = $ligne_select['Numero_identification'];

	if (empty($Adresse)) {
		///////////////////////////////SELECT
		$req_select = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id_membre=?");
		$req_select->execute(array($id_oo));
		$ligne_select = $req_select->fetch();
		$req_select->closeCursor();
		$idoneinfos_artciles_blog_profil = $ligne_select['id'];
		$Adresse = $ligne_select['adresse'];
		$Code_postal = $ligne_select['cp'];
		$Ville = $ligne_select['ville'];
	}


	echo "<form id='formulaire_inscription' method='post' action='#' >";
	include('panel/Profil/Compte-formulaire.php');
	echo "</form>";
} else {
	header('location:/');
}
?>