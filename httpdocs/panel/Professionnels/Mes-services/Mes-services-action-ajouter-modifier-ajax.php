<?php
ob_start();
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

$dir_fonction= "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {

function truncateText($text, $maxLength) {
    if (strlen($text) <= $maxLength) {
        return $text;
    }
    $truncated = substr($text, 0, $maxLength);
    $lastSpace = strrpos($truncated, ' ');
    if ($lastSpace !== false) {
        $truncated = substr($truncated, 0, $lastSpace);
    }
    return $truncated;
}

function createSlug($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return rtrim($string, '-');
}

try {
    $action = $_POST['action'];
    $idaction = $_POST['idaction'];
    $statut = $_POST['statut'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $id_categorie = $_POST['id_categorie'];
    $ville = $_POST['ville'];
    $id_departement = $_POST['departement'];
    $specialite = $_POST['specialite'];
    $meta_description = truncateText($description, 160);
    $meta_keyword = truncateText($description, 160);
    $mot_cle_1 = $_POST['mot_cle_1'];
    $mot_cle_2 = $_POST['mot_cle_2'];
    $mot_cle_3 = $_POST['mot_cle_3'];
    $mot_cle_4 = $_POST['mot_cle_4'];
    $mot_cle_5 = $_POST['mot_cle_5'];

  
    $sql_professional = $bdd->prepare("SELECT nom, prenom FROM membres WHERE pseudo = ?");
    $sql_professional->execute(array($user));
    $professional = $sql_professional->fetch();
    $sql_professional->closeCursor();
    $nom_professional = $professional['nom'];
    $prenom_professional = $professional['prenom'];

    if (empty($id_categorie)) {
        $result = array("Texte_rapport" => "La catégorie est obligatoire.", "retour_validation" => "erreur");
        echo json_encode($result);
        exit;
    }
  /*   if (!is_numeric($id_departement)) {
        $result = array(
            "Texte_rapport" => "ID de département invalide: " . htmlspecialchars($id_departement),
            "retour_validation" => "erreur"
        );
        echo json_encode($result);
        exit;
    }

    if (!is_numeric($id_categorie)) {
        $result = array(
            "Texte_rapport" => "ID de catégorie invalide: " . htmlspecialchars($id_categorie),
            "retour_validation" => "erreur"
        );
        echo json_encode($result);
        exit;
    } */

    // Vérification des champs
    if (empty($statut) || empty($nom) || empty($description) || empty($ville) || empty($id_departement) || empty($id_categorie)) {
        $result = array("Texte_rapport" => "Tous les champs obligatoires doivent être remplis.", "retour_validation" => "erreur");
    } else {
  
        $emaildefault = $emaildefault ?? 'contact@mon-espace-auto.com'; 
        $nomsiteweb;

        if ($action == "modifier-action") {
            // Vérification de l'existence d'images pour l'annonce
            $sql_check_images = $bdd->prepare("SELECT COUNT(*) FROM membres_services_images WHERE id_annonce_service = ? AND id_membre = ?");
            $sql_check_images->execute(array($idaction, $id_oo));
            $image_count = $sql_check_images->fetchColumn();
            $sql_check_images->closeCursor();

            if ($image_count == 0) {
                $result = array("Texte_rapport" => "Veuillez ajouter au moins une image pour l'annonce.", "retour_validation" => "erreur");
                echo json_encode($result);
                exit;
            }

            $sql_update = $bdd->prepare("UPDATE membres_services SET 
                statut = ?, 
                nom = ?, 
                description = ?, 
                id_categorie = ?, 
                ville = ?, 
                id_departement = ?, 
                specialite = ?, 
                meta_description = ?, 
                meta_keyword = ?, 
                mot_cle_1 = ?, 
                mot_cle_2 = ?, 
                mot_cle_3 = ?, 
                mot_cle_4 = ?, 
                mot_cle_5 = ? 
                WHERE id = ? 
                AND id_membre = ?");
            $sql_update->execute(array(
                $statut,
                $nom,
                $description,
                $id_categorie,
                $ville,
                $id_departement,
                $specialite,
                $meta_description,
                $meta_keyword,
                $mot_cle_1,
                $mot_cle_2,
                $mot_cle_3,
                $mot_cle_4,
                $mot_cle_5,
                $idaction,
                $id_oo
            ));
            $sql_update->closeCursor();

            $result = array("Texte_rapport" => "Service modifié !", "retour_validation" => "ok", "retour_lien" => "");

            $de_nom = $nomsiteweb; 
            $de_mail = $emaildefault; 
            $vers_nom = 'Admin';
            $vers_mail = $emaildefault;
            $sujet = "Service modifié";
            $cleanTitle = createSlug($nom);
            $message_principalone = "<br/>Bonjour,<br/><br/><p>Un service a été modifié avec succès.</p>
                                     <p>Description: $description</p>
                                     <p>Date: " . date('d-m-Y H:i:s') . "</p>
                                     <p>Statut: $statut</p>
                                     <p>Nom du professionnel: $prenom_professional $nom_professional</p>
                                     <p><a href='$nomsiteweb/Page-service/$cleanTitle/$idaction'>Lien vers le service</a></p>
                                     L’équipe $nomsiteweb";
            mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);
        }

        if ($action == "ajouter-action") {
            $date = time();
            $sql_insert = $bdd->prepare("INSERT INTO membres_services (statut, nom, description, id_categorie, ville, id_departement, specialite, meta_description, meta_keyword, mot_cle_1, mot_cle_2, mot_cle_3, mot_cle_4, mot_cle_5, id_membre,date,pseudo, title) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $sql_insert->execute(array(
                $statut,
                $nom,
                $description,
                $id_categorie,
                $ville,
                $id_departement,
                $specialite,
                $meta_description,
                $meta_keyword,
                $mot_cle_1,
                $mot_cle_2,
                $mot_cle_3,
                $mot_cle_4,
                $mot_cle_5,
                $id_oo,
                $date,
                $user,
                $nom

            ));
            $id_annonce = $bdd->lastInsertId();
            $sql_insert->closeCursor();

            // Création du slug URL
            $slug = createSlug($nom);
            $lien = "/Page-annonce/$slug/$id_annonce";

            // Mise à jour du champ lien_produit
            $sql_update = $bdd->prepare("UPDATE membres_services SET lien = ? WHERE id = ?");
            $sql_update->execute(array($lien, $id_annonce));
            $sql_update->closeCursor();

            // Mettre à jour les images avec le bon id_annonce
            $sql_update_images = $bdd->prepare("UPDATE membres_services_images SET id_annonce_service = ? WHERE id_annonce_service = ? AND id_membre=?");
            $sql_update_images->execute(array($id_annonce, $_SESSION['id_temporaire_image_annonce'], $id_oo));
            $sql_update_images->closeCursor();

            $result = array("Texte_rapport" => "Service ajouté !", "retour_validation" => "ok", "retour_lien" => "");

            $de_nom = $nomsiteweb; // Nom de l'envoyeur
            $de_mail = $emaildefault; // Email de l'envoyeur
            $vers_nom = 'Admin'; // Nom du receveur
            $vers_mail = $emaildefault; // Email del receveur
            $sujet = "Nouveau service ajouté";
            $cleanTitle = createSlug($nom);
            $message_principalone = "<br/>Bonjour,<br/><br/><p>Un nouveau service a été ajouté avec succès.</p>
                                     <p>Description: $description</p>
                                     <p>Date: " . date('d-m-Y H:i:s') . "</p>
                                     <p>Statut: $statut</p>
                                     <p>Nom du professionnel: $nom_professional $prenom_professional</p>
                                     <p><a href='$nomsiteweb/Page-service/$cleanTitle/$id_annonce'>Lien vers le service</a></p>
                                     L’équipe $nomsiteweb";

  
            mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);
            echo json_encode($result);
        }
    }

    echo json_encode($result);
} catch (Exception $e) {
    $result = array("Texte_rapport" => "Erreur inattendue " .$e -> getMessage(), "retour_validation" => "erreur");
    echo json_encode($result);
}

} else {
header('location: /');
}

ob_end_flush();
?>