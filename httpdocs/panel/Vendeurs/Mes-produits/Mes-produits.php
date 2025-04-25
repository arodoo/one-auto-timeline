<?php
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_bdd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Configurations_modules.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php';


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
  $articlenumber = $_GET['articlenumber'];
  $selectedid = $_GET['selectedid'];


  $isResponseDataEmpty = 'false';


  if (!empty($articlenumber) && !empty($selectedid)) {
    //////////////////DATA EXTRACTION CODE WHEN REDIRECTED FROM PAGE-MARKETPLACE
    $data = [
      "getArticles" => [
        "articleCountry" => "FR",
        "provider" => $provider_oo,
        "searchQuery" => $articlenumber,
        "searchType" => 0,
        "assemblyGroupNodeIds" => $selectedid,
        "lang" => "fr",
        "perPage" => 100,
        "page" => 1,
        "includeAll" => true
      ]
    ];

    $dataJson = json_encode($data);

    $ch = curl_init($urlTecalliance);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Content-Type: application/json",
      "X-Api-Key: $apiKeyTech",
      "Content-Length: " . strlen($dataJson)
    ]);

    $response = curl_exec($ch);
    $error = curl_errno($ch) ? curl_error($ch) : null;
    curl_close($ch);

    $responseData = json_decode($response, true);

    $isResponseDataEmpty = empty($responseData) ? 'true' : 'false';


    $assemblyGroupNodeId = $responseData['articles'][0]['genericArticles'][0]['assemblyGroupNodeId'] ?? '';
    $assemblyGroupName = $responseData['articles'][0]['genericArticles'][0]['assemblyGroupName'] ?? '';
    $genericArticleDescription = $responseData['articles'][0]['genericArticles'][0]['genericArticleDescription'] ?? '';

    if ($error || !$responseData) {
      die("La requête API a échoué  " . $error);
    }
    /////////////////////
  }

 /*  var_dump($_GET); */

  $req_select = $bdd->prepare("SELECT * FROM membres_profil_paiement WHERE id_membre = ? ");
  $req_select->execute(array($id_oo));
  $profile_data = $req_select->fetch();
  $req_select->closeCursor();
  $profil_complet = $profile_data['profil_complet'];

  /* var_dump($profil_complet); */


?>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

  <script>
    $(document).ready(function() {

      //AJAX SOUMISSION DU FORMULAIRE - MODIFIER - AJOUTER
      $(document).on("click", "#bouton", function(e) {
        e.preventDefault();
        tinyMCE.triggerSave();

        var form = $("#formulaire-ajouter")[0];
        if ("<?php echo $_GET['action']; ?>" == "modifier") {
          form = $("#formulaire-modifier")[0];
        }

        var formData = new FormData(form);

        // Retrieves values ​​from GET
        var idProduitApi = "<?php echo $_GET['articlenumber']; ?>";
        var nodeIdsApi = "<?php echo $_GET['selectedid']; ?>";

        // If idProduitApi is empty, the value of the product selected in the dynamic select is taken
        if (!idProduitApi || idProduitApi.trim() === "") {
          var dynamicSelect = document.getElementById("dynamic-product-select");
          if (dynamicSelect) {
            var selectedOption = dynamicSelect.options[dynamicSelect.selectedIndex];
            if (selectedOption) {

              var articleString = selectedOption.getAttribute('data-article');
              if (articleString) {
                var selectedArticle = JSON.parse(articleString.replace(/&quot;/g, '"'));
                // The articleNumber obtained from the JSON is assigned
                idProduitApi = selectedArticle.articleNumber || '';
              }
            }
          }
        }

        
        
        // Assign the value of the assembly group select directly to node_ids_api
        var selectedId = null;
        var selectAssemblyGroup = document.getElementById("selectAssemblyGroup");
        if (selectAssemblyGroup && selectAssemblyGroup.value) {
          selectedId = selectAssemblyGroup.value;
        } else {
          // Use the value from the URL as a fallback
          selectedId = "<?php echo $_GET['selectedid']; ?>";
        }

        formData.append('id_produit_api', idProduitApi);
        formData.append('node_ids_api', selectedId);


        console.log("Datos enviados al backend:", Object.fromEntries(formData.entries()));

        $.post({
          url: '/panel/Vendeurs/Mes-produits/Mes-produits-action-ajouter-modifier-ajax.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          dataType: "json",
          success: function(res) {
            if (res.retour_validation == "ok") {
              popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
              if ("<?php echo $_GET['action']; ?>" != "modifier") {
                $("#formulaire-ajouter")[0].reset();
              }
            } else {
              popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
            }
            liste();
          }
        });

        $("html, body").animate({
          scrollTop: 0
        }, "slow");
      });


      //AJAX - SUPPRIMER
      $(document).on("click", ".lien-supprimer", function() {
        $.post({
          url: '/panel/Vendeurs/Mes-produits/Mes-produits-action-supprimer-ajax.php',
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
             alert('Erreur lors de la soumission du formulaire.' + errorThrown, jqXHR, textStatus);
           } */
        });
      });

      //FUNCTION AJAX - LISTE
      function liste() {
        $.post({
          url: '/panel/Vendeurs/Mes-produits/Mes-produits-liste-ajax.php',
          type: 'POST',
          dataType: "html",
          success: function(res) {
            $("#liste").html(res);
          }
        });
      }
      liste();

      $('#marque').on('change', function() {
        var marque = $(this).val();
        $.ajax({
          type: 'POST',
          dataType: "html",
          url: '/panel/Vendeurs/Mes-produits/modeles.php',
          data: {
            marque: marque,
            idaction: "<?php echo $_GET['idaction']; ?>"
          },
          success: function(response) {
            $('#model_select').html(response);
            $('#model_select').selectpicker('refresh'); // Recharger le selectpicker
          }
        });
      });

      var photoCount = 0;
      var formData = new FormData();

      function handleFileSelect(event, previewId, photoNum) {
        var files = event.target.files;

        $.each(files, function(index, file) {
          var reader = new FileReader();
          reader.onload = function(e) {
            var img = $('<img>').attr('src', e.target.result).css('max-width', '100%');
            var cropContainer = $('<div>').addClass('crop-container').append(img);
            $('#' + previewId).empty().append(cropContainer); // Vider le conteneur avant d'ajouter la nouvelle image

            img.on('load', function() {
              if (img[0].naturalWidth < 545 || img[0].naturalHeight < 545) {
                alert('L\'image doit faire au moins 545 pixels de largeur et de hauteur.');
                cropContainer.remove();
                return;
              }

              var cropper = new Cropper(img[0], {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1,
                ready: function() {
                  var cropButton = $('<button>').text('Valider le recadrage').addClass('btn btn-success crop-button');
                  cropContainer.append(cropButton);

                  cropButton.on('click', function(event) {
                    event.preventDefault(); // Empêcher le comportement par défaut du bouton
                    var canvas = cropper.getCroppedCanvas({
                      width: 545,
                      height: 545
                    });
                    canvas.toBlob(function(blob) {
                      var timestamp = Date.now();
                      var slug = convertToSlug(file.name) + '-' + timestamp;
                      var newFile = new File([blob], slug + '.jpg', {
                        type: 'image/jpeg'
                      });
                      formData.append('photos[]', newFile);
                      formData.append('photo_num[]', photoNum); // Ajouter le numéro de champ
                      cropContainer.remove();
                      photoCount++;

                      // Afficher la prévisualisation de l'image recadrée
                      var croppedImg = $('<img>').attr('src', URL.createObjectURL(blob)).css('max-width', '100%');
                      $('#' + previewId).empty().append(croppedImg); // Vider le conteneur avant d'ajouter la nouvelle image

                      // Envoyer l'image recadrée via AJAX
                      $.ajax({
                        url: '/panel/Vendeurs/Mes-produits/Mes-produits-upload.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                          var jsonResponse = JSON.parse(response);

                          jsonResponse.forEach(function(res) {
                            if (res.retour_validation === "ok") {
                              popup_alert(res.Texte_rapport, "green filledlight", "#009900", "uk-icon-check");
                            } else {
                              popup_alert(res.Texte_rapport, "#CC0000 filledlight", "#CC0000", "uk-icon-times");
                            }
                          });
                          formData = new FormData(); // Réinitialiser formData après chaque envoi
                        },
                        error: function() {
                          alert('Erreur lors de la soumission du formulaire.');
                        }
                      });
                    });
                  });
                }
              });
            });
          };
          reader.readAsDataURL(file);
        });
      }

      $('#photos1').on('change', function(event) {
        handleFileSelect(event, 'preview1', 1);
      });

      $('#photos2').on('change', function(event) {
        handleFileSelect(event, 'preview2', 2);
      });

      $('#photos3').on('change', function(event) {
        handleFileSelect(event, 'preview3', 3);
      });

      $('#photos4').on('change', function(event) {
        handleFileSelect(event, 'preview4', 4);
      });

      function convertToSlug(text) {
        return text.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
      }

    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var isResponseDataEmpty = <?php echo $isResponseDataEmpty; ?>;
      var action = "<?php echo $action; ?>";

      const typeVehiculeElement = document.getElementById("type_vehicule");
      const selectAssemblyGroup = document.getElementById("selectAssemblyGroup"); // Aseguramos que esta variable esté definida
      const containerApi = document.getElementById("container-api");

      // Verificar si selectAssemblyGroup existe antes de usarlo
      if (!selectAssemblyGroup) {
        console.warn("El elemento selectAssemblyGroup no está definido en el DOM.");
        return;
      }

      function lettre_sans_accent(chaine) {
        return chaine.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[\s',]/g, '-');
      }

      // Generic function to make fetch requests with async/await
      async function fetchData(payload) {
        try {
          const response = await fetch("/pages/Marketplace/Marketplace-API-ajax.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
          });
          if (!response.ok) throw new Error("HTTP error ");
          return await response.json();
        } catch (error) {
          console.error("Error fetching data:");
        }
      }

      // Function to update the assembly group selection
      async function updateAssemblyGroup() {
        const typeVehicule = typeVehiculeElement.value;
        const data = await fetchData({
          type_vehicule: typeVehicule
        });


        if (data?.response?.assemblyGroupFacets?.counts?.length > 0) {
          let opciones = '<option value="" disabled selected>Selecciona una opción</option>';
          data.response.assemblyGroupFacets.counts.forEach(item => {

            /*   console.log("assemblyGroupNodeId:", item.assemblyGroupNodeId);
              console.log("assemblyGroupName:", item.assemblyGroupName); */

            opciones += `<option value="${item.assemblyGroupNodeId}">${item.assemblyGroupName}</option>`;
          });

          selectAssemblyGroup.innerHTML = opciones;
          selectAssemblyGroup.disabled = false;

          if (typeof $(selectAssemblyGroup).selectpicker === "function") {
            $(selectAssemblyGroup).selectpicker('refresh');
          }
        } else {
          console.warn("Aucune donnée trouvée dans la réponse");
        }
      }


      // Function to update the dynamic selection of products
      async function updateDynamicProductSelect() {
        if (!selectAssemblyGroup) {
          console.warn("selectAssemblyGroup no está definido.");
          return;
        }

        const selectedId = selectAssemblyGroup.value;

        /*         console.log("Valor de assemblyGroupNodeId que se envía en la segunda consulta:", selectedId); */

        const data = await fetchData({
          assemblyGroupNodeIds: selectedId
        });

        if (data?.response?.articles?.length > 0) {

          const previousWrapper = document.getElementById("dynamic-product-wrapper");
          if (previousWrapper) previousWrapper.remove();

          // Build the select options with the information from the items
          const options = data.response.articles.map(article => {

            const produit_id = article.genericArticles?.[0]?.legacyArticleId ?? '';
            const produit_description = article.genericArticles?.[0]?.genericArticleDescription ?? '';
            const articleNumber = article.articleNumber ?? '';
            let image_url = "/images/erreurBad.webp";
            if (article.images && article.images.length > 0) {
              image_url = (
                article.images[0].imageURL200 ||
                article.images[0].imageURL400 ||
                article.images[0].imageURL800 ||
                article.images[0].imageURL1600 ||
                article.images[0].imageURL100 ||
                article.images[0].imageURL50 ||
                "/images/erreurBad.webp"
              );
            }
            const id_categorie = article.genericArticles?.[0]?.assemblyGroupName ?? '';
            const article_status_description = article?.misc?.articleStatusDescription ?? '';

            const articleJson = JSON.stringify(article).replace(/"/g, '&quot;');

            // We return the option including data-article
            return `
              <option value="${produit_id}" 
                data-article="${articleJson}"
                data-image="${image_url}" 
                data-description="${produit_description}" 
                data-category="${id_categorie}" 
                data-status="${article_status_description}" 
                data-article-number="${articleNumber}"
                data-node-id="${article.genericArticles?.[0]?.assemblyGroupNodeId ?? ''}"
                data-content="<img src='${image_url}' width='40' height='40' class='me-2'> ${produit_description} - ${articleNumber}">
              </option>
            `;
          }).join('');



          const selectHtml = `
          <div class="col-lg-12 col-md-12 mt-3" id="dynamic-product-wrapper">
            <label class="form-label">Sélectionnez un produit</label>
            <select id="dynamic-product-select" class="selectpicker form-control" data-live-search="true">
              <option value="">Sélectionner un produit</option>
              ${options}
            </select>
          </div>
        `;
          containerApi.insertAdjacentHTML('beforeend', selectHtml);

          const newSelect = document.getElementById("dynamic-product-select");
          if (typeof $(newSelect).selectpicker === "function") {
            $(newSelect).selectpicker();
            $(newSelect).selectpicker('refresh');
          }

          // Listener to capture the data of the selected product
          newSelect.addEventListener("change", function() {
            const selectedOption = this.options[this.selectedIndex];
            if (!selectedOption.value) return;


            const articleString = selectedOption.dataset.article;
            const selectedArticle = JSON.parse(articleString.replace(/&quot;/g, '"'));
            /*  console.log("Artículo completo seleccionado:", selectedArticle); */

            // Muestra los valores usando los nombres originales de los campos del JSON
            /*     console.log("Campo original - genericArticles.legacyArticleId:", selectedArticle.genericArticles?.[0]?.legacyArticleId);
                console.log("Campo original - genericArticles.genericArticleDescription:", selectedArticle.genericArticles?.[0]?.genericArticleDescription); */

            // Extract assemblyGroupNodeId and assemblyGroupName from the JSON object
            const assemblyGroupNodeId = selectedArticle.genericArticles?.[0]?.assemblyGroupNodeId || '';
            const assemblyGroupName = selectedArticle.genericArticles?.[0]?.assemblyGroupName || '';
            /*   console.log("Campo original - genericArticles.assemblyGroupNodeId:", assemblyGroupNodeId);
              console.log("Campo original - genericArticles.assemblyGroupName:", assemblyGroupName); */


            const produit_description = selectedOption.dataset.description;
            const image_url = selectedOption.dataset.image;
            const articleNumber = selectedOption.dataset.articleNumber;
            const article_status_description = selectedOption.dataset.status;

            /* console.log("Producto seleccionado:");
            console.log("ID (value del option):", selectedOption.value);
            console.log("Descripción (data-description):", produit_description);
            console.log("URL de imagen (data-image):", image_url);
            console.log("Número de artículo (data-article-number):", articleNumber);
            console.log("Estado (data-status):", article_status_description); */

            // Only fill in the fields if action == "add"
            if (typeof action !== 'undefined' && action === "ajouter") {
              // Fill in "Offer Name"
              const nomProduitHidden = document.getElementById("nom_produit_hidden");
              const nomProduitText = document.getElementById("nom_produit");
              if (nomProduitHidden && nomProduitText) {
                nomProduitHidden.value = produit_description;
                nomProduitText.value = produit_description;
              }

              // Populate the category: assign the category name to nom_categorie_api
              // and the id (assemblyGroupNodeId) to id_categorie
              const nomCategorieApi = document.getElementById("nom_categorie_api");
              const idCategorie = document.getElementById("id_categorie");
              const nomCategorieApiText = document.getElementById("nom_categorie_api_text");
              if (nomCategorieApi && idCategorie && nomCategorieApiText) {
                nomCategorieApi.value = assemblyGroupName; // Assign the original name of the category
                idCategorie.value = assemblyGroupNodeId; // Assign the category id
                nomCategorieApiText.value = assemblyGroupName;
              }
            }
          });

        } else {
          console.warn("Aucun article trouvé dans la réponse");
        }
      }

      // Assign the event listeners to the corresponding selects
      if (typeVehiculeElement) {
        typeVehiculeElement.addEventListener("change", updateAssemblyGroup);
      }
      if (selectAssemblyGroup) {
        selectAssemblyGroup.addEventListener("change", updateDynamicProductSelect);
      }
    });
  </script>





  <?php

  if ($profil_complet == 'oui') {
  ?>

    <div class=mes-produits style='padding: 5px; text-align: center;'>

      <?php
      if ($action != "ajouter" && $action != "modifier") {
      ?>
        <a href="/Mes-produits/ajouter" class="btn btn-primary" style="float: right;  margin-right: 5px; margin-bottom: 20px;">Ajouter une offre</a>
        <?php
      }

      ////////////////////////////FORMULAIRE AJOUTER / MODIFIER
      if ($action == "ajouter" || $action == "modifier") {

        if ($action == "modifier") {

          ///////////////////////////////SELECT
          $req_select = $bdd->prepare("SELECT * FROM membres_produits WHERE id=? AND id_membre=?");
          $req_select->execute(array($idaction, $id_oo));
          $ligne_select = $req_select->fetch();
          $req_select->closeCursor();

          $_SESSION['id_temporaire_image'] = $idaction;

        ?>

          <div align='left'>
            <h2 style="float: left;">Modifier</h2>
            <a href="/Mes-produits" class="btn btn-primary" style="float: right;">Liste</a>
            <a href="/Mes-produits/ajouter" class="btn btn-primary" style="float: right;  margin-right: 5px;">Ajouter une offre</a>
          </div><br />
          <div style='clear: both;'></div>

          <?php
          $images = [];
          $id_membre = $id_oo;
          $id_produit = $_SESSION['id_temporaire_image'];
          $sql = "SELECT numero, nom_image FROM membres_produits_images WHERE id_membre = ? AND id_produit = ?";
          $stmt = $bdd->prepare($sql);
          $stmt->execute([$id_membre, $id_produit]);
          $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
          ?>

          <div class="container mt-5 text-left" style="text-align: left;">
            <h2>*Téléchargement photos</h2>
            <form id="commandeForm" enctype="multipart/form-data">
              <div class="row mb-3">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                  <div class="col-md-12 text-left" style="text-align: left;">
                    <input type="file" name="photos[]" id="photos<?= $i ?>" class="form-control" accept="image/*">
                    <input type="hidden" name="photo_num[]" value="<?= $i ?>">
                    <div id="preview<?= $i ?>"></div>
                    <?php
                    if ($action == 'modifier') {
                      foreach ($images as $image) {
                        if ($image['numero'] == $i) {
                          echo '<a href="/images/membres/' . $user . '/' . $image['nom_image'] . '" target="_blank">' . $image['nom_image'] . '</a>';
                        }
                      }
                    }
                    ?>
                  </div>
                <?php endfor; ?>
              </div>
            </form>
          </div>
          <?php ob_end_flush(); ?>

          <form id='formulaire-modifier' method="post" enctype="multipart/form-data">
            <input id="action" type="hidden" name="action" value="modifier-action">
            <input id="idaction" type="hidden" name="idaction" value="<?php echo $_GET['idaction']; ?>">

          <?php
        } else {

          $_SESSION['id_temporaire_image'] = time();

          ?>

            <div align='left'>
              <h2 style="float: left;">Ajouter</h2>
              <a href="/Mes-produits" class="btn btn-primary" style="float: right;">Liste</a>
              <a href="/Mes-produits/ajouter" class="btn btn-primary" style="float: right; margin-right: 5px;">Ajouter une offre</a>
            </div><br />
            <div style='clear: both;'></div>

            <?php
            $images = [];
            $id_membre = $id_oo;
            $id_produit = $_SESSION['id_temporaire_image'];
            $sql = "SELECT numero, nom_image FROM membres_produits_images WHERE id_membre = ? AND id_produit = ?";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([$id_membre, $id_produit]);
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="container mt-5 text-left" style="text-align: left;">
              <h2>*Téléchargement photos</h2>
              <form id="commandeForm" enctype="multipart/form-data">
                <div class="row mb-3">
                  <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="col-md-12 text-left" style="text-align: left;">
                      <input type="file" name="photos[]" id="photos<?= $i ?>" class="form-control" accept="image/*">
                      <input type="hidden" name="photo_num[]" value="<?= $i ?>">
                      <div id="preview<?= $i ?>"></div>
                      <?php
                      if ($action == 'modifier') {
                        foreach ($images as $image) {
                          if ($image['numero'] == $i) {
                            echo '<a href="/images/membres/' . $user . '/' . $image['nom_image'] . '" target="_blank">' . $image['nom_image'] . '</a>';
                          }
                        }
                      }
                      ?>
                    </div>
                  <?php endfor; ?>
                </div>
              </form>
            </div>
            <?php ob_end_flush(); ?>


            <?php if ($responseData) { ?>
              <p>Vous avez sélectionné un article sur le marché pour faire une offre.</p>
            <?php } else {  ?>
              <div class="container">
                <div class="row">


                  <div class="col-md-6 text-left">
                    <label class="form-label">Type de véhicule</label>
                    <select id="type_vehicule" name="type_vehicule" class="form-control">
                      <option value="" selected disabled>Sélectionnez une option</option>
                      <option value="P">Passenger Car/LCV (w/o Motorcycle)</option>
                      <option value="B">Motorcycle</option>
                      <option value="O">Commercial Vehicle</option>
                      <option value="M">Engine</option>
                      <option value="A">Axle</option>
                      <option value="U">Universal</option>
                    </select>
                  </div>

                  <div class="col-md-6 text-left">
                    <label class="form-label">Categorie</label>
                    <select id="selectAssemblyGroup" class="selectpicker form-control" data-live-search="true" disabled>
                      <option value="" disabled selected>Sélectionnez une option</option>
                    </select>
                  </div>
                </div>

                <div class="row" id="container-api"></div>

              </div>




            <?php }   ?>
            <form id='formulaire-ajouter' method="post" enctype="multipart/form-data">
              <input id="action" type="hidden" name="action" value="ajouter-action">

            <?php
          }
            ?>

            <div class="container mt-5 text-left" style="text-align: left;">
              <h2>Informations produit</h2>

              <div class="row mb-3">
                <div class="col-md-12 text-left">
                  <label for="nom_produit">*Nom offre:</label>
                  <?php if (!empty($genericArticleDescription)): 
                   
                    ?>
                    <input type="hidden" id="nom_produit_hidden" name="nom_produit" value="<?php echo htmlspecialchars($genericArticleDescription); ?>">
                    <input type="text" id="nom_produit" class="form-control" value="<?php echo htmlspecialchars($genericArticleDescription); ?>">
                  <?php else: ?>
                    <input type="hidden" id="nom_produit_hidden" name="nom_produit" value="<?php echo htmlspecialchars($ligne_select['nom_produit']); ?>">
                    <input type="text" id="nom_produit" class="form-control" value="<?php echo htmlspecialchars($ligne_select['nom_produit']); ?>">
                  <?php endif; ?>
                </div>
              </div>


              <div class="col-md-12 text-left">
                <label for="description_produit">*Description offre:</label>
                <textarea name="description_produit" id="description_produit" class="form-control"><?php echo nl2br($ligne_select['description_produit']); ?></textarea>
              </div>

              <div class="col-md-12 text-left">
                <label for="description_livraison">*Description livraison offre:</label>
                <textarea name="description_livraison" id="description_livraison" class="form-control"><?php echo nl2br($ligne_select['description_livraison']); ?></textarea>
              </div>

              <div class="col-md-12 text-left">
                <label for="categorie">*Catégorie:</label>
                <div class="col-md-12 text-left">
                  <?php if (!empty($assemblyGroupName)): ?>
                    <input type="hidden" id="nom_categorie_api" name="nom_categorie_api"
                      value="<?php echo htmlspecialchars($assemblyGroupName); ?>">
                    <input type="hidden" id="id_categorie" name="id_categorie"
                      value="<?php echo htmlspecialchars($assemblyGroupNodeId); ?>">
                    <input type="text" id="nom_categorie_api_text" class="form-control"
                      value="<?php echo htmlspecialchars($assemblyGroupName); ?>" disabled>
                  <?php else: ?>
                    <input type="hidden" id="nom_categorie_api" name="nom_categorie_api"
                      value="<?php echo $ligne_select['nom_categorie_api']; ?>">
                    <input type="hidden" id="id_categorie" name="id_categorie"
                      value="<?php echo $ligne_select['id_categorie']; ?>">
                    <input type="text" id="nom_categorie_api_text" class="form-control"
                      value="<?php echo $ligne_select['nom_categorie_api']; ?>" disabled>
                  <?php endif; ?>
                </div>
              </div>

            </div>



            <div class="row mb-3">
              <div class="col-md-4 text-left">
                <label for="quantite">*Quantité:</label>
                <input type="number" name="quantite" id="quantite" class="form-control" value="<?php echo $ligne_select['quantite']; ?>">
              </div>
              <div class="col-md-4 text-left">
                <label for="montant">*Montant:</label> <br>
                <input type="text" name="montant" id="montant" class="form-control" value="<?php echo $ligne_select['montant_unite']; ?>" style="width: 80%; display: inline-block;">€
              </div>
              <div class="col-md-4 text-left">
                <label for="montant_livraison">*Montant livraison:</label>
                <input type="text" name="montant_livraison" id="montant_livraison" class="form-control" value="<?php echo $ligne_select['montant_livraison']; ?>" style="width: 80%; display: inline-block;">€
              </div>

              <div class="col-md-4 text-left">
                <label for="statut">*Statut:</label>
                <select name="statut" id="statut" class="form-control">
                  <option value="brouillon" <?php if ($ligne_select['statut'] == "brouillon") echo 'selected'; ?>>Brouillon</option>
                  <option value="activé" <?php if ($ligne_select['statut'] == "activé") echo 'selected'; ?>>Activée</option>
                </select>
              </div>
              <?php if ($action == "modifier") { ?>
                <div class="col-md-4 text-left">
                  <label for="date_statut">Date statut:</label> <br>
                  <div style="margin-top: 10px;">
                    <?php echo date('d-m-Y', $ligne_select['date']); ?>
                  </div>
                </div>
              <?php } ?>
            </div>
            <div class="col-md-4 text-left">
              <label for="valider"> </label> <br>
              <input type="submit" name="bouton" id="bouton" class="btn btn-primary" value="Valider">
            </div>
            </form>
    </div>
    <br /><br />

  <?php

      }
      ////////////////////////////FORMULAIRE AJOUTER / MODIFIER

      /////////////////////////////////////////Si aucune action
      if (!isset($action)) {
  ?>

    <div id='liste'></div>

    </div>

  <?php
      }
  ?>

<?php
  } else {  ?>
  <div class="alert alert-danger">Vous devez configurer votre profil de vendeur pour pouvoir recevoir de l'argent et créer des produits.</div>
  <a href="/Mon-profil-vendeur" class="btn btn-primary mt-3">Configurer mon profil </a>
<?php
  }



  /////////////////////////////////////////Si aucune action

  /*    echo "</div>"; */
} else {
  header('location: /');
}
?>