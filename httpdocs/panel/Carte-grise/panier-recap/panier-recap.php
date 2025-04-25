<?php
echo '<style>
  @media (max-width: 768px) {
    .container {
      flex-direction: column;
    }
    .container > div {
      width: 100% !important;
    }
  }
</style>';
echo '<div id="recap-main-container" class="container" style="display: flex; flex-wrap: wrap;">';
echo '<div style="width: 60%;">';

$cwd = getcwd();

$file1 = $cwd . '/panel/Carte-grise/panier-recap/Panier-informations-include.php';
if (file_exists($file1) && is_readable($file1)) {
  include($file1);
}
echo '</div>';

echo '<div style="width: 40%;">';

$file2 = $cwd . '/panel/Carte-grise/panier-recap/Panier-recapitulatif-paiement-include.php';
if (file_exists($file2) && is_readable($file2)) {
  include($file2);
}
echo '</div>';
echo '<button class="btn uk-icon-arrow-left" onclick="showMainContent()"> Retour</button>';
echo '</div>';
?>

<script type="module">
  import { showMainContent } from '/panel/Carte-grise/js/payer-loading.js';

  // Make showMainContent globally accessible
  window.showMainContent = showMainContent;
</script>