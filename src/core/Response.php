<?php
/**
 * Created by PhpStorm.
 * User: samxiao
 * Date: 2018/2/6
 * Time: 上午11:29
 */

namespace bright\support\core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Response
 *
 * @property-read $body
 * @property-read $status
 * @property-read $statusCode
 *
 * @package bright\support\core
 */
class Response
{
    protected $response;

    protected $success;

    public function __construct(ResponseInterface $response, $success = true)
    {
        $this->response = $response;
        $this->success = $success;
        $body = json_decode($this->response->getBody(), true);
        $this->body = $body;
    }

    public function getBody()
    {
        return json_decode($this->response->getBody(), true);
    }

    public function getStatus()
    {
        return $this->success;
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        $method = 'get' . ucfirst($name);
        return $this->$method();
    }

}