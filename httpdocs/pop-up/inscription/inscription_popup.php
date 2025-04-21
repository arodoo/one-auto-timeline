<?php
ob_start();

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

unset($_SESSION['creation_compte_ok']);

?>

<script>
    $(document).ready(function () {

        // Get token from URL parameter if present
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('token');
        if (token) {
            document.getElementById('invitation_token').value = token;
        }

        //AJAX SOUMISSION DU FORMULAIRE
        $("#inscription_submit").click(function (event) {
            $.post({
                url: '/pop-up/inscription/inscription_popup_ajax.php',
                type: 'POST',
                data: new FormData($("#inscription_formulaire")[0]),
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    <?php
                    //Si mode inscription - Connexion automatique après inscription
                    if ($mod_inscription == 2) {
                        ?>

                        if (res.retour_validation == "") {
                            $('#retour_inscription').html("<div class='alert alert-danger' role='alert' style='text-align: left;' >" + res.Texte_rapport + "</div>");
                        }

                        if (res.retour_validation == "ok") {
                            //$('#retour_inscription').html("<div class='alert alert-success' role='alert' style='text-align: left;' >"+res.Texte_rapport+"</div>");
                            $(location).attr("href", res.retour_lien);
                        }

                        <?php
                        //Si mode inscription autre
                    } else {
                        ?>
                        if (res.retour_validation == "") {
                            $('#retour_inscription').html("<div class='alert alert-danger' role='alert' style='text-align: left;' >" + res.Texte_rapport + "</div>");
                        }

                        if (res.retour_validation == "ok") {
                            $('#retour_inscription').html("<div class='alert alert-success' role='alert' style='text-align: left;' >" + res.Texte_rapport + "</div>");
                            $('#inscription_formulaire').css("display", "none");
                        }
                        <?php
                    }
                    ?>
                }
            });

        });

        //AFFICHE INFORMATIONS MOT DE PASSE
        $(document).on("click", "#password", function () {
            $('#rappot_mot_de_passe').css("display", "");
        });

        $(document).on("click", ".pxp-header-inscription", function () {
            var type = $(this).attr("data-type");
            if (type == "extra") {
                $(".type_compte").css("display", "none");
                $(".type_compte_2").css("display", "");
                $(".type_compte_3").css("display", "");
                $(".type_compte_4").css("display", "");
                $(".type_compte_5").css("display", "");
                $(".type_compte_7").css("display", "");
                $(".type_compte_8").css("display", "");
                $(".type_compte_9").css("display", "");
                $(".type_compte_10").css("display", "");
                $(".type_compte_11").css("display", "");
            }
            if (type == "pro") {
                $(".type_compte").css("display", "none");
                $(".type_compte_1").css("display", "");
                $(".type_compte_6").css("display", "");
            }
            //alert(type);
        });

        if ($('#Ville').val()) {
            getCoordsInscription()
        }

        $('#Ville').change(() => {
            if ($('#Ville').val()) {
                getCoordsInscription()
            }
        })

        // $('#adresse').change(() => {
        //if($('#id_ville').val() && $('#adresse').val()) {
        // getCoordsInscription()
        //}
        //})

        function getCoordsInscription() {
            let ville = $("#Ville").val()
            let googleAdressFormat = " " + ville + ", France"
          /*   console.log(googleAdressFormat) */

            $.ajax({
                method: 'GET',
                url: 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDB8vOqn4NsaIIHSk4bP8hHMpdly2jzGEI&address=' + googleAdressFormat,
                success: (res) => {
                    $('#lat').val(res.results[0].geometry.location.lat)
                    $('#lng').val(res.results[0].geometry.location.lng)
                  /*   console.log(res.results[0].geometry.location.lat);
                    console.log(res.results[0].geometry.location.lng); */
                }
            })
        }

    });
</script>


<div class="modal fade" id="pxp-signin-modal-inscription" tabindex="-1" role="dialog"
    aria-labelledby="pxpSigninModalInscription" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="text-align: left;">
                <h2 class="modal-title style_color" style="float: left;">Inscription</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div style="clear: both;"></div>
            </div>
            <div class="modal-body" style="text-align: left;">

                <div id='retour_inscription'></div>

                <form id='inscription_formulaire' method='post' action='#' style="margin-top: 5px;">

                    <input type="hidden" name="lat" id="lat" />
                    <input type="hidden" name="lng" id="lng" />
                    
                    <!-- Hidden input for invitation token -->
                    <input type="hidden" name="invitation_token" id="invitation_token" value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>" />

                    <?php
                    ///////////////////////////////////////////////SI MODULE TYPE DE COMPTE ACTIVE
                    if ($type_de_compte_module == "oui") {
                        ?>
                        <div class="input-group MarginBottom10">
                            <select class="form-control" id="type_de_compte" name="type_de_compte"
                                style="<?php echo "$coloorpccc1type_compte"; ?>">
                                <option class="type_compte" value=""> <?php echo "*Vous êtes"; ?></option>
                                <?php
                                ///////////////////////////////SELECT BOUCLE
                                $req_boucle = $bdd->prepare("SELECT * FROM membres_type_de_compte WHERE activer='oui' and inscription='oui' ORDER BY position ASC"); //AND id!=1 AND id!=6
                                $req_boucle->execute(array());
                                while ($ligne_boucle = $req_boucle->fetch()) {
                                    $id_type = $ligne_boucle['id'];
                                    $Nom_type_type = $ligne_boucle['Nom_type'];

                                    if ($id_type == $_GET['compte']) {
                                        ?>
                                        <option selected class="type_compte type_compte_<?php echo "$id_type"; ?>"
                                            value="<?php echo "$id_type"; ?>"> <?php echo "$Nom_type_type"; ?></option>
                                        <?php
                                    } else {
                                        if (empty($_GET['compte']) || $_GET['compte'] == "undefined") {
                                            ?>
                                            <option class="type_compte type_compte_<?php echo "$id_type"; ?>"
                                                value="<?php echo "$id_type"; ?>"> <?php echo "$Nom_type_type"; ?></option>
                                            <?php
                                        }
                                    }

                                }
                                $req_boucle->closeCursor();
                                ?>
                            </select>

                        </div>

                        <?php
                    }
                    ///////////////////////////////////////////////SI MODULE TYPE DE COMPTE ACTIVE
                    ?>

                    <div class="input-group mb-2">
                        <input class="form-control" id="Nom" type="text" name="Nom" placeholder="*<?php echo "Nom"; ?>"
                            value="<?php echo "$Nom"; ?>" style='<?php echo "$coloorm"; ?>' />
                        <input class="form-control" id="Prenom" type="text" name="Prenom"
                            placeholder="*<?php echo "Prenom"; ?>" value="<?php echo "$Prenom"; ?>"
                            style='<?php echo "$coloorm"; ?>' />
                    </div>

                    <!-- <div class="input-group MarginBottom10">
<div class="input-group-addon"><span class='uk-icon-phone'></span></div>
<input class="form-control" type="text" id="Telephone_portable" name="Telephone_portable" title="Téléphone portable" placeholder="*<?php echo "Portable"; ?>" value="<?php echo "$Telephone_portable"; ?>" style='<?php echo "$coloorpr"; ?>' />
</div> -->

                    <div class='MarginBottom20' style='clear: both;'></div>

                    <div class="input-group MarginBottom10">
                        <input class="form-control" id="Adresse" type="text" name="Adresse"
                            placeholder="*<?php echo "Adresse"; ?>" value="<?php echo "$Adresse"; ?>"
                            style='<?php echo "$coloorm"; ?>' />
                    </div>

                    <div class="input-group MarginBottom10">
                        <input class="form-control" id="Code_postal" type="text" name="Code_postal"
                            placeholder="*<?php echo "Code postal"; ?>" value="<?php echo "$Code_postal"; ?>"
                            style='<?php echo "$coloorm"; ?>' />
                        <input class="form-control" id="Ville" type="text" name="Ville"
                            placeholder="*<?php echo "Ville"; ?>" value="<?php echo "$Ville"; ?>"
                            style='<?php echo "$coloorm"; ?>' maxlength="10" />
                    </div>

                    <div class="input-group MarginBottom10">
                        <input class="form-control" id="Mail" type="email" name="Mail"
                            placeholder="*<?php echo "Adresse mail"; ?>" value="<?php echo "$Mail"; ?>"
                            style='<?php echo "$coloorm"; ?>' />
                        <input class="form-control" id="Telephone_portable" type="tel" name="Telephone_portable"
                            placeholder="*<?php echo "Téléphone"; ?>" value="<?php echo "$Telephone_portable"; ?>"
                            style='<?php echo "$coloorm"; ?>' maxlength="10" />
                    </div>

                    <div id="rappot_mot_de_passe" class="alert alert-warning" role="alert"
                        style="margin-bottom: 10px; display: none;"><span class="uk-icon-exclamation-circle"></span>
                        <b>Mot de passe</b> : Alphanumérique, 8 caractères minimum
                    </div>

                    <div class="input-group MarginBottom10">
                        <input class="form-control" id="password" type="password" name="password"
                            title="Indiquez un password" placeholder="*<?php echo "Indiquez un password"; ?>"
                            autocomplete="off" style='<?php echo "$coloorppasse"; ?>' />
                    </div>
                    <!--
<div class="input-group MarginBottom10">
<input class="form-control" id="passwordclient2" type="password" name="passwordclient2" title="Retapez password" placeholder="*<?php echo "Retapez password"; ?>" autocomplete="off" style='<?php echo "$coloorppasse"; ?>' />
</div>
-->

                    <?php
                    //////////////////////////////////////SI LES CONDITIONS GENERALES EXISTES
                    if (!empty($lien_conditions_generales)) {
                        ?>
                        <div class="MarginBottom10" style="font-size: 12px;">
                            <input id='cbaonepost' name='cbaonepost' type="checkbox" value='1' <?php echo "$checkedok $checkediiinfos"; ?> />
                            Je reconnais avoir pris connaissance des <a href='/CGU' target='blank_'
                                class="style_color">CGU</a>, des <a href='/CGV' target='blank_' class="style_color">CGV</a>,
                            de la <a href="/Traitements-de-mes-donnees" target="_blank" class="style_color">Politique de
                                confidentialité</a> et les accepte -
                            et d'adhésion et m’engage à ne pas proposer ni de réaliser de services illégaux, contraire à la
                            législation Française.
                        </div>
                        <?php
                    }
                    ?>

                    <div class="input-group MarginBottom10" style="width: 100%; text-align: center;">
                        <button type='button' id='inscription_submit' class='btn btn-default w-space '
                            style='width: 200px;' onclick='return false;'>VALIDER</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php
ob_end_flush();
?>