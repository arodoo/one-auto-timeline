<?php
/**
 * Invitation Email Template
 * 
 * Template for sending invitation emails to insurance agencies
 * Variables available:
 * - $invitationLink: URL for accepting the invitation
 * - $nomsiteweb: Website name
 * - $constatId: ID of the constat being shared
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Invitation à accéder à un constat d'accident</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 650px;
            margin: 0 auto;
        }
        .header {
            background-color: #e3e151;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #e3e151;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class='header'>
        <h1>Invitation à accéder à un constat d'accident</h1>
        <p>Constat #<?php echo $constatId; ?></p>
    </div>
    
    <div class='content'>
        <p>Bonjour,</p>
        
        <p>Vous avez été identifié comme l'agence d'assurance pour un constat d'accident automobile soumis par l'un de nos utilisateurs.</p>
        
        <p>Pour accéder à ce constat et aux informations associées, vous devez créer un compte sur notre plateforme.</p>
        
        <p><a href='<?php echo $invitationLink; ?>' class='button'>Créer un compte</a></p>
        
        <p>Si vous avez déjà un compte, connectez-vous et accédez à la section "Constats clients" dans votre tableau de bord.</p>
        
        <p>Notez que l'accès aux constats nécessite un abonnement actif à notre plateforme.</p>
        
        <p>Ce lien d'invitation expirera dans 7 jours.</p>
        
        <p>Cordialement,<br>L'équipe <?php echo $nomsiteweb; ?></p>
    </div>
    
    <div class='footer'>
        <p>Ce message est généré automatiquement, merci de ne pas y répondre.</p>
        <p>© <?php echo date('Y'); ?> <?php echo $nomsiteweb; ?> - Tous droits réservés</p>
    </div>
</body>
</html>