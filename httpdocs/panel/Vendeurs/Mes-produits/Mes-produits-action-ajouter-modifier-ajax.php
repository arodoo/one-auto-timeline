<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
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
        $nom_produit = $_POST['nom_produit'];
        $nom_categorie_api = $_POST['nom_categorie_api'];
        
        if ($statut_compte_oo == 5) {
           /*  $nom_produit = $_POST['nom_article']; */
            $nom_categorie_api = $_POST['nom_categorie_api'];
        }
        $description_produit = $_POST['description_produit'];
        $quantite = $_POST['quantite'];
        $montant = $_POST['montant'];
        $montant_livraison = $_POST['montant_livraison'];
        $id_categorie = $_POST['id_categorie'];
        if ($statut_compte_oo == 5) {
            $id_categorie = $_POST['id_categorie'];
        }
        $description_livraison = $_POST['description_livraison'];
        $meta_description = truncateText($description_produit, 160);
        $meta_keyword = truncateText($description_produit, 160);

        ////// RECEIVE IDS DATA REGARDING FILTERS TO QUERY A SINGLE PRODUCT IN THE API
        $id_produit_api = $_POST['id_produit_api'];
        $node_ids_api = $_POST['node_ids_api'];
        /////

        if (empty($id_categorie)) {
            $result = array("Texte_rapport" => "La catégorie est obligatoire.", "retour_validation" => "erreur");
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
        }


        // Vérification des champs
        if (empty($statut) || empty($nom_produit) || empty($description_produit) || empty($quantite) || empty($montant) || empty($montant_livraison)) {
            $missing_fields = [];
            if (empty($statut)) $missing_fields[] = 'statut';
            if (empty($nom_produit)) $missing_fields[] = 'nom produit';
            if (empty($description_produit)) $missing_fields[] = 'description produit';
            if (empty($quantite)) $missing_fields[] = 'quantite';
            if (empty($montant)) $missing_fields[] = 'montant';
            if (empty($montant_livraison)) $missing_fields[] = 'montant livraison';

            $result = array(
                "Texte_rapport" => "Tous les champs doivent être remplis. Champs manquants: " . implode(', ', $missing_fields),
                "retour_validation" => "erreur"
            );
        } elseif (!is_numeric($quantite) || $quantite < 0) {
            $result = array("Texte_rapport" => "La quantité doit être un nombre positif.", "retour_validation" => "erreur");
        } elseif (!is_numeric($montant) || !is_numeric($montant_livraison)) {
            $result = array("Texte_rapport" => "Les montants doivent être numériques.", "retour_validation" => "erreur");
        } else {
            if ($action == "modifier-action") {
                // Vérification de l'existence d'images pour le produit
                $sql_check_images = $bdd->prepare("SELECT COUNT(*) FROM membres_produits_images WHERE id_produit = ? AND id_membre = ?");
                $sql_check_images->execute(array($idaction, $id_oo));
                $image_count = $sql_check_images->fetchColumn();
                $sql_check_images->closeCursor();

                if ($image_count == 0) {
                    $result = array("Texte_rapport" => "Veuillez ajouter au moins une image pour le produit.", "retour_validation" => "erreur");
                    echo json_encode($result);
                    exit;
                }

                $sql_update = $bdd->prepare("UPDATE membres_produits SET 
                    statut = ?, 
                    nom_produit = ?, 
                    description_produit = ?, 
                    quantite = ?, 
                    montant_unite = ?, 
                    montant_livraison = ?, 
                    id_categorie = ?, 
                    title = ?,
                    meta_description = ?, 
                    meta_keyword = ?,
                    description_livraison = ?,
                    nom_categorie_api = ? 
                    WHERE id = ? 
                    AND id_membre = ?");
                $sql_update->execute(array(
                    $statut,
                    $nom_produit,
                    $description_produit,
                    $quantite,
                    $montant,
                    $montant_livraison,
                    $id_categorie,
                    $nom_produit,
                    $meta_description,
                    $meta_keyword,
                    $description_livraison,
                    $nom_categorie_api,
                    $idaction,
                    $id_oo
                ));



                $sql_update->closeCursor();

                $result = array("Texte_rapport" => "Produit modifié !", "retour_validation" => "ok", "retour_lien" => "");
            }

            if ($action == "ajouter-action") {
                $date = time();
                $sql_insert = $bdd->prepare("INSERT INTO membres_produits (pseudo, statut, nom_produit, description_produit, quantite, montant_unite, montant_livraison, id_categorie, title, meta_description, meta_keyword, id_membre, description_livraison, date, nom_categorie_api, id_produit_api, node_ids_api) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $sql_insert->execute(array(
                    $user,
                    $statut,
                    $nom_produit,
                    $description_produit,
                    $quantite,
                    $montant,
                    $montant_livraison,
                    $id_categorie,
                    $nom_produit,
                    $meta_description,
                    $meta_keyword,
                    $id_oo,
                    $description_livraison,
                    $date,
                    $nom_categorie_api,
                    $id_produit_api,
                    $node_ids_api
                ));



                $id_produit = $bdd->lastInsertId();
                $sql_insert->closeCursor();

                // Création du slug URL
                $slug = createSlug($nom_produit);
                $lien_produit = "/Page-marketplace/$slug/$id_produit";

                // Mise à jour du champ lien_produit
                $sql_update = $bdd->prepare("UPDATE membres_produits SET lien_produit = ? WHERE id = ?");
                $sql_update->execute(array($lien_produit, $id_produit));
                $sql_update->closeCursor();

                // Mettre à jour les images avec le bon id_produit
                $sql_update_images = $bdd->prepare("UPDATE membres_produits_images SET id_produit = ? WHERE id_produit = ? AND id_membre=?");
                $sql_update_images->execute(array($id_produit, $_SESSION['id_temporaire_image'], $id_oo));
                $sql_update_images->closeCursor();

                $result = array("Texte_rapport" => "Produit ajouté !", "retour_validation" => "ok", "retour_lien" => "");
            }
        }
    } catch (Exception $e) {
        $result = array("Texte_rapport" => "Erreur inattendue ", "retour_validation" => "erreur");
    }

    echo json_encode($result);
} else {
    header('location: /');
}

ob_end_flush();
