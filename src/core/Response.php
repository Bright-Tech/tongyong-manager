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

class Response
{
    protected $response;

    public $body;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->body = json_decode($this->response->getBody(), true);
    }

    public function getBody()
    {
        return json_decode($this->response->getBody(), true);
    }


}