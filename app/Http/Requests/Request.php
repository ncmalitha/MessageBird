<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/22/2017
 * Time: 3:24 PM
 */

namespace Http\Requests;


class Request
{

    /**
     * @var array
     */
    public $data;

    /**
     * @var string
     */
    public $method;

    /**
     * Request constructor.
     */
    public function __construct()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->method  = 'POST';
            $requestParams = $_POST;
        } else {
            $this->method  = 'GET';
            $requestParams = $_GET;
        }

        $this->data = [];

        foreach ($requestParams as $key => $requestParam) {
            $this->data[$key] = $this->sanitize($requestParam);
        }


    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function  __isset($name) {
        return array_key_exists($name, $this->data);
    }

    /**
     * @param $name
     */
    public function  __unset($name) {
        unset($this->data[$name]);
    }

    /**
     * @param $str
     * @return mixed
     */
    public function sanitize($str){
        $newstr = filter_var($str, FILTER_SANITIZE_STRING);
        return $newstr;
    }

    /**
     * @return bool
     */
    public function isValid(){
        return true;
    }

}