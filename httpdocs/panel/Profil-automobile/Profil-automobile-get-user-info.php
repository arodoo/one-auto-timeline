<?php
require_once('../../Configurations_bdd.php');

$response = ['status' => 400, 'data' => null];

if (!empty($_POST['id_membre'])) {
    $id_membre = $_POST['id_membre'];

    try {
        $sql = "SELECT * FROM membres_profil_auto WHERE id_membre = :id_membre";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([':id_membre' => $id_membre]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $response = ['status' => 200, 'data' => $result];
        } else {
            $response['message'] = 'Aucune donnée trouvée';
        }
    } catch (PDOException $e) {
        $response = ['status' => 500, 'message' => 'Erreur lors de la récupération des données: ' . $e->getMessage()];
    }
} else {
    $response['message'] = 'ID membre manquant';
}

echo json_encode($response);
?>