<?php
ob_start();
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

$dir_fonction = "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];


if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {

    function truncateText($text, $maxLength)
    {
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

    function createSlug($string)
    {
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
       /*  $id_categorie = $_POST['id_categorie']; */
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
        $prix = $_POST['prix'];

      /*   if (empty($id_categorie)) {
            $result = array("Texte_rapport" => "La catégorie est obligatoire.", "retour_validation" => "erreur");
            echo json_encode($result);
            exit;
        } */

      /*   if (!is_numeric($id_categorie)) {
            $result = array(
                "Texte_rapport" => "ID de catégorie invalide: " . htmlspecialchars($id_categorie),
                "retour_validation" => "erreur"
            );
            echo json_encode($result);
            exit;
        } */

        // Vérification des champs
        if (empty($statut) || empty($nom) || empty($description) || empty($ville) || empty($id_departement) || empty($prix)) {
            $result = array("Texte_rapport" => "Tous les champs obligatoires doivent être remplis.", "retour_validation" => "erreur");
        } else {
            if ($action == "modifier-action") {
                // Vérification de l'existence d'images pour l'annonce
                $sql_check_images = $bdd->prepare("SELECT COUNT(*) FROM membres_annonces_ct_images WHERE id_annonce_service = ? AND id_membre = ?");
                $sql_check_images->execute(array($idaction, $id_oo));
                $image_count = $sql_check_images->fetchColumn();
                $sql_check_images->closeCursor();

                if ($image_count == 0) {
                    $result = array("Texte_rapport" => "Veuillez ajouter au moins une image pour l'annonce.", "retour_validation" => "erreur");
                    echo json_encode($result);
                    exit;
                }

                $sql_update = $bdd->prepare("UPDATE membres_annonces_ct SET 
                    statut = ?, 
                    nom = ?, 
                    description = ?, 
                    ville = ?, 
                    id_departement = ?, 
                    specialite = ?, 
                    meta_description = ?, 
                    meta_keyword = ?, 
                    mot_cle_1 = ?, 
                    mot_cle_2 = ?, 
                    mot_cle_3 = ?, 
                    mot_cle_4 = ?, 
                    mot_cle_5 = ?,
                    prix = ?
                    WHERE id = ? 
                    AND id_membre = ?");
                $sql_update->execute(array(
                    $statut,
                    $nom,
                    $description,
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
                    $prix,
                    $idaction,
                    $id_oo
                ));
                $sql_update->closeCursor();

                $result = array("Texte_rapport" => "Annonce modifiée !", "retour_validation" => "ok", "retour_lien" => "");
            }

            if ($action == "ajouter-action") {
                $date = time();
                $sql_insert = $bdd->prepare("INSERT INTO membres_annonces_ct (statut, nom, description, ville, id_departement, specialite, meta_description, meta_keyword, mot_cle_1, mot_cle_2, mot_cle_3, mot_cle_4, mot_cle_5, prix, id_membre, pseudo, date, title) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $sql_insert->execute(array(
                    $statut,
                    $nom,
                    $description,
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
                    $prix,
                    $id_oo,
                    $user,
                    $date,
                    $nom
                ));
                $id_annonce = $bdd->lastInsertId();
                $sql_insert->closeCursor();

                // Création du slug URL
                $slug = createSlug($nom);
                $lien = "/Page-annonce/$slug/$id_annonce";

                // Mise à jour du champ lien
                $sql_update = $bdd->prepare("UPDATE membres_annonces_ct SET lien = ? WHERE id = ?");
                $sql_update->execute(array($lien, $id_annonce));
                $sql_update->closeCursor();

                // Mettre à jour les images avec le bon id_annonce
                $sql_update_images = $bdd->prepare("UPDATE membres_annonces_ct_images SET id_annonce_service = ? WHERE id_annonce_service = ? AND id_membre=?");
                $sql_update_images->execute(array($id_annonce, $_SESSION['id_temporaire_image_annonce'], $id_oo));
                $sql_update_images->closeCursor();

                $result = array("Texte_rapport" => "Annonce ajoutée !", "retour_validation" => "ok", "retour_lien" => "");
            }
        }
    } catch (Exception $e) {
        $result = array("Texte_rapport" => "Erreur inattendue " . $e -> getMessage(), "retour_validation" => "erreur");
    }

    echo json_encode($result);
    exit;
} else {
    header('Content-Type: application/json');
    echo json_encode(array("Texte_rapport" => "Accès refusé.", "retour_validation" => "erreur"));
    exit;
}

ob_end_flush();
