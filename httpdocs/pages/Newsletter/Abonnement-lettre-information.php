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


if(!empty($_POST['pseudomail'])){
//////////////////Ici l'action s'effectue

$passcone = create_password();

$pseudomail = $_POST['pseudomail'];

if(!empty($pseudomail) & preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/", $pseudomail)){
$array = explode('@', $pseudomail);
$ap = $array[1];
$domain = checkdnsrr($ap);
}

///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT * FROM g_Newsletter_listing WHERE Mail=?");
$req_select->execute(array($pseudomail));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idmailoneass = $ligne_select['Mail'];

if(empty($idmailoneass) && $domain == true){

$passc = create_password();
$passct = sha1($passc);
$passcone = create_password();

///////////////////////////////INSERT
$sql_insert = $bdd->prepare("INSERT INTO Newsletter_listing
	(id,
	Mail,
	Numero_id,
	date,
	plus,
	plus1)
	VALUES (?,?,?,?,?,?)");
$sql_insert->execute(array(
	"",
	$pseudomail,
	"$passct-$passcone",
	time(),
	"",
	""));                     
$sql_insert->closeCursor();

////////////RAPPORT JS
?>
<script language="javascript" type="text/javascript">
alert("<?php echo "$Votreadressemailabien_traduction"; ?>");
document.location.replace("/index.html");
</script>
<?php
////////////RAPPORT JS

}elseif( $domain != 1){
////////////RAPPORT JS
?>
<script language="javascript" type="text/javascript">
alert("<?php echo "Le domaine de l'adresse n'est pas valide !"; ?>");
document.location.replace("/");
</script>
<?php;
////////////RAPPORT JS

}elseif(!preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/", $pseudomail)){
////////////RAPPORT JS
?>
<script language="javascript" type="text/javascript">
alert("<?php echo "Adresse mail non conforme"; ?>");
document.location.replace("/");
</script>
<?php
////////////RAPPORT JS

}elseif(!empty($idmailoneass)){
////////////RAPPORT JS
?>
<script language="javascript" type="text/javascript">
alert("<?php echo "L'adresse mail est déjà associée !"; ?>");
document.location.replace("/");
</script>
<?php
////////////RAPPORT JS
}

}

?>
