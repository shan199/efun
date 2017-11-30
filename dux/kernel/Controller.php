<?php

/**
 * 公共控制器
 */

namespace dux\kernel;

class Controller
{

    private $view;
    public $layout = NULL;

    /**
     * 实例化公共控制器
     */
    public function __construct()
    {
        if (method_exists($this, 'init')) {
            $this->init();
        }
    }

    /**
     * 模板赋值
     * @param  string $name 变量名
     * @param  mixed $value 变量值
     * @return void
     */
    public function assign($name, $value = NULL)
    {
        return $this->_getView()->set($name, $value);
    }

    /**
     * 模板输出
     * @param  string $tpl 模板名
     * @return mixed
     */
    public function display($tpl = '')
    {
        if (empty($tpl)) {
            $tpl = 'app/' . APP_NAME . '/view/' . LAYER_NAME . '/' . strtolower(MODULE_NAME) . '/' . strtolower(ACTION_NAME);
//            $tpl = 'app/' . APP_NAME . '/view/theme/' . strtolower(MODULE_NAME) . '/' . strtolower(ACTION_NAME);
        }
        if ($this->layout) {
            $this->assign('layout', $this->_getView()->fetch($tpl));
            $tpl = $this->layout;
        }
        return $this->_getView()->render($tpl);
    }

    /**
     * 获取模板对象
     * @return object
     */
    protected function _getView()
    {
        if (!isset($this->view)) {
            $this->view = \Dux::view();
        }
        return $this->view;
    }

    /**
     * 页面跳转
     * @param  string $url 跳转地址
     * @param  integer $code 跳转代码
     * @return void
     */
    public function redirect($url, $code = 302)
    {
        header('location:' . $url, true, $code);
        exit;
    }

    /**
     * JSON输出
     * @param $data 输出数据
     * @param string $callback jsonp回调名称
     */
    public function json($data = [], $callback = '', $code = 200)
    {
        if ($callback) {
            \Dux::header($code, function ($info) {
                if (!headers_sent()) {
                    header('application/javascript;charset=utf-8;');
                }
                echo $info['callback'] . '(' . json_encode($info['data']) . ');';
            }, ['data' => $data, 'callback' => $callback]);
        } else {
            \Dux::header($code, function ($data) {
                if (!headers_sent()) {
                    header('application/json;charset=utf-8;');
                }
                echo json_encode($data);
            }, $data);
        }
    }

    /**
     * 成功提示方法
     * @param  string $msg 提示消息
     * @param  string $url 跳转URL
     */
    public function success($msg = '操作成功！', $url = null)
    {
        if (isAjax()) {
            $data = [
                'code' => 200,
                'message' => $msg,
                'url' => $url
            ];
            $this->json($data);
        } else {
            $this->alert($msg, $url);
        }
    }

    /**
     * 失败提示方法
     * @param  string $msg 提示消息
     * @param  string $url 跳转URL
     */
    public function error($msg = '操作失败', $url = null)
    {
        if (isAjax()) {
            $data = [
                'code' => 500,
                'message' => $msg,
                'url' => $url
            ];
            $this->json($data, '', 500);
        } else {
            $this->alert($msg, $url);
        }
    }

    /**
     * 404页面输出
     */
    public function error404()
    {
        \Dux::notFound();
    }

    /**
     * JS窗口提示
     * @param  string $msg 提示消息
     * @param  string $url 跳转URL
     * @param  string $charset 页面编码
     * @return void
     */
    public function alert($msg, $url = NULL, $charset = 'utf-8')
    {
        header("Content-type: text/html; charset={$charset}");
        $alert_msg = "alert('$msg');";
        if (empty($url)) {
            $go_url = 'history.go(-1);';
        } else {
            $go_url = "window.location.href = '{$url}'";
        }
        echo "<script>$alert_msg $go_url</script>";
        exit;
    }


    function getIp()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return ($ip);
    }

}