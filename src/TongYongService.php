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
            $guzzle = new Client();
            $response = $guzzle->request('POST', $this->home . 'oauth/token', [
                'form_params' => [
                    'grant_type' => $this->grant_type,
                    'client_id' => $this->client_id,
                    'client_secret' => $this->secret,
                    'scope' => $this->scope,
                ],
            ]);
            $arr_token = json_decode((string)$response->getBody(), true);
            Cache::put('tongyong_access_token', $arr_token['access_token'], 1000);
        }
        return Cache::get('tongyong_access_token');
    }

    public function getUserLogin($username, $password)
    {
        $guzzle = new Client();
        $response = $guzzle->request('POST', $this->home . 'api/user/login', [
            'form_params' => [
                'password' => $password,
                'username' => $username
            ],
            'header' => $this->header()
        ]);
        return json_decode((string)$response->getBody(), true);
    }

    public function addUser($model)
    {
        $guzzle = new Client();

        $response = $guzzle->request('POST', $this->home . 'api/user', [
            'form_params' => $model,
            'header' => $this->header()
        ]);
        return json_decode((string)$response->getBody(), true);
    }

    public function updateUser($model)
    {
        $guzzle = new Client();
        $response = $guzzle->request('PUT', $this->home . 'api/user/' . $model->id, [
            'form_params' => $model,
            'header' => $this->header()
        ]);
        return json_decode((string)$response->getBody(), true);
    }

    public function getUser($id)
    {
        $guzzle = new Client();
        // dd($this->home.'api/user/'. $id);
        $response = $guzzle->request('GET', $this->home . 'api/user/' . $id, [
            'headers' => $this->header()
        ]);
        return json_decode((string)$response->getBody(true), true);
    }

    public function deleteUser($id)
    {
        $guzzle = new Client();
        $response = $guzzle->request('DELETE', $this->home . 'api/user/' . $id, [
            'header' => $this->header()
        ]);
        return json_decode((string)$response->getBody(), true);
    }

    protected function header()
    {
        return [
            'Authorization' => 'Bearer ' . $this->getToken()
        ];
    }
}