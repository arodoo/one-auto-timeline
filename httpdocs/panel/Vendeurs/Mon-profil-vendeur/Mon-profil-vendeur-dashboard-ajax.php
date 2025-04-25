<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_stripe_keys.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');
$lasturl = $_SERVER['HTTP_REFERER'];

$req_select = $bdd->prepare("SELECT * FROM membres_profil_paiement WHERE id_membre = ? ");
$req_select->execute(array($id_oo));
$profile_data = $req_select->fetch();
$req_select->closeCursor();
$id_account = $profile_data['id_account'];

if(isset($user)){

    $link = $customer = $stripe->accounts->createLoginLink(
        $id_account,
        []
      );

    $result = array("Texte_rapport"=>"","retour_validation"=>"ok","retour_lien"=>"$link->url");

$result = json_encode($result);
echo $result;

}

?>