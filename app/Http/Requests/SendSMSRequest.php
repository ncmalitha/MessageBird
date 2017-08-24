<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/24/2017
 * Time: 12:57 PM
 */

namespace Http\Requests;

use Validation\Validator\Validator;

class SendSMSRequest extends Request
{

    public function isValid()
    {
        $rules =[
            'recipient'  => 'required|phone',
            'originator' => 'required',
            'message'    => 'required',
        ];

        $validator = new Validator($this);
    }

}