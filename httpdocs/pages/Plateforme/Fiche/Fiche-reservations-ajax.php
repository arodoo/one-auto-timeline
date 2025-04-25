<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

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

$id_prestation = $_POST['id_prestation'];
$date = $_POST['date'];
$heure = $_POST['heure'];

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$telephone = $_POST['telephone'];
$mail = $_POST['mail'];

$_SESSION['nom'] = $_POST['nom'];
$_SESSION['prenom'] = $_POST['prenom'];
$_SESSION['telephone'] = $_POST['telephone'];
$_SESSION['mail'] = $_POST['mail'];

if (!empty($mail) && preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/", $mail)){
    $array = explode('@', $mail);
    $ap = $array[1];
    $domain = checkdnsrr($ap);
}

if($domain == true){

	if(!empty($date) && !empty($heure) && !empty($nom) && !empty($prenom) && !empty($mail) && !empty($telephone) ){

		$sql_resa = $bdd->prepare("SELECT * FROM membres_prestations WHERE id=?");
		$sql_resa->execute(array($id_prestation)); 
		$resa = $sql_resa->fetch();                   
		$sql_resa->closeCursor();

		$sql_eta = $bdd->prepare("SELECT * FROM membres_etablissements WHERE id=?");
		$sql_eta->execute(array($resa['id_etablissement'])); 
		$eta = $sql_eta->fetch();                   
		$sql_eta->closeCursor();

		$sql_membre = $bdd->prepare("SELECT * FROM membres WHERE id=?");
		$sql_membre->execute(array($eta['id_membre'])); 
		$membre = $sql_membre->fetch();                   
		$sql_membre->closeCursor();

		$duree_prestation_minute = $resa['duree_prestation'];
		$duree_prestation_seconde = ($duree_prestation_minute*60);

		if(strlen($heure) == 5 ){

			if(!empty($resa['id'])){

			include('Fiche-reservations-ajax-jours-verifs.php');
				if(!empty($ouvert) && !empty($heure_ok)){

				$req_reservation = $bdd->prepare("SELECT * FROM membres_biens_reservations 
							WHERE bien_id = ? 
								AND 
								(
								((date_debut BETWEEN ? AND ?) OR (date_fin BETWEEN ? AND ?))
									OR
								((date_debut <= ?) AND (date_fin >= ?))
								)
								LIMIT 1");			   
				$req_reservation->execute(array($resa['id_etablissement'],$date_debut,$date_fin,$date_debut,$date_fin ,$date_debut,$date_fin));
		
				$reservation = $req_reservation->fetch();
				$req_reservation->closeCursor();

				/////////////////////////////RESERVATION OK 
				if(!$reservation){


					$sql_update = $bdd->prepare("INSERT INTO membres_biens_reservations
						(bien_id,
						membre_id,
						membre_annonceur_id,
						date_debut,
						date_debut_seconde,
						date_fin,
						date_fin_seconde,
						duree,
						nombre_personne,
						date_reservation,
						statut_reservation,
						montant_reservation,
						montant_commission,
						date_derniere_operation,
						nom,
						prenom,
						telephone,
						mail,
						id_prestation)
					VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
					$sql_update->execute(
					array(
						$resa['id_etablissement'], 
						$id_oo, 
						$eta['id_membre'], 
						$date_debut,
						$date_debut_seconde, 
						$date_fin,
						$date_fin_seconde, 
						$nbre, 
						"",
						date('Y-m-d H:i:s'),
						'Demande',
						$resa['prix_prestation'],
						"",
						time(),
						$nom,
						$prenom,
						$telephone,
						$mail,
						$id_prestation));   
					$sql_update->closeCursor();
					$lastInsertId = $bdd->lastInsertId();

    					///////////////////////Mail annonceur
   	 				$de_nom = "$nomsiteweb"; //Nom de l'envoyeur
    					$de_mail = "$emaildefault"; //Email de l'envoyeur
   	 				$vers_nom = " ".$membre['nom']." ".$membre['prenom']." ".$eta['nom_etablissement'].""; //Nom du receveur
    					$vers_mail = "".$eta['mail'].""; //Email du receveur
    					$sujet = "Vous avez une réservation sur $nomsiteweb";

    					$message_principalone = "<b>Bonjour, </b><br /><br />  
					Vous avez une réservation sur $nomsiteweb.<br /><br />
					<u>Récapitulatif de la réservation</u> : <br />
					Nom établissement : <b>".$eta['nom_etablissement']."</b> <br />
					Demandeur : <b> $prenom $nom </b> <br />
					Coordonnée demandeur : <b> $mail $telephone </b> <br />
					Fiche établisement : <a href='".$http.$nomsiteweb."/".$eta['nom_etablissement_url']."' target='_blank' style='text-decoration: underline;' >Visualiser la fiche établissement</a><br />
					Date de réservation : <b>$date_debut</b> à <b>$date_fin</b> <br />
    					Cordialement, l'équipe
    					<br />";
    					mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);

					$result = array("Texte_rapport"=>"<div class='rapport_red' ><span class='uk-icon-warning' ></span> Réservation validée !</div>","Texte_rapport_panier"=>"Réservation validée !","retour_validation"=>"ok","retour_lien"=>"");

				}else{
					$result = array("Texte_rapport"=>"<div class='rapport_red' ><span class='uk-icon-warning' ></span> Réservation non disponible ! </div>","Texte_rapport_panier"=>"Réservation non disponible !","retour_validation"=>"","retour_lien"=>"");

				}

				}else{
					$result = array("Texte_rapport"=>"<div class='rapport_red' ><span class='uk-icon-warning' ></span> Créneau horaire non disponible ! </div>","Texte_rapport_panier"=>"Créneau horaire non disponible !","retour_validation"=>"","retour_lien"=>"");

				}

			}else{
				$result = array("Texte_rapport"=>"<div class='rapport_red' ><span class='uk-icon-warning' ></span> La prestation n'existe pas ! </div>","Texte_rapport_panier"=>"La prestation n'existe pas !","retour_validation"=>"","retour_lien"=>"");

			}

		}else{
			$result = array("Texte_rapport"=>"<div class='rapport_red' ><span class='uk-icon-warning' ></span> Vous devez indiquer des minutes ! </div>","Texte_rapport_panier"=>"Vous devez indiquer des minutes !","retour_validation"=>"","retour_lien"=>"");

		}

	}else{
		$result = array("Texte_rapport"=>"<div class='rapport_red' ><span class='uk-icon-warning' ></span> *Tous les champs doivent être remplis ! </div>","Texte_rapport_panier"=>"*Tous les champs doivent être remplis ! ","retour_validation"=>"","retour_lien"=>"");

	}

}else{
	$result = array("Texte_rapport"=>"<div class='rapport_red' ><span class='uk-icon-warning' ></span> Mail non conforme ! </div>","Texte_rapport_panier"=>"Mail non conforme ! ","retour_validation"=>"","retour_lien"=>"");

}

$result = json_encode($result);
echo $result;

ob_end_flush();
?>