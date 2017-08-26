<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/26/2017
 * Time: 11:01 PM
 */

namespace Models;

require_once dirname(__FILE__) . '/../DB/DB.php';

use DB\DB;

class Message
{

    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

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
        return $this->db->insert('messages', $params);
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
        return $this->db->update('messages', $setParams, $whereParams);

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
        return $this->db->update('messages', $setParams, $whereParams);

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
        return $this->db->first('messages', $whereParams, $orderBy);

    }

}