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

if (!empty($_SESSION['4M8e7M5b1R2e8s']) && !empty($user))
{

    $nowtime = date('d-m-Y');

    require 'paypal.php';

    $paypal = new Paypal();

    $response = $paypal->request('GetExpressCheckoutDetails', array(
        'TOKEN' => $_GET['token']
    ));

    if ($response)
    {

        ///////////////////////////////////////////PAIEMENT DEJA VALIDE
        if ($response['CHECKOUTSTATUS'] == 'PaymentActionCompleted')
        {
            //die('Ce paiement a déja été validé !');
            ////////////RAPPORT JS
            
?>
<script language="javascript" type="text/javascript">
alert("<?php echo "Ce montant est déjà validé !"; ?>");
</script>
<?php
            ////////////RAPPORT JS
            
        }
        ///////////////////////////////////////////PAIEMENT DEJA VALIDE
        
    }
    else
    {
        //var_dump($paypal->errors);
        //die();
        ////////////RAPPORT JS
        
?>
<script language="javascript" type="text/javascript">
alert("<?php echo "Il y a eu un problème, veuillez contacter le service client !"; ?>");
</script>
<?php
        ////////////RAPPORT JS
        
    }

    $params = array(
        'TOKEN' => $_GET['token'],
        'PAYERID' => $_GET['PayerID'],
        'PAYMENTACTION' => 'Sale',
        'PAYMENTREQUEST_0_AMT' => $_SESSION['total_TTC'] + $_SESSION['port'],
        'PAYMENTREQUEST_0_CURRENCYCODE' => 'EUR',
        'PAYMENTREQUEST_0_SHIPPINGAMT' => $_SESSION['port'],
        'PAYMENTREQUEST_0_ITEMAMT' => $_SESSION['total_TTC'],
    );

    ////////////////////////////////////////////////////////////////LISTE DES LIBELLES PANIER OU FACTURE
    ///////////////////////////////SELECT BOUCLE
    $req_boucle = $bdd->prepare("SELECT * FROM " . $_SESSION['table_liste_detail'] . "");
    $req_boucle->execute();
    while ($ligne_boucle = $req_boucle->fetch())
    {
        $id_facture_panier_dd = $ligne_boucle['id'];
        $id_panier_facture_details_idd = $ligne_boucle['id'];
        $libelled = utf8_encode($ligne_boucle['libelle']);
        $PU_HTd = sprintf('%.2f', $ligne_boucle['PU_HT']);
        $quantited = $ligne_boucle['quantite'];
        $PU_HT_totald = sprintf('%.2f', (($PU_HT * $quantited) + $PU_HT_total));
        $PU_TTC_totald = sprintf('%.2f', $PU_HT_totald * $Tva_coef);
        $PU_HT_total_panierd = sprintf('%.2f', ($PU_HT_totald + $PU_HT_totald));
        $k++;
        $params["L_PAYMENTREQUEST_0_NAME$k"] = $libelled;
        $params["L_PAYMENTREQUEST_0_DESC$k"] = '';
        $params["L_PAYMENTREQUEST_0_AMT$k"] = $PU_TTC_totald;
        $params["L_PAYMENTREQUEST_0_QTY$k"] = $quantited;
    }
    $req_boucle->closeCursor();
    ////////////////////////////////////////////////////////////////LISTE DES LIBELLES PANIER OU FACTURE
    $response = $paypal->request('DoExpressCheckoutPayment', $params);

    /////////////////////////////////PAIEMENT EFFECTUE AVEC SUCCES
    if ($response)
    {

        var_dump($response);
        $response['PAYMENTINFO_0_TRANSACTIONID'];

        //////////////ATTRIBUTION SERVICE OU ACTIONS METIER
        if ($_SESSION['total_TTC'] == $response["PAYMENTINFO_0_AMT"])
        {
            $modepaiements = "Paypal";
            include ('pages/paiements/Traitements-actions.php');
        }
        else
        {
            ////////////RAPPORT JS
            
?>
<script language="javascript" type="text/javascript">
alert("<?php echo "Le montant correspond pas !"; ?>");
</script>
<?php
            ////////////RAPPORT JS
            
        }
        //}
        //////////////ATTRIBUTION SERVICE OU ACTIONS METIER
        /////////////////////////////////PAIEMENT EFFECTUE AVEC SUCCES
        //echo "".$_SESSION['total_TTC']." == ".$response['PAYMENTINFO_0_AMT']."";
        /////////////////////////////////PAIEMENT Refusé
        
    }
    else
    {
        //var_dump($paypal->errors);
        ////////////RAPPORT JS
        
?>
<script language="javascript" type="text/javascript">
alert("<?php echo "Il y a eu un problème, veuillez contacter le service client !"; ?>");
</script>
<?php
        ////////////RAPPORT JS
        
    }
    /////////////////////////////////PAIEMENT Refusé
    

    
}
else
{
    //header("location: /");
    
}
?>
