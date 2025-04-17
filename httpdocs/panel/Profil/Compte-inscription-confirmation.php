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

$action = $_GET['action'];
$nbrid = $_GET['nbrid'];
$now = time();

/////////////////Confirmation
if($action == "confirmation" && !empty($nbrid) && isset($nbrid)){

	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT * FROM membres WHERE nbractivation=?");
	$req_select->execute(array(htmlspecialchars($nbrid)));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
       	$idccontrolec = $ligne_select ['id'];
       	$pseudoec = $ligne_select ['pseudo'];
       	$mailc = $ligne_select ['mail'];
       	$statut_compte = $ligne_select ['statut_compte'];
       	$nbractivationec = $ligne_select ['nbractivation'];
       	$nbractivationecstatut_compte = $ligne_select ['statut_compte'];

if(isset($idccontrolec) && !empty($idccontrolec) && isset($nbractivationec) && !empty($nbractivationec)){

///////////////////////////////UPDATE
$sql_update = $bdd->prepare("UPDATE membres SET nbractivation=? WHERE nbractivation=?");
$sql_update->execute(array('',htmlspecialchars($nbrid)));                     
$sql_update->closeCursor();

///////////////////////////////UPDATE
$sql_update = $bdd->prepare("UPDATE membres SET last_login=? WHERE pseudo=?");
$sql_update->execute(array($now,htmlspecialchars($pseudoec)));                     
$sql_update->closeCursor();

$user = "$pseudoec";
$_SESSION['4M8e7M5b1R2e8s'] = "A9lKJF0HJ12YtG7WxCl12";
$_SESSION['pseudo'] = "$pseudoec";

///////////////////////Mail client
$de_nom = "$nomsiteweb"; //Nom de l'envoyeur
$de_mail = "$emaildefault"; //Email de l'envoyeur
$vers_nom = "$pseudoec"; //Nom du receveur
$vers_mail = "$mailc"; //Email du receveur
$sujet = "$nomsiteweb - Connexion validée";

$message_principalone = "
<b>Bonjour $pseudoec, </b><br /><br />  
Votre compte est validé et nous vous en remercions. <br /><br /> 
Vous pouvez dès à présent accéder à votre compte.<br /><br /> 
Cordialement l'équipe,<br /> ";
mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);

///////////////////////Mail client

header("location: /Gestion-de-votre-compte.html");

}else{
header("location: /index.html");
}

}else{
header("location: /index.html");
}
/////////////////Confirmation

?>
