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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {

    $action = $_GET['action'];
    $idaction = $_GET['idaction'];
?>
    <link rel="stylesheet" type="text/css" href="/panel/Utilisateurs/Mes-annonces-client/Mes-annonces-client.css">
    <script>
        $(document).ready(function() {
            var action = "<?php echo $action; ?>";
            var idaction = "<?php echo $idaction; ?>";

            // Inclure le fichier du minuteur
            $.getScript('/panel/Utilisateurs/Mes-annonces-client/Mes-annonces-client-clock-timer.js', function() {
                handleTimerOnLoad();
            });

            // SOUMISSION DU FORMULAIRE AJAX - MODIFIER - AJOUTER
            $(document).on("click", "#bouton", function(event) {
                event.preventDefault(); // Empêcher le rechargement de la page
                // SOUMETTRE LE TEXTAREA TINYMCE
                tinyMCE.triggerSave();

               
                var formElement = $("#formulaire-" + (action == 'modifier' ? 'modifier' : 'ajouter'))[0];
                var formData = new FormData(formElement);

               /*  // Mostrar en consola cada par clave-valor del FormData
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                } */

                $.post({
                    url: '/panel/Utilisateurs/Mes-annonces-client/Mes-annonces-client-action-ajouter-modifier-ajax.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(res) {
                        if (res.retour_validation == "ok") {
                            popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
                            if (action != "modifier") {
                                $("#formulaire-ajouter")[0].reset();
                                // Démarrer le minuteur après avoir soumis la demande
                                handleTimer(100);
                            }
                        } else {
                            popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                        }
                        liste();
                    },
                   /*  error: function(jqXHR, textStatus, errorThrown) {
                        let errorMsg = "Une erreur est survenue : " + textStatus + " - " + errorThrown + " - " + jqXHR.responseText;
                        popup_alert(errorMsg, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                        console.log("Erreur:", errorMsg);
                    } */
                });
                $("html, body").animate({
                    scrollTop: 0
                }, "slow");
            });


            // AJAX - SUPPRIMER
            $(document).on("click", ".lien-supprimer", function() {
                $.post({
                    url: '/panel/Utilisateurs/Mes-annonces-client/Mes-annonces-client-action-supprimer-ajax.php',
                    type: 'POST',
                    data: {
                        idaction: $(this).attr("data-id")
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.retour_validation == "ok") {
                            popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
                        } else {
                            popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                        }
                        liste();
                    },
                   /*  error: function(jqXHR, textStatus, errorThrown) {
                        popup_alert("Une erreur est survenue : " + textStatus, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                    } */
                });
            });

            // FUNCTION AJAX - LISTE
            function liste() {
                $.post({
                    url: '/panel/Utilisateurs/Mes-annonces-client/Mes-annonces-client-liste-ajax.php',
                    type: 'POST',
                    dataType: "html",
                    success: function(res) {
                        $("#liste").html(res);
                    },
                    /* error: function(jqXHR, textStatus, errorThrown) {
                        popup_alert("Une erreur est survenue : " + textStatus, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                    } */
                });
            }
            liste();

        });
    </script>

    <div style='padding: 5px; text-align: center;'>

        <?php
        if ($action != "ajouter" && $action != "modifier") {
        ?>
            <a href="/Mes-annonces-client/ajouter" class="btn btn-primary"
                style="float: right; margin-right: 5px; margin-bottom: 20px;">Ajouter une demande</a>
            <?php
        }

        //////////////////////////// FORMULAIRE AJOUTER / MODIFIER
        if ($action == "ajouter" || $action == "modifier") {

            if ($action == "modifier") {

                /////////////////////////////// SELECT
                $req_select = $bdd->prepare("SELECT * FROM membres_annonces_clients WHERE id=? AND id_membre=?");
                $req_select->execute(array($idaction, $id_oo));
                $ligne_select = $req_select->fetch();
                $req_select->closeCursor();
            ?>

                <div align='left'>
                    <h2 style="float: left;">Modifier</h2>
                    <a href="/Mes-annonces-client" class="btn btn-primary" style="float: right;">Liste</a>
                    <a href="/Mes-annonces-client/ajouter" class="btn btn-primary" style="float: right; margin-right: 5px;">Ajouter
                        une demande</a>
                </div><br />
                <div style='clear: both;'></div>

                <form id='formulaire-modifier' method="post" action="#">
                    <input id="action" type="hidden" name="action" value="modifier-action">
                    <input id="idaction" type="hidden" name="idaction" value="<?php echo $_GET['idaction']; ?>">

                <?php
            } else {
                ?>

                    <div align='left'>
                        <h2 style="float: left;">Ajouter</h2>
                        <a href="/Mes-annonces-client" class="btn btn-primary" style="float: right;">Liste</a>
                        <a href="/Mes-annonces-client/ajouter" class="btn btn-primary"
                            style="float: right; margin-right: 5px;">Ajouter une demande</a>
                    </div><br />
                    <div style='clear: both;'></div>

                    <form id='formulaire-ajouter' method="post" action="#">
                        <input id="action" type="hidden" name="action" value="ajouter-action">

                    <?php
                }
                    ?>

                    <div class="container mt-5 text-left" style="text-align: left;">
                        <h2>Informations annonce</h2>

                        <div class="row mb-3">
                            <div class="col-md-6 text-left">
                                <label for="nom_produit">*Catégorie:</label>
                                <select name="id_type_compte_categorie" id="id_type_compte_categorie"
                                    class="form-control selectpicker" data-live-search="true">
                                    <option value=''>Sélection</option>
                                    <option value='2' <?php if ($ligne_select['id_type_compte_categorie'] == 2) echo 'selected'; ?>>Dépannage</option>
                                    <option value='3' <?php if ($ligne_select['id_type_compte_categorie'] == 3) echo 'selected'; ?>>Devis carrosserie</option>
                                    <option value='4' <?php if ($ligne_select['id_type_compte_categorie'] == 4) echo 'selected'; ?>>Devis mécanique</option>
                                    <option value='6' <?php if ($ligne_select['id_type_compte_categorie'] == 6) echo 'selected'; ?>>Devis service</option>
                                </select>
                            </div>
                            <div class="col-md-12 text-left">
                                <label for="nom">*Nom de la demande:</label>
                                <input type="text" name="nom" id="nom" class="form-control"
                                    value="<?php echo $ligne_select['nom']; ?>">
                            </div>
                            <div class="col-md-12 text-left">
                                <label for="description">*Description complète:</label>
                                <textarea name="description" id="description"
                                    class="form-control"><?php echo $ligne_select['description']; ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <?php
                            // Requête pour récupérer les départements
                            $sql = "SELECT id, name, code FROM dpts ORDER BY name";
                            $stmt = $bdd->prepare($sql);
                            $stmt->execute();
                            $departements = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="col-md-4 text-left">
                                <label for="departement">*Département:</label> <br>
                                <select name="departement" id="departement" class="form-control" data-live-search="true">
                                    <option value=''>Sélection</option>
                                    <?php foreach ($departements as $departement): ?>
                                        <option value="<?= $departement['id'] ?>" <?= ($departement['id'] == $ligne_select['departement']) ? 'selected' : '' ?>>
                                            <?= $departement['code'] ?> - <?= $departement['name'] ?>
                                        </option>

                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 text-left">
                                <label for="statut">*Statut:</label>
                                <select name="statut" id="statut" class="form-control">
                                    <option value="brouillon" <?php if ($ligne_select['statut'] == "brouillon")
                                                                    echo 'selected'; ?>>Brouillon</option>
                                    <option value="activé" <?php if ($ligne_select['statut'] == "activé")
                                                                echo 'selected'; ?>>
                                        Activée</option>
                                </select>
                            </div>
                            <?php if ($action == "modifier") { ?>
                                <div class="col-md-4 text-left">
                                    <label for="date_statut">Date statut:</label> <br>
                                    <input type="text" id="date_statut" name="date_statut" class="form-control" value="<?php echo date('d-m-Y', $ligne_select['date']); ?>" readonly>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-4 text-left">
                            <label for="valider"> </label> <br>
                            <input type="submit" name="bouton" id="bouton" class="btn btn-primary" value="<?php echo ($action == 'modifier') ? 'Mise à jour' : 'Valider'; ?>">
                        </div>
                    </form>
    </div>
    <br /><br />

    <div id="timer">
        <div id="clock">
            <span id="time">00:00</span>
        </div>
        <div>
            Vous devez attendre avant de pouvoir envoyer une autre demande.
        </div>
    </div>

<?php

        }
        //////////////////////////// FORMULAIRE AJOUTER / MODIFIER

        ///////////////////////////////////////// Si aucune action
        if (!isset($action)) {
?>

    <div id='liste'></div>

<?php
        }
        ///////////////////////////////////////// Si aucune action

        echo "</div>";
    } else {
        header('location: /');
    }
    ob_end_flush();
?>