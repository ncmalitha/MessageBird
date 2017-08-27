<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/27/2017
 * Time: 1:00 AM
 */

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../app/Models/Message.php';
require_once dirname(__FILE__) . '/../app/MessageClients/MessageBird.php';

use Models\Message;
use MessageClients\MessageBird;

// Since cron job will run only every 60 seconds min
while (true) {


    $message       = new \Models\Message();
    $queuedMessage = $message->fetchMessageFromQueue();

    if($queuedMessage) {

        try {

            $messageBirdClient = new \MessageClients\MessageBird();
            if ($messageBirdClient->hasCredit()) {
                $response = $messageBirdClient->sendMessage($queuedMessage['recipient'], $queuedMessage['originator'],
                    $queuedMessage['message']);

                if($response and $response->recipients->totalSentCount > 0) {
                    $message->updateStatus($queuedMessage['id'], 'DELIVERED');
                }
            }

        }catch( Exception $e) {

        }

        $message->updateAttempts($queuedMessage['id'], $queuedMessage['attempts'] + 1);

    }

    sleep(1);

}