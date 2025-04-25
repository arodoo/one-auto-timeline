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
 
?>

<script>
$(document).ready(function (){

ContenuAvis();

$(document).on("click" , "#button-deposer-un-avis-en-ligne", function (){
$('#deposer-un-avis-en-ligne').modal({ backdrop: 'static', keyboard: false, show: true });
});

//AJAX SOUMISSION DU FORMULAIRE
$(document).on("click", "#commentaire_post", function (){
$.post({
url : '/pages/avis/Avis-ajax.php',
type : 'POST',
data : {
contenulivredor:$('#contenulivredor').val(),
titrepostlivredor:$('#titrepostlivredor').val(),
note_post:$('#note_post').val(),
commentaire_post:$('#note_post').val(),
action:"send",
pseudomail1:$('#pseudomail1').val(),
eelogin:$('#eelogin').val(),
eeemail:$('#eeemail').val()
},
dataType: "json",
success: function (res) {
if(res.retour_validation == "ok"){
deletenote1('etoile11');
ContenuAvis();
$('#deposer-un-avis-en-ligne').modal('toggle');
popup_alert(res.Texte_rapport,"green filledlight","#009900","uk-icon-check");
$('#contenulivredor').val("");
$('#titrepostlivredor').val("");
$('#note_post').val("");
}else{
popup_alert(res.Texte_rapport,"#CC0000 filledlight","#CC0000","uk-icon-times");
}
}
});

});

<?php
//////////////SI ADMIN
if(!empty($_SESSION['7A5d8M9i4N9']) && !empty($_SESSION['4M8e7M5b1R2e8s'])){
?>

//AJAX ON SUPPRIME EN BOUCLE LES AVIS SELECTIONNES
$(document).on("click", "#avissupprimerfor", function (){
$.post({
url : '/pages/avis/Avis-ad-supprimer.php',
type : 'POST',
data: new FormData($("#avisforma")[0]),
processData: false,
contentType: false,
dataType: "json",
success: function (res) {
if(res.retour_validation == "ok"){
ContenuAvis();
popup_alert(res.Texte_rapport,"green filledlight","#009900","uk-icon-check");
}else{
popup_alert(res.Texte_rapport,"#CC0000 filledlight","#CC0000","uk-icon-times");
}
}
});
});

//AJAX ON ACTIVE L'AVIS SELECTIONNE
$(document).on("click", "#activeravis", function (){
$.post({
url : '/pages/avis/Avis-ad-activer.php',
type : 'POST',
data: {
action:"valider", 
idaction:$('#activeravis').attr("data-id")
},
dataType: "json",
success: function (res) {
if(res.retour_validation == "ok"){
ContenuAvis();
popup_alert(res.Texte_rapport,"green filledlight","#009900","uk-icon-check");
}else{
popup_alert(res.Texte_rapport,"#CC0000 filledlight","#CC0000","uk-icon-times");
}
}
});
});

//AJAX ON DESACTIVE L'AVIS SELECTIONNE
$(document).on("click", "#desactiveravis", function (){
$.post({
url : '/pages/avis/Avis-ad-desactiver.php',
type : 'POST',
data: {
action:"desactiver", 
idaction:$('#desactiveravis').attr("data-id")
},
dataType: "json",
success: function (res) {
if(res.retour_validation == "ok"){
ContenuAvis();
popup_alert(res.Texte_rapport,"green filledlight","#009900","uk-icon-check");
}else{
popup_alert(res.Texte_rapport,"#CC0000 filledlight","#CC0000","uk-icon-times");
}
}
});
});

<?php
}
//////////////SI ADMIN
?>

function ContenuAvis(){
$.post({
url : '/pages/avis/Avis-liste-ajax.php',
type : 'GET',
data:{},
dataType: "html",
success: function (res) {
$("#contenu-avis").html(res);
}
});
}

});
</script>

<div class="contact-form-wrapper background-white p30" >

<?php

//////////////SI ADMIN
if(!empty($_SESSION['7A5d8M9i4N9']) && !empty($_SESSION['4M8e7M5b1R2e8s'])){

}else{
//////////////SI ADMIN

}

?>

<div id='contenu-avis'></div>

<?php
/////////////////////////////////////////////////////INCLUDE FORMULAIRE MODAL
include('pages/avis/Avis-formulaire-modal.php');
/////////////////////////////////////////////////////INCLUDE FORMULAIRE MODAL

/////////////////////////////////////////Si aucune action
?>

</div>