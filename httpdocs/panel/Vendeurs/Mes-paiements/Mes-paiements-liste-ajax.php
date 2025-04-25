<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');

////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction= "../../../";
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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)){

$nom_fichier = "Mes-paiements";
$nom_fichier_datatable = "Mes-paiements-".date('d-m-Y', time())."-$nomsiteweb"; 
?>
<script>
$(document).ready(function(){
$('#Tableau_7').DataTable({
"order": [],
responsive: true,
stateSave: true,
dom: 'Bftipr',
buttons: [
{
extend: 'print',
text: "Imprimer",
exportOptions: {
columns: ':visible'
}
},
{
extend: 'pdf',
filename: "<?php echo "$nom_fichier_datatable"; ?>",
title: "<?php echo "$nom_fichier"; ?>",
exportOptions: {
columns: ':visible'
}
},
{
extend: 'csv',
filename: "<?php echo "$nom_fichier_datatable"; ?>",
exportOptions: {
columns: ':visible'
}
},
{
extend: 'colvis',
text: "Colonnes visibles",
}
],
columnDefs: [
{ visible: false },
{ orderable: false, targets: 4 }
],
"language": {
"sProcessing": "Traitement en cours...",
"sSearch": "Rechercher :",
"sLengthMenu": "Afficher MENU éléments",
"sInfo": "Affichage de l'élément START à END sur TOTAL éléments",
"sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 éléments",
"sInfoFiltered": "(filtré de MAX éléments au total)",
"sLoadingRecords": "Chargement en cours...",
"sZeroRecords": "Aucun élément à afficher",
"sEmptyTable": "Aucune donnée disponible dans le tableau",
"oPaginate": {
"sFirst": "Premier",
"sPrevious": "Précédent",
"sNext": "Suivant",
"sLast": "Dernier"
},
"oAria": {
"sSortAscending": ": activer pour trier la colonne par ordre croissant",
"sSortDescending": ": activer pour trier la colonne par ordre décroissant"
}
}
});

// Champs de recherche sur colonne
$('#Tableau_7 tfoot .search_table').each(function () {
var title = $(this).text();
$(this).html('<input type="text" class="form-control" placeholder="' + title + '" style="width:100%; font-weight: normal;"/>');
});
var table = $('#Tableau_7').DataTable();
table.columns().every(function () {
var that = this;
$('input', this.footer()).on('keyup change', function () {
if (that.search() !== this.value) {
that.search(this.value).draw();
}
});
});
});
</script>

<table id='Tableau_7' class='display' style='text-align: center; width: 100%; margin-top: 15px;' cellpadding='2' cellspacing='2'>
<thead>
<tr scope='col'>
<th style='text-align: center;'>CLIENT</th>
<th style='text-align: center;'>PRODUIT</th>
<th style='text-align: center;'>COMMANDE</th>
<th style='text-align: center;'>DATE</th>
<th style='text-align: center;'>MONTANT</th>
</tr>
</thead>
<tfoot>
<tr>
<th class='search_table' style='text-align: center;'>CLIENT</th>
<th class='search_table' style='text-align: center;'>PRODUIT</th>
<th class='search_table'style='text-align: center;'>COMMANDE</th>
<th class='search_table' style='text-align: center;'>DATE</th>
<th class='search_table' style='text-align: center;'>MONTANT</th>
</tr>
</tfoot>
<tbody>
<?php
// Boucle de sélection
$req_boucle = $bdd->prepare("SELECT * FROM membres_paiements_marketplace WHERE id_membre_vendeur=? ORDER BY id DESC");
$req_boucle->execute(array($id_oo));
while ($ligne_boucle = $req_boucle->fetch()) {
$idoneinfos = $ligne_boucle['id'];

// Sélection des clients
$req_select_clients = $bdd->prepare("SELECT * FROM membres WHERE id=?");
$req_select_clients->execute(array($ligne_boucle['id_membre']));
$ligne_select_clients = $req_select_clients->fetch();
$req_select_clients->closeCursor();

$req_select_pro = $bdd->prepare("SELECT * FROM membres_professionnel WHERE id=?");
$req_select_pro->execute(array($ligne_boucle['id_membre_vendeur']));
$ligne_select_pro = $req_select_pro->fetch();
$req_select_pro->closeCursor();
if(!empty($ligne_select_pro['Nom_societe'])){ $nom_pro = "(Société ".$ligne_select_pro['Nom_societe'].")"; }

// Sélection des vendeurs
$req_select_produit = $bdd->prepare("SELECT * FROM membres_produits WHERE id=?");
$req_select_produit->execute(array($ligne_boucle['id_produit']));
$ligne_select_produit = $req_select_produit->fetch();
$req_select_produit->closeCursor();

//Commandes
$req_select_commande = $bdd->prepare("SELECT * FROM membres_commandes WHERE id=?");
$req_select_commande->execute(array($ligne_boucle['id_commande']));
$ligne_select_commande = $req_select_commande->fetch();
$req_select_commande->closeCursor();

?>
<tr class='odd'>
<td class='dtr-control' style='text-align: center;'><?php echo $ligne_select_clients['prenom']; ?> <?php echo $ligne_select_clients['nom']; ?></td>
<td style='text-align: center;'><a href="/<?php echo $ligne_select_produit['lien_produit']; ?>" target="blank_" ><?php echo $ligne_select_produit['nom_produit']; ?></a></td>
<td style='text-align: center;'><a href="/Mes-commandes/modifier/<?php echo $ligne_select_commande['id']; ?>" target="blank_" >#<?php echo $ligne_select_commande['id']; ?></a></td>
<td style='text-align: center;'><?php echo date('d-m-Y', $ligne_boucle['date']); ?></td>
<td style='text-align: center; width: 90px;'><?php echo $ligne_boucle['montant']; ?>€</td>
</tr>
<?php
}
$req_boucle->closeCursor();
?>
</tbody>
</table>
<br /><br />

<?php
} else {
header('location: /');
}
ob_end_flush();
?>