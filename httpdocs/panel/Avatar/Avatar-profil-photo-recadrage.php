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

/////////Name de l'imput files "images"
/////////VARIABLES / CONFIGURATIONS

if ($statut_compte_oo != "3") {
	$page_avant = "/";
} else {
	$page_avant = "/";
}

$page_fonction = "Photos";
$recadrage_active = "oui";
$newlargeur = "380";
$newhauteur = "380";
//TAILLE FINAL
$large_img_finale = "380"; //largeur de l'image finale crée
$haute_img_finale = "380"; //hauteur de l'image finale crée
$repertoire = "images/membres/$user";
$repertoire_chmod = "images/membres/$user";
$caractere_replace_repertoire = "function/cara_replace.php";
/////////VARIABLES / CONFIGURATIONS

////////TYPE D'IMAGE VOULU : 
// large => plus large que haute
// haute => plus haute que large
// egale => longueur = largeur
$type_image_voulu = "large";
?>

<style>
	#container {
		position: relative;
		max-width: 100%;
		overflow: hidden;
	}

	#box {
		max-width: 100%;
	}
</style>

<script type="text/javascript">
	$(function () {
		$('#box').Jcrop({
			onSelect: updateCoords,
			bgFade: true,
			bgOpacity: .2,
			aspectRatio: <?php echo "$large_img_finale/$haute_img_finale"; ?>,
			minSize: [300, 300],
			setSelect: [0, 0, 50, 50]
		}, function () {
			jcrop_api = this;
		});
	});

	function updateCoords(c) {
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#w').val(c.w);
		$('#h').val(c.h);
	};

	function checkCoords() {
		if (parseInt($('#w').val())) return true;
		toastr.error("Il vous faut recadrer votre image, puis sauvegardez.", "Erreur");
		return false;
	};
</script>

<?php
$actionn = $_GET['actionn'];
$submit_recadrage = $_POST['submit_recadrage'];
$nomphotopost = $_POST['nomphotopost'];

if (!file_exists($repertoire)) {
	mkdir($repertoire);
}

////////////Upload image recadrage
if (!empty($_POST['submit_recadrage'])) {

	$targ_w = "$large_img_finale";
	$targ_h = "$haute_img_finale";
	$jpeg_quality = 100;

	$src = "" . $repertoire_chmod . "/" . $_SESSION['namebruto_extension'] . "";
	$src2 = "" . $repertoire_chmod . "/2-" . $_SESSION['namebruto_extension'] . "";
	$avatar2 = "2-" . $_SESSION['namebruto_extension'] . "";

	if (substr($_SESSION['namebruto_extension'], -4) == "jpeg" || substr($_SESSION['namebruto_extension'], -4) == "JPEG" || substr($_SESSION['namebruto_extension'], -3) == "jpg" || substr($_SESSION['namebruto_extension'], -3) == "JPG") {
		$img_r = imagecreatefromjpeg($src);
		$dst_r = ImageCreateTrueColor($targ_w, $targ_h);
		imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
		imagejpeg($dst_r, "$src2", 100);
	}

	if (substr($_SESSION['namebruto_extension'], -3) == "png" || substr($_SESSION['namebruto_extension'], -3) == "PNG") {
		$img_r = imagecreatefrompng($src);
		$dst_r = imagecreatetruecolor($targ_w, $targ_h);
		imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
		imagepng($dst_r, "$src2");
	}

	if (substr($_SESSION['namebruto_extension'], -3) == "gif" || substr($_SESSION['namebruto_extension'], -3) == "GIF") {
		$img_r = imagecreatefromgif($src);
		$dst_r = imagecreatetruecolor($targ_w, $targ_h);
		imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
		imagepng($dst_r, "$src2");
	}

	///////////////////////////////UPDATE
	$sql_update = $bdd->prepare("UPDATE membres SET 
		image_profil=? 
		WHERE pseudo=?");
	$sql_update->execute(array(
		$avatar2,
		$user
	));
	$sql_update->closeCursor();

	header("location: /");
	exit();
}
////////////Upload image recadrage

////////////Upload des images
if ($actionn == "upload") {

	$icon = $_FILES['images']['name'];

	if (!empty($icon)) {

		if (substr($icon, -4) == "jpeg" || substr($icon, -4) == "JPEG" || substr($icon, -3) == "jpg" || substr($icon, -3) == "JPG" || substr($icon, -3) == "png" || substr($icon, -3) == "PNG" || substr($icon, -3) == "gif" || substr($icon, -3) == "GIF") {

			$icon = $_FILES['images']['name'];
			$taille = $_FILES['images']['size'];
			$tmp = $_FILES['images']['tmp_name'];
			$type = $_FILES['images']['type'];
			$erreur = $_FILES['images']['error'];
			$source_file = $_FILES['images']['tmp_name'];
			$destination_file = "$repertoire/$icon";
			unset($_SESSION['namebruto_extension']);
			unset($_SESSION['attributs_title_alt_image']);
			$namebrut = explode('.', $icon);
			$namebruto = $namebrut[0];
			$now = time();
			$namebruto = "$namebruto-$now";

			$nouveaucontenu = "$namebruto";
			include("$caractere_replace_repertoire");
			$namebruto = "$nouveaucontenu";

			$repertoire_move = "$repertoire_chmod/$icon";
			move_uploaded_file($tmp, $repertoire_move);

			///////////Produit en croix
			$gettsizee = getimagesize("$repertoire_chmod/$icon");
			$largeur = $gettsizee[0];
			$hauteur = $gettsizee[1];
			$newhauteur = ($largeur / $hauteur);
			$newhauteur = ($newlargeur / $newhauteur);

			///////////Produit en croix
			if (substr($icon, -4) == "jpeg" || substr($icon, -4) == "JPEG" || substr($icon, -3) == "jpg" || substr($icon, -3) == "JPG") {
				$img_bigee = imagecreatefromjpeg("$repertoire_chmod/$icon");
				$exif = exif_read_data("$repertoire_chmod/$icon");
				if (!empty($exif['Orientation'])) {
					$largeur = $gettsizee[1];
					$hauteur = $gettsizee[0];
					$newhauteur = ($largeur / $hauteur);
					$newhauteur = ($newlargeur / $newhauteur);
					switch ($exif['Orientation']) {
						case 8:
							$img_bigee = imagerotate($img_bigee, 90, 0);
							break;
						case 3:
							$img_bigee = imagerotate($img_bigee, 180, 0);
							break;
						case 6:
							$img_bigee = imagerotate($img_bigee, -90, 0);
							break;
					}
				}

				$img_miniee = imagecreatetruecolor($newlargeur, $newhauteur);
				imagecopyresampled($img_miniee, $img_bigee, 0, 0, 0, 0, $newlargeur, $newhauteur, $largeur, $hauteur);
				imagejpeg($img_miniee, "$repertoire_chmod/$namebruto.jpg", 100);
				$_SESSION['namebruto_extension'] = "" . $namebruto . ".jpg";
				$_SESSION['attributs_title_alt_image'] = "$nomphotopost";
				unlink("$repertoire_chmod/$icon");
			}
			//////////////Redimensionner en png
			if (substr($icon, -3) == "png" || substr($icon, -3) == "PNG") {
				$img_bigee = @imagecreatefrompng("$repertoire_chmod/$icon");
				$img_miniee = imagecreatetruecolor($newlargeur, $newhauteur);
				imagecopyresampled($img_miniee, $img_bigee, 0, 0, 0, 0, $newlargeur, $newhauteur, $largeur, $hauteur);
				imagepng($img_miniee, "$repertoire_chmod/$namebruto.png");
				$_SESSION['namebruto_extension'] = "$namebruto.png";
				$_SESSION['attributs_title_alt_image'] = "$nomphotopost";
				unlink("$repertoire_chmod/$icon");
				//////////////Redimensionner en png
			}

			//////////////Redimensionner en gif
			if (substr($icon, -3) == "gif" || substr($icon, -3) == "GIF") {
				$img_bigee = imagecreatefromgif("$repertoire_chmod/$icon");
				$exif = exif_read_data("$repertoire_chmod/$icon");
				if (!empty($exif['Orientation'])) {
					$largeur = $gettsizee[1];
					$hauteur = $gettsizee[0];
					$newhauteur = ($largeur / $hauteur);
					$newhauteur = ($newlargeur / $newhauteur);
					switch ($exif['Orientation']) {
						case 8:
							$img_bigee = imagerotate($img_bigee, 90, 0);
							break;
						case 3:
							$img_bigee = imagerotate($img_bigee, 180, 0);
							break;
						case 6:
							$img_bigee = imagerotate($img_bigee, -90, 0);
							break;
					}
				}
				$img_miniee = imagecreatetruecolor($newlargeur, $newhauteur);
				imagecopyresampled($img_miniee, $img_bigee, 0, 0, 0, 0, $newlargeur, $newhauteur, $largeur, $hauteur);
				imagegif($img_miniee, "$repertoire_chmod/$namebruto.gif");
				unlink("$repertoire_chmod/$icon");
				$_SESSION['namebruto_extension'] = "$namebruto.gif";
				$_SESSION['attributs_title_alt_image'] = "$nomphotopost";
				//////////////Redimensionner en gif
			}

			///////////////////////////////ACTIONS
			$now = time();
			$uploadok = "oui";
			///////////////////////////////ACTIONS

			////////////Si n'est pas une image ou si aucune image choisie
		} else {
			////////////RAPPORT JS
			?>
			<script type="text/javascript">
				toastr.error("Seulement les images sont autorisés !", "Erreur");
			</script>
			<?php
			////////////RAPPORT JS
		}
		////////////Si n'est pas une image ou si aucune image choisie

		////////////Si n'est pas une image ou si aucune image choisie
	} else {
		////////////RAPPORT JS
		?>
		<script type="text/javascript">
			toastr.error("Vous devez choisir une image !", "Erreur");
		</script>
		<?php
		////////////RAPPORT JS
	}
	////////////Si n'est pas une image ou si aucune image choisie

}
////////////Upload des images

////////////////////////////////////////RECADRAGE FORMULAIRE
if ($uploadok == "oui" && $recadrage_active == "oui" && empty($erreur_upload)) {
	?>

	<div style='width: <?php echo "$newlargeur"; ?>px; height: <?php echo "$newhauteur"; ?>px;'>
		<img src="/<?php echo "" . $repertoire_chmod . "/" . $_SESSION['namebruto_extension'] . ""; ?>" id="box"
			alt="Image" />
	</div>

	<div style='clear: both;'></div>

	<br />

	<form id='recadrage' action="/<?php echo "$page_fonction"; ?>/enregistrement/recadrage" method="post"
		onsubmit="return checkCoords();">
		<input type="hidden" id="x" name="x" />
		<input type="hidden" id="y" name="y" />
		<input type="hidden" id="w" name="w" />
		<input type="hidden" id="h" name="h" />
		<input type="submit" class="form-control btn btn-primary" name='submit_recadrage'
			value="<?php echo "SAUVEGARDER"; ?>" style="width: auto;">
	</form>

	<?php
	////////////////////////////////////////RECADRAGE FORMULAIRE

	////////////////////////////////////////UPLOAD FORMULAIRE
} else {


	if ($_GET['upload_et_recadrage_ok'] == "oui") {

		////////////RAPPORT JS
		?>
		<script type="text/javascript">
			window.location.href = "/Profil";
			toastr.success("Votre photo à été téléchargée.", "Effectué");
		</script>
		<?php
		////////////RAPPORT JS

	}

}
////////////////////////////////////////UPLOAD FORMULAIRE
?>