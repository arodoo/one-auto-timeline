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

if(isset($user) ){

$idaction = $_POST['idaction'];
$nom_fichier = "Logs";
$nom_fichier_datatable = "Logs-".date('d-m-Y', time())."-$nomsiteweb"; 
?>
<script>
$(document).ready(function(){
    $('#Tableau_10').DataTable(
{
"order": [],
responsive: true,
dom: 'Bftipr',
          buttons: [
       {
         extend: 'print',
           text  : "Imprimer",
                exportOptions: {
                    columns: ':visible'
                }
          },
          {
           extend: 'pdf',
           filename : "<?php echo "$nom_fichier_datatable"; ?>",
           title : "<?php echo "$nom_fichier"; ?>",
                exportOptions: {
                    columns: ':visible'
                }
          },{
          extend: 'csv',
           filename : "<?php echo "$nom_fichier_datatable"; ?>",
                exportOptions: {
                    columns: ':visible'
                }
          },{
          extend: 'colvis',
	text  : "Colonnes visibles",
          }
             ],
        columnDefs: [ {
            visible: false
       } ],
  "columnDefs": [
    { "orderable": false, "targets": 4, },
  ],
"language": {
	"sProcessing":     "Traitement en cours...",
	"sSearch":         "Rechercher&nbsp;:",
    "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
	"sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
	"sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
	"sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
	"sInfoPostFix":    "",
	"sLoadingRecords": "Chargement en cours...",
    "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
	"sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
	"oPaginate": {
		"sFirst":      "Premier",
		"sPrevious":   "Pr&eacute;c&eacute;dent",
		"sNext":       "Suivant",
		"sLast":       "Dernier"
	},
	"oAria": {
		"sSortAscending":  ": activer pour trier la colonne par ordre croissant",
		"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
	}
}
}
);

});
</script>

<table id='Tableau_10' class="display" style="text-align: center; width: 100%; margin-top: 15px;" cellpadding="2" cellspacing="2">

<thead>
<tr scope="col" >
<th style="text-align: center;" >DATE</th>
<th style="text-align: center;">MODULE</th>
<th style="text-align: center; min-width: 500px;" >SUJET</th>
<th style="text-align: center;" >LU</th>
<th style="text-align: center; width: 90px;">MODIFIER</th>
</tr>
</thead>
<tfoot>
<tr>
<th style="text-align: center;" >DATE</th>
<th style="text-align: center;">MODULE</th>
<th style="text-align: center;" >SUJET</th>
<th style="text-align: center;" >LU</th>
<th style="text-align: center; width: 90px;">MODIFIER</th>
</tr>
</tfoot>
<tbody>

<?php

	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT * FROM membres WHERE id=?");
	$req_select->execute(array($id_oo));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
	$idd2dddf = $ligne_select['id']; 
	$loginm = $ligne_select['pseudo'];
	$emailm = $ligne_select['mail'];
	$adminm = $ligne_select['admin'];
	$nomm = $ligne_select['nom'];
	$prenomm = $ligne_select['prenom'];

	///////////////////////////////SELECT BOUCLE
	$req_boucle = $bdd->prepare("SELECT * FROM membres_logs WHERE mail_compte_concerne=? AND mail_compte_concerne!=? ORDER BY date_seconde DESC");
	$req_boucle->execute(array($emailm,''));
	while($ligne_boucle = $req_boucle->fetch()){
	$idd = $ligne_boucle['id']; 
	$id_membre = $ligne_boucle['id_membre'];
	$pseudo = $ligne_boucle['pseudo'];
	$mail_compte_concerne = $ligne_boucle['mail_compte_concerne'];
	$module = $ligne_boucle['module'];
	$action_sujet = $ligne_boucle['action_sujet'];
	$action_libelle = $ligne_boucle['action_libelle'];
	$action = $ligne_boucle['action'];
	$date = $ligne_boucle['date'];
	$date_seconde = $ligne_boucle['date_seconde'];
	$heure = $ligne_boucle['heure'];
	$ip = $ligne_boucle['ip'];
	$navigateur = $ligne_boucle['navigateur'];
	$navigateur_version = $ligne_boucle['navigateur_version'];
	$referrer = $ligne_boucle['referrer'];
	$uri = $ligne_boucle['uri'];
	$cookies_autorisees = $ligne_boucle['cookies_autorisees'];
	$os = $ligne_boucle['os'];
	$langue = $ligne_boucle['langue'];
	$niveau = $ligne_boucle['niveau'];
	$lieu = $ligne_boucle['lieu'];
	$compte_bloque = $ligne_boucle['compte_bloque'];
	$lu = $ligne_boucle['lu'];

	if($compte_bloque == "oui"){
		$compte_bloque_rapport = "oui";
	}else{
		$compte_bloque_rapport = "--";
	}

	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT * FROM membres WHERE mail=?");
	$req_select->execute(array($mail_compte_concerne));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
	$login_membre = $ligne_select['pseudo'];
	$email_membre = $ligne_select['mail'];

?>
<tr class="odd" onclick="document.location.replace('<?php echo "/Notifications/consulter/".$idd.""; ?>');" style="cursor: pointer;" >
<td class="dtr-control" style='text-align: center;'><?php echo "$date à $heure"; ?></td>
<td style='text-align: center;'><?php echo "$module"; ?></td>
<td style='text-align: center;'>
<?php
if($compte_bloque == "oui"){
echo "<span class='label label-danger'>Compte bloqué</span>";
}
if($niveau == 1){
echo "<span class='label label-danger'>Niveau important</span>";
}
if($niveau == 2){
echo "<span class='label label-warning'>Niveau moyen</span>";
}
if($niveau == 3){
echo "<span class='label label-info'>Niveau faible</span>";
}
if($niveau > 3){
echo "<span class='label label-default'>Information</span>";
}
?>
<br />
<?php echo html_entity_decode($action_sujet) ?>
</td>
<td style='text-align: center;'>
<?php if($lu == "oui"){ echo "<span class='uk-icon-check' style='color: green;' ></span>"; }else{ echo "<span class='uk-icon-circle-o' style='color: red;' ></span>"; } ?>
</td>
<td style='text-align: center;'>
<?php echo "<a href='/Notifications/consulter/".$idd."' title='Consulter' data-id='".$idd."' ><span class='uk-icon-file-text' ></span></a>"; ?>
</td>
</tr>
<?php echo "</a>"; ?>
<?php
}
$req_boucle->closeCursor();

echo '</tbody></table><br /><br />';

}else{
header('location: /index.html');
}

ob_end_flush();
?>