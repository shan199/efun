<?php

/**
 * 基础API
 */

namespace app\base\api;

use dux\kernel\Api;

class BaseApi extends Api
{
    protected $appConfig;
    protected $sysInfo;
    protected $sysConfig;

    public function __construct()
    {
        parent::__construct();
        $this->sysInfo = \Config::get('dux.info');
        $this->sysConfig = \Config::get('dux.use');
        $this->appConfig = \Config::get('dux.app');

//        $this->checkLink();
    }

    /**
     * 检查链接码
     */
    private function checkLink()
    {
        if ($this->sysConfig['com_key'] <> $this->data['auth_link']) {
            $this->error('Link Error code', 403);
        }
    }

    /**
     * @param int $len
     * @param string $format
     * @return string
     * 生成随机字符和字符串（默认数字）
     */
    function randomStr($len = 8, $format = 'NUMBER')
    {
        $is_abc = $is_numer = 0;
        $password = $tmp = '';
        switch ($format) {
            case 'ALL':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
            case 'CHAR':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXY1234567890';
                break;
            case 'SCHAR':
                $chars = 'abcdefghijklmnopqrstuvwxyz1234567890';
                break;
            case 'NUMBER':
                $chars = '123456789';
                break;
            default :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
        }
        mt_srand((double)microtime() * 1000000 * getmypid());
        while (strlen($password) < $len) {
            $tmp = substr($chars, (mt_rand() % strlen($chars)), 1);
            if (($is_numer <> 1 && is_numeric($tmp) && $tmp > 0) || $format == 'CHAR') {
                $is_numer = 1;
            }
            if (($is_abc <> 1 && preg_match('/[a-zA-Z]/', $tmp)) || $format == 'NUMBER') {
                $is_abc = 1;
            }
            $password .= $tmp;
        }
        if ($is_numer <> 1 || $is_abc <> 1 || empty($password)) {
            $password = randpw($len, $format);
        }
        return $password;
    }



    /**
     * @param $field
     * @param $str
     * @return bool
     * 字段验证
     */
    protected function _VerificationField($field, $str)
    {
        if (empty($field)) {
            die($this->_AppError($str));
        }

        return true;

    }


    /**
     * @param $input
     * @param string $key
     * @param string $iv
     * @return mixed|string
     * aes加密 CBC 128位
     */
    public function encrypt($input, $key = '', $iv = '')
    {
        if (empty($key)) {
            $key = $this->appConfig['DECRYPT_KEY'];
        }
        if (empty($iv)) {
            $iv = $this->appConfig['DECRYPT_IV'];
        }

        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $input = $this->pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');

        mcrypt_generic_init($td, $key, $iv);

        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        $data = str_replace('+', '[/]', $data);
        return $data;
    }

    private function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }


    /**
     * @param $str
     * @param string $key
     * @param string $iv
     * @return string
     * aes解密 CBC 128位
     */
    public function decrypt($str, $key = '', $iv = '')
    {

        if (empty($key)) {
            $key = $this->appConfig['DECRYPT_KEY'];
        }
        if (empty($iv)) {
            $iv = $this->appConfig['DECRYPT_IV'];
        }


        $str = str_replace('[/]', '+', $str);

        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($str), MCRYPT_MODE_CBC, $iv);

        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s - 1]);
        $decrypted = substr($decrypted, 0, -$padding);

        return $decrypted;
    }

    /**
     * @param $postData
     * @param $inputArray
     * @return array
     * 转换并从加密数据中提取到需要的值
     */
    public function _analyzeDecrypt($encryptionStr, $inputArray)
    {
        $arrKeys = array_keys($inputArray);
        $arrValue = array_values($inputArray);
        $dataArr = [];

        //判断是否启用加密
        if (!is_array($encryptionStr)) {

            $str = $this->decrypt($encryptionStr);//数据进行解密

            $dataObj = json_decode($str, true);

            if (is_array($arrKeys)) {
                foreach ($arrKeys as $k => $item) {
                    if ($arrValue[$k] == 1) {
                        $this->_VerificationField($dataObj[$item], $arrKeys[$k]);
                    }
//                    $dataArr[$item] = $dataObj->$item ?: '';
                    $dataArr[$item] = $dataObj[$item] ?: '';
                }
            }

        } else {
            if (is_array($inputArray)) {
                foreach ($arrKeys as $k => $item) {
                    if ($arrValue[$k] == 1) {
                        $this->_VerificationField($encryptionStr[$item], $arrKeys[$k]);
                    }
                    $dataArr[$item] = $encryptionStr[$item] ?: '';
                }
            }
        }

        return $dataArr;

    }


    private function mulitArraytoSingle($arr)
    {
        $resultData = [];
        foreach ($arr as $things) {
            $data = [];
            if (is_array($things)) {
                foreach ($things as $key => $value) {
                    if (empty($value)) {
                        $value = '';
                    }
                    $data[$key] = $value;
                }
            }
            $resultData[] = $data;
        }

        return $resultData;
    }




    /**
     * @param array $data
     * @return string
     * by tim
     * 返回json数据结构
     * app接口成功后的标准格式
     */
    function _AppSuccess($data = array())
    {

        header('Content-Type: application/json; charset=utf-8');

        $ResultData['result'] = '1';
        $ResultData['msg'] = '';
        if ($this->appConfig['IS_DECRYPT'] == 1) {
            $ResultData['data'] = $this->encrypt(json_encode($data)) ?: '';
        } else {
            $ResultData['data'] = $data ?: array();
        }
        die(json_encode($data));

    }


    /**
     * @param string $msg
     * @param string $result
     * @return string
     * by tim
     * 返回json数据结构
     */
    function _AppError($msg = '', $result = '0')
    {
        header('Content-type: application/json');

        $jsonData['result'] = (string)$result;
        $jsonData['msg'] = '参数 ' . $msg . ' 未输入!';

        die(json_encode($jsonData));

    }
}