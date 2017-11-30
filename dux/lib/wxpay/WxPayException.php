<?php
/**
 * Created by PhpStorm.
 * User: TL
 * Date: 2017/3/16
 * Time: 15:42
 */

namespace dux\lib\wxpay;


class WxPayException extends \Exception
{
    public function errorMessage()
    {
        return $this->getMessage();
    }
}