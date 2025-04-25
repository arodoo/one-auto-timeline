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

if (isset($_POST['avatar'])) {
$avatar = $_POST['avatar'];
$source = $_SERVER['DOCUMENT_ROOT'] . $avatar;
$destinationDir = $_SERVER['DOCUMENT_ROOT'] . '/images/membres/' . $user . '/';
$destination = $destinationDir . basename($avatar);

if (copy($source, $destination)) {
///////////////////////////////UPDATE
$sql_update = $bdd->prepare("UPDATE membres SET image_profil=? WHERE id=?");
$sql_update->execute(array(basename($avatar), $id_oo));
$sql_update->closeCursor();
//echo 'Avatar copié avec succès!';
} else {
//echo 'Erreur lors de la copie de l\'avatar.';
}
}

} else {
    header("location: /");
}
ob_end_flush();
?>
