<?php
require_once('../../Configurations_bdd.php');
require_once('../../Configurations.php');
require_once('../../Configurations_stripe_keys.php');
require_once('../../vendor/autoload.php');

// Check if user is logged in
if (!empty($user)) {
    try {
        // Get subscription_id from database
        $stmt = $bdd->prepare("SELECT subscription_id FROM membres WHERE id = ?");
        $stmt->execute(array($id_oo));
        $result = $stmt->fetch();
        
        if (!$result || !$result['subscription_id']) {
            throw new Exception('No active subscription found');
        }

        // Cancel at period end
        $subscription = $stripe->subscriptions->update(
            $result['subscription_id'],
            ['cancel_at_period_end' => true]
        );

        // Store cancellation details
        $stmt = $bdd->prepare("UPDATE membres SET 
            cancel_scheduled = ?, 
            cancellation_date = ?, 
            subscription_end_date = ? 
            WHERE id = ?");
        $stmt->execute(array(
            'oui',
            date('d-m-Y'),
            date('d-m-Y', $subscription->current_period_end),
            $id_oo
        ));

        echo json_encode([
            'status' => 'success', 
            'message' => 'Subscription will end at the current billing period',
            'end_date' => date('d-m-Y', $subscription->current_period_end)
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
}