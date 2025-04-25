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


//////////ON DETERMINE SI LA PERSONNE EST ENREGISTREE
if(isset($user)){
$nom_post_commentaire = "$user";
}

?>

<form id='commentaires' method='post' action='#commentaires'>

<input type='hidden' id='note_post' name='note_post' value='' />

<div id='etoile1' class='etoile_evaluation' onclick="addnote1(this.id)"></div>
<div id='etoile11' class='etoile_evaluation2' style='display: none;' onclick="deletenote1(this.id)"></div>

<div id='etoile2' class='etoile_evaluation' onclick="addnote1(this.id)"></div>
<div id='etoile22' class='etoile_evaluation2' style='display: none;' onclick="deletenote1(this.id)"></div>

<div id='etoile3' class='etoile_evaluation' onclick="addnote1(this.id)"></div>
<div id='etoile33' class='etoile_evaluation2' style='display: none;' onclick="deletenote1(this.id)"></div>

<div id='etoile4' class='etoile_evaluation' onclick="addnote1(this.id)"></div>
<div id='etoile44' class='etoile_evaluation2' style='display: none;' onclick="deletenote1(this.id)"></div>

<div id='etoile5' class='etoile_evaluation' onclick="addnote1(this.id)"></div>
<div id='etoile55' class='etoile_evaluation2' style='display: none;' onclick="deletenote1(this.id)"></div>

<div style='clear: both;'></div>

<div style='clear: both; margin-top: 20px; text-align: center;'>
<input type='text' id='nom_post_commentaire' name='nom_post_commentaire' class='form-control' placeholder="<?php echo "Nom"; ?>" value="<?php echo "$nom_post_commentaire"; ?>" style='width: 100%; margin-bottom: 5px;'/>
<input type='text' id='titre_post_commentaire' name='titre_post_commentaire' class='form-control' placeholder="<?php echo "Titre du commentaire"; ?>" value="<?php echo "$titre_post_commentaire"; ?>" style='width: 100%; margin-bottom: 5px;'/>
<textarea id='post_commentaire' name='post_commentaire' class='form-control' style='width: 100%; height: 60px; margin-bottom: 20px;' placeholder="<?php echo "Votre commentaire"; ?>"><?php echo "$post_commentaire"; ?></textarea>
<button type='button' id='envoyer_commentaire_post' class='btn btn-success' style='width: 200px;' onclick='return false;' >ENREGISTRER</button>
</div>

</form>

<script type="text/javascript">

var id;
var notejsjs;

function resetnote1(){

document.getElementById("etoile1").style.display = "";
document.getElementById("etoile11").style.display = "none";

document.getElementById("etoile2").style.display = "";
document.getElementById("etoile22").style.display = "none";

document.getElementById("etoile3").style.display = "";
document.getElementById("etoile33").style.display = "none";

document.getElementById("etoile4").style.display = "";
document.getElementById("etoile44").style.display = "none";

document.getElementById("etoile5").style.display = "";
document.getElementById("etoile55").style.display = "none";

notejsjs = "0";
document.getElementById("note_post").value = notejsjs;
}

function addnote1(id){

if(id == "etoile1" || id == "etoile2" || id == "etoile3" || id == "etoile4" || id == "etoile5"){
document.getElementById("etoile1").style.display = "none";
document.getElementById("etoile11").style.display = "";
notejsjs = "1";
}

if(id == "etoile2" || id == "etoile3" || id == "etoile4" || id == "etoile5"){
document.getElementById("etoile2").style.display = "none";
document.getElementById("etoile22").style.display = "";
notejsjs = "2";
}

if(id == "etoile3" || id == "etoile4" || id == "etoile5"){
document.getElementById("etoile3").style.display = "none";
document.getElementById("etoile33").style.display = "";
notejsjs = "3";
}

if(id == "etoile4" || id == "etoile5"){
document.getElementById("etoile4").style.display = "none";
document.getElementById("etoile44").style.display = "";
notejsjs = "4";
}

if(id == "etoile5"){
document.getElementById("etoile5").style.display = "none";
document.getElementById("etoile55").style.display = "";
notejsjs = "5";
}

document.getElementById("note_post").value = notejsjs;
}

function deletenote1(id){
var id;

if(id == "etoile11"){
document.getElementById("etoile1").style.display = "";
document.getElementById("etoile11").style.display = "none";

document.getElementById("etoile2").style.display = "";
document.getElementById("etoile22").style.display = "none";

document.getElementById("etoile3").style.display = "";
document.getElementById("etoile33").style.display = "none";

document.getElementById("etoile4").style.display = "";
document.getElementById("etoile44").style.display = "none";

document.getElementById("etoile5").style.display = "";
document.getElementById("etoile55").style.display = "none";
notejsjs = "0";
}

if(id == "etoile22"){
document.getElementById("etoile2").style.display = "";
document.getElementById("etoile22").style.display = "none";

document.getElementById("etoile3").style.display = "";
document.getElementById("etoile33").style.display = "none";

document.getElementById("etoile4").style.display = "";
document.getElementById("etoile44").style.display = "none";

document.getElementById("etoile5").style.display = "";
document.getElementById("etoile55").style.display = "none";
notejsjs = "1";
}

if(id == "etoile33"){
document.getElementById("etoile3").style.display = "";
document.getElementById("etoile33").style.display = "none";

document.getElementById("etoile4").style.display = "";
document.getElementById("etoile44").style.display = "none";

document.getElementById("etoile5").style.display = "";
document.getElementById("etoile55").style.display = "none";
notejsjs = "2";
}

if(id == "etoile44"){
document.getElementById("etoile4").style.display = "";
document.getElementById("etoile44").style.display = "none";

document.getElementById("etoile5").style.display = "";
document.getElementById("etoile55").style.display = "none";
notejsjs = "3";
}

if(id == "etoile55"){
document.getElementById("etoile5").style.display = "";
document.getElementById("etoile55").style.display = "none";
notejsjs = "4";
}

document.getElementById("note_post").value = notejsjs;
}

</script>

</div>
