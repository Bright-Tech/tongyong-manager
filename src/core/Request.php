<?php
/**
 * Created by PhpStorm.
 * User: samxiao
 * Date: 2018/2/6
 * Time: 上午11:29
 */

namespace bright\support\core;

use bright\support\event\SupportNetWorkLogs;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;

class Request
{
    /**
     *
     * @var string
     */
    protected $clientId = '';
    /**
     *
     * @var string
     */
    protected $clientSecret = '';
    /**
     *
     * @var string
     */
    public $domain;
    /**
     *
     * @var string
     */
    public $grantType;
    /**
     *
     * @var string
     */
    public $scope;
    /**
     *
     * @var Client
     */
    protected $sender;

    /**
     * @var array
     */
    protected $headers = ['Accept' => 'application/json'];

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        $token = $this->getToken();
        $this->headers['Authorization'] = 'Bearer ' . $token;
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     *
     * @return Client
     */
    public function getSender()
    {
        if (!$this->sender) {
            $this->sender = new Client();
        }
        return $this->sender;
    }

    public function __construct($clientId, $clientSecret, $domain, $grantType = 'client_credentials', $scope = '')
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->domain = $domain;
        $this->grantType = $grantType;
        $this->scope = $scope;
    }

    public function getToken()
    {

        if (!\Cache::get('support_access_token')) {
            $sender = $this->getSender();
            $response = $sender->request('POST', $this->domain . '/oauth/token', [
                'form_params' => [
                    'grant_type' => $this->grantType,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'scope' => $this->scope,
                ],
            ]);
            $response = json_decode($response->getBody());
            \Cache::put('support_access_token', $response->access_token, $response->expires_in);
        }
        return \Cache::get('support_access_token');
    }

    /**
     *
     * @param string $url
     * @param array $options
     *
     * @return Response
     */
    public function requestGet($url, $options = [])
    {
        return $this->request('GET', $url, $options);
    }

    /**
     *
     * @param string $url
     * @param array $options
     *
     * @return Response
     */
    public function requestPost($url, array $options = [])
    {
        return $this->request('POST', $url, $options);
    }

    /**
     * @param $method
     * @param $url
     * @param array $options
     * @return Response
     */
    public function request($method, $url, array $options = [])
    {
        try {
            $sender = $this->getSender();
            $options = array_merge($options, ['headers' => $this->getHeaders()]);
            $response = $sender->request($method, $url, $options);
            $successResult = new Response($response);
            event(new SupportNetWorkLogs($successResult,'support-sdk',$method,$url,$options));
            return $successResult;
        } catch (RequestException $e) {
            $errorResult = new Response($e->getResponse(), false);
            event(new SupportNetWorkLogs($errorResult,'support-sdk',$method,$url,$options));
            return $errorResult;
        }

    }


}
