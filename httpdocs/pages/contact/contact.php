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

?>

<script>
$(document).ready(function (){

	//AJAX SOUMISSION DU FORMULAIRE
	$(document).on("click", "#Envoyer", function (){
		$.post({
			url : '/pages/contact/contact-ajax.php',
			type : 'POST',
			data: new FormData($("#contact-form")[0]),
			processData: false,
			contentType: false,
			dataType: "json",
			success: function (res) {
        if(res.retour_validation == "ok"){
          popup_alert(res.Texte_rapport,"green filledlight","#009900","uk-icon-check");
          service_mail:$('#service_mail').val(),
          $('#objetpost').val("");
          $('#messagepost').val("");
          $('html, body').animate({ scrollTop: 0 }, 'slow');
          setTimeout(function(){
            location.reload();
          }, 1500);
				}else{
					popup_alert(res.Texte_rapport,"#CC0000 filledlight","#CC0000","uk-icon-times");
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
			}
		});
	});
});

</script>

<div class="container mt-5">
      <div class="row justify-content-center" style="text-align: left;" >
        <div class="col-lg-6 col-md-8 col-sm-10">

	<form id="contact-form" method='post' action='#' enctype="multipart/form-data">

			<input type='hidden' id='pseudomail' name='pseudomail' value="exemple@domaine.com"/>
			<div style='display: none;'>
				* <?php echo "Mail"; ?> <input type='text' id='eeemail' name='eeemail' value=""/> Ne pas remplir ce champ, merci !
			</div>

			<input type='hidden' id='pseudomail' name='pseudomail' value="exemple@domaine.com"/>
			<div style='display: none;'>
				* <?php echo "Mail"; ?> <input type='text' id='eeemail' name='eeemail' value=""/> Ne pas remplir ce champ, merci !
			</div>

            <div class="mb-3">
				<label>*<?php echo "Contact"; ?> :</label>
				<select id='service_mail' name='service_mail' class="form-control" >
				<?php
				///////////////////////////////SELECT BOUCLE
				$req_boucle = $bdd->prepare("SELECT * FROM contact WHERE activer='oui' ORDER BY position ASC");
				$req_boucle->execute();
				while($ligne_boucle = $req_boucle->fetch()){
				$idgcontact = $ligne_boucle['id'];	
				$serviceone = $ligne_boucle['service'];
				$mailonemail = $ligne_boucle['mail'];
				$activeronemail = $ligne_boucle['activer'];

					if($service_mail == $mailonemail){
					?>
						<option selected='selected' value="<?php echo "$mailonemail"; ?>"> <?php echo "$serviceone"; ?> &nbsp;</option>
					<?php
					}else{
					?>
						<option value="<?php echo "$mailonemail"; ?>"> <?php echo "$serviceone"; ?> &nbsp;</option>
					<?php
					}
				}
				?>
				</select>
	    </div>

            <div class="mb-3">
              <label for="nom" class="form-label">*Sujet</label>
              <input
                type="text"
                class="form-control"
                id="objetpost"
                name="objetpost"
                placeholder="Cam"
                required
              />
            </div>

            <div class="mb-3">
              <label for="nom" class="form-label">*Nom</label>
              <input
                type="text"
                class="form-control"
                id="Namepost"
                name="Namepost"
                placeholder="Cam"
                required
              />
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                class="form-control"
                id="mailpost"
                name="mailpost"
                placeholder="@"
              />
            </div>

            <div class="mb-3">
              <label for="telephone" class="form-label">Téléphone</label>
              <input
                type="tel"
                class="form-control"
                id="telephone"
                name="telephone"
                placeholder=""
              />
            </div>

            <div class="mb-3">
              <label for="commentaire" class="form-label">*Commentaire</label>
              <textarea
                class="form-control"
                id="messagepost"
                name="messagepost"
                rows="3"
                placeholder="Commentaire"
                required
              ></textarea>
            </div>

            <div class="mb-3">
			"Les données collectées par le formulaire de contact sont nécessaires pour répondre à votre message. Vous disposez d'un droit d'accès, de rectification, d'opposition, de limitation du traitement, de suppression et de portabilité.
	    </div>

            <div class="d-grid text-center">
              <button type="submit" id='Envoyer' class="btn" onclick="return false;" >ENVOYER</button>
            </div>

          </form>
        </div>
      </div>
    </div>

