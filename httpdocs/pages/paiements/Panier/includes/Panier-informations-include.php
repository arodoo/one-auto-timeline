<div id='Vos-informations' class='col-sm-12 col-md-5'>

      <div style='text-align: left;'>
            <h2>Vos informations</h2>
      </div>
      <hr />

      <?php
      //////////////////////////////////BANDEAU INFORMATIONS ACTIVE
      if ($activer_bandeau_page_informations == "oui") {
            ?>
            <div class="alert <?php echo "$type_bandeau_page_informations"; ?> alert-dismissible" role="alert"
                  style="position: relative;">
                  <div class="container" style="width: 90%; position: relative;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <p><span class="<?php echo "$type_icone_page_informations"; ?> "></span>
                              <?php echo "$contenu_bandeau_page_informations"; ?></p>
                  </div>
            </div>
            <?php
      }
      //////////////////////////////////BANDEAU INFORMATIONS ACTIVE
      ?>

      <form id='form-informations_mise_a_jour_panier' method='post' action='#'>

            <div class="col-sm-6 col-xs-6" style='margin-bottom: 15px; text-align: left;'>
                  <label>*Nom</label>
                  <input type='text' class='form-control' id='nom' name='nom' value='<?php echo "$nom_oo"; ?>'
                        placeholder='Nom' required autocomplete="off" style='width: 100%;'>
            </div>
            <div class="col-sm-6 col-xs-6" style='margin-bottom: 15px; text-align: left;'>
                  <label>*Prénom</label>
                  <input type='text' class='form-control' id='prenom' name='prenom' value='<?php echo "$prenom_oo"; ?>'
                        placeholder='Prénom' autocomplete="off" required style='width: 100%;'>
            </div>
            <div class="col-sm-6 col-xs-6" style='margin-bottom: 15px; text-align: left;'>
                  <label>*Mail</label>
                  <input type='email' class='form-control' id='mail' name='mail' value='<?php echo "$mail_oo"; ?>'
                        placeholder='Mail' autocomplete="off" required style='width: 100%;'>
            </div>
            <div class="col-sm-6 col-xs-6" style='margin-bottom: 15px; text-align: left;'>
                  <label>Portable <span class="text-danger font-weight-bold ">(Facultatif)</span></label>
                  <input type='text' class='form-control' id='Telephone_portable' name='Telephone_portable'
                        value='<?php echo "$Telephone_portable_oo"; ?>' placeholder='Téléphone' autocomplete="off"
                        style='width: 100%;'>
            </div>

            <div class="col-sm-6 col-xs-6" style='margin-bottom: 15px; text-align: left;'>
                  <label>Fixe <span class="text-danger font-weight-bold ">(Facultatif)</span></label>
                  <input class="form-control" type="text" id="Telephone" name="Telephone"
                        value='<?php echo "$Telephone_oo"; ?>' placeholder="<?php echo "Téléphone fixe"; ?>"
                        autocomplete="off" style='<?php echo "$coloorppasse"; ?>' />
            </div>

            <div class="col-sm-6 col-xs-6" style='margin-bottom: 15px; text-align: left;'>
                  <label>*Adresse</label>
                  <input class="form-control" type="text" id="adresse" name="adresse"
                        placeholder="<?php echo "Adresse"; ?>" value='<?php echo "$adresse_oo"; ?>' autocomplete="off"
                        style='<?php echo "$coloorppasse"; ?>' />
            </div>

            <div class="col-sm-6 col-xs-6" style='margin-bottom: 15px; text-align: left;'>
                  <label>*Ville</label>
                  <input type='text' class='form-control' id='ville' name='ville' value='<?php echo "$ville_oo"; ?>'
                        placeholder='Ville' required style='width: 100%;'>
            </div>
            <div class="col-sm-6 col-xs-6" style='margin-bottom: 15px; text-align: left;'>
                  <label>*Code postal</label>
                  <input type='text' class='form-control' id='cp' name='cp' value='<?php echo "$cp_oo"; ?>'
                        placeholder='Code postale' required maxlength="5" pattern="[0-9]{1,5}" style='width: 100%;'>
            </div>

            <div class="col-sm-12 col-xs-12" style='margin-bottom: 15px; text-align: left;'>
                  <hr style='padding: 0px; margin:0px;' />
            </div>

            <div class="col-sm-12 col-xs-12" style='margin-bottom: 15px; text-align: left;'>
                  *Tous les champs précédés d'une étoile doivent êtres remplis
            </div>


            <?php
            //////////////////////////////////////SI LES CONDITIONS GENERALES EXISTES
            if (!empty($lien_conditions_generales_compte)) {
                  ?>
                  <div style="clear: both;"></div>
                  <div class="form-group" style="text-align: left;">
                        <div class="col-sm-12" style="margin-bottom: 15px;">
                              <?php echo "En cliquant sur confirmer vous acceptez nos <a href='/CGU-CGV'>Cgu et Cgv</a>, ainsi que le <a href='/Traitements-de-mes-donnees'>traitements de vos données.</a>"; ?></a>
                        </div>
                  </div>
                  <?php
            }
            ?>

            <div class="col-sm-12 col-xs-12 text-center" style='margin-bottom: 15px; text-align: left;'>
                  <button id='button-informations_mise_a_jour_panier' class='btn btn-default'
                        style='text-transform : uppercase;' onclick="return false;"> ENREGISTRER </button>
            </div>
      </form>
      <div style="clear: both;"></div>

</div>