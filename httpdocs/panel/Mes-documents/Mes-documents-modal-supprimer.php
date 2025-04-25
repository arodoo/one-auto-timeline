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

		/* $req_select = $bdd->prepare("SELECT * FROM configurations_categorie_documents WHERE id=? AND id_membre=?");
		$req_select->execute(array($idaction,$id_oo));
		$ligne_select = $req_select->fetch();
		$req_select->closeCursor();
		$id = $ligne_select['id'];
		$id_client = $ligne_select['id_client'];
		$nom_projet = $ligne_select['nom_projet'];
		$dossier_projet_images = $ligne_select['dossier_projet_images'];
		$date_debut_projet = $ligne_select['date_debut_projet'];
		if (!empty($date_debut_projet)) {
			$date_debut_projet = date('Y-m-d', $ligne_select['date_debut_projet']);
		} else {
			$date_debut_projet = "--";
		}
		$date_fin_projet = $ligne_select['date_fin_projet'];
		if (!empty($date_fin_projet)) {
			$date_fin_projet = date('Y-m-d', $ligne_select['date_fin_projet']);
		} else {
			$date_fin_projet = "--";
		} */

?>

<div class="modal fade" id="modalSuppr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Confirmation de suppression</h4>
      </div>
      <div class="modal-body">
        <p>Êtes-vous sûr(e) de vouloir supprimer cet élément ?</p>
      </div>
      <div class="modal-footer">
        <button id="btnNon" type="button" class="btn btn-default" data-dismiss="modal">Non</button>
		<button id="btnSuppr" data-id="<?php echo $idaction; ?>" type="button" class="btn btn-danger" style="background-color: #FF2E2E; border-color: #FF2E2E;">Oui</button>
      </div>
    </div>
  </div>
</div>

<?php }
}catch(Exception $e){
  echo $e->getMessage();
} ?>

