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
    $req_select->execute(array($nbrid));
    $ligne_select = $req_select->fetch();
    $req_select->closeCursor();
    
    $idccontrolec                 = $ligne_select['id'];
    $pseudoec                     = $ligne_select['pseudo'];
    $mailc                        = $ligne_select['mail'];
    $statut_compte                = $ligne_select['statut_compte'];
    $nbractivationec              = $ligne_select['nbractivation'];
    $nbractivationecstatut_compte = $ligne_select['statut_compte'];

    if(isset($idccontrolec) && !empty($idccontrolec) && isset($nbractivationec) && !empty($nbractivationec)){

        ///////////////////////////////UPDATE
        $sql_update = $bdd->prepare("UPDATE membres SET 
        	nbractivation=? 
        	WHERE nbractivation=?");
        $sql_update->execute(array(
        	"",
        	$nbrid));                     
        $sql_update->closeCursor();

        ///////////////////////////////UPDATE
        $sql_update = $bdd->prepare("UPDATE membres SET 
        	last_login=? 
        	WHERE pseudo=?");
        $sql_update->execute(array(
        	$now,
        	$pseudoec));                     
        $sql_update->closeCursor();

        $user = "$pseudoec";
        $_SESSION['4M8e7M5b1R2e8s'] = "A9lKJF0HJ12YtG7WxCl12";
        $_SESSION['pseudo'] = "$idccontrolec";

        ///////////////////////Mail client
        $de_nom = "$nomsiteweb"; //Nom de l'envoyeur
        $de_mail = "$emaildefault"; //Email de l'envoyeur
        $vers_nom = "$pseudoec"; //Nom du receveur
        $vers_mail = "$mailc"; //Email du receveur
        $sujet = "Compte confirmé sur $nomsiteweb";

        $message_principalone = "<b>Objet :</b> $sujet<br /><br />
        <b>Bonjour, </b><br /><br />  
    	Merci pour votre confiance.<br /><br />
        <b><u>Compte confirmé:</u></b><br /><br />
        Votre compte sur $nomsiteweb à bien été validé. Vous pouvez maintenant accéder à votre compte.
        <br /><br />
        Vous devez mettre à jour toutes vos informations personnelles et/ou professionnelles 
        pour bénéficier de tous nos services en ligne et pour l'utilisation du site internet.
        <br /><br />
        Cordialement, la team PEP'S
        <br />";
        mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);
        ///////////////////////Mail client

	if($statut_compte == 1 || $statut_compte == 6){
        	header("location: /Gestion-de-votre-compte.html");
	}else{
        	header("location: /Guide");
	}

    }else{
        header("location: /index.html");
    }

}else{
    header("location: /index.html");
}
/////////////////Confirmation
?>