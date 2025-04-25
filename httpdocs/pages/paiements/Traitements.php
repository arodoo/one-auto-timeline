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

if(isset($user)){

    /**
     * Vérification PayPal
     */
    $nowtime = date('d-m-Y');

    require 'Api-Paypal/paypal.php';

    $paypal = new Paypal();

    $response = $paypal->request('GetExpressCheckoutDetails', array(
        'TOKEN' => $_GET['token']
    ));

    if ($response){

        ///////////////////////////////////////////PAIEMENT DEJA VALIDE
        if ($response['CHECKOUTSTATUS'] == 'PaymentActionCompleted'){
            ////////////RAPPORT JS
            
            ?>
            <script language="javascript" type="text/javascript">
            alert("<?php echo "Ce montant est déjà validé !"; ?>");
            $(location).attr("href", "/");
            </script>
            <?php
            ////////////RAPPORT JS
            
        }
        ///////////////////////////////////////////PAIEMENT DEJA VALIDE
        
    }else{
        ////////////RAPPORT JS
        
        ?>
        <script language="javascript" type="text/javascript">
        alert("<?php echo "Il y a eu un problème, veuillez contacter le service client !"; ?>");
        $(location).attr("href", "/");
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

    $response = $paypal->request('DoExpressCheckoutPayment', $params);

    /////////////////////////////////PAIEMENT EFFECTUE AVEC SUCCES
    if ($response)
    {
        $response['PAYMENTINFO_0_TRANSACTIONID'];

        //////////////ATTRIBUTION SERVICE OU ACTIONS METIER
        if ($_SESSION['total_TTC'] == $response["PAYMENTINFO_0_AMT"])
        {
            $modepaiements = "Paypal";
            include('Traitements-actions.php');
        }
        else
        {
            ////////////RAPPORT JS
                    
            ?>
            <script language="javascript" type="text/javascript">
            alert("<?php echo "Le montant correspond pas !"; ?>");
	    $(location).attr("href", "/");
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
        ////////////RAPPORT JS
               
       ?>
       <script language="javascript" type="text/javascript">
	$(location).attr("href", "/");
       </script>
       <?php
        ////////////RAPPORT JS
               
    }
    /////////////////////////////////PAIEMENT Refusé
}
else
{
    header("location: /");
    
}
?>