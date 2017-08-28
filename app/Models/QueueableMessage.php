<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/28/2017
 * Time: 5:17 PM
 */

namespace Models;

require_once 'Model.php';

class QueueableMessage extends Model
{

    /**Saves new message to the database in message_queue
     * @param $recipient
     * @param $originator
     * @param $message
     * @param string $type
     * @param null $udh
     * @return mixed
     */

    public function create($messageId, $recipient , $originator , $message, $type = 'DEFAULT', $udh = null)
    {

        $params = [
            'message_id' => $messageId,
            'recipient'  => $recipient,
            'originator' => $originator,
            'message'    => $message,
            'type'       => $type,
            'udh'        => $udh,
        ];
        return $this->db->insert('message_queue', $params);
    }

    /**
     * Updates number of attempts
     * @param $id
     * @param $attempts
     * @return mixed
     */
    public function updateAttempts($id, $attempts)
    {

        $setParams = [
            'attempts'  => $attempts,
        ];

        $whereParams = [
            'id' => $id
        ];
        return $this->db->update('message_queue', $setParams, $whereParams);

    }

    /**
     * @param $id
     * @param $status
     * @param null $messageBirdId
     * @return mixed
     */
    public function update($id, $status, $messageBirdId = null)
    {

        $setParams = [
            'status'  => $status,
        ];

        if($messageBirdId) {
            $setParams['messagebird_id'] = $messageBirdId;
        }

        $whereParams = [
            'id' => $id
        ];
        return $this->db->update('message_queue', $setParams, $whereParams);

    }

    /**
     * Fetches the first top message to be sent MessageBird
     */
    public function fetchMessageFromQueue()
    {
        $whereParams = [
            'status'  => 'PENDING',
        ];
        $orderBy = 'attempts';
        return $this->db->first('message_queue', $whereParams, $orderBy);

    }
}