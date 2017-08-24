<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/23/2017
 * Time: 6:58 PM
 */

namespace Validation\Validator;


class Validator
{

    protected $request;
    private $errors;

    /**
     * Validator constructor.
     * @param $request
     */
    public function __construct($request)
    {
        $this->errors  = array();
        $this->request = $request;

    }

    /**
     * @param $rules
     * @return bool
     */
    public function validate($rules){

        return true;
    }

    /**
     * @param $value
     * @return bool
     */
    private function required($value)
    {
        return ($value != '') ? true : false;
    }

    /**
     * @param $value
     * @return bool
     */
    private function numeric($value)
    {
        return is_numeric($value) ? true : false;
    }

    /**
     * @param $value
     * @param $length
     * @return bool
     */
    private function exactLength($value, $length)
    {
        //check unicode here
        return (strlen($value) == $length) ? true : false;
    }

    /**
     * @param $value
     * @param $length
     * @return bool
     */
    private function min($value, $length)
    {
        return (strlen($value) >= $length) ? true : false;
    }

    /**
     * @param $value
     * @param $length
     * @return bool
     */
    private function max($value, $length)
    {
        return (strlen($value) <= $length) ? true : false;
    }

//    public function CharecterControl(input) {
//        var str = '/[^A-Za-z0-9 \\r\\n@£$¥èéùìòÇØøÅå\u0394_\u03A6\u0393\u0027\u0022\u039B\u03A9\u03A0\u03A8\u03A3\u0398\u039EÆæßÉ!\#$%&amp;()*+,\\./\-:;&lt;=&gt;?¡ÄÖÑÜ§¿äöñüà^{}\\\\\\[~\\]|\u20AC]*/';
//        return !new RegExp(str).test(input);
//    }

    public function phone($value){
        return true;
    }

    public function sms(){
        return true;
    }


}