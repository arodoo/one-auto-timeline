<?php 
try{
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../";
require_once('../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)){

$idaction = $_POST['idaction'];

$req_select = $bdd->prepare("SELECT nom_categorie FROM membres_constats WHERE id=? AND id_membre=?");
$req_select->execute(array($idaction,$id_oo));
$pays = $req_select->fetch();
$req_select->closeCursor();

?>

<!-- <div class="modal fade" id="myModal" style="display: none;"> -->
<div class="modal fade" id="modalSuppr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmation de suppression</h4>
      </div>
      <div class="modal-body">

        <p>Êtes-vous sûr(e) de vouloir supprimer le constant N°<?php echo $pays['id']; ?> ?</p>
      </div>
      <div class="modal-footer">
        <button id="btnNon" type="button" class="btn btn-default" data-dismiss="modal">Non</button>
        <button id="btnSuppr" data-id="<?= $idaction ?>" type="button" class="btn btn-primary">Oui</button>
      </div>
    </div>
  </div>
</div>

<?php }
}catch(Exception $e){
  echo $e->getMessage();
} ?>