<?php

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

////////////Upload des images

$icon1 = $_FILES['icon1']['name'];
$now = time();

if (!empty($icon1)){

function get_file_extension($file) {
    return substr(strrchr($file,'.'),1);
  }
    
$taille = $_FILES['icon1']['size'];
$tmp = $_FILES['icon1']['tmp_name'];
$type = $_FILES['icon1']['type'];
$erreur = $_FILES['icon1']['error'];
$source_file = $_FILES['icon1']['tmp_name'];
$destination_file = "../../images/membres/".$user."/".$icon1;

$namebrut = explode('.', $icon1);
$namebruto = $namebrut[0];
$namebruto_extansion = $namebrut[1];

$nouveaucontenu = "$namebruto";
include('../../function/cara_replace.php');
$namebruto = "$nouveaucontenu";

$nouveau_nom_fichier = "".$namebruto."-".$now.".".get_file_extension($icon1)."";

$repertoire_move = "../../images/membres/$user/$nouveau_nom_fichier";
move_uploaded_file($tmp, $repertoire_move);

}

////////////Upload des images

?>