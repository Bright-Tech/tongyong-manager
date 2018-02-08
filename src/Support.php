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
    /**
     * @var Request
     */
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

    /**
     * @param $username
     * @param $password
     * @return core\Response
     */
    public function authUser($username, $password)
    {
        return $this->request->requestPost($this->domain . '/api/user/login', [
            'form_params' => [
                'password' => $password,
                'username' => $username
            ]
        ]);
    }

    /**
     * @param array $model
     * @return mixed
     */
    public function addUser(array $model)
    {
        return $this->request->requestPost($this->domain . '/api/user', [
            'form_params' => $model
        ]);
    }

    /**
     * @param array $model
     * @param $id
     * @return core\Response
     */
    public function updateUser(array $model, $id)
    {
        return $this->request->request('PUT', $this->domain . '/api/user/' . $id, [
            'form_params' => $model
        ]);
    }

    /**
     * @param $field
     * @param $value
     * @return core\Response
     */
    public function searchUser($field, $value)
    {
        return $this->request->requestGet($this->domain . "/api/user/search/{$field}/{$value}");
    }

    /**
     * @param $id
     * @return core\Response
     */
    public function getUserById($id)
    {
        return $this->request->requestGet($this->domain . "/api/user/{$id}");
    }

    /**
     * @param $id
     * @return core\Response
     */
    public function deleteUser($id)
    {
        return $this->request->request('DELETE', $this->domain . '/api/user/' . $id);
    }

//    public function resetUserPassword($model, $id)
//    {
//        try {
//            $guzzle = new Client();
//            $response = $guzzle->request('POST', $this->home . 'api/user/reset-password/' . $id, [
//                'form_params' => $model,
//                'headers' => $this->header()
//            ]);
//            $data = json_decode((string)$response->getBody(), true);
//            return $this->RequestResult(true, $data);
//        } catch (RequestException $e) {
//            return $this->RequestResult(false, $e->getMessage());
//        }
//    }

    public function authAdmin($username, $password)
    {
        return $this->request->requestPost($this->domain . '/api/admin/login', [
            'form_params' => [
                'password' => $password,
                'username' => $username
            ]
        ]);
    }

    public function addAdmin(array $model)
    {
        return $this->request->requestPost($this->domain . '/api/admin', [
            'form_params' => $model
        ]);
    }

    public function updateAdmin($model, $id)
    {
        return $this->request->request('PUT', $this->domain . "/api/admin/{$id}", [
            'form_params' => $model
        ]);
    }

    public function getAdminById($id)
    {
        return $this->request->requestGet($this->domain . "/api/admin/{$id}");
    }

    public function deleteAdmin($id)
    {
        return $this->request->request('DELETE', $this->domain . "/api/admin/{$id}");
    }

//    public function resetAdminPassword($model, $id)
//    {
//
//        $guzzle = new Client();
//        try {
//            $response = $guzzle->request('POST', $this->home . 'api/admin/reset-password/' . $id, [
//                'form_params' => $model,
//                'headers' => $this->header()
//            ]);
//            $data = json_decode((string)$response->getBody(), true);
//            return $this->RequestResult(true, $data);
//        } catch (RequestException $e) {
//            return $this->RequestResult(false, $e->getMessage());
//        }
//    }

    /**
     * 使用code认证用户
     * @param $code
     * @return mixed
     */
//    public function codeLogin($code)
//    {
//        $guzzle = new Client();
//        try {
//            $response = $guzzle->request('GET', $this->home . 'api/user/code-login/' . $code . '/' . $this->client_id, [
//                'headers' => $this->header()
//            ]);
//            $data = json_decode((string)$response->getBody(), true);
//            return $this->RequestResult(true, $data);
//        } catch (RequestException $e) {
//            return $this->RequestResult(false, $e->getMessage());
//        }
//    }

    /**
     * @param $union_id 微信Unionid
     * @return array
     */
//    public function getUserByUnionid($union_id)
//    {
//
////        try {
//        $response = $this->request->request('POST', $this->domain . '/api/user/get-user-by-unionid', [
//            'form_params' => [
//                'union_id' => $union_id
//            ]
//        ]);
//        return $response;
////        } catch (RequestException $e) {
////            return $this->RequestResult(false, $e->getMessage());
////        }
//    }


    /**
     *
     */

    /**
     * @param $mobile
     * @return core\Response
     */
    public function sendSmsConfirmationCode($mobile)
    {
        return $this->request->requestPost($this->domain . '/api/sms/send-confirmation-code', [
            'form_params' => ['phone-number' => $mobile]
        ]);
    }

    /**
     * @param $mobile
     * @param $code
     * @return core\Response
     */
    public function checkSmsConfirmationCode($mobile, $code)
    {
        return $this->request->requestPost($this->domain . '/api/sms/check-confirmation-code', [
            'form_params' => ['phone-number' => $mobile, 'code' => $code]
        ]);
    }
}
