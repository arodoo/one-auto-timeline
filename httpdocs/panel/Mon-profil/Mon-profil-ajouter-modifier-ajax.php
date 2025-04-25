<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once ('../../Configurations_bdd.php');
require_once ('../../Configurations.php');
require_once ('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../";
require_once ('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user) && ($statut_compte_oo != 1)){

$id_type = 1;

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM membres_etablissements WHERE type_demande=? AND id_membre=?");
$req_select->execute(array($id_type,$id_oo));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idoneinfos_artciles_blog_profil = $ligne_select['id'];

//On créer profil si n'existe pas
if(empty($idoneinfos_artciles_blog_profil)){
///////////////////////////////INSERT
$sql_insert = $bdd->prepare("INSERT INTO membres_etablissements
	(id_membre,
	pseudo,
	type_demande,
    avis,
    nbr_vue,
    activer)
	VALUES (?,?,?,?,?,?)");
$sql_insert->execute(array(
	$id_oo,
	$user,
	$id_type,
    "5",
    "",
    "oui"));                     
$sql_insert->closeCursor();

    $idaction = $bdd->lastInsertId();

}else{

    $idaction = htmlentities(strip_tags($_POST['idaction']));

}


    $action = htmlentities(strip_tags($_POST['action']));

            $mode_vacance = strip_tags($_POST['mode_vacance']);

            $nom_etablissement = strip_tags($_POST['nom_etablissement']);

            $mail = strip_tags($_POST['mail']);
            $telephone = strip_tags($_POST['telephone']);
            $site_web = strip_tags($_POST['site_web']);
            $description = strip_tags($_POST['description']);
            $photo_principale1 = strip_tags($_POST['photo_principale1']);
            $photo_principale2 = strip_tags($_POST['photo_principale2']);
            $photo_principale3 = strip_tags($_POST['photo_principale3']);
            $photo_principale4 = strip_tags($_POST['photo_principale4']);
            $photo_principale5 = strip_tags($_POST['photo_principale5']);
            $horaire_semaine = strip_tags($_POST['horaire_semaine']);
            $horaire_samedi = strip_tags($_POST['horaire_samedi']);
            $horaire_ferme = strip_tags($_POST['horaire_ferme']);
            $avis = strip_tags($_POST['avis']);
            $nbr_vue = strip_tags($_POST['nbr_vue']);
            $activer = strip_tags($_POST['activer']);

            $avec_promotion = strip_tags($_POST['avec_promotion']) ?: 'non';

            $activer_telephone = strip_tags($_POST['activer_telephone']);

            $id_ville = strip_tags($_POST['id_ville']);
            $adresse = strip_tags($_POST['adresse']);
            $longitude = $_POST['lng'];
            $latitude = $_POST['lat'];
            $cp = $_POST['cp'];
            
            $ville = $id_ville;

            $slug_ville = $id_ville;
            
            $title = "$nom_etablissement";
            $meta_description = "$nom_etablissement $adresse $cp $ville";
            $meta_keyword = "$meta_description";

            
	    $avec_messagerie = "oui";

	    if($platine == "oui"){
            	$avec_reservation = "oui";
	    }else{
            	$avec_reservation = "non";
	    }

	    if($platine == "oui"){
		$platine = "oui";
		$vip = "non";
		$premium = "non";

	    }elseif($vip == "oui"){
		$platine = "non";
		$vip = "oui";
		$premium = "non";

	    }elseif($premium == "oui"){
		$platine = "non";
		$vip = "non";
		$premium = "oui";

	    }

            if(!file_exists("../../images/membres/".$user)){
                mkdir("../../images/membres/".$user);
            }


if(!empty($photo_principale1) || empty($photo_principale1) ){

if(!empty($_POST['id_categorie']) && !empty($cp) && !empty($id_ville) && !empty($description) ){

    ////////////////////////////AJOUTER
    if ($action == "ajouter-action")
    {

///////////////////////////////INSERT
$sql_insert = $bdd->prepare("INSERT INTO membres_etablissements
	(id_membre,
	pseudo,
            mode_vacance,
            nom_etablissement,
            adresse,
            cp,
            ville,
            slug_ville,
            id_ville,
            longitude,
            latitude,
            mail,
            telephone,
            site_web,
            description,
            photo_principale1,
            photo_principale2,
            photo_principale3,
            photo_principale4,
            photo_principale5,
            horaire_semaine,
            horaire_samedi,
            horaire_ferme,
            title,
            meta_description,
            meta_keyword,
            avis,
            nbr_vue,
            activer,
	    date,
	    platine,
	    vip,
	    premium,
	    avec_messagerie,
            avec_reservation,
	    avec_promotion,
	    activer_telephone,
	    type_demande
        )
	VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
$sql_insert->execute(array(
	$id_oo,
	$user,
            $mode_vacance,
            $nom_etablissement,
            $adresse,
            $cp,
            $ville,
            $slug_ville,
            $id_ville,
            $longitude,
            $latitude,
            $mail_oo,
            $Telephone_portable_oo,
            $site_web,
            $description,
            $photo_principale1,
            $photo_principale2,
            $photo_principale3,
            $photo_principale4,
            $photo_principale5,
            $horaire_semaine,
            $horaire_samedi,
            $horaire_ferme,
            $title,
            $meta_description,
            $meta_keyword,
            "5",
            "",
            "oui",
	    time(),
	    $platine,
	    $vip,
	    $premium,
	    $avec_messagerie,
            $avec_reservation,
	    $avec_promotion,
	    $activer_telephone,
	    $id_type
	));                     
//$sql_insert->closeCursor();
///////////////////////////////On nome l'url de la page si ce n'est pas un module

    $lastInsertId = $bdd->lastInsertId();

    $idaction = $lastInsertId;

	$nom_etablissement = "".$prenom_oo.", ".$id_statut_compte_membre."";

        //////////////////////////////////////On nomme l'url de la page
        $nouveaucontenu = "$nom_etablissement";
        include ("../../function/cara_replace.php");
        $nom_etablissement_url = "$nouveaucontenu";
        $nom_etablissement_url = "Fiche/" . $nom_etablissement_url . "/" . $idaction . "";

        ///////////////////////////////UPDATE
        $sql_update = $bdd->prepare("UPDATE membres_etablissements SET nom_etablissement=?, nom_etablissement_url=? WHERE id=?");
        $sql_update->execute(array(
            $nom_etablissement,
            $nom_etablissement_url,
            $idaction
        ));
        $sql_update->closeCursor();

        $result = array(
            "Texte_rapport" => "Annonce ajoutée avec succès !",
            "retour_validation" => "ok",
            "retour_lien" => ""
        );

    }
    ////////////////////////////AJOUTER

    ////////////////////////////MODIFIER
    if ($action == "modifier-action")
    {

        ///////////////////////////////UPDATE
        $sql_update = $bdd->prepare("UPDATE membres_etablissements SET 
            mode_vacance=?,
            nom_etablissement=?,
            adresse=?,
            cp=?,
            ville=?,
            slug_ville=?,
            id_ville=?,
            longitude=?,
            latitude=?,
            mail=?,
            telephone=?,
            site_web=?,
            description=?,
            photo_principale1=?,
            photo_principale2=?,
            photo_principale3=?,
            photo_principale4=?,
            photo_principale5=?,
            horaire_semaine=?,
            horaire_samedi=?,
            horaire_ferme=?,
            title=?,
            meta_description=?,
            meta_keyword=?,
	    date=?,
	    platine=?,
	    vip=?,
	    premium=?,
	    avec_messagerie=?,
            avec_reservation=?,
	    avec_promotion=?,
	    activer_telephone=?,
	    type_demande=?
		WHERE id=? AND id_membre=?");
        $sql_update->execute(array(
            $mode_vacance,
            $nom_etablissement,
            $adresse,
            $cp,
            $ville,
            $slug_ville,
            $id_ville,
            $longitude,
            $latitude,
            $mail_oo,
            $civilites_oo,
            $site_web,
            $description,
            $photo_principale1,
            $photo_principale2,
            $photo_principale3,
            $photo_principale4,
            $photo_principale5,
            $horaire_semaine,
            $horaire_samedi,
            $horaire_ferme,
            $title,
            $meta_description,
            $meta_keyword,
	    time(),
	    $platine,
	    $vip,
	    $premium,
	    $avec_messagerie,
            $avec_reservation,
	    $avec_promotion,
	    $activer_telephone,
	    $id_type,
        $idaction,
	    $id_oo
        ));
        $sql_update->closeCursor();

	$nom_etablissement = "".$prenom_oo.", ".$id_statut_compte_membre."";

        //////////////////////////////////////On nomme l'url de la page
        $nouveaucontenu = "$nom_etablissement";
        include ("../../function/cara_replace.php");
        $nom_etablissement_url = "$nouveaucontenu";
        $nom_etablissement_url = "Fiche/" . $nom_etablissement_url . "/" . $idaction . "";

        ///////////////////////////////UPDATE
        $sql_update = $bdd->prepare("UPDATE membres_etablissements SET nom_etablissement=?, nom_etablissement_url=? WHERE id=?");
        $sql_update->execute(array(
            $nom_etablissement,
            $nom_etablissement_url,
            $idaction
        ));
        $sql_update->closeCursor();

        $result = array(
            "Texte_rapport" => "Modifications effectuées !",
            "retour_validation" => "ok",
            "retour_lien" => ""
        );

    }
    ////////////////////////////MODIFIER

///////////////////////////////INSERT CATEGORIES
if(!empty($_POST['id_categorie'])){

///////////////////////////////DELETE
$sql_delete = $bdd->prepare("DELETE FROM membres_etablissements_categories WHERE id_etablissement=?");
$sql_delete->execute(array($idaction));                     
$sql_delete->closeCursor();

foreach($_POST['id_categorie'] as $id_categorie){
///////////////////////////////INSERT
$sql_insert = $bdd->prepare("INSERT INTO membres_etablissements_categories
	(id_membre,
	pseudo,
	id_etablissement,
	id_categorie)
	VALUES (?,?,?,?)");
$sql_insert->execute(array(
	$id_oo,
	$user,
	$idaction,
	$id_categorie));                     
$sql_insert->closeCursor();
}
}

///////////////////////////////INSERT SOUS CATEGORIES
if(!empty($_POST['id_categorie_sous'])){

    ///////////////////////////////DELETE
    $sql_delete = $bdd->prepare("DELETE FROM membres_etablissements_categories_sous WHERE id_etablissement=?");
    $sql_delete->execute(array($idaction));                     
    $sql_delete->closeCursor();
    
    foreach($_POST['id_categorie_sous'] as $id_categorie_sous){
    ///////////////////////////////INSERT
    $sql_insert = $bdd->prepare("INSERT INTO membres_etablissements_categories_sous
        (id_membre,
        pseudo,
        id_etablissement,
        id_categorie)
        VALUES (?,?,?,?)");
    $sql_insert->execute(array(
        $id_oo,
        $user,
        $idaction,
        $id_categorie_sous));                     
    $sql_insert->closeCursor();
    }
    }

}else{
        $result = array(
            "Texte_rapport" => "*Tous les champs doivent être remplis !",
            "retour_validation" => "",
            "retour_lien" => ""
        );

}

}else{
        $result = array(
            "Texte_rapport" => "Vous devez choisir une image !",
            "retour_validation" => "",
            "retour_lien" => ""
        );

}

    $result = json_encode($result);
    echo $result;

}
else
{
    header('location: /index.html');
}

ob_end_flush();
?>
