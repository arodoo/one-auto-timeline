<?php
ob_start();
////INCLUDES CONFIGURATIONS CMS CODI ONE
require_once('../../../Configurations_bdd.php');
require_once('../../../Configurations.php');
require_once('../../../Configurations_modules.php');
require_once('../../../Configurations_stripe_keys.php');

require_once('../../../vendor/autoload.php');
////INCLUDE FUNCTION HAUT CMS CODI ONE
$dir_fonction = "../../../";
require_once('../../../function/INCLUDE-FUNCTION-HAUT-CMS-CODI-ONE.php');

$lasturl = $_SERVER['HTTP_REFERER'];

if (isset($user)) {
    $idaction = $_POST['idaction'];

    //var_dump($line);
    $now = time();
    ///////////////////////////////SELECT
    $req_select = $bdd->prepare("SELECT * FROM plateforme_membres_commandes WHERE id=? AND id_membre=? AND statut='Panier'");
    $req_select->execute(array($idaction, $id_oo));
    $ligne_commande = $req_select->fetch();
    $req_select->closeCursor();

    ///////////////////////////////SELECT
    $req_select = $bdd->prepare("SELECT * FROM plateforme_membres_profil_paiement WHERE id_membre=? AND profil_configure='oui'");
    $req_select->execute(array($ligne_commande['id_vendeur']));
    $ligne_profil_p = $req_select->fetch();
    $req_select->closeCursor();


    $montant = $_POST['montant'];
    $montant = floatval($montant) * 100;

    if ($ligne_profil_p) {

        $customer = $stripe->balance->retrieve([], ['stripe_account' => $id_account_oo]);

        $dispo = ($customer->available[0]->amount);

        if ($dispo >= $montant) {

            $customer = $stripe->charges->create([
                'amount' => $montant,
                'currency' => 'eur',
                'source' => $id_account_oo,
                'metadata' => [
                    'order_id' => $idaction,
                ],
            ]);

            if (!empty($customer->id)) {

                ///////////////////////////////SELECT URL
                $req_select = $bdd->prepare("SELECT credits FROM membres WHERE id=?");
                $req_select->execute(array($id_oo));
                $ligne_credits = $req_select->fetch();
                $req_select->closeCursor();

                $newcredits = $ligne_credits['credits'] - ($montant / 100);
                ///////////////////////////////UPDATE PANIER GENERALE
                $sql_update = $bdd->prepare("UPDATE membres SET
credits=?
WHERE id=?");
                $sql_update->execute(array(
                    $newcredits,
                    $id_oo
                ));
                $sql_update->closeCursor();

                ///////////////////////////////SELECT
                $req_select = $bdd->prepare("SELECT * FROM plateforme_membres_commandes WHERE id=?");
                $req_select->execute(array($idaction));
                $ligne_c = $req_select->fetch();
                $req_select->closeCursor();

                $numero_commande = $ligne_c['numero_commande'];

                ///////////////////////////////SELECT
                $req_select = $bdd->prepare("SELECT * FROM membres WHERE id=?");
                $req_select->execute(array($ligne_c['id_membre']));
                $ligne_client = $req_select->fetch();
                $req_select->closeCursor();

                ///////////////////////////////SELECT
                $req_select = $bdd->prepare("SELECT * FROM configurations_pdf_devis_factures WHERE id=1");
                $req_select->execute();
                $ligne_select = $req_select->fetch();
                $req_select->closeCursor();
                $LAST_REFERENCE_FACTURE = $ligne_select['LAST_REFERENCE_FACTURE'];
                $LAST_REFERENCE_FACTURE = ($LAST_REFERENCE_FACTURE + 1);

                $sql_update = $bdd->prepare("UPDATE configurations_pdf_devis_factures SET 
 LAST_REFERENCE_FACTURE=?
 WHERE id=?");
                $sql_update->execute(array(
                    $LAST_REFERENCE_FACTURE,
                    '1'
                ));
                $sql_update->closeCursor();

                $LAST_REFERENCE_FACTURE = "FA-" . $LAST_REFERENCE_FACTURE . "";

                if ($_SESSION['remise_panier_facture'] < 1) {
                    $_SESSION['remise_panier_facture'] = 0;
                }

                $Titre_facture = 'Commission plateforme';

                $total_HT = $ligne_c['prix_commission'];
                $total_HT_net = $ligne_c['prix_commission'];
                $total_TTC = $ligne_c['prix_commission'];
                $total_TVA = 0;
                //$modepaiements = 'Carte';
                //CREATION FACTURE
                ///////////////////////////////INSERT
                $sql_insert = $bdd->prepare("INSERT INTO membres_prestataire_facture 
 (id_membre,
 pseudo,
 REFERENCE_NUMERO,
 numero_facture,
 Titre_facture,
 Contenu,
 Suivi,
 date_edition,
 departement,
 jour_edition,
 mois_edition,
 annee_edition,
 mod_paiement,
 Tarif_HT,
 Remise,
 Tarif_HT_net,
 Tarif_TTC,
 Total_Tva,
 taux_tva,
 condition_reglement,
 delai_livraison,
 code_promotion,
 Type_compte_F,
 id_devis,
 statut
 )
 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $sql_insert->execute(array(
                    $ligne_client['id'],
                    $ligne_client['pseudo'],
                    $LAST_REFERENCE_FACTURE,
                    $LAST_REFERENCE_FACTURE,
                    $Titre_facture,
                    '',
                    'payer',
                    $now,
                    '',
                    '',
                    '',
                    '',
                    $modepaiements,
                    $total_HT,
                    $remise_panier_facture,
                    $total_HT_net,
                    $total_TTC,
                    $total_TVA,
                    $Tva_coef,
                    'Immédiat',
                    $condition_livraison,
                    '',
                    '',
                    '',
                    'Activée'
                ));
                $sql_insert->closeCursor();

                ///////////////////////////////SELECT
                $req_select = $bdd->prepare("SELECT * FROM membres_prestataire_facture WHERE date_edition=?");
                $req_select->execute(array(
                    $now
                ));
                $ligne_select = $req_select->fetch();
                $req_select->closeCursor();
                $id_url = $ligne_select['id'];
                $_SESSION['LAST_REFERENCE_FACTURE'] = $LAST_REFERENCE_FACTURE;
                //////////////////////////////CREATION FACTURE
                //////////////////////////////CREATION DETAILS FACTURE
                ///////////////////////////////SELECT BOUCLE
                /*$req_boucle = $bdd->prepare("SELECT * FROM plateforme_membres_commandes_details WHERE numero_panier=? ORDER BY id ASC");
                $req_boucle->execute(array($numero_panier));
                while ($ligne_boucle = $req_boucle->fetch()){
                    $id_facture_panier_dd = $ligne_boucle['id'];
                    $id_panier_facture_details_idd = $ligne_boucle['id'];
                    $id_panier_SERVICE_PRODUIT_idd = $ligne_boucle['id_panier_SERVICE_PRODUIT'];
                    $libelled = $ligne_boucle['libelle'];
                    $PU_HTd = sprintf('%.2f', $ligne_boucle['PU_HT']);
                    $quantited = $ligne_boucle['quantite'];
                    $action_module_service_produit = $ligne_boucle['action_module_service_produit'];
                    $Duree_service = $ligne_boucle['Duree_service'];*/
                $PU_HTd = $ligne_c['prix_commission'];
                $quantited = 1;
                ///////////////////////////////INSERT
                $sql_insert = $bdd->prepare("INSERT INTO membres_prestataire_facture_details
 (id_membre,
 pseudo,
 numero_facture,
 libelle,
 PU_HT,
 quantite,
 REFERENCE_DETAIL,
 Type_detail)
 VALUES (?,?,?,?,?,?,?,?)");
                $sql_insert->execute(array(
                    $ligne_client['id'],
                    $ligne_client['pseudo'],
                    $LAST_REFERENCE_FACTURE,
                    'Commission commande ' . $numero_commande,
                    $PU_HTd,
                    $quantited,
                    '',
                    $type_detail
                ));
                $sql_insert->closeCursor();
                //}
                ///////////////////////////////UPDATE
                $sql_update = $bdd->prepare("UPDATE plateforme_membres_commandes SET 
     id_charge=?,
     statut=?,
     statut_date=?,
     date_payer=?,
     date_payer2=?
     WHERE id=?");
                $sql_update->execute(array(
                    $customer->id,
                    'Payée',
                    $now,
                    $now,
                    date('d-m-Y', $now),
                    $idaction
                ));
                $sql_update->closeCursor();



                $req_boucle2 = $bdd->prepare("SELECT * FROM plateforme_membres_commandes_details WHERE id_commande=?");
                $req_boucle2->execute(array($ligne_c['id']));
                while ($ligne_boucle2 = $req_boucle2->fetch()) {

                    ///////////////////////////////SELECT
                    $req_select = $bdd->prepare("SELECT * FROM plateforme_membres_offres_produits WHERE id=?");
                    $req_select->execute(array($ligne_boucle2['id_produit']));
                    $ligne_produit = $req_select->fetch();
                    $req_select->closeCursor();

                    $new_nbr_ventes = $ligne_produit['nombre_de_vente'] + $ligne_boucle2['quantite'];

                    ///////////////////////////////SELECT
                    $req_select = $bdd->prepare("SELECT * FROM membres WHERE id=?");
                    $req_select->execute(array($ligne_c['id_vendeur']));
                    $ligne_select = $req_select->fetch();
                    $req_select->closeCursor();

                    $produits .= "- " . $ligne_boucle2['quantite'] . " x " . $ligne_produit['nom_produit'] . " [" . $ligne_boucle2['prix_total'] . " €] <br />";

                    ///////////////////////////////UPDATE
                    $sql_update = $bdd->prepare("UPDATE plateforme_membres_offres_produits SET 
 nombre_de_vente=?
 WHERE id=?");
                    $sql_update->execute(array(
                        $new_nbr_ventes,
                        $ligne_produit['id']
                    ));
                    $sql_update->closeCursor();

                    $numero_transaction = $now;

                    ///////////////////////////////INSERT
                    $sql_insert = $bdd->prepare("INSERT INTO plateforme_historiques_transactions
     (id_membre,
     pseudo,
     numero_transaction,
     categorie_transaction,
     sous_categorie_transaction,
     destinataire,
     reference,
     montant,
     date,
     mois,
     année,
     id_vente,
     id_membre_vente,
     pseudo_id_vente
     )
     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                    $sql_insert->execute(array(
                        $ligne_select['id'],
                        $ligne_select['pseudo'],
                        $numero_transaction,
                        'Ventes',
                        'Ventes',
                        $ligne_select['pseudo'],
                        $now,
                        $ligne_boucle2['prix_total'],
                        $now,
                        date('m', $now),
                        date('Y', $now),
                        $ligne_c['id'],
                        $ligne_client['id'],
                        $ligne_client['pseudo']
                    ));
                    $sql_insert->closeCursor();

                }
                $req_boucle2->closeCursor();



                /*$new_credits = $ligne_select['credits'] + $ligne_boucle['prix_total'] + $ligne_boucle['prix_frais_port'];
                ///////////////////////////////UPDATE
                $sql_update = $bdd->prepare("UPDATE membres SET 
                credits=?
                WHERE id=?");
                $sql_update->execute(array(
                $new_credits,
                $ligne_boucle['id_vendeur']));                     
                $sql_update->closeCursor();*/

                $mail_destinataire = $ligne_select['mail'];
                $notif_mail_destinataire = $ligne_select['Mail_nouvelle_commande'];
                $nom_destinataire = $ligne_select['nom'];
                $pseudo_destinataire = $ligne_select['pseudo'];
                $prenom_destinataire = $ligne_select['prenom'];

                ///////////////////////////////SELECT
                $req_select = $bdd->prepare("SELECT * FROM membres WHERE id=?");
                $req_select->execute(array($ligne_c['id_membre']));
                $ligne_acheteur = $req_select->fetch();
                $req_select->closeCursor();

                $pseudo_acheteur = $ligne_acheteur['pseudo'];
                $mail_acheteur = $ligne_acheteur['mail'];
                $notif_mail_acheteur = $ligne_acheteur['Mail_nouvelle_commande'];
                $nom_acheteur = $ligne_acheteur['nom'];
                $pseudo_acheteur = $ligne_acheteur['pseudo'];
                $prenom_acheteur = $ligne_acheteur['prenom'];
                ///////////////////////////////SELECT
                $req_select = $bdd->prepare("SELECT * FROM plateforme_membres_adresses WHERE id=?");
                $req_select->execute(array($ligne_c['id_adresse_livraison']));
                $ligne_adresse = $req_select->fetch();
                $req_select->closeCursor();

                $adresse_acheteur = $ligne_adresse['adresse'];
                $ville_acheteur = $ligne_adresse['ville'];
                $code_postale_acheteur = $ligne_adresse['code_postale'];
                $pays_acheteur = $ligne_adresse['pays'];
                $adresse_complement_acheteur = $ligne_adresse['adresse_complement'];

                $prixtotal = $ligne_c['prix_total'] + $ligne_c['prix_frais_port'];
                $rix_commission = $ligne_c['prix_commission'];
                $prixtotal_sans_commission = $ligne_c['prix_total_sans_commission'];

                if (!empty($mail_destinataire) && $notif_mail_destinataire != "non") {

                    //////////////////////////////////////Mail DESTINATAIRE
                    $de_nom = "$nomsiteweb"; //Nom de l'envoyeur
                    $de_mail = "$emaildefault"; //Email de l'envoyeur
                    $vers_nom = "$prenom_destinataire $nom_destinataire"; //Nom du receveur
                    $vers_mail = "$mail_destinataire"; //Email du receveur
                    $sujet = "Nouvelle commande payée sur $nomsiteweb";

                    $message_principalone = "
   Objet : $sujet <br /><br />
   Bonjour $pseudo_destinataire, <br />
   La commande $numero_commande a été payée par <b>$pseudo_acheteur</b>.<br />
   Merci de bien vouloir préparer et expédier sa commande au plus vite, en utilisant le mode de livraison qu'il aura choisi, et de CONFIRMER L'ENVOI sur Vendstesjeux.fr (Dans le menu MES VENTES / Mes ventes / Payées / ID de la commande: [ID de commande]).
   <a href='" . $http . "" . $nomsiteweb . "/Mes-ventes/Fiche/" . $ligne_c['id'] . "'>Clic ici</a><br /><br />
 
   Si tu ne respectes pas le délai de 7 jours (ou 7 jours après la sortie d'un article en cas de précommande) pour envoyer ou confirmer l’envoi, la commande sera automatiquement annulée et l'acheteur sera remboursé intégralement.<br /><br />
 
   Adresse de l'acheteur: <br />
   $adresse_acheteur<br />
 $ville_acheteur<br />
 $code_postale_acheteur<br />
 $pays_acheteur<br />
 $adresse_complement_acheteur<br /><br />
 
 <b>Détail de la commande:</b><br /><br />
 
 $produits<br />
 -----------------<br /><br />
 
 Valeur totale de la vente: $prixtotal EUR<br />
 Commission plateforme: $rix_commission EUR<br />
 Valeur nette de la vente: $prixtotal_sans_commission EUR <br /><br />
 
 Cordialement,<br />
 L'équipe Vendstesjeux
   ";

                    mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);
                    //////////////////////////////////////Mail DESTINATAIRE
                }


                //////////////////////////////////////Mail DESTINATAIRE
                $de_nom = "$nomsiteweb"; //Nom de l'envoyeur
                $de_mail = "$emaildefault"; //Email de l'envoyeur
                $vers_nom = "$prenom_acheteur $nom_acheteur"; //Nom du receveur
                $vers_mail = "$mail_acheteur"; //Email du receveur
                $sujet = "Nouvelle commande payée sur $nomsiteweb";

                $message_principalone = "
   Objet : $sujet <br /><br />
   Bonjour $pseudo_acheteur, <br />
   Nous te remercions de ta commande sur vendstesjeux.fr ! <br />
 
   Votre commande $numero_commande du vendeur $pseudo_destinataire a bien été réglée. <br /><br />
 
   Le vendeur $pseudo_destinataire vient d'être informé du règlement de ta commande et devra désormais la préparer et te l'envoyer.<br />
 Si l'envoi n'est pas confirmé sous 7 jours (ou 7 jours après la sortie d'un article en cas de précommande), la commande sera automatiquement annulée et tu seras remboursé intégralement.<br /><br />
 
 <b>Détail de la commande:</b><br /><br />
 
 $produits<br />
 -----------------<br /><br />
 
 Valeur totale de la commande: $prixtotal EUR<br /><br />
 
 Cordialement,<br />
 L'équipe Vendstesjeux
   ";

                mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);

                ////////////////MAIL ADMINISTRATEUR
                $de_nom = "$pseudo_acheteur"; //Nom de l'envoyeur
                $de_mail = "$mail_acheteur"; //Email de l'envoyeur
                $vers_nom = "$nomsiteweb"; //Nom du receveur
                $vers_mail = "$emaildefault"; //Email du receveur
                $sujet = "Nouvelle commande payée sur $nomsiteweb"; //Sujet du mail
                $message_principalone = "<b>Objet:</b> $sujet<br /><br />
     Bonjour, <br />
     Une commande a été payée .<br /><br />
     <b>Voici les informations du acheteur concerné :</b><br /><br />
     Pseudo client : $pseudo_acheteur <br />
 
     <b>Détails de la commande:</b><br /><br />
 
 $produits<br /><br />
 
 Valeur totale de la vente: $prixtotal EUR<br />
 Commission plateforme: $rix_commission EUR<br />
 
 
 
 
     Cordialement, l'équipe<br /><br />";
                mailsend($vers_mail, $vers_nom, $de_mail, $de_nom, $sujet, $message_principalone);



                $result = array("Texte_rapport" => "Paiement effectué !", "retour_validation" => "ok", "retour_lien" => "/Panier/Success");
            }

        }



    } else {
        $result = array("Texte_rapport" => "Le vendeur n'as pas configure son profil paiement", "retour_validation" => "", "retour_lien" => "");
    }

    $result = json_encode($result);
    echo $result;
}

?>