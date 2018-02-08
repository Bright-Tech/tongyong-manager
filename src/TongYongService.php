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
use GuzzleHttp\Exception\RequestException;

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

    /**token获取
     * @return mixed
     */
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

    /**用户登录验证
     * @param $username
     * @param $password
     * @return array
     */
    public function getUserLogin($username, $password)
    {
        $guzzle = new Client();
        try {
            $response = $guzzle->request('POST', $this->home . 'api/user/login', [
                'form_params' => [
                    'password' => $password,
                    'username' => $username
                ],
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**
     * 添加用户
     * @param array $model
     * @return mixed
     */
    public function addUser($model)
    {
        $guzzle = new Client();
        try {
            $response = $guzzle->request('POST', $this->home . 'api/user', [
                'form_params' => $model,
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }
    /**
     * 公用头部
     * @return array
     */
    protected function header()
    {
        return [
            'Authorization' => 'Bearer ' . $this->getToken(), 'Accept' => 'application/json'
        ];
    }

    /**
     * 统一返回格式
     * @param $success
     * @param $data
     * @return array
     */
    private function RequestResult($success, $data)
    {
        return [
            'success' => $success,
            'data' => $data
        ];
    }

    /**
     * 修改用户
     * @param $model
     * @param $id
     * @return array
     */
    public function updateUser($model,$id)
    {
        $guzzle = new Client();
        try {
            $response = $guzzle->request('PUT', $this->home . 'api/user/' . $id, [
                'form_params' => $model,
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**
     * 获取用户
     * @param $id
     * @return array
     */
    public function getUser($id)
    {
        $guzzle = new Client();

        try {
            $response = $guzzle->request('GET', $this->home . 'api/user/' . $id, [
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**删除用户
     * @param $id
     * @return array
     */
    public function deleteUser($id)
    {
        $guzzle = new Client();

        try {
            $response = $guzzle->request('DELETE', $this->home . 'api/user/' . $id, [
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**
     * 重置用户密码
     * @param $model
     * @param $id
     * @return array
     */
    public function resetUserPassword($model,$id)
    {
        try {
            $guzzle = new Client();
            $response = $guzzle->request('POST', $this->home . 'api/user/reset-password/' . $id, [
                'form_params' => $model,
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**
     * 后台用户登录
     * @param $username
     * @param $password
     * @return array
     */
    public function getAdminLogin($username, $password)
    {
        $guzzle = new Client();

        try {
            $response = $guzzle->request('POST', $this->home . 'api/admin/login', [
                'form_params' => [
                    'password' => $password,
                    'username' => $username
                ],
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**
     * 添加后台用户
     * @param $model
     * @return array
     */
    public function addAdmin($model)
    {
        $guzzle = new Client();
        try {
            $response = $guzzle->request('POST', $this->home . 'api/admin', [
                'form_params' => $model,
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**
     * 修改后台用户信息
     * @param $model
     * @param $id
     * @return array
     */
    public function updateAdmin($model,$id)
    {
        $guzzle = new Client();
        try {
            $response = $guzzle->request('PUT', $this->home . 'api/admin/' . $id, [
                'form_params' => $model,
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**
     * 获取后台用户信息
     * @param $id
     * @return array
     */
    public function getAdmin($id)
    {
        $guzzle = new Client();
        try {
            $response = $guzzle->request('GET', $this->home . 'api/admin/' . $id, [
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**
     * 删除后台用户
     * @param $id
     * @return array
     */
    public function deleteAdmin($id)
    {
        $guzzle = new Client();
        try {
            $response = $guzzle->request('DELETE', $this->home . 'api/admin/' . $id, [
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**
     * 重置后台用户密码
     * @param $model
     * @param $id
     * @return array
     */
    public function resetAdminPassword($model,$id)
    {
        $guzzle = new Client();
        try {
            $response = $guzzle->request('POST', $this->home . 'api/admin/reset-password/' . $id, [
                'form_params' => $model,
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**
     * 使用code认证用户
     * @param $code
     * @return mixed
     */
    public function codeLogin($code)
    {
        $guzzle = new Client();
        try {
            $response = $guzzle->request('GET', $this->home . 'api/user/code-login/' . $code . '/' . $this->client_id, [
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**
     * @param $union_id 微信Unionid
     * @return array
     */
    public function getUserByUnionid($union_id)
    {
        $guzzle = new Client();
        try {
            $response = $guzzle->request('POST', $this->home . 'api/user/get-user-by-unionid', [
                'form_params' => [
                    'union_id'=>$union_id
                ],
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**
     * 根据手机号获取用户
     * @param $union_id
     * @return array
     */
    public function getUserByMobile($mobile)
    {
        $guzzle = new Client();
        try {
            $response = $guzzle->request('POST', $this->home . 'api/user/get-user-by-mobile', [
                'form_params' => [
                    'mobile'=>$mobile
                ],
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $data;
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }

    /**
     * 短信验证
     * @param $mobile
     * @param $code
     * @return array
     */
    public function checkSMS($mobile,$code){
        $guzzle = new Client();
        try {
            $response = $guzzle->request('POST', $this->home . 'api/admin/sms/check-confirmation-code', [
                'form_params' => [
                    'phone-number'=>$mobile,
                    'code'=>$code
                ],
                'headers' => $this->header()
            ]);
            $data = json_decode((string)$response->getBody(), true);
            return $this->RequestResult(true, $data);
        } catch (RequestException $e) {
            return $this->RequestResult(false, $e->getMessage());
        }
    }





}
