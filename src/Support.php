<?php
/**
 * Created by PhpStorm.
 * User: JX
 * Date: 2017/12/29
 * Time: 12:55
 */

namespace bright\support;

use bright\support\core\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\RequestException;

class Support
{
    /**
     * callback url
     * @var string
     */
    public $domain;


    public $request;

    /**
     * Support constructor.
     * @param $clientId
     * @param $clientSecret
     * @param $domain
     * @param string $grantType
     * @param string $scope
     *
     * @return void
     */
    public function __construct($clientId, $clientSecret, $domain, $grantType = 'client_credentials', $scope = '')
    {
        $this->domain = $domain;
        $this->request = new Request($clientId, $clientSecret, $domain, $grantType, $scope);
    }

    public function authUser($username, $password)
    {
        return $this->request->requestPost($this->domain . '/api/user/login', [
            'form_params' => [
                'password' => $password,
                'username' => $username
            ]
        ]);
    }

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

    private function RequestResult($success, $data)
    {
        return [
            'success' => $success,
            'data' => $data
        ];
    }

    public function updateUser($model, $id)
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

    public function resetUserPassword($model, $id)
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

    protected function header()
    {
        return [
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Accept' => 'application/json'
        ];
    }

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

    public function updateAdmin($model, $id)
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

    public function resetAdminPassword($model, $id)
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

//        try {
            $response = $this->request->request('POST', $this->domain . '/api/user/get-user-by-unionid', [
                'form_params' => [
                    'union_id' => $union_id
                ]
            ]);
            return $response;
//        } catch (RequestException $e) {
//            return $this->RequestResult(false, $e->getMessage());
//        }
    }


}
