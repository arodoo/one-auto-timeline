<?php

  /*****************************************************\
  * Adresse e-mail => staff@codi-one.com                *
  * La conception est assujettie à une autorisation     *
  * spéciale de codi-one.com. Si vous ne disposez pas de*
  * cette autorisation, vous êtes dans l'illégalité.    *
  * L'auteur de la conception est et restera            *
  * codi-one.com                                        *
  * Codage, script & images (all contenu) sont réalisés * 
  * par codi-one.com                                    *
  * La conception est à usage unique et privé.          *
  * La tierce personne qui utilise le script se porte   *
  * garante de disposer des autorisations nécessaires   *
  *                                                     *
  * Copyright ... Tous droits réservés auteur (Fabien B)*
  \*****************************************************/

$action = $_GET['action'];
$crypt = $_GET['crypt'];

if($action == "delete"){

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM g_Newsletter_listing WHERE Numero_id=?");
$req_select->execute(array($crypt));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idmaiNumeroid = $ligne_select['Numero_id'];

///////////////////////////////DELETE
$sql_delete = $bdd->prepare("DELETE FROM g_Newsletter_listing WHERE Numero_id=?");
$sql_delete->execute(array($idmaiNumeroid));                     
$sql_delete->closeCursor();

if($req6 == true){
////////////RAPPORT JS
?>
<script language="javascript" type="text/javascript">
alert("<?php echo "Votre adresse mail à bien été supprimée !"; ?>");
document.location.replace("/");
</script>
<?php
////////////RAPPORT JS

}else{
////////////RAPPORT JS
?>
<script language="javascript" type="text/javascript">
alert("<?php echo "Il y a eu une erreur !"; ?>");
document.location.replace("/");
</script>
<?php
////////////RAPPORT JS
}

}else{
////////////RAPPORT JS
?>
<script language="javascript" type="text/javascript">
document.location.replace("/");
</script>
<?php
////////////RAPPORT JS
}
?>
