<?php

/**
 * 系统首页
 */

namespace app\index\controller;

use dux\kernel\Controller;

class IndexController extends Controller
{

    public function index()
    {
            $this->display();
    }
}