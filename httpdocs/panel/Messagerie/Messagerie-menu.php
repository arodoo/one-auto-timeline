<?php

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

/////////////////////////////////////////////////////////////////////////////////////////////////RAPPORT MESSAGE

////////////////////////////////////////////////////MESSAGE OUVERT/ENVOYE
///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT COUNT(*) AS idd_message_o_rccl_count_total FROM membres_messages WHERE pseudo=?");
$req_select->execute(array($user));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idd_message_o_rccl_count_total_oo = $ligne_select['idd_message_o_rccl_count_total'];
////////////////////////////////////////////////////MESSAGE OUVERT/ENVOYE

////////////////////////////////////////////////////MESSAGE TOTAL
///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT COUNT(*) AS idd_message_o_rccl_count_total FROM membres_messages WHERE pseudo_destinataire=?");
$req_select->execute(array($user));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idd_message_o_rccl_count_total = $ligne_select['idd_message_o_rccl_count_total'];

///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM membres_messages
	WHERE pseudo_destinataire=? 
	OR pseudo=?");
$req_boucle->execute(array(
	$user,
	$user
));
while ($ligne_boucle = $req_boucle->fetch()) {
	$idd_mb = $ligne_boucle['id'];
	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT COUNT(*) AS nbr_message_rrp FROM membres_messages_reponse WHERE id_message=?");
	$req_select->execute(array($idd_mb));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
	$nbr_message_rrp = $ligne_select['nbr_message_rrp'];
	$nbr_message_rrp_tt = ($nbr_message_rrp_tt + $nbr_message_rrp);
}
$req_boucle->closeCursor();
$total_message = ($idd_message_o_rccl_count_total + $nbr_message_rrp_tt);
////////////////////////////////////////////////////MESSAGE TOTAL

////////////////////////////////////////////////////MESSAGE LU
///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT COUNT(*) AS idd_message_o_rccl_count FROM membres_messages WHERE pseudo_destinataire=? AND message_lu=?");
$req_select->execute(array($user, 'oui'));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idd_message_o_rccl_count = $ligne_select['idd_message_o_rccl_count'];

///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM membres_messages 
	WHERE pseudo_destinataire=? 
	AND message_lu=?");
$req_boucle->execute(array(
	$user,
	'oui'
));
while ($ligne_boucle = $req_boucle->fetch()) {
	$idd_message_o_rccl_count_lu = $ligne_boucle['id'];
	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT COUNT(*) AS idd_message_o_rcll_count FROM membres_messages_reponse 
	WHERE id_message=?
	AND pseudo!=?
	AND message_reponse_lu=?");
	$req_select->execute(array(
		$idd_message_o_rccl_count_lu,
		$user,
		'oui'
	));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
	$idd_message_o_rcll_count = $ligne_select['idd_message_o_rcll_count'];
	$idd_message_o_rcll_count_tt = ($idd_message_o_rcll_count_tt + $idd_message_o_rcll_count);
}
$req_boucle->closeCursor();
$total_message_lu = ($idd_message_o_rccl_count + $idd_message_o_rcll_count_tt);
////////////////////////////////////////////////////MESSAGE LU


////////////////////////////////////////////////////MESSAGE NON LU
///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT COUNT(*) AS idd_message_o_rcc_count FROM membres_messages WHERE pseudo_destinataire=? AND message_lu!=?");
$req_select->execute(array($user, 'oui'));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idd_message_o_rcc_count = $ligne_select['idd_message_o_rcc_count'];

///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM membres_messages 
	WHERE pseudo_destinataire=?
	AND message_lu!=?");
$req_boucle->execute(array(
	$user,
	'oui'
));
while ($ligne_boucle = $req_boucle->fetch()) {
	$idd_message_o_rcc_countb = $ligne_boucle['id'];
	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT COUNT(*) AS idd_message_o_rc_count FROM membres_messages_reponse 
	WHERE id_message=?
	AND pseudo!=? 
	AND message_reponse_lu!=?");
	$req_select->execute(array(
		$idd_message_o_rcc_countb,
		$user,
		'oui'
	));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
	$idd_message_o_rc_count = $ligne_select['idd_message_o_rc_count'];
	$idd_message_o_rc_count_tt = ($idd_message_o_rc_count_tt + $idd_message_o_rc_count);
}
$req_boucle->closeCursor();
$total_message_non_lu = ($idd_message_o_rcc_count + $idd_message_o_rc_count_tt);
////////////////////////////////////////////////////MESSAGE NON LU

////////////////////////////////////////////////////MESSAGE EN ATTENTE DE LECTURE
///////////////////////////////SELECT
$req_select = $bdd->prepare("SELECT COUNT(*) AS idd_message_o_rccc_count FROM membres_messages 
	WHERE pseudo=? 
	AND message_lu!=?");
$req_select->execute(array(
	$user,
	'oui'
));
$ligne_select = $req_select->fetch();
$req_select->closeCursor();
$idd_message_o_rccc_count = $ligne_select['idd_message_o_rccc_count'];
///////////////////////////////SELECT BOUCLE
$req_boucle = $bdd->prepare("SELECT * FROM membres_messages 
	WHERE pseudo=? 
	AND message_lu!=?");
$req_boucle->execute(array(
	$user,
	'oui'
));
while ($ligne_boucle = $req_boucle->fetch()) {
	$idd_message_o_rccc_countb = $ligne_boucle['id'];
	///////////////////////////////SELECT
	$req_select = $bdd->prepare("SELECT COUNT(*) AS idd_message_o_rcccc_count FROM membres_messages_reponse WHERE pseudo=? AND message_reponse_lu!=?");
	$req_select->execute(array($user, 'oui'));
	$ligne_select = $req_select->fetch();
	$req_select->closeCursor();
	$idd_message_o_rcccc_count = $ligne_select['idd_message_o_rcccc_count'];
	$idd_message_o_rcccc_count = ($idd_message_o_rcccc_count_tt + $idd_message_o_rcccc_count);
}
$req_boucle->closeCursor();
$total_message_en_attente = ($idd_message_o_rccc_count + $idd_message_o_rcccc_count);
////////////////////////////////////////////////////MESSAGE EN ATTENTE DE LECTURE

/////////////////////////////////////////////////////////////////////////////////////////////////RAPPORT MESSAGE

?>

<div style='text-align: left; margin-bottom: 10px;'>
	<a href='/<?php echo "Messagerie"; ?>/Message-en-attente'>
		<div class='btn  btn-message-responsive' style="background-color: #b58210; color: #fff "> <span class='uk-icon-warning'></span> <?php echo "En attente de lecture <span class='badge'>$total_message_en_attente</span>"; ?> </div>
	</a>
	<a href='/<?php echo "Messagerie"; ?>/Message-ouverts'>
		<div class='btn btn-message-responsive' style="background-color: #003e49; color: #fff "> <span class='uk-icon-folder-o'></span> <?php echo "Messages ouverts <span class='badge'>$idd_message_o_rccl_count_total_oo</span>"; ?> </div>
	</a>
	<a href='/<?php echo "Messagerie"; ?>/Messages-lus'>
		<div class='btn btn-message-responsive' style="background-color: #5a9367; color: #fff "> <span class='uk-icon-envelope-o'></span> <?php echo "Messages lus <span class='badge'>$total_message_lu</span>"; ?> </div>
	</a>
	<a href='/<?php echo "Messagerie"; ?>/Messages-non-lus'>
		<div class='btn btn-primary btn-message-responsive'> <span class='uk-icon-envelope'></span> <?php echo "Messages non lus <span class='badge'>$total_message_non_lu</span>"; ?> </div>
	</a>
	<a href='/<?php echo "Messagerie"; ?>/Tous-les-messages'>
		<div class='btn btn-message-responsive' style="background-color: #003e49; color: #fff "> <span class='uk-icon-folder'></span> <?php echo "Tous les messages <span class='badge'>$total_message</span>"; ?> </div>
	</a>
	<?php
	if (!empty($pseudo_contact_messagerie) && !empty($contact_messagerie)) {
	?>
		<a href='/<?php echo "Messagerie"; ?>-<?php echo "$pseudo_contact_messagerie"; ?>.html'>
			<div class='btn btn-default btn-message-responsive'> <span class='uk-icon-user'></span> <?php echo "$contact_messagerie"; ?> </div>
		</a>
	<?php
	}
	?>
</div>