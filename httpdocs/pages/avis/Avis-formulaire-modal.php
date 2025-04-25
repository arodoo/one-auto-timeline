<!-- Modal -->
<div class="modal" id="deposer-un-avis-en-ligne" role="dialog" aria-hidden="true" style='z-index:9999; text-align: center;'>
  <div class="modal-dialog" style='z-index:9999;'>
    <div class="modal-content">
	<!-- FORMULAIRE VALIDATION -->
      <div class="modal-header" style='text-align: left;'>
        <h2 class="modal-title" style="text-transform: uppercase; font-size: 20px;" ><?php echo "Ajouter un avis sur $nom_proprietaire"; ?></h2>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body" style="text-align:left;" >
<form method='post' id='avis-form' action='#' >
<?php
/////////Si le robot desactive les champs avec attribut hidden
/////////On contrôle que se champ est plein avec !empty()
/////////et qui correspond bien à la valeur par défault
?>
<input type='hidden' id='pseudomail1' name='pseudomail1' value="exemple@domaine.com"/>
<?php
/////////Si le robot passe il rempliera se champ avec attribut hidden
/////////On contrôle que se champ reste vide avec empty()
/////////On contrôle que la variable eelogin existe avec isset()
?>
<input type='hidden' id='eelogin' name='eelogin' value=""/>
<?php
/////////Si le robot passe il rempliera se champ
/////////On contrôle que se champ reste vide avec empty()
?>
<div style='display: none;'>
* Mail <input type='text' id='eeemail' name='eeemail' value=""/> Ne pas remplir ce champ, merci !
</div>

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

<div style="display: none;"><input type="text" id="note_post" /> </div>

<div style='clear: both; margin-bottom: 10px;'></div>

<input type='text' id='titrepostlivredor' name='titrepostlivredor' placeholder='Titre' class='form-control' value="<?php echo htmlentities(stripslashes($titrepostlivredor)); ?>" style='width: 100%; margin-bottom: 10px; ' />

<textarea id='contenulivredor' name='contenulivredor' placeholder='Contenu' class='form-control' style='width: 100%; margin-bottom: 10px;' rows='6'><?php echo stripslashes($contenulivredor); ?></textarea>

<div style='text-align: center;' >
<button type='button' id='commentaire_post' class='btn btn-default' style='width: 200px; margin-top: 15px;' onclick='return false;' >ENREGISTRER</button>
</div>

</form>
<?php
//}
?>

<script>

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
      </div>
    </div>
   </div>