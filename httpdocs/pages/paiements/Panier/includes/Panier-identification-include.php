
<div id='identification-panier-bloc' class='col-sm-12 col-md-5'>

<div style='text-align: left;'>
<h2>Identifiez-vous</h2>
</div>
<hr />

<?php
//////////////////////////////////BANDEAU IDENTIFICATION ACTIVE
if($activer_bandeau_page_login == "oui"){
?>
<div class="alert <?php echo "$type_bandeau_page_login"; ?> alert-dismissible" role="alert" style="position: relative;">
<div class="container" style="width: 90%; position: relative;">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <span class="<?php echo "$type_icone_page_login"; ?> "></span> <?php echo "$contenu_bandeau_page_login"; ?> 
</div>
</div>
<?php
}
//////////////////////////////////BANDEAU IDENTIFICATION ACTIVE
?>

<form id='identification-panier' method='post' action='#' >

                                  <div class="col-sm-6 col-xs-6" style='margin-bottom: 15px; text-align: left;'>
						<label>*Adresse email</label>
                                          <input type='text' class='form-control' id='login' name='login' value='' placeholder='Adresse email' required style='width: 100%;'>
                                    </div>
                                    <div class="col-sm-6 col-xs-6" style='margin-bottom: 15px; text-align: left;'>
						<label>*Mot de passe</label>
                                          <input type='password' class='form-control' id='password_login' name='password_login' value='' placeholder='Mot de passe' required style='width: 100%;'>
                                    </div>
                                    <div class="col-sm-6 col-xs-6" style='margin-bottom: 15px; text-align: left;'>
					<button id='login_post' class='btn btn-success' name="login_post" value="<?php echo "CONNEXION"; ?>" style='text-transform : uppercase;' onclick="return false;" > IDENTIFIEZ-VOUS </button>
                                    </div>
</form>

<div style="clear: both;"></div>
</div>
