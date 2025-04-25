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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user)) {
?>

<style>

.avatar {
width: 100px;
height: 100px;
margin: 10px;
cursor: pointer;
}

.profile-avatar {
border: 2px solid #20c997; /* Changez la couleur selon vos besoins */
}

</style>

<script>
$(document).ready(function() {

// Charger les avatars depuis un répertoire
$.ajax({
url: '/panel/Liste-avatar/Liste-avatar-load-avatars.php',
method: 'GET',
success: function(data) {
$('#avatars').html(data);
}
});

$(document).on('click', '.avatar', function() {
var avatarSrc = $(this).attr('src');
$.ajax({
url: '/panel/Liste-avatar/Liste-avatar-copy-avatar.php',
method: 'POST',
data: { avatar: avatarSrc },
success: function(response) {
location.reload();
}
});
});

});
</script>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
			<div id="avatars"></div>
                </div>
            </div>
        </div>
    </div>

<?php
} else {
    header("location: /");
}
?>