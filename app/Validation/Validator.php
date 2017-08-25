<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/23/2017
 * Time: 6:58 PM
 */

namespace Validation;

use Errors\Error;

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
        foreach ($rules as $field => $rule) {

            $subrules = explode('|', $rule);

            foreach ($subrules as $subrule) {
                if(isset($this->request->{$field})) {
                    $this->errors[$field] = Error::getError(1001);
                }else {
                    $validation = $this->{$subrule}($this->request->{$field});

                    if(!$validation['status']) {
                        $this->errors[$field] = Error::getError($validation['error_code']);
                    }

                }
                if($this->errors[$field]){
                    break;
                }

            }

        }
        if(count($this->errors)){
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return [
            'errors' => $this->errors
        ];
    }

    /**
     * @param $value
     * @return array
     */
    private function required($value)
    {
        if ($value != '') {
            return ['status' => true];
        }
        return [
            'status' => false,
            'error_code' => 1001
        ];
    }

    /**
     * @param $value
     * @return array
     */
    private function numeric($value)
    {
        if (is_numeric($value)) {
            return ['status' => true];
        }
        return [
            'status' => false,
            'error_code' => 1001
        ];
    }

    /**
     * @param $value
     * @param $length
     * @return array
     */
    private function exactLength($value, $length)
    {
        //check unicode here
        if (strlen($value) == $length) {
            return ['status' => true];
        }
        return [
            'status' => false,
            'error_code' => 1001
        ];
    }

//    /**
//     * @param $value
//     * @param $length
//     * @return bool
//     */
//    private function min($value, $length)
//    {
//        return (strlen($value) >= $length) ? true : false;
//    }
//
//    /**
//     * @param $value
//     * @param $length
//     * @return bool
//     */
//    private function max($value, $length)
//    {
//        return (strlen($value) <= $length) ? true : false;
//    }

//    public function CharecterControl(input) {
//        var str = '/[^A-Za-z0-9 \\r\\n@£$¥èéùìòÇØøÅå\u0394_\u03A6\u0393\u0027\u0022\u039B\u03A9\u03A0\u03A8\u03A3\u0398\u039EÆæßÉ!\#$%&amp;()*+,\\./\-:;&lt;=&gt;?¡ÄÖÑÜ§¿äöñüà^{}\\\\\\[~\\]|\u20AC]*/';
//        return !new RegExp(str).test(input);
//    }

    public function phone($value){
        return ['status' => true];
    }

    public function sms(){
        return ['status' => true];
    }


}