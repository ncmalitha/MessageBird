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

    /**
     * Saves new message to the database in message_queue
     * @param $recipient
     * @param $originator
     * @param $message
     */
    public function create($recipient , $originator , $message)
    {

        $db = DB::getInstance();

        $params = [
            'recipient'  => $recipient,
            'originator' => $originator,
            'message'    => $message
        ];
        $db->insert('messages', $params);
    }

    /**
     * Updates number of attempts
     * @param $id
     */
    public function updateAttempts($id)
    {

    }

    /**
     * Fetches the first top message to be sent MessageBird
     */
    public function fetchMessageFromQueue()
    {

    }

}