<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../../Configurations_bdd.php');
require_once('../../../../Configurations.php');
require_once('../../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../../../";
require_once('../../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

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

// if(isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && isset($user) ){

$idaction = $_POST['idaction'];

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$mail = $_POST['mail'];
$Telephone_portable = $_POST['Telephone_portable'];
$Telephone = $_POST['Telephone'];
$adresse = $_POST['adresse'];
$ville = $_POST['ville'];
$cp = $_POST['cp'];

$Nom_societe  = $_POST['Nom_societe'];
$Numero_identification  = $_POST['Numero_identification'];

if(!empty($mail) && preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/", $mail)){
  $array = explode('@', $mail);
  $ap = $array[1];
  $domain = checkdnsrr($ap);
}

/////CONTRÔLE SI MAIL EXISTE PAS
///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM membres WHERE pseudo!=? AND mail=?");
$req_select->execute(array($user,$mail));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$id_mail_existe = $ligne_select['id'];

// if(empty($id_mail_existe) && preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/", $mail) && $domain == true && !empty($nom) && !empty($prenom) && !empty($mail) && !empty($adresse) && !empty($ville) && !empty($cp) && 
// ($configurations_informations_champs_professionnels == "" || $configurations_informations_champs_professionnels == "non" ) ||
// ($configurations_informations_champs_professionnels == "oui" && $configurations_informations_champs_professionnels_obligatoire == ""  || $configurations_informations_champs_professionnels_obligatoire == "non" ) || 
// (!empty($Nom_societe) && !empty($Numero_identification) && $configurations_informations_champs_professionnels == "oui" && $configurations_informations_champs_professionnels_obligatoire == "oui") ){

if(empty($id_mail_existe) && preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/", $mail) && $domain == true && !empty($nom) && !empty($prenom) && !empty($mail) && !empty($adresse) && !empty($ville) && !empty($cp)) {

if(!empty($cp)){
$departement = substr($cp,0,2);
}

//////////////////////REQUETE UPDATE TABLE MEMBRES
///////////////////////////////UPDATE
$sql_update = $bdd->prepare("UPDATE membres SET 
	nom=?, 
	prenom=?,
	mail=?, 
	Telephone=?, 
	Telephone_portable=?, 
	adresse=?, 
	cp=?, 
	departement=?, 
	ville=? 
	WHERE pseudo=?");
$sql_update->execute(array(
	$nom, 
	$prenom,
	$mail, 
	$Telephone, 
	$Telephone_portable, 
	$adresse, 
	$cp, 
	$departement, 
	$ville,
	$user));                     
$sql_update->closeCursor();

//////////////////////REQUETE UPDATE TABLE MEMBRES PROFESSIONNELS
if($configurations_informations_champs_professionnels == "oui" && $configurations_informations_champs_professionnels_obligatoire == "oui"){

	/////////////////////Si compte professionnel ou non
	if(!empty($id_pro)){
	///////////////////////////////UPDATE
	$sql_update = $bdd->prepare("UPDATE membres_professionnel SET 
		Nom_societe=?, 
		Numero_identification=? 
		WHERE pseudo=?");
	$sql_update->execute(array(
		$Nom_societe, 
		$Numero_identification, 
		$user));                     
	$sql_update->closeCursor();

	}elseif(empty($id_pro)){

///////////////////////////////INSERT
$sql_insert = $bdd->prepare("INSERT INTO membres_professionnel
	(id,
	id_membre,
	pseudo,
	Nom_societe,
	Votre_role,
	Type_societe,
	Effectif,
	Numero_identification,
	Non_assujetti,
	Numero_tva,
	plus,
	plus1)
	VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
$sql_insert->execute(array(
	'',
	'".$id_oo."',
	'".$user."',
	'".$Nom_societe."',
	'',
	'',
	'',
	'".$Numero_identification."',
	'',
	'',
	'',
	''));                     
$sql_insert->closeCursor();

	}
	/////////////////////Si compte professionnel ou non

}

$result = array("Texte_rapport"=>"Modifié avec succès !","retour_validation"=>"ok","retour_lien"=>"");

///////MAIL EXISTE
}elseif(!empty($id_mail_existe) ){
$result = array("Texte_rapport"=>"Le mail existe déjà !","retour_validation"=>"","retour_lien"=>"");

///////MAIL INVALIDE
}elseif(!preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/", $mail) || $domain == false ){
$result = array("Texte_rapport"=>"Le mail est invalide !","retour_validation"=>"","retour_lien"=>"");

///////CHAMPS VIDES
}elseif(empty($nom) || empty($prenom) || empty($mail) || empty($adresse) || empty($ville) || empty($cp) || 
empty($Nom_societe) && $configurations_informations_champs_professionnels == "oui" && $configurations_informations_champs_professionnels_obligatoire == "oui" ||
empty($Numero_identification) &&  $configurations_informations_champs_professionnels == "oui" && $configurations_informations_champs_professionnels_obligatoire == "oui"){
$result = array("Texte_rapport"=>"Les champs précédés d'une étoile doivent êtres remplis !","retour_validation"=>"","retour_lien"=>"");
}

$result = json_encode($result);
echo $result;

// }

ob_end_flush();
?>