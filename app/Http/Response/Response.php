<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/22/2017
 * Time: 3:23 PM
 */

namespace Http\Response;

/**
 *
 * For sending JSON Response .
 * Since there are no other types responses no need to create a sub class for JSONResponse
 *
 * Class Response
 * @package Http\Response
 */
class Response
{

    /**
     * @var Response headers
     */
    private $headers;
    /**
     * @var Response status code
     */
    private $status;
    /**
     * @var Response body
     */
    private $body;

    /**
     * Http Codes that will be used
     * @var array
     */
    public static $httpCodes = [
        200 => '200 OK',
        400 => '400 Bad Request',
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
    ];

    public function __construct($status, $body, $headers = null)
    {
        $this->setStatus($status);
        $this->setBody($status);
        $this->setHeaders($headers);
    }

    /**
     * @param $headers
     */
    public function setHeaders($headers)
    {
        // need validations here
        $this->headers = $headers;
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param Response $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    public function sendJSON()
    {

        header_remove();
        http_response_code($this->status);
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        header('Content-Type: application/json');

        header('Status: ' . self::$httpCodes[$this->status]);

        echo json_encode(array(
            'status'  => $this->status < 300,
            'message' => $this->body
        ));

    }
}