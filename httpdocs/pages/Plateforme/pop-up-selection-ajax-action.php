<?php
ob_start();

// INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

// INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
    try {
        $sql = $bdd->prepare("SELECT * FROM membres WHERE id=?");
        $sql->execute([$_POST['idaction']]);
        $membreco = $sql->fetch();
        $sql->closeCursor();

        if (!empty($_POST['objet_de_la_demande']) && !empty($_POST['description_de_la_demande'])) {
            if (!empty($_POST['idaction']) && !empty($membreco['id'])) {
                $timestamp = time();
                $stmt = $bdd->prepare("INSERT INTO `membres_devis` (
                    `id_membre_utilisateur`, 
                    `id_membre_depanneur`, 
                    `objet_de_la_demande`, 
                    `description_de_la_demande`, 
                    `date_demande`, 
                    `lien_devis`, 
                    `date_statut`, 
                    `statut_devis`,
                    `type`
                ) VALUES (?, ?, ?, ?, ?, '', ?, 'Non traité',?)");
                $stmt->execute([$id_oo, $_POST['idaction'], $_POST['objet_de_la_demande'], $_POST['description_de_la_demande'], $timestamp, $timestamp, $_POST['type']]);
                $last_id = $bdd->lastInsertId();

                // Nueva consulta para obtener url_profil
                $req_select_profil = $bdd->prepare("SELECT url_profil FROM membres_profils WHERE id_membre=?");
                $req_select_profil->execute([$membreco['id']]);
                $ligne_select_profil = $req_select_profil->fetch();
                $req_select_profil->closeCursor();

                //$url_profil = $http . $nomsiteweb . $ligne_select_profil['url_profil'];

                $de_nom = "$nomsiteweb"; // Nom de l'envoyeur
                $de_mail = "$emaildefault"; // Email de l'envoyeur
                $vers_nom = $membreco['prenom'] . " " . $membreco['nom']; // Nom du receveur
                $vers_mail = $membreco['mail']; // Email du receveur
                $sujet = "Nouveau devis sur $nomsiteweb"; // Sujet du mail
                $message_principalone = "<b>Bonjour " . $membreco['prenom'] . ",</b><br /><br />
                    Vous avez une demande de devis envoyé par " . $prenom_oo . " " . $non_oo . ".<br />
                    Objet de la demande : " . $_POST['objet_de_la_demande'] . "<br />
                    Connectez vous à votre espace dépanneur pour consulter la demande.<br />
                    En cliquant <a href='".$http ."" . $nomsiteweb . "' target='blank_'>ici</a><br /><br />
                    PS: Ne pas répondre à l'e-mail.<br />
                    Cordialement,<br /><br />";
                mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);

                $result = ["Texte_rapport" => "Demande envoyée au dépanneur !", "retour_validation" => "ok", "retour_lien" => ""];
            } else {
                $result = ["Texte_rapport" => "Une erreur s'est produite.", "retour_validation" => "", "retour_lien" => ""];
            }
        } else {
            $result = ["Texte_rapport" => "*Tous les champs doivent être remplis.", "retour_validation" => "", "retour_lien" => ""];
        }
    } catch (PDOException $e) {
        $result = ["Texte_rapport" => "Erreur : " . $e->getMessage(), "retour_validation" => "", "retour_lien" => ""];
    }

    echo json_encode($result);
} else {
    header('Location: /');
}

ob_end_flush();
?>
