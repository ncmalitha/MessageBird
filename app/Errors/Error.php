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
            'code'    => 2001,
            'Message' => 'field is not valid!',
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