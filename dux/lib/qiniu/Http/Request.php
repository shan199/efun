<?php
/**
 * Created by PhpStorm.
 * User: timlee
 * Date: 2017/6/1
 * Time: 下午3:22
 */

namespace dux\lib\qiniu\Http;


class Request
{
    public $url;
    public $headers;
    public $body;
    public $method;

    public function __construct($method, $url, array $headers = array(), $body = null)
    {
        $this->method = strtoupper($method);
        $this->url = $url;
        $this->headers = $headers;
        $this->body = $body;
    }
}