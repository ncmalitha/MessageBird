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
//        $hex = '';
//        for ($i=0; $i < mb_strlen($str); $i++){
//            $character = mb_substr($str, $i, $i+1);
//            $hex      .= dechex(ord($character));
//        }

        $hex = unpack("H*",$str);

        return $hex[1];


    }

}