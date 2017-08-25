<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/24/2017
 * Time: 12:57 PM
 */

namespace Http\Requests;

use Http\Response\Response;
use Validation\Validator;

class SendSMSRequest extends Request
{

    public function isValid()
    {
        $rules = [
            'recipient'  => 'required|phone',
            'originator' => 'required|originator',
            'message'    => 'required|sms',
        ];

        $validator = new Validator($this);
        $result = $validator->validate($rules);

        if (!$result) {
            $response = new Response(422, $validator->getErrors());

            $response->sendJSON();
        }

        return $result;
    }

}