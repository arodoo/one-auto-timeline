<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

///////////////////////CONTENU
///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT COUNT(*) AS nbravis FROM avis WHERE plus1='oui'");
$req_select->execute();
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$nbravis = $ligne_select['nbravis'];	

///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM avis WHERE plus1='oui'");
$req_boucle->execute();
while($ligne_boucle = $req_boucle->fetch()){
$NOTEnewonehh = $ligne_boucle['Plus'];
$newsnote = ($NOTEnewonehh+$newsnote);
}
$req_boucle->closeCursor();
if($nbravis > 0){
$newsnote = ($newsnote/$nbravis);
$newsnote = round($newsnote, 2);
}else{
$newsnote = round($newsnote, 2);
$newsnote = "$newsnote";
}
$newsnoteimg = round($newsnote);
?>

<button id="button-deposer-un-avis-en-ligne" type='button'  class='btn btn-default' style='text-align: center; text-transform: uppercase; float: right;' onclick='return false;' >Déposez un avis</button>

<p style='text-align: left; margin-bottom: 15px;'>
<span style=' font-size: 15px; font-weight: bold;'><?php echo "Actuellement"; ?> <span class='color_1'><?php echo "$nbravis avis postés </span> - $Note moyenne"; ?>  : <span class='color_1' style='font-weight: bold;'><?php echo "$newsnote"; ?> / 5</span> <img src='/images/etoiles/etoiles<?php echo "$newsnoteimg"; ?>.png' alt='Réputation <?php echo "$newsnoteimg"; ?>' style='margin-left: 5px; margin-bottom: 4px;'/>
</p>

<div style='clear: both; margin-bottom: 15px;'></div>

<?php
if(!empty($_SESSION['7A5d8M9i4N9']) && !empty($_SESSION['4M8e7M5b1R2e8s'])){
echo "<form method='post' id='avisforma' action='/Avis/update'>
<input type='hidden' id='action' name='action' value='update'  />";
$validerradmin = "";
$validerradmin1 = "";
}else{
$validerradmin = "AND plus1='oui'";
$validerradmin1 = "WHERE plus1='oui'";
}

///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM avis $validerradmin1 ORDER by date DESC");
$req_boucle->execute();
while($ligne_boucle = $req_boucle->fetch()){
$idnewone = $ligne_boucle['id'];	
$Titrenewone = stripslashes($ligne_boucle['Titre']);
$Contenunewone = stripslashes($ligne_boucle['Contenu']);
$Auteurnewone = $ligne_boucle['Auteur'];
$ip_postnewone = $ligne_boucle['ip_post'];
$datenewone = $ligne_boucle['date'];
$datenewoneaffichage = date('d-m-y', $datenewone);
$NOTEnewone = $ligne_boucle['Plus'];
$Validateone = $ligne_boucle['plus1'];

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM membres WHERE pseudo=?");
$req_select->execute(array($Auteurnewone));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idnewonemail = $ligne_select['mail'];	

if(empty($Auteurnewone)){
$Auteurnewone = "un visiteur";
}

?>

<div class="panel panel-default">
  <div class="panel-body">

<span class='uk-icon-comments'></span> <?php echo "Message écrit par $Auteurnewone"; ?> <img src='/images/etoiles/etoiles<?php echo "$NOTEnewone"; ?>.png' alt='Réputation <?php echo "$NOTEnewone"; ?>' style='margin-right: 5px;'/><br />
<hr />
<?php 
//////////////SI ADMIN
if(!empty($_SESSION['7A5d8M9i4N9']) && !empty($_SESSION['4M8e7M5b1R2e8s'])){
?>
<div style='display: block;' >
<input type='checkbox' id='supprimeaction' name='supprimeaction[]' value='<?php echo "$idnewone"; ?>' />  
<span style='color: red;'> Supprimer l'avis </span>
</div>
<?php
}
//////////////SI ADMIN
?>

<h2 style="display: block;"><?php echo "$Titrenewone"; ?></h2>
<?php echo nl2br($Contenunewone); ?><br />
<div style='text-align: right; font-size: 12px; '>
<?php 
//////////////SI ADMIN
if(!empty($_SESSION['7A5d8M9i4N9']) && !empty($_SESSION['4M8e7M5b1R2e8s'])){
?>
<div style='display: inline-block;' >
<span style='color: red;'><span class='uk-icon-user' ></span> Ip : <?php echo "$ip_postnewone"; ?></span>
<?php
if($Validateone != "oui"){
?>
<div id='activeravis' style='display: inline-block; cursor: pointer;' data-id="<?php echo "$idnewone"; ?>" onclick='return false;' > <span style='color: green;'><span class='uk-icon-circle-o' ></span> Valider l'avis</span></div>
<?php
}else{
?>
<div id='desactiveravis' style='display: inline-block; cursor: pointer;' onclick='return false;' data-id="<?php echo "$idnewone"; ?>" > <span style='color: red;'><span class='uk-icon-circle' ></span> Désactiver l'avis</span></a>
<?php
}
?>
</div>
<?php
}
//////////////SI ADMIN
?>
<span class='uk-icon-clock-o' ></span> <?php echo "Posté le $datenewoneaffichage"; ?>
</div>

  </div>
</div>
</div>

<div style='clear: both;'></div>

<?php
}
$req_boucle->closeCursor();

if(!empty($_SESSION['7A5d8M9i4N9']) && !empty($_SESSION['4M8e7M5b1R2e8s'])){
if(!empty($idnewone)){
?>
<p style='text-align: right;'>
<button type='button' id='avissupprimerfor' class='btn btn-default' onclick='return false;' >SUPPRIMER</button>
</p>
<?php
}
?>
</form>
<?php
}

if(empty($idnewone)){
?>
<div class="alert alert-warning" role="alert" style="text-align: left;" >
<span class='uk-icon-warning'></span> <?php echo "Aucun avis pour le moment !"; ?>
</div>
<?php
}
///////////////////////CONTENU

ob_end_flush();
?>
