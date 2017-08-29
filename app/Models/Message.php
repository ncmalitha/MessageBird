<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/26/2017
 * Time: 11:01 PM
 */

namespace Models;

require_once 'Model.php';
require_once 'QueueableMessage.php';


class Message extends Model
{

    protected $table = 'messages';

    /**
     * Saves new message to the database in message_queue
     * @param $recipient
     * @param $originator
     * @param $message
     */

    public function create($recipient , $originator , $message)
    {

        $params = [
            'recipient'  => $recipient,
            'originator' => $originator,
            'message'    => $message
        ];
        $messageId = $this->db->insert($this->table, $params);

        $queuableMessage = new QueueableMessage();

        if(strlen($message) <= 160) {
            $queuableMessage->create($messageId, $recipient, $originator, $message);
        }else{

            $concetenatedMessages = $this->getConcatenatedMessage($message);

            foreach ($concetenatedMessages as $concatenatedMesssage) {

                $udh         = $concatenatedMesssage['udh'];
                $messagePart = $concatenatedMesssage['message'];
                $queuableMessage->create($messageId, $recipient, $originator, $messagePart,'CONCAT', $udh);

            }

        }

        return $messageId;
    }


    /**
     * Updates status
     * @param $id
     * @param $status
     * @return mixed
     */
    public function updateStatus($id, $status)
    {

        $setParams = [
            'status'  => $status,
        ];

        $whereParams = [
            'id' => $id
        ];
        return $this->db->update($this->table, $setParams, $whereParams);

    }


    /**
     * @param $message
     * @return array
     */
    public function getConcatenatedMessage($message)
    {

        $messageArray = [];

        if (strlen($message) != mb_strlen($message)) {
            $maxCharforMessage = 67;
        } else {
            $maxCharforMessage = 153;
        }

        $messagePiecesNo       = ceil(mb_strlen($message) / $maxCharforMessage);
        $messagePiecesHexValue = dechex($messagePiecesNo);

        if(strlen($messagePiecesHexValue) == 1) {
            $messagePiecesHexValue = "0" . $messagePiecesHexValue;
        }

        $identifyCode               = rand(0, 255);//generate random decimal number from range 0 to 255
        $identifyCodeHex            = dechex($identifyCode);//converts from decimal to hexadecimal; 2 digit, so range is  16= 10 ; ff=255
        $messageCharacterIndexStart = 0;

        for ($i = 1 ; $i <= $messagePiecesNo; $i++) {

            $messagePiece                = mb_substr($message,$messageCharacterIndexStart,$maxCharforMessage);
            $messageCharacterIndexStart += $maxCharforMessage;
            $currentMessagePartsNoHex    = dechex($i);

            if(strlen($currentMessagePartsNoHex) == 1) {
                $currentMessagePartsNoHex = "0". $currentMessagePartsNoHex;
            }

            $udh = '050003'. $identifyCodeHex . $messagePiecesHexValue . $currentMessagePartsNoHex;
//            $udh = '050003'. 'CC' . $messagePiecesHexValue . $currentMessagePartsNoHex;

            $messageArray[] = [
                'udh'     => $udh,
                'message' => $messagePiece
            ];

        }

        return $messageArray;

    }

}