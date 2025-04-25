<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

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

$type = $_POST['type'];
$id_categorie = $_POST['id_categorie'];
$idaction = $_POST['idaction'];

if(!empty($id_categorie)){

foreach($_POST['id_categorie'] as $id_categorie){

      ///////////////////////////////SELECT BOUCLE
      $req_bouclec = $bdd->prepare("SELECT * FROM pages_categories_sous WHERE id=?");
      $req_bouclec->execute(array($id_categorie));
      $ligne_bouclec = $req_bouclec->fetch();
        ?>

	<div class="col-md-12" style="margin-bottom: 20px; text-align: left;" >
		<hr />
		<span style="font-weight: bold;">Sous catégories de secteur : <?php echo $ligne_bouclec['nom_categorie']; ?></span>
	</div>

      <?php
      ///////////////////////////////SELECT BOUCLE
      $req_boucle = $bdd->prepare("SELECT * FROM pages_categories_sous WHERE id_categorie=? AND activer='oui' ORDER BY nom_services_proposes ASC");
      $req_boucle->execute(array($ligne_bouclec['id']));
      while($ligne_boucle = $req_boucle->fetch()){

      ///////////////////////////////SELECT BOUCLE
      $req_bouclecm = $bdd->prepare("SELECT * FROM membres_etablissements_categories_sous WHERE id_etablissement=? AND id_categorie=?");
      $req_bouclecm->execute(array($idaction,$ligne_boucle['id']));
      $ligne_bouclecm = $req_bouclecm->fetch();
        ?>
        <div class="col-md-4" style="text-align: left;" ><input class="form-control" name="id_categorie_sous[]" type="checkbox" <?php if($ligne_boucle['id'] == $ligne_bouclecm['id_categorie']){ echo "checked"; } ?> value="<?php echo $ligne_boucle['id']; ?>" style="display: inline-block; height: 15px; width: 15px;" > <?php echo $ligne_boucle['nom_services_proposes']; ?> </div>
        <?php
      }
      $req_boucle->closeCursor();
      ?>

<?php
}
?>

	<div class="col-md-12" style="text-align: left;" >
		<hr />
	</div>

<?php
}

}else{
header('location: /index.html');
}

ob_end_flush();
?>