<?php
/**
 * Created by PhpStorm.
 * User: JX
 * Date: 2017/12/29
 * Time: 12:55
 */

namespace bright_tech\laravel\tongyong_manager;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class TongYongService
{

    /**
     *
     * @var string
     */
    protected $client_id = '';
    /**
     *
     * @var string
     */
    protected $secret = '';
    /**
     * callback url
     * @var string
     */
    public $home;
    /**
     *
     * @var string
     */
    public $grant_type;
    /**
     *
     * @var string
     */
    public $scope;

    /**
     * AliyunOssService constructor.
     *
     * @param $client_id
     * @param $secret
     * @param $home
     * @param $grant_type
     * @param $scope
     */
    public function __construct($client_id, $secret, $home, $grant_type = 'client_credentials', $scope = '')
    {
        $this->client_id = $client_id;
        $this->secret = $secret;
        $this->home = $home;
        $this->grant_type = $grant_type;
        $this->scope = $scope;
    }

    protected function getToken()
    {
        if (!Cache::get('tongyong_access_token')) {
            $url = $this->home . 'oauth/token';
            $from_data = [
                'grant_type' => $this->grant_type,
                'client_id' => $this->client_id,
                'client_secret' => $this->secret,
                'scope' => $this->scope,
            ];
            $arr_token = $this->file_get_contents_post($url, $from_data);
            Cache::put('tongyong_access_token', $arr_token->access_token, 1000);
        }
        return Cache::get('tongyong_access_token');
    }

    public function file_get_contents_post($url, $post)
    {
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json",
                'content' => json_encode($post, JSON_UNESCAPED_UNICODE),
            ),
        );
        $result = \GuzzleHttp\json_decode(file_get_contents($url, false, stream_context_create($options)));
        return $result;
    }

    public function getUserLogin($username, $password)
    {
        $guzzle = new Client();
        $response = $guzzle->post($this->home . 'api/auth/login', [
            'form_params' => [
                'password' => $password,
                'username' => $username
            ],
            'handler' => $this->handler()
        ]);
        return json_decode((string)$response->getBody(), true);
    }

    public function addUser($model)
    {
        $guzzle = new Client();

        $response = $guzzle->post($this->home . 'api/admin/user', [
            'form_params' => $model,
            'handler' => $this->handler()
        ]);
        return json_decode((string)$response->getBody(), true);
    }

    public function updateUser($model)
    {
        $guzzle = new Client();
        $response = $guzzle->put($this->home . 'api/admin/user/' . $model->id, [
            'form_params' => $model,
            'handler' => $this->handler()
        ]);
        return json_decode((string)$response->getBody(), true);
    }

    public function getUser($id)
    {
        $guzzle = new Client();
        $response = $guzzle->get($this->home . 'api/admin/user/' . $id, [
            'handler' => $this->handler()
        ]);
        return json_decode((string)$response->getBody(), true);
    }

    public function deleteUser($id)
    {
        $guzzle = new Client();
        $response = $guzzle->delete($this->home . 'api/admin/user/' . $id, [
            'handler' => $this->handler()
        ]);
        return json_decode((string)$response->getBody(), true);
    }

    protected function handler()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/',
            'Authorization' => 'Bearer ' . $this->getToken()
        ];
    }
}