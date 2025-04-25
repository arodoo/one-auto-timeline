<?php
$radiusKm = 30; // Rayon en kilomètres

function getDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371; // rayon de la Terre en kilomètres
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    $latDiff = $lat2 - $lat1;
    $lonDiff = $lon2 - $lon1;
    $angle = 2 * asin(sqrt(pow(sin($latDiff / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($lonDiff / 2), 2)));

    return round($angle * $earthRadius, 1);
}

$latitudeMission = $_SESSION['lat'] ?? 47.9108329; // Valeur par défaut si non définie
$longitudeMission = $_SESSION['lng'] ?? 1.9157977; // Valeur par défaut si non définie

$idaction = $_GET['idaction'];
$idactionn = $_GET['idactionn'];
$categorie = $_GET['categorie'];

$_SESSION['filtre_par'] = "all";
$_SESSION['filtre_par_sql1'] = "ORDER by m.id DESC";
$_SESSION['sql_requete'] = "";
$_SESSION['params'] = [];

// FILTRES
$req_filtres = $bdd->prepare("
SELECT m.*, mp.*, mpf.url_profil
FROM membres m
JOIN membres_professionnel mp ON m.id = mp.id_membre
JOIN membres_profils mpf ON m.id = mpf.id_membre
WHERE m.activer = 'oui' 
AND m.statut_compte = 2 
AND m.abonnement = 'oui'
AND mpf.activer = 'oui'
" . $_SESSION['sql_requete'] . " 
" . $_SESSION['filtre_par_sql1'] . "
");
$req_filtres->execute(); //$_SESSION['params']

$etablissements = [];
while ($ligne_boucle = $req_filtres->fetch()) {
    $longitude = $ligne_boucle['longitude'] ?: 0.00; // VALEUR PAR DEFAUT POUR NE PAS FAIRE BUGUER LA FONCTION DE CALCUL DE DISTANCE
    $latitude = $ligne_boucle['latitude'] ?: 0.00; // IDEM

    if (!empty($latitudeMission) && !empty($longitudeMission) && !empty($latitude) && !empty($longitude)) {
        $kmToMe = getDistance(floatval($latitudeMission), floatval($longitudeMission), floatval($latitude), floatval($longitude));
    }
    $ligne_boucle['distance'] = $kmToMe;
    if (!empty($kmToMe) && $kmToMe <= $radiusKm) { // ON AFFICHE QUE LES ETABLISSEMENTS A MOINS DE $radiusKm km
        $etablissement_oui = "oui";
        array_push($etablissements, $ligne_boucle);
    }
}
$req_filtres->closeCursor();

if ($statut_compte_oo == 1) {
    $distances = array_column($etablissements, 'distance');
    array_multisort($distances, SORT_ASC, $etablissements);
}

///////////////////////////////SELECT
$req_selectfd = $bdd->prepare("SELECT * FROM membres_devis WHERE id_membre_utilisateur=? AND id_membre_depanneur=? AND TIMESTAMPDIFF(SECOND, date_demande, NOW()) < 86400");
$req_selectfd->execute(array($id_oo, $ligne_boucle['id_membre']));
$ligne_selectfd = $req_selectfd->fetch();
$req_selectfd->closeCursor();
$id_devis_existe = $ligne_selectfd['id'];
?>
<section style="margin-top: 0px; padding-top: 0px;">
    <div class="container">
        <div class="row justify-content-center">
            <?php
            if (empty($etablissement_oui)) { ?>
                <div class="alert alert-warning" style="text-align: center; width: 100%;">
                    Il n'y a aucun résultat pour votre recherche.<br />
                </div>
            <?php } else {
                foreach ($etablissements as $ligne_boucle) {
                    $id_etablissement = $ligne_boucle['id'];
                    $distance = $ligne_boucle['distance'];
                    // Construir URL absoluta
                    $url_profil = 'https://mon-espace-auto.com' . $ligne_boucle['url_profil'];
                    ?>
                    <div class="popupselectionextra col-lg-4 col-md-6 col-sm-12 col-xs-12"
                        style="margin-bottom: 10px; cursor: pointer;" data-id="<?php echo $ligne_boucle['id_membre']; ?>">
                        <div class="single_menu_product" style="padding: 15px; position: relative;">
                            <div class="row">
                                <div class="profile-icon">
                                <a href="<?php echo $url_profil; ?>" target="_blank" title="Visiter le profil">
                                        <span class="uk-icon-user"></span>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 text-center" style="position: relative;">
                                    <?php if (!empty($ligne_boucle['image_profil'])) { ?>
                                        <img class="imageRadius"
                                            src="/images/membres/<?php echo $ligne_boucle['pseudo']; ?>/<?php echo $ligne_boucle['image_profil']; ?>"
                                            alt="<?php echo $ligne_boucle['image_profil']; ?>" style="width: 100%; height: auto;">
                                    <?php } else { ?>
                                        <img class="imageRadius" src="/images/profile/1.jpg" alt="1.jpg"
                                            style="width: 100%; height: auto;">
                                    <?php } ?>
                                    <?php if (!empty($distance)) { ?>
                                    <?php } ?>
                                </div>
                                <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                                    <div class="menu_product_info" style="font-size: 16px; padding: 5px; padding-top: 0px;">
                                        <h4 class="selectionextra" data-id="<?php echo $ligne_boucle['id_membre']; ?>"
                                            title="<?php echo $ligne_boucle['ville']; ?> <?php echo $ligne_boucle['cp']; ?>"
                                            style="font-size: 18px;  padding-bottom: 0px; margin-bottom: 0px;">
                                            <?php if (!empty($ligne_boucle['Nom_societe'])) {
                                                echo $ligne_boucle['Nom_societe'];
                                            } else {
                                                echo $ligne_boucle['prenom'];
                                                echo " ";
                                                echo $ligne_boucle['nom'];
                                            } ?>
                                        </h4>
                                        <p style="margin-bottom: 0px; font-size: 16px;">
                                            <a href="#"
                                                title="<?php echo $ligne_boucle['ville']; ?> <?php echo $ligne_boucle['cp']; ?>"
                                                onclick="return false;"> <?php echo $ligne_boucle['ville']; ?>
                                                <?php echo $ligne_boucle['cp']; ?></a>
                                        </p>
                                        <?php
                                        if (!empty($kmToMe)) {
                                            echo "<span class='label label-success'>$distance KM</span>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
                                    <div style="margin-top: 10px; padding: 5px;" class="btn btn-default btn-c2"
                                        data-id="<?php echo $ligne_boucle['id_membre']; ?>">
                                        <?php if ($ligne_select['statut'] == "oui") {
                                            echo "<span class='uk-icon-check'></span>";
                                        } ?>
                                        <?php if ($ligne_select['statut'] == "non") {
                                            echo "<span class='uk-icon-times'></span>";
                                        } ?>
                                        <?php if (!empty($id_selectionner_oui) && $ligne_select['statut'] == "") {
                                            echo "<span class='uk-icon-check' style='color: #b31c1c;'></span>";
                                        } ?>
                                        Demande de devis
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
</section>

<style>
    .profile-icon {
        display: flex;
        justify-content: end;
        font-size: 24px;
    }

    .uk-icon-user::before {
        content: '\f007';
        color: #e3e151;
        z-index: 12;
    }

    .profile-icon a {
        color: inherit;
        text-decoration: none;
    }
    @media (max-width: 768px) {
        .menu_product_info {
            justify-content: center;
            display: flex;
        }
    }
</style>