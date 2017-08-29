<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/28/2017
 * Time: 8:02 PM
 */

namespace Utils;

class Utils
{

    public static function convertToHex($str)
    {

        $hex = unpack("H*",$str);
        return $hex[1];

    }

}