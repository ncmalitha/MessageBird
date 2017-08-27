<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/22/2017
 * Time: 12:26 PM
 */

require_once 'app/Http/Response/Response.php';
require_once 'app/Errors/Error.php';
require_once 'app/Validation/Validator.php';
require_once 'app/Http/Requests/Request.php';
require_once 'app/Http/Requests/SendSMSRequest.php';
require_once 'app/Http/Router.php';
require_once 'app/MessageClients/MessageBird.php';
require_once 'app/Models/Message.php';
require_once 'vendor/autoload.php';

use Http\Response\Response;
use Http\Requests\SendSMSRequest;

$validRoute = \Http\Router\Router::validate();

switch ($validRoute['route']['name']) {
    case 'sms_send_v1':

        $request = new SendSMSRequest();
        if($request->isValid()){

            $message   = new \Models\Message();
            $messageId = $message->create($request->recipient, $request->originator, $request->message);
            $response  = new Response(200, [
                'status' => 'SUCCESS',
                'id'     => $messageId,
            ]);
            // Ideally should send  201 Response and need to have another endpoint that verifies/confirms the message delivery
            $response->sendJSON();
        }


        break;
    case 'sms_send_v2':
        // do something
        break;

    default:
        die();
}


