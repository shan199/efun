<?php
/**
 * Created by PhpStorm.
 * User: timlee
 * Date: 2017/5/10
 * Time: 上午10:15
 * 微信api接口类
 */

namespace dux\lib\wx;

class WxApiBase
{
    protected $wxConfig = [];

    public function __construct()
    {
        $this->wxConfig = \Config::get('dux.weixin');
    }

    /**
     * @param $url
     * @param null $data
     * @return mixed
     * curl 请求方法
     */
    public function http_curl($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public function getOpenID()
    {
        session_start();

        if ($_SESSION['openid']) {
            return $_SESSION['openid'];
        } else {
            $baseUrl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);

            if (!isset($_GET['code'])) {
                if ($_POST) {
                    $_SESSION['post'] = $_POST;
                }

                $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->wxConfig['appId'] . '&redirect_uri=' . $baseUrl . '&response_type=code&scope=snsapi_base#wechat_redirect';
                header("location:$url");
                exit();
            }

            if (isset($_GET['code'])) {
                if ($_SESSION['post']) {
                    $_POST = $_SESSION['post'];
                    extract($_POST, 1);
                }

                $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->wxConfig['appId'] . '&secret=' . $this->wxConfig['appSecret'] . '&code=' . $_GET['code'] . '&grant_type=authorization_code';

                $output = (array)json_decode($this->http_curl($url));
                $_SESSION['openid'] = $output['openid'];

                return $output['openid'];
            }
        }

    }


    /**
     * 获取Access_token
     */

    public function getAccessToken()
    {
        $where['type'] = 'access_token';
        $where['appId'] = $this->wxConfig['appId'];
        $tokenInfo = target('wechat/TBAccessToken')->getWhereInfo($where, 'id DESC');
        if ($tokenInfo) {
            $extime = time() - strtotime($tokenInfo['updated']);
            if ($extime < 7200) {
                $access_token = $tokenInfo['token'];
            } else {
                $updateData['token'] = $this->wxgetaccesstoken();
                $updateData['updated'] = date('Y-m-d H:i:s', time());

                $updateWhere['type'] = 'access_token';
                $updateWhere['appId'] = $this->wxConfig['appId'];

                $status = target('wechat/TBAccessToken')->saveData('edit',$updateData, $updateWhere);
                if ($status) {
                    $access_token = $updateData['token'];
                } else {
                    $access_token = '';
                }
            }

        } else {

            $data['token'] = $this->wxgetaccesstoken();
            $data['type'] = 'access_token';
            $data['appId'] = $this->wxConfig['appId'];
            $data['expired'] = 7200;
            $data['appSecret'] = $this->wxConfig['appSecret'];
            $data['uk'] = $data['type'] . '@' . $this->wxConfig['appId'];
            $data['created'] = date('Y-m-d H:i:s', time());
            $data['updated'] = date('Y-m-d H:i:s', time());
            $data['id'] = 1;

            target('wechat/TBAccessToken')->saveData('add', $data);

            $access_token = $data['token'];
        }

        return $access_token;
    }
    /**
     * 获取Access_token
     */

//    public function getAccessToken_old()
//    {
//        $where[] = 'expires_time >' . time();
//        $where['name'] = 'wx_access_token';
//        $tokenInfo = target('wechat/WxMemcache')->getWhereInfo($where, 'id DESC');
//        $access_token = $tokenInfo['value'];
//        if (!$access_token) {
//            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->wxConfig['appId'] . '&secret=' . $this->wxConfig['appSecret'];
//            $output = (array)json_decode($this->http_curl($url));
//
//            $access_token = $output['access_token'];
//
//            $data['value'] = $access_token;
//            $data['name'] = 'wx_access_token';
//            $data['expires_time'] = time() + ($output['expires_in'] - 60);  //超时时间 减少一分钟，预防不同步
//            target('wechat/WxMemcache')->saveData('add', $data);
//        }
//
//        return $access_token;
//    }


    /**
     * 获取jsapi_ticket
     */

    public function getJsapiTicket()
    {
        $where['type'] = 'js_ticket';
        $where['appId'] = $this->wxConfig['appId'];
        $tokenInfo = target('wechat/TBAccessToken')->getWhereInfo($where, 'id DESC');
        if ($tokenInfo) {
            $extime = time() - strtotime($tokenInfo['updated']);
            if ($extime < 7200) {
                $access_token = $tokenInfo['token'];
            } else {
                $updateData['token'] = $this->wxgetticket();
                $updateData['updated'] = date('Y-m-d H:i:s', time());

                $updateWhere['type'] = 'js_ticket';
                $updateWhere['appId'] = $this->wxConfig['appId'];

                $status = target('wechat/TBAccessToken')->saveData('edit', $updateData, $updateWhere);
                if ($status) {
                    $access_token = $updateData['token'];
                } else {
                    return false;
                }
            }

        } else {

            $data['token'] = $this->wxgetticket();
            $data['type'] = 'js_ticket';
            $data['appId'] = $this->wxConfig['appId'];
            $data['expired'] = 7200;
            $data['appSecret'] = $this->wxConfig['appSecret'];
            $data['uk'] = $data['type'] . '@' . $this->wxConfig['appId'];
            $data['created'] = date('Y-m-d H:i:s', time());
            $data['updated'] = date('Y-m-d H:i:s', time());
            $data['id'] = 2;

            target('wechat/TBAccessToken')->saveData('add', $data);

            $access_token = $data['token'];
        }

        return $access_token;


//        $where[] = 'expires_time >' . time();
//        $where['name'] = 'jsapi_ticket';
//        $tokenInfo = target('wechat/WxMemcache')->getWhereInfo($where, 'id DESC');
//        $jsapi_ticket = $tokenInfo['value'];
//
//        if (!$jsapi_ticket) {
//            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $this->getAccessToken() . '&type=jsapi';
//            $output = (array)json_decode($this->http_curl($url));
//
//            $jsapi_ticket = $output['ticket'];
//            $data['value'] = $jsapi_ticket;
//            $data['name'] = 'jsapi_ticket';
//            $data['expires_time'] = time() + ($output['expires_in'] - 60);  //超时时间 减少一分钟，预防不同步
//            target('wechat/WxMemcache')->saveData('add', $data);
//        }
//
//        return $jsapi_ticket;
    }

    /**
     * @param $id
     * @return array
     * 生成永久二维码
     */
    public function sceneqrcodeCreate($id)
    {
        $id = intval($id);

        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $this->getAccessToken();
        $output = (array)json_decode($this->http_curl($url, '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": ' . $id . '}}}'));

        $output['expiretime'] = $output['expire_seconds'] ? (time() + $output['expire_seconds']) : 0;

        return $output;
    }

    public function wxgetaccesstoken()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->wxConfig['appId'] . '&secret=' . $this->wxConfig['appSecret'];
        $output = (array)json_decode($this->http_curl($url));

        $access_token = $output['access_token'];

        return $access_token;
    }

    public function wxgetticket()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $this->getAccessToken() . '&type=jsapi';
        $output = (array)json_decode($this->http_curl($url));

        $jsapi_ticket = $output['ticket'];

        return $jsapi_ticket;
    }
}