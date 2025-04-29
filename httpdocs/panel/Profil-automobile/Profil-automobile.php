<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 1); */

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
  global $id_oo;

  // Fetch user's vehicles from membres_profil_auto
  $user_vehicles = [];
  try {
    $sql = "SELECT id, immat, marque, modele, date1erCir_fr, energieNGC, couleur 
            FROM membres_profil_auto WHERE id_membre = :id_membre 
            ORDER BY id DESC";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([':id_membre' => $id_oo]);
    $user_vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    // Handle error
  }
  
  // Fetch immatriculation from membres_cartes_grise for new vehicle addition
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

  // Use empty string as default display value for the form
  $display_immatriculation = '';
  ?>
  <div class="tab-pane" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
    <div class="card-body">
      <!-- Include vehicle management menu -->
      <?php include_once('includes/menu-vehicle.php'); ?>
      
      <ul class="nav nav-tabs" id="profileTabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="vehicle-tab" data-toggle="tab" href="#vehicle-info" role="tab" 
            aria-controls="vehicle-info" aria-selected="true">Gestion des véhicules</a>
        </li>
      </ul>
      
      <div class="tab-content" id="profileTabsContent">
        <!-- Vehicle Tab Content -->
        <div class="tab-pane fade show active" id="vehicle-info" role="tabpanel" aria-labelledby="vehicle-tab">
          <!-- Vehicle List Section -->
          <div class="row mb-4">
            <div class="col-12">
              <h4>Mes véhicules</h4>
              <?php if (empty($user_vehicles)): ?>
                <div class="alert alert-info">
                  Vous n'avez pas encore enregistré de véhicule. Utilisez le formulaire ci-dessous pour ajouter votre premier véhicule.
                </div>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Immatriculation</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Date de circulation</th>
                        <th>Carburant</th>
                        <th>Couleur</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($user_vehicles as $vehicle): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($vehicle['immat']); ?></td>
                          <td><?php echo htmlspecialchars($vehicle['marque']); ?></td>
                          <td><?php echo htmlspecialchars($vehicle['modele']); ?></td>
                          <td><?php echo htmlspecialchars($vehicle['date1erCir_fr']); ?></td>
                          <td><?php echo htmlspecialchars($vehicle['energieNGC']); ?></td>
                          <td><?php echo htmlspecialchars($vehicle['couleur']); ?></td>
                          <td>
                            <button class="btn btn-sm btn-primary edit-vehicle" 
                              data-id="<?php echo $vehicle['id']; ?>" 
                              data-immat="<?php echo htmlspecialchars($vehicle['immat']); ?>">
                              <i class="fas fa-edit"></i> Modifier
                            </button>
                            <button class="btn btn-sm btn-danger delete-vehicle" 
                              data-id="<?php echo $vehicle['id']; ?>" 
                              data-immat="<?php echo htmlspecialchars($vehicle['immat']); ?>">
                              <i class="fas fa-trash"></i> Supprimer
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
              
              <button class="btn btn-success mt-3" id="add-new-vehicle">
                <i class="fas fa-plus"></i> Ajouter un nouveau véhicule
              </button>
              
              <div class="mt-3">
                <a href="<?php echo $path_cms_general; ?>Vehicule-manuel" class="btn btn-outline-secondary">
                  <i class="fas fa-pencil-alt"></i> Saisie manuelle des informations
                </a>
              </div>
            </div>
          </div>
          
          <!-- Vehicle Addition/Edition Form Section (hidden by default) -->
          <div id="vehicle-form-section" class="row mt-4" style="display: none;">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h5 id="vehicle-form-title">Ajouter un véhicule</h5>
                </div>
                <div class="card-body">
                  <form id="voir_immatriculation_form" method="post" action="#">
                    <div class="row">
                      <div class="col-md-6 col-sm-12" style="margin-bottom: 20px;">
                        <label id="immatriculation_label">Renseignez l'immatriculation *</label>
                        <input type="text" name="voir_immatriculation" id="voir_immatriculation" class="form-control"
                          placeholder="4321 AB 78 ou AC-123-WZ" value="<?php echo $display_immatriculation; ?>">
                        <input type="hidden" id="vehicle_id" value="">
                      </div>
                      <div class="col-md-6 col-sm-12" style="margin-bottom: 20px;">
                        <button type="submit" id="btn_calculer_immatriculation" class="btn btn-lg btn-block btn-warning"
                          onclick="event.preventDefault();">Voir</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
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

  <!-- Modal de confirmación para actualización -->
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

  <!-- Modal pour la suppression de véhicule -->
  <div class="modal fade" id="deleteVehicleModal" tabindex="-1" role="dialog" aria-labelledby="deleteVehicleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteVehicleModalLabel">Confirmer la suppression</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Êtes-vous sûr de vouloir supprimer ce véhicule ? Cette action est irréversible.
          <p><strong>Immatriculation:</strong> <span id="delete-vehicle-immat"></span></p>
        </div>
        <div class="modal-footer">
          <input type="hidden" id="delete-vehicle-id">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-danger" id="confirm-delete-vehicle">Supprimer</button>
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
      let currentVehicleId = null;

      // Show/hide vehicle form section
      $("#add-new-vehicle").click(function() {
        resetVehicleForm();
        $("#vehicle-form-title").text("Ajouter un véhicule");
        $("#vehicle-form-section").slideDown();
        $("#vehicle_id").val("");
        $("#aimm-container").hide();
        userIsChanguingImmat = false;
        userIsEditing = false;
        userHasInfo = false;
        updateFormState();
      });

      // Edit vehicle button click handler
      $(document).on("click", ".edit-vehicle", function() {
        const vehicleId = $(this).data("id");
        const vehicleImmat = $(this).data("immat");
        
        currentVehicleId = vehicleId;
        $("#vehicle_id").val(vehicleId);
        $("#vehicle-form-title").text("Modifier le véhicule");
        $("#vehicle-form-section").slideDown();
        $("#voir_immatriculation").val(vehicleImmat).prop("readonly", true);
        
        // Fetch vehicle details and populate the form
        $.ajax({
          url: 'panel/Profil-automobile/Profil-automobile-get-vehicle-info.php',
          type: 'POST',
          data: { vehicle_id: vehicleId },
          dataType: "json",
          success: function(res) {
            if (res.status === 200 && res.data) {
              handleUserInfo(res);
              $("#aimm-container").show();
              userIsEditing = true;
              userHasInfo = true;
              userIsChanguingImmat = false;
              updateFormState();
            }
          },
          error: handleError
        });
        
        $('html, body').animate({
          scrollTop: $("#vehicle-form-section").offset().top - 100
        }, 500);
      });

      // Delete vehicle confirmation
      $(document).on("click", ".delete-vehicle", function() {
        const vehicleId = $(this).data("id");
        const vehicleImmat = $(this).data("immat");
        
        $("#delete-vehicle-id").val(vehicleId);
        $("#delete-vehicle-immat").text(vehicleImmat);
        $("#deleteVehicleModal").modal("show");
      });

      // Delete vehicle action
      $("#confirm-delete-vehicle").click(function() {
        const vehicleId = $("#delete-vehicle-id").val();
        
        $.ajax({
          url: 'panel/Profil-automobile/Profil-automobile-delete-vehicle.php',
          type: 'POST',
          data: { vehicle_id: vehicleId },
          dataType: "json",
          success: function(res) {
            if (res.status === 200) {
              popup_alert(res.message, "green filledlight", "#009900", "uk-icon-check");
              setTimeout(function() {
                location.reload();
              }, 1500);
            } else {
              popup_alert(res.message || "Erreur de suppression", "red filledlight", "#ff0000", "uk-icon-close");
            }
            $("#deleteVehicleModal").modal("hide");
          },
          error: function() {
            popup_alert("Erreur lors de la suppression du véhicule", "red filledlight", "#ff0000", "uk-icon-close");
            $("#deleteVehicleModal").modal("hide");
          }
        });
      });

      function resetVehicleForm() {
        // Clear all form fields
        $("#voir_immatriculation").val("").prop("readonly", false);
        keys.concat(additionalKeys).forEach(key => {
          $("#aim_" + key).val("").prop("readonly", false).css("border", "");
        });
        
        // Reset state variables
        currentVehicleId = null;
      }

      function updateFormState() {
        if (userHasInfo) {
          $("#aimm-btn").text("Update").prop("disabled", true);
          $("#modifier-btn").show();
          $("#voir_immatriculation").prop("readonly", true);
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

      // When editing an existing vehicle, load its data
      if (currentVehicleId) {
        $.ajax({
          url: 'panel/Profil-automobile/Profil-automobile-get-vehicle-info.php',
          type: 'POST',
          data: { vehicle_id: currentVehicleId },
          dataType: "json",
          success: handleUserInfo,
          error: handleError
        });
      }

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
        
        // Add vehicle ID if editing
        if ($("#vehicle_id").val()) {
          formData['vehicle_id'] = $("#vehicle_id").val();
        }

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
              }, 1500);
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