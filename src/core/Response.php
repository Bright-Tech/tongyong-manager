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
 *
 * @package bright\support\core
 */
class Response
{
    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->body = json_decode($this->response->getBody(), true);
    }

    public function getBody()
    {
        return json_decode($this->response->getBody(), true);
    }


    public function __get($name)
    {
        // TODO: Implement __get() method.
        $method = 'get' . ucfirst($name);
        return $this->$method;
    }

}