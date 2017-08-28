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
    public function validate($rules)
    {
        foreach ($rules as $field => $rule) {

            $subrules = explode('|', $rule);

            foreach ($subrules as $subrule) {
                if(!isset($this->request->{$field})) {
                    $this->errors[$field] = Error::getError(1001);
                }else {
                    $validation = $this->{$subrule}($this->request->{$field});

                    if(!$validation['status']) {
                        $this->errors[$field] = Error::getError($validation['error_code']);
                    }

                }
                if(isset($this->errors[$field])){
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
            'status'     => false,
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
            'status'     => false,
            'error_code' => 1002
        ];
    }

    /**
     * @param $value
     * @param $length
     * @return array
     */
    private function exactLength($value, $length)
    {
        if (strlen($value) == $length) {
            return ['status' => true];
        }
        return [
            'status'     => false,
            'error_code' => 1002
        ];
    }

    /**
     * @param $value
     * @param $length
     * @return array
     */
    private function max($value, $length){
        if (strlen($value) <= $length) {
            return ['status' => true];
        }
        return [
            'status'     => false,
            'error_code' => 1003
        ];
    }

    /**
     * @param $value
     * @return array
     */
    private function phone($value)
    {
        $pattern = '/^(?!(?:\d*-){5,})(?!(?:\d* ){5,})\+?[\d- ]+$/';
        if(preg_match($pattern, $value)){
            return ['status' => true];
        }else{

            return [
                'status'     => false,
                'error_code' => 2001
            ];
        }

    }

    /**
     * @param $value
     * @return array
     */
    private function sms($value)
    {

        return $this->max($value, 1072); //1377

    }

    /**
     * @param $value
     * @return array
     */
    private function originator($value){

        if($this->phone($value)['status']){
            return ['status' => true];
        }else if(ctype_alnum($value)){
            if($this->max($value, 11)['status']){
                return ['status' => true];
            }
        }

        return [
            'status'     => false,
            'error_code' => 1002
        ];

    }


}