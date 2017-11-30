<?php
/**
 * Created by PhpStorm.
 * User: timlee
 * Date: 2017/6/1
 * Time: 下午3:22
 */

namespace dux\lib\qiniu\Http;


class Error
{
    private $url;
    private $response;

    public function __construct($url, $response)
    {
        $this->url = $url;
        $this->response = $response;
    }

    public function code()
    {
        return $this->response->statusCode;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function message()
    {
        return $this->response->error;
    }
}