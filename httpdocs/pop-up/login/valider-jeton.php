<?php

require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

$dir_fonction = "../../";
//require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

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

header('Content-Type: application/json');

// Decrypt the token
function decryptToken($token) {
    $secret = SECRET_KEY;
    $parts = explode('.', $token);
    if (count($parts) !== 2) {
        throw new Exception('Invalid token format');
    }
    list($tokenPart, $hashPart) = $parts;
    $validHash = hash_hmac('sha256', $tokenPart, $secret);
    if ($hashPart !== $validHash) {
        throw new Exception('Invalid token');
    }
    return $tokenPart;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['token'])) {
            throw new Exception('Token not provided');
        }

        $token = decryptToken($data['token']); // Decrypt the token

        // delete expired tokens
        $stmt = $bdd->prepare("DELETE FROM jetons_utilisateur WHERE mis_a_jour_a < (UNIX_TIMESTAMP() - (30 * 24 * 60 * 60))");
        $stmt->execute();

        if (!empty($token)) {
            $stmt = $bdd->prepare("SELECT id_membre FROM jetons_utilisateur WHERE token_hash = ?");
            $stmt->execute([$data['token']]); // Use the original token for the query
            $result = $stmt->fetch();

            if ($result) {
                session_start();
                $_SESSION['user_id'] = $result['id_membre'];

                $stmt = $bdd->prepare("UPDATE jetons_utilisateur SET mis_a_jour_a = ? WHERE token_hash = ?");
                $stmt->execute([time(), $data['token']]); // Use the original token for the update

                $stmt = $bdd->prepare("SELECT mail, admin FROM membres WHERE id = ?");
                $stmt->execute([$result['id_membre']]);
                $user_info = $stmt->fetch();

                $_SESSION['pseudo'] = $result['id_membre'];
                $_SESSION['4M8e7M5b1R2e8s'] = "A9lKJF0HJ12YtG7WxCl12";
                if ($user_info['admin'] > 0) {
                    $_SESSION['7A5d8M9i4N9'] = "GY1x79VmPH5yXwbT18hGdg";
                }

                $stmt = $bdd->prepare("UPDATE membres SET last_ip = ?, last_login = ? WHERE id = ?");
                $stmt->execute([$_SERVER['REMOTE_ADDR'], time(), $result['id_membre']]);

                $url = '/';

                $stmt->closeCursor();
                echo json_encode(['valid' => true, 'user_id' => $result['id_membre'], 'retour_lien' => $url]);
                exit;
            } else {
                throw new Exception('Invalid token');
            }
        } else {
            throw new Exception('Token not provided');
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['valid' => false, 'message' => $e->getMessage()]);
    exit;
}
?>
