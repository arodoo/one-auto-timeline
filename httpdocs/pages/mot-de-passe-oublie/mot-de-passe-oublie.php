<?php

if(empty($user)){

$action = $_GET['action'];
$idaction = $_GET['idaction'];

$erreur = $_GET['erreur'];
$dateverif = (time()-7200);

$idverif = $_GET['idverif'];
$mail = $_GET['mail'];

?>

<script>
$(document).ready(function (){

<?php
//JQUERY PASSWORD - RETOUR MAIL
if(!empty($_GET['idverif'])){
?>
	//AJAX SOUMISSION DU FORMULAIRE
	$(document).on("click", "#Password_submit", function (){
		$.post({
			url : "/pages/mot-de-passe-oublie/mot-de-passe-oublie-nouveau-ajax.php<?php echo "?action_password=modif&idverif=".$_GET['idverif']."&mail=".$_GET['mail']."";?>",
			type : 'POST',
			data : {
				nouveau_mot_de_passe:$('#nouveau_mot_de_passe').val(),
				nouveau_mot_de_passe_controle:$('#nouveau_mot_de_passe_controle').val()
			},
			dataType: "json",
			success: function (res) {
     				if(res.retour_validation == "ok"){
            				$('#retour_password').html(""+res.Texte_rapport+"");
            				$('#bloc_definir_nouveau_mot_de_passe').css("display","none");
            				//$('#formulaire_nouveau_mot_de_passe').css("display","none");
					setTimeout(function() {
						window.location.href = "/";
					}, 2000); // 2000 millisecondes = 2 secondes
     				}else{
            				$('#retour_password').html(""+res.Texte_rapport+"");
     				}
			}
		});
	});
<?php
}
?>

     //AJAX SOUMISSION DU FORMULAIRE - MODIFIER - AJOUTER
     $(document).on("click", "#nouveau_mot_de_passe_submit", function (){
            $.post({
			url : '/pages/mot-de-passe-oublie/mot-de-passe-oublie-ajax.php',
                     type : 'POST',
                     data: new FormData($("#formulaire_definir_nouveau_mot_de_passe")[0]),
                     processData: false,
                     contentType: false,
                     dataType: "json",
                     success: function (res) {
                         if(res.retour_validation == "ok"){
                             $('#retour_password').html(res.Texte_rapport);
            			$('#bloc_definir_nouveau_mot_de_passe').css("display","none");
            			$('#formulaire_nouveau_mot_de_passe').css("display","none");
                         }else{
                             $('#retour_password').html(res.Texte_rapport);
				}
                     }


                     });
            });

});
</script>

<div class="login-account" style="margin-bottom: 40px;" >
		<div class="row h-100">
			<div class="col-lg-12 col-md-12 col-sm-12 mx-auto align-self-center" style="max-width: 500px;" >
				<div class="login-form">

					<div id='retour_password'></div>

					<?php
					/////////////////////////////////////////FORMULAIRE DEMANDE NOUVEAU MOT DE PASSE
					if(empty($_GET['idverif'])){
					?>
						<form id='formulaire_definir_nouveau_mot_de_passe' method='post' action='#' >
							<div class="text-center mb-4">
								<input class="form-control" type="mail" id="mail_password_redefinition" name="mail_password_redefinition" title="Adresse mail" placeholder='Adresse mail' /></td></tr>
							</div>
							<div class="text-center mb-4">
								<button id='nouveau_mot_de_passe_submit' type="submit" class="btn btn-default btn-block" onclick="return false;" >CONFIRMER</button>
							</div>
						</form>

					<?php
					}

					/////////////////////////////////////////SI LA DEMANDE EXISTE ET QU'ELLE EST CONFIRME AU TRAVERS DU MAIL
					if(!empty($_GET['idverif']) && !empty($_GET['mail']) ){

					///////////////////////////////SELECT
					$req_select = $bdd->prepare("SELECT * FROM membres WHERE mail=?");
					$req_select->execute(array($mail));
					$ligne_select = $req_select->fetch();
					$req_select->closeCursor();
					$id = $ligne_select['id'];
					$nom_password = $ligne_select['nom'];
					$prenom_password = $ligne_select['prenom'];

					if(!empty($ligne_select['id'])){

						///////////////////////////////SELECT
						$req_select = $bdd->prepare("SELECT * FROM membres_password_perdu 
							WHERE mail=?
							and pseudo_id=? 
							and idverif=?
							and date >?");
						$req_select->execute(array(
							$mail,
							$id,
							$idverif,
							$dateverif));
						$ligne_select = $req_select->fetch();
						$req_select->closeCursor();
						$id1 = $ligne_select['id'];
						$membreid1 = $ligne_select['pseudo_id'];

						if(isset($id1)){

							$_SESSION['definition_mot_de_passe_autorise_id'] = "$id1";
							$_SESSION['definition_mot_de_passe_autorise'] = "oui";
							$_SESSION['definition_mot_de_passe_autorise_idverif'] = "$idverif";
							$_SESSION['definition_mot_de_passe_autorise_mail'] = "$mail";

							?>

							<div id='bloc_definir_nouveau_mot_de_passe' >

								<div id="information_mot_de_passe" class='alert alert-info' role='alert' style='text-align: left;' ><span class='uk-icon-warning'></span> Indiquez un nouveau mot de passe sécurisé. Le mot de passe doit être alphanumériqe 
								et constitué de minuscules et majuscules.</div>

								<form id='formulaire_definir_nouveau_mot_de_passe' method='post' action='#' >
									<input class="form-control" type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" title="<?php echo "Nouveau mot de passe"; ?>" placeholder="<?php echo "Nouveau mot de passe"; ?>" />
									<input class="form-control" type="password" id="nouveau_mot_de_passe_controle" name="nouveau_mot_de_passe_controle" title="<?php echo "Retapez le mot de passe"; ?>" placeholder="<?php echo "Retapez le mot de passe"; ?>" />
									<div class="text-center mb-4">
										<button id="Password_submit" type="submit" class="btn btn-default btn-block" onclick="return false;" >CONFIRMER</button>
									</div>
								</form>

							</div>

						<?php
						}else{
						?>
							<div class='alert alert-danger' role='alert' style='text-align: left;' ><span class='uk-icon-warning'></span> La demande de redéfinition de mot de passe est introuvable !</div>
						<?php
						}

					}else{
					?>
						<div class='alert alert-danger' role='alert' style='text-align: left;' ><span class='uk-icon-warning'></span> L'adresse mail n'existe pas.</div>
					<?php
					}

					}elseif($_GET['action_password'] == "modif" && empty($mail) ){
					?>
						<div class='alert alert-danger' role='alert' style='text-align: left;' ><span class='uk-icon-warning'></span> La demande de redéfinition de mot de passe est introuvable.</div>
					<?php
					}
					/////////////////////////////////////////SI LA DEMANDE EXISTE ET QU'ELLE EST CONFIRME AU TRAVERS DU MAIL
					?>

				</div>
			</div>
		</div>
	</div>

<?php
}else{
        header("location: /");
}
?>