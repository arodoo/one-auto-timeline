<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 1); */

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
  global $id_oo;

  // Fetch immatriculation from membres_cartes_grise
  $immatriculation = null;
  try {
    $sql = "SELECT immatriculation FROM membres_cartes_grise WHERE id_membre = :id_membre";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([':id_membre' => $id_oo]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
      $immatriculation = $result['immatriculation'];
    }
  } catch (PDOException $e) {
    // Handle error
  }

  // Fetch immatriculation from membres_profil_auto if exists
  $profil_immatriculation = null;
  try {
    $sql = "SELECT immat FROM membres_profil_auto WHERE id_membre = :id_membre";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([':id_membre' => $id_oo]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
      $profil_immatriculation = $result['immat'];
    }
  } catch (PDOException $e) {
    // Handle error
  }

  $display_immatriculation = $profil_immatriculation ?? $immatriculation;
  ?>
  <div class="tab-pane" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
    <div class="card-body">
      <ul class="nav nav-tabs" id="profileTabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="vehicle-tab" data-toggle="tab" href="#vehicle-info" role="tab" 
            aria-controls="vehicle-info" aria-selected="true">Information du véhicule</a>
        </li>
      </ul>
      
      <div class="tab-content" id="profileTabsContent">
        <!-- Vehicle Tab Content -->
        <div class="tab-pane fade show active" id="vehicle-info" role="tabpanel" aria-labelledby="vehicle-tab">
          <div class="row">
            <form id="voir_immatriculation_form" method="post" action="#">
              <div class="col-md-6 col-sm-12" style="margin-bottom: 20px;">
                <label id="immatriculation_label">Renseignez l'immatriculation *</label>
                <input type="text" name="voir_immatriculation" id="voir_immatriculation" class="form-control"
                  placeholder="4321 AB 78 ae AC-123-WZ" value="<?php echo $display_immatriculation; ?>">
              </div>
              <div class="col-md-6 col-sm-12" style="margin-bottom: 20px;">
                <button type="submit" id="btn_calculer_immatriculation" class="btn btn-lg btn-block btn-warning"
                  onclick="event.preventDefault();">Voir</button>
              </div>
            </form>
          </div>
        </div>
        
        <!-- Personal Info Tab Content -->
        <div class="tab-pane fade" id="personal-info" role="tabpanel" aria-labelledby="personal-tab">
          <?php include 'personal-info-section.php'; ?>
        </div>
      </div>
    </div>
  </div>
  <?php include 'api-immatriculation-montrer.php'; ?>
  <?php include '/var/www/vhosts/mon-espace-auto.com/httpdocs/pop-up/loading/loading-pop-up.php'; ?>

  <!-- Floating 'X' icon for exiting edit mode -->
  <div id="exit-edit-mode" style="display:none;" title="Exit edit mode">
    &#10006;
  </div>

  <!-- Modal de confirmación -->
  <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Vous êtes sur le point de mettre à jour les informations relatives à votre véhicule, souhaitez-vous continuer ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelUpdate">Non</button>
          <button type="button" class="btn btn-primary" id="confirmUpdate">Oui</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function () {
      const keys = [
        "immat", "co2", "energie", "energieNGC", "genreVCG", "genreVCGNGC",
        "puisFisc", "carrosserieCG", "marque", "modele", "date1erCir_us", "date1erCir_fr",
        "collection", "date30", "vin", "boite_vitesse", "puisFiscReel", "nr_passagers",
        "nb_portes", "type_mine", "couleur", "poids", "cylindres", "sra_id", "sra_group",
        "sra_commercial", "code_moteur", "k_type", "db_c", "date_dernier_control_tecnique"
      ];

      const additionalKeys = ["erreur", "nbr_req_restants", "logo_marque"];
      const mandatoryKeys = [
        "boite_vitesse", "couleur", "puisFisc", "date1erCir_us", "date1erCir_fr",
        "marque", "modele"
      ];

      let userHasInfo = false;
      let userIsEditing = false;
      let userIsChanguingImmat = false;

      function updateFormState() {
        if (userHasInfo) {
          $("#aimm-btn").text("Update").prop("disabled", true);
          $("#modifier-btn").show();
          $("#voir_immatriculation").prop("readonly", false);
          $("#btn_calculer_immatriculation").text("Changer").prop("disabled", false);
          $("#aimm-container").show();
          $("#immatriculation_label").text("Changer de véhicule");
          keys.concat(additionalKeys).forEach(key => {
            $("#aim_" + key).prop("readonly", true);
          });
        } else {
          $("#aimm-btn").text("Enregistrer").prop("disabled", false);
          $("#modifier-btn").hide();
          $("#voir_immatriculation").prop("readonly", false);
          $("#btn_calculer_immatriculation").text("Voir").prop("disabled", false);
          $("#aimm-container").hide();
          $("#immatriculation_label").text("Renseignez l'immatriculation *");
          keys.concat(additionalKeys).forEach(key => {
            $("#aim_" + key).prop("readonly", false);
          });
        }
      }

      function handleUserInfo(res) {
        if (res.status === 200 && res.data) {
          const userData = res.data;

          keys.concat(additionalKeys).forEach(key => {
            let value = userData[key] || "";
            if (key === "date_dernier_control_tecnique" && value) {
              value = new Date(value * 1000).toISOString().split('T')[0];
            }
            $("#aim_" + key).val(value).css("background-color", "");
          });

          userHasInfo = true;
          updateFormState();
        }
      }

      function handleError(xhr, status, error) {
        popup_alert("Erreur lors de la récupération des informations utilisateur", "red filledlight", "#ff0000", "uk-icon-close");
      }

      $.ajax({
        url: 'panel/Profil-automobile/Profil-automobile-get-user-info.php',
        type: 'POST',
        data: { id_membre: <?php echo $id_oo; ?> },
        dataType: "json",
        success: function (res) {
          handleUserInfo(res);
          if (!userHasInfo && "<?php echo $immatriculation; ?>") {
            $("#btn_calculer_immatriculation").trigger("click");
          }
        },
        error: handleError
      });

      $("#modifier-btn").on("click", function () {
        userIsEditing = true;
        showLoadingScreen(1);
        keys.concat(additionalKeys).forEach(key => {
          $("#aim_" + key).css("background-color", "").prop("readonly", false);
        });
        $("#aimm-btn").prop("disabled", false);
        $("#modifier-btn").prop("disabled", true);
        $("#exit-edit-mode").show();
        showLoadingScreen(1);
      });

      $("#exit-edit-mode").on("click", function () {
        userIsEditing = false;
        keys.concat(additionalKeys).forEach(key => {
          $("#aim_" + key).css("background-color", "").prop("readonly", true);
        });
        $("#aimm-btn").prop("disabled", true);
        $("#modifier-btn").prop("disabled", false);
        $("#exit-edit-mode").hide();
        $('html, body').animate({ scrollTop: 0 }, 'slow');
      });

      $("#btn_calculer_immatriculation").on("click", function (event) {
        event.preventDefault();

        const formData = new FormData($("#voir_immatriculation_form")[0]);

        $.ajax({
          url: 'panel/Profil-automobile/Profil-automobile-get-api-info.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          dataType: "json",
          success: function (res) {
            if (res.status === 200) {
              const vehicleData = res.data.data;

              keys.forEach(key => {
                let value = vehicleData[key];
                if (mandatoryKeys.includes(key) && !value) {
                  value = "";
                } else {
                  value = value || "";
                }
                $("#aim_" + key).val(value).css("background-color", "");
              });

              additionalKeys.forEach(key => {
                const value = vehicleData[key] || "";
                $("#aim_" + key).val(value);
              });

              $("#aimm-container").show();
              popup_alert("Informations trouvées", "green filledlight", "#009900", "uk-icon-check");
              $('html, body').animate({
                scrollTop: $("#aimm-btn").offset().top
              }, 1000);

              if (userHasInfo) {
                $("#modifier-btn").hide();
                $("#aimm-btn").prop("disabled", false);
                userIsChanguingImmat = true;
                keys.concat(additionalKeys).forEach(key => {
                  $("#aim_" + key).prop("readonly", false);
                });
              }
            } else {
              popup_alert(res.message, "red filledlight", "#ff0000", "uk-icon-close");
            }
          },
          error: function (xhr, status, error) {
            popup_alert("Erreur lors de la recherche", "red filledlight", "#ff0000", "uk-icon-close");
          }
        });
      });

      // Handle missing fields
      function handleMissingFields(missingFields) {
        missingFields.forEach(field => {
          $("#aim_" + field).css("border", "1px solid red");
          $("#aim_" + field).on("input", function () {
            $(this).css("border", "");
          });
        });
      }

      $("#aimm-btn").on("click", function (event) {
        event.preventDefault();

        if (userIsChanguingImmat) {
          $('#confirmationModal').modal('show');
        } else {
          submitForm();
        }
      });

      $("#confirmUpdate").on("click", function () {
        $('#confirmationModal').modal('hide');
        submitForm();
      });

      $("#cancelUpdate").on("click", function () {
        $('#confirmationModal').modal('hide');
      });

      function submitForm() {
        const formData = {};

        keys.concat(additionalKeys).forEach(key => {
          formData[key] = $("#aim_" + key).val();
        });

        formData['userIsChanguingImmat'] = userIsChanguingImmat;

        $.ajax({
          url: 'panel/Profil-automobile/Profil-automobile-ajouter-modifier-ajax.php',
          type: 'POST',
          contentType: 'application/json',
          data: JSON.stringify(formData),
          dataType: "json",
          success: function (res) {
            if (res.status === 200) {
              popup_alert(res.message, "green filledlight", "#009900", "uk-icon-check");
              setTimeout(function () {
                location.reload();
              }, 1000);
            } else if (res.status === 400) {
              popup_alert(res.message, "red filledlight", "#ff0000", "uk-icon-close");
              handleMissingFields(res.missingFields);
            } else {
              popup_alert(res.message, "red filledlight", "#ff0000", "uk-icon-close");
            }
          },
          error: function (xhr, status, error) {
            popup_alert("Erreur lors de la soumission", "red filledlight", "#ff0000", "uk-icon-close");
          }
        });
      }

      updateFormState();
    });

    function showLoadingScreen(seconds) {
      document.getElementById('loading-screen').style.display = 'flex';
      setTimeout(function () {
        document.getElementById('loading-screen').style.display = 'none';
        $('html, body').animate({ scrollTop: 0 }, 'slow');
      }, seconds * 1000);
    }
  </script>
  <?php
} else {
  header("location: /");
}
?>