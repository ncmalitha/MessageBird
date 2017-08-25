<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/22/2017
 * Time: 5:08 PM
 */

namespace Errors;

class Error
{

    public static $errors = [
        [
            'code'    => 1001,
            'Message' => 'field is required!',
        ],
        [
            'code'    => 1002,
            'Message' => 'field is not valid!',
        ],
        [
            'code'    => 1003,
            'Message' => 'field exeeds maximum length!',
        ],
        [
            'code'    => 2001,
            'Message' => 'field is not a valid phone number!',
        ],


    ];

    public static function getError($code)
    {
        foreach (self::$errors as $error) {
            if($error['code'] == $code) {
                return $error;
            }
        }
        return null;
    }
}