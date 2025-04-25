<?php
///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM configurations_publicites WHERE statut_activer_post=? AND url_page_publicite=? ORDER BY position_publicite ASC");
$req_boucle->execute(array('oui',$_SERVER['REQUEST_URI']));
while($ligne_boucle = $req_boucle->fetch()){
$idoneinfos = $ligne_boucle['id'];
$url_page_publicite= $ligne_boucle['url_page_publicite'];
$nom_publicite= $ligne_boucle['nom_publicite'];
$type_publicite= $ligne_boucle['type_publicite'];
$destination_publicite= $ligne_boucle['destination_publicite'];
$imagepublicite= $ligne_boucle['imagepublicite'];
$lien_publicite= $ligne_boucle['lien_publicite'];
$description_publicite= $ligne_boucle['description_publicite'];
$Duree_de_la_publicite= $ligne_boucle['Duree_de_la_publicite'];
$date_debut= $ligne_boucle['date_debut'];
$date_fin= $ligne_boucle['date_fin'];
$montant_publicite= $ligne_boucle['montant_publicite'];
$position_publicite= $ligne_boucle['position_publicite'];
$statut_activer_post= $ligne_boucle['statut_activer_post'];

if($Duree_de_la_publicite == "Illimité"){
$Dureedelapublicite1 = "selected";
}elseif($Duree_de_la_publicite == "Avec une limite de date"){
$Dureedelapublicite2 = "selected";
}

if($destination_publicite == "Interne"){
$destinationpublicite1 = "selected";
}elseif($destination_publicite == "Externe grauit"){
$destinationpublicite2 = "selected";
}elseif($destination_publicite == "Externe payant"){
$destinationpublicite3 = "selected";
}

if($Duree_de_la_publicite == "Illimité" || $Duree_de_la_publicite == "Avec une limite de date" && $date_debut < time() && time() < $date_fin ){
if($type_publicite == "Image avec lien" && !empty($imagepublicite) && !empty($lien_publicite) ){
?>
<img src='/images/publicites/<?php echo "$imagepublicite"; ?>' alt='<?php echo "$nom_publicite sssdfsd"; ?>' style='width: 100%;' >
<?php
}elseif($type_publicite == "Image sans lien" && !empty($imagepublicite) ){
?>
<img src='/images/publicites/<?php echo $imagepublicite; ?>' alt='<?php echo $nom_publicite; ?>' style='width: 100%;' >
<?php
}elseif($type_publicite == "Texte libre"  && !empty($description_publicite)){

?>
<div style='text-align: left;'>
<?php echo $description_publicite; ?>
</div>
<?php
}
}
}
$req_boucle->closeCursor();

?>