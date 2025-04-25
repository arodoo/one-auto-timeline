<style>
      #container-paiement {
            background: #e8e8e8;
            border-radius: 15px;
            margin: 30px 0 !important;
      }

      #recap-container {
            display: none;
      }

      .div-title {
            margin-bottom: 50px;
      }

      @media (max-width: 768px) {

            #container-paiement {
                  max-width: 100%;
            }
      }
      .link{
            cursor: pointer;
      }
</style>
<div id="container-paiement" class="container">
      <div class="row div-title">
            <div class="col">
                  <h1 style="color: black; margin-bottom: 10px;">Coordonnées du nouveau titulaire et de livraison</h1>
                  <p>Ces informations seront utilisées pour effectuer votre démarche et remplir vos documents cerfa.</p>
            </div>
      </div>
      <div class="row">
            <div class="col">
                  <div class="form-check">
                        <input class="form-check-input" type="radio" name="typeContact" id="particulier" checked>
                        <label class="form-check-label" for="particulier">Particulier</label>
                  </div>
            </div>
            <div class="col">
                  <div class="form-check">
                        <input class="form-check-input" type="radio" name="typeContact" id="entreprise">
                        <label class="form-check-label" for="entreprise">Entreprise</label>
                  </div>
            </div>
      </div>

      <?php include $_SERVER['DOCUMENT_ROOT'] . '/panel/Carte-grise/panier-recap/form-particulier.php'; ?>
      <?php include $_SERVER['DOCUMENT_ROOT'] . '/panel/Carte-grise/panier-recap/form-entreprise.php'; ?>

      <div id="cotitulaires-container"></div>

      <div id="recap-container">
            <?php 
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/panel/Carte-grise/panier-recap/ajouter-cotitulaire.php';
            echo("Including file: " . $filePath);
            include $filePath; 
            ?>
      </div>

      <div class="row mt-3">
            <div class="col">
                  <p class="link" id="ajouter-cotitulaire">+ Ajouter un cotitulaire</p>
            </div>
      </div>

      <div class="row mt-3">
            <div class="col">
                  <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="" id="acceptCGV">
                        <label class="form-check-label" for="acceptCGV">J'accepte les <u>conditions générales de vente
                                    Mon espace auto</u></label>
                  </div>
            </div>
      </div>
      <div class="row mt-3">
            <div class="col">
                  <button class="btn btn-primary btn-block" id="payer-btn-process">TERMINER LE PAIEMENT</button>
            </div>
      </div>
</div>

<script>
      // Get references to the elements
      const acceptCGVCheckbox = document.getElementById('acceptCGV');
      const payerButton = document.getElementById('payer-btn-process');

      // Set initial button state
      payerButton.disabled = !acceptCGVCheckbox.checked;

      // Add event listener to checkbox
      acceptCGVCheckbox.addEventListener('change', function() {
            payerButton.disabled = !this.checked;
      });
</script>

<script type="module" src="/panel/Carte-grise/panier-recap/JS/panier-informations.js"></script>
