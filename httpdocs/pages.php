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

if(!empty($_GET['page'])){

switch($_GET['page']){

case "page-introuvable-404":include ("function/404/404r.php");break;

//www
case "sitemap":include ("sitemap.php");break;

case "Abonnement":include ("pages/Abonnement/Abonnement.php");break;

//Plateforme
case "Plateforme":include ("pages/Plateforme/Plateforme.php");break;
case "Fiche":include ("pages/Plateforme/Fiche/fiche.php");break;

//pages / mot-de-passe-oublie
case "mot-de-passe-oublie":include ("pages/mot-de-passe-oublie/mot-de-passe-oublie.php");break;

//Contact
case "Contact":include ("pages/contact/contact.php");break;
//Page dynamique
case "page-dynamique":include ("pages/page-dynamique/page-dynamique.php");break;
//Page catégorie dynamique
case "page-categorie-dynamique":include ("pages/page-dynamique/page-categorie-dynamique.php");break;
//Pages / Blog
case "Blog":include ("pages/blog/blog.php");break;
//Newsletter
case "Desabonnement-lettre-information":include ("function/Newsletter/Desabonnement-lettre-information.php");break;
case "Abonnement-lettre-information":include ("function/Newsletter/Abonnement-lettre-information.php");break;
//Paiements
case "Panier":include ("pages/paiements/Panier/Panier.php");break;
case "Traitement-Paiement":include ("pages/paiements/Traitement-paiement-mangopay.php");break;
case "Traitements-paypal":include ("pages/paiements/Api-Paypal/Traitements.php");break;
case "Traitements":include ("pages/paiements/Traitements.php");break;
case "Traitements-informations":include ("pages/paiements/Traitements-informations.php");break;
case "Traitements-gratuit":include ("pages/paiements/Traitements-gratuit.php");break;
case "Traitements-admin":include ("pages/paiements/Traitements-admin.php");break;
case "Success":include ("pages/paiements/Panier/Panier-succeed.php");break;

//Pop-up
case "mot-de-passe-perdu":include ("pop-up/password_popup_actions.php");break;
//Confirmation inscription
case "inscription-confirmation":include ("pop-up/inscription/inscription-confirmation.php");break;

//panel / Annonces
case "Annonces":include ("pages/Annonces/Annonces.php");break;
case "Page-annonce":include ("pages/Annonces/Page-annonce.php");break;

//panel / Services
case "Services":include ("pages/Services/Services.php");break;
case "Page-service":include ("pages/Services/Page-service.php");break;
/* case "Page-service-ct":include ("pages/Services/Services-et-catgories.php");break; */

//panel / Marketplace
case "Marketplace":include ("pages/Marketplace/Marketplace.php");break;
case "Page-marketplace":include ("pages/Marketplace/Page-marketplace.php");break;

//panel / Centres-controles-techniques
case "Centres-controles-techniques":include ("pages/Centres-controles-techniques/Centres-controles-techniques.php");break;
case "Page-centre-controle-technique":include ("pages/Centres-controles-techniques/Page-centre-controle-technique.php");break;


////////////////////////////////////////////////////////////////////////////////////////////////PANEL

//panel / Liste-avatar
case "Liste-avatar":include ("panel/Liste-avatar/Liste-avatar.php");break;

//panel / Profil-professionnel
case "Profil-professionnel":include ("panel/Profil-professionnel/Profil-professionnel.php");break;

//panel / Professionnels / Mes-annonces
case "Mes-annonces":include ("panel/Professionnels/Mes-annonces/Mes-annonces.php");break;
//panel / Professionnels / Mes-annonces-ct
case "Mes-annonces-ct":include ("panel/Professionnels/Mes-annonces-ct/Mes-annonces-ct.php");break;
//panel / Professionnels / Mes-services
case "Mes-services":include ("panel/Professionnels/Mes-services/Mes-services.php");break;


//panel / Vendeurs 
case "Mon-profil-vendeur":include ("panel/Vendeurs/Mon-profil-vendeur/Mon-profil-vendeur.php");break;
case "Mon-profil-vendeur-return":include ("panel/Vendeurs/Mon-profil-vendeur/onboarding-return-handler.php");break;
case "Mes-produits":include ("panel/Vendeurs/Mes-produits/Mes-produits.php");break;
case "Mes-ventes":include ("panel/Vendeurs/Mes-ventes/Mes-ventes.php");break;
case "Mes-paiements":include ("panel/Vendeurs/Mes-paiements/Mes-paiements.php");break;

//panel / Utilisateurs 
case "Mes-annonces-client":include ("panel/Utilisateurs/Mes-annonces-client/Mes-annonces-client.php");break;
case "Mes-produits-favoris":include ("panel/Utilisateurs/Mes-produits-favoris/Mes-produits-favoris.php");break;
case "Mes-commandes":include ("panel/Utilisateurs/Mes-commandes/Mes-commandes.php");break;
case "Paiements":include ("panel/Utilisateurs/Paiements/Paiements.php");break;

//panel / Avatar
case "Avatar":include ("panel/Avatar/Avatar.php");break;
case "modifier-profil-photo":include("panel/Avatar/Avatar-profil-photo.php");break;

//panel / Profil-automobile
case "Profil-automobile":include ("panel/Profil-automobile/Profil-automobile.php");break;

//panel / Mes-devis
case "Mes-devis":include ("panel/Mes-devis/Mes-devis.php");break;

//panel / Devis
case "Devis":include ("panel/Devis/Devis.php");break;

//panel / Carte-grise
case "Carte-grise":include ("panel/Carte-grise/Carte-grise.php");break;

//panel / Mes-documents
case "Mes-documents":include ("panel/Mes-documents/Mes-documents.php");break;

//panel / Constats
case "Constats":include ("panel/Constats/Constats.php");break;
case "Constats-clients":include ("panel/Constats/constats-client.php");break;
case "Constat-amiable-accident":include ("panel/Constats/constant-form/index-file-etape.php");break;
case "Constats-pdf":include ("panel/Constats/constant-form/PDFGenerator/index.php");break;

//panel / Profil
case "Compte-modifications":include ("panel/Profil/Compte-modifications.php");break;
//panel / Profil / Confirmation-mail-telephone-ajax
case "Confirmation-mail":include ("panel/Profil/Confirmation-mail-telephone-ajax/Confirmation-mail.php");break;
//panel / Notifications
case 'Notifications': include("panel/Notifications/Notifications.php"); break;

//panel / Facturations
case "factures":include ("panel/Facturations/factures.php");break;

//panel / Messagerie
case "Messagerie":include ("panel/Messagerie/Messagerie.php");break;
case "Message":include ("panel/Messagerie/Message.php");break;

//panel / Abonnements-annuaires
case "Abonnements-annuaires":include ("panel/Abonnements-annuaires/Abonnements-annuaires.php");break;

//panel / Mes-photos
case "Mes-photos":include ("panel/Mes-photos/photos-banniere.php");break;

//panel / Favoris
case "Favoris":include ("panel/favoris/favoris.php");break;

//panel / Mon-profil
case "Mon-profil":include ("panel/Mon-profil/Mon-profil.php");break;

}

////////////////////////////////////////////////////////////////////////////////////////////////PAGE HOME

}elseif(empty($page)){
/////////////////////////////SI JSPANEL POUR PANEL ADMINISTRATEUR EN IFRAM

if(!empty($panel_admin_jspanel_index) && isset($_SESSION['7A5d8M9i4N9']) && isset($_SESSION['4M8e7M5b1R2e8s']) && isset($user) && $admin_oo == 1){
include ("$panel_admin_jspanel");
}else{
/////////////////////////////SI PAGES STATICS
include ("index-accueil.php");
}

}

?>
