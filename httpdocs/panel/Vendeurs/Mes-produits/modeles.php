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

if (isset($_POST['marque'])) {

$marque = $_POST['marque'];
$idaction = $_POST['idaction'];

echo $marque;

// Récupérer les valeurs de la base de données
$req_select = $bdd->prepare("SELECT * FROM membres_produits WHERE id_membre= ? AND id = ?");
$req_select->execute(array($id_oo,$idaction));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$model = $ligne_select['model'];

// Requête pour obtenir les modèles liés à la marque
$sql = "SELECT modele FROM configurations_modeles WHERE rappel_marque = :marque";
$stmt = $bdd->prepare($sql);
$stmt->bindParam(':marque', $marque, PDO::PARAM_STR);
$stmt->execute();
$options = '<option value="">Sélectionnez un modèle</option>';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
$selected = ($row['modele'] == $model) ? 'selected' : '';
$options .= '<option value="' . htmlspecialchars($row['modele']) . '" ' . $selected . '>' . htmlspecialchars($row['modele']) . '</option>';
}

echo $options;
}

ob_end_flush();
?>