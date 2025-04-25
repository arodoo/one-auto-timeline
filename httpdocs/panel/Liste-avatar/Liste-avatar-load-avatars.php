<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
// $dir_fonction = "../../";
// require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

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

$dir = $_SERVER['DOCUMENT_ROOT'] . '/images/avatars/';
if (!is_dir($dir)) {
//echo "Le répertoire n'existe pas : " . $dir;
} elseif (!is_readable($dir)) {
//echo "Le répertoire n'est pas lisible : " . $dir;
} else {
$files = scandir($dir);
if ($files === false) {
//echo "Erreur lors de la lecture du répertoire.";
} else {
foreach ($files as $file) {
//echo "Fichier trouvé : " . $file . "<br>";
if ($file !== '.' && $file !== '..') {
$class = ($file == $image_profil_oo) ? 'avatar profile-avatar' : 'avatar';
echo '<img src="/images/avatars/' . $file . '" class="img-fluid rounded-circle ' . $class . '">';
}
}
}
}

} else {
    header("location: /");
}
ob_end_flush();
?>
