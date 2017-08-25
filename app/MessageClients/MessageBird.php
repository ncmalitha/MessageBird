<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/25/2017
 * Time: 12:53 PM
 */

namespace MessageClients;


class MessageBird
{

    private $client;

    public function __construct()
    {
        $messageBirdConfigs = include dirname(__FILE__).'/../../configs/messagebird.php';
        $this->client = new \MessageBird\Client($messageBirdConfigs['api_key']);

    }

    public function hasCredit()
    {
        $balanceData = $this->client->balance->read();
        if ($balanceData->amount > 0) {
            return true;
        }
        return false;
    }

    public function sendMessage($reciepent, $originator, $message)
    {
        $message             = new \MessageBird\Objects\Message();
        $message->originator = $originator;
        $message->recipients = array($reciepent);
        $message->body       = $message;

        return $this->client->messages->create($message);

    }
}