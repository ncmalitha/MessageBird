<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/27/2017
 * Time: 1:00 AM
 */

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../app/Models/QueueableMessage.php';
require_once dirname(__FILE__) . '/../app/MessageClients/MessageBird.php';

use Models\QueueableMessage;
use MessageClients\MessageBird;

// Since cron job will run only every 60 seconds min
while (true) {


    $message           = new \Models\QueueableMessage();
    $queuedMessage     = $message->fetchMessageFromQueue();
    $messageBirdClient = new \MessageClients\MessageBird();

print_r($queuedMessage);
    if($queuedMessage) {

        try {

            if ($messageBirdClient->hasCredit()) {
                if($queuedMessage['type'] == 'DEFAULT') {
                    $response = $messageBirdClient->sendMessage($queuedMessage['recipient'], $queuedMessage['originator'],
                        $queuedMessage['message']);
                }
                else {
                    $response = $messageBirdClient->sendConcatenatedMessage($queuedMessage['recipient'], $queuedMessage['originator'],
                        $queuedMessage['message'], $queuedMessage['udh']);
                    print_r($response);
                }


                if($response and $response->recipients->totalSentCount > 0) {
                    $message->update($queuedMessage['id'], 'SENT');
                }
            }

        }catch( Exception $e) {
            print_r($e->getMessage() .' '. $e->getLine());
            // log here
        }

        $message->updateAttempts($queuedMessage['id'], $queuedMessage['attempts'] + 1);

    }

    sleep($messageBirdClient->sleepTime);

}