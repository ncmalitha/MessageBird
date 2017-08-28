<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/25/2017
 * Time: 12:53 PM
 */

namespace MessageClients;


use MessageBird\Objects\Message;

class MessageBird
{

    private $client;
    public $sleepTime;

    /**
     * MessageBird constructor.
     */
    public function __construct()
    {
        $messageBirdConfigs = include dirname(__FILE__).'/../../configs/messagebird.php';
        $this->client       = new \MessageBird\Client($messageBirdConfigs['api_key']);
        $this->sleepTime    = $messageBirdConfigs['waiting_time'];

    }

    /**
     * Returns if account has credit
     * @return bool
     */
    public function hasCredit()
    {
        $balanceData = $this->client->balance->read();
        if ($balanceData->amount > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param $reciepent
     * @param $originator
     * @param $smsText
     * @return \MessageBird\Objects\Balance|\MessageBird\Objects\Hlr|\MessageBird\Objects\Lookup|\MessageBird\Objects\Message|\MessageBird\Objects\Verify|\MessageBird\Objects\VoiceMessage
     */
    public function sendMessage($reciepent, $originator, $smsText)
    {
        $message             = new \MessageBird\Objects\Message();
        $message->originator = $originator;
        $message->recipients = array($reciepent);
        $message->body       = $smsText;
        $message->datacoding = 'auto';

        return $this->client->messages->create($message);

    }

    /**
     * @param $reciepent
     * @param $originator
     * @param $smsText
     * @param $udh
     * @return \MessageBird\Objects\Balance|\MessageBird\Objects\Hlr|\MessageBird\Objects\Lookup|Message|\MessageBird\Objects\Verify|\MessageBird\Objects\VoiceMessage0.
     */
    public function sendConcatenatedMessage($reciepent, $originator, $smsText, $udh)
    {
        $message             = new \MessageBird\Objects\Message();
        $message->originator = $originator;
        $message->recipients = array($reciepent);
        $message->type       = 'binary';
        $message->datacoding = 'auto';

        $message->typeDetails['udh'] = $udh;
        $message->body               = $smsText;

        return $this->client->messages->create($message);


    }

}