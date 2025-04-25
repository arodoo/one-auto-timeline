<?php
if (!empty($user) && !empty($_SESSION['LAST_REFERENCE_FACTURE'])) {
?>
	<div id="retour-paiement" style="max-width: 600px; border: 1px dashed; margin: auto; padding: 10px; margin-bottom: 20px;">
		<h2>Merci pour votre paiement !</h2><br />

		<h3 style=" text-align: left;">Votre facture N°<?php echo $_SESSION['LAST_REFERENCE_FACTURE']; ?> est disponible.</h3><br />
		<p>Un mail de confirmation vous a été envoyé.</p>
		<p>A tout moment vous pouvez retrouver la facture dans votre espace membre.</p>
		<p>Vous pouvez dès maintenant profiter des différents services associés !</p>
		<p>Pour toutes questions, vous pouvez contacter le service commercial <a href="/Contact"><u>ici</u></a></p>
		<a href="/Missions/ajouter" class="btn btn-default" target="blank_" style="width: 250px;" ><span class="uk-icon-file"></span> Créer une mission </a>
		<a href="/facture/<?php echo $_SESSION['LAST_REFERENCE_FACTURE']; ?>/<?php echo "$nomsiteweb"; ?>" class="btn btn-default" target="blank_" style="width: 250px;" ><span class="uk-icon-file-pdf-o"></span> Votre facture</a>
	</div>

<?php

	unset($_SESSION['abonnement']);
	unset($_SESSION['duree']);

} else {

	header('location: /index.html');
}
?>