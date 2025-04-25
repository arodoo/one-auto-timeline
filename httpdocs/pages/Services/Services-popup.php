<?php
ob_start();

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    ?>
    <link rel="stylesheet" type="text/css" href="/panel/Utilisateurs/Mes-annonces-client/Mes-annonces-client.css">
    <script>
        $(document).ready(function () {
            $.getScript('/panel/Utilisateurs/Mes-annonces-client/Mes-annonces-client-clock-timer.js', function () {
                handleTimerOnLoad();
            });

            $(document).on("click", "#bouton", function (event) {
                event.preventDefault();
                tinyMCE.triggerSave();
                $.post({
                    url: '/panel/Utilisateurs/Mes-annonces-client/Mes-annonces-client-action-ajouter-ajax.php',
                    type: 'POST',
                    data: new FormData($("#formulaire-ajouter")[0]),
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (res) {
                        if (res.retour_validation == "ok") {
                            popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
                            $("#formulaire-ajouter")[0].reset();
                            handleTimer(100);
                        } else {
                            popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        let errorMsg = "Une erreur est survenue : " + textStatus + " - " + errorThrown + " - " + jqXHR.responseText;
                        popup_alert(errorMsg, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                        console.log("Erreur:", errorMsg);
                    }
                });
                $("html, body").animate({ scrollTop: 0 }, "slow");
            });
        });
    </script>

    <div class="modal fade" id="ajouterModal" tabindex="-1" aria-labelledby="ajouterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajouterModalLabel">Ajouter une demande</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id='formulaire-ajouter' method="post" action="#">
                        <input type="hidden" name="action" value="ajouter-action">
                        <div class="form-group">
                            <label for="nom">*Nom de la demande:</label>
                            <input type="text" name="nom" id="nom" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">*Description complète:</label>
                            <textarea name="description" id="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="departement">*Département:</label>
                            <select name="departement" id="departement" class="form-control" required>
                                <option value=''>Sélection</option>
                                <?php
                                $sql = "SELECT id, name, code FROM dpts ORDER BY name";
                                $stmt = $bdd->prepare($sql);
                                $stmt->execute();
                                $departements = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($departements as $departement) {
                                    echo "<option value='" . $departement['region_code'] . "'>" . $departement['code'] . " - " . $departement['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="statut">*Statut:</label>
                            <select name="statut" id="statut" class="form-control">
                                <option value="brouillon">Brouillon</option>
                                <option value="activé">Activée</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="bouton" id="bouton" class="btn btn-primary">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

   
    <?php
}
ob_end_flush();
?>
