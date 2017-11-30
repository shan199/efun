<?php

/**
 * 系统首页
 */

namespace app\index\common;

use app\base\Common\SiteCommon;

class IndexCommon extends SiteCommon {


    protected function index() {
        $this->setMeta('首页');
        $this->setCrumb([
            [
                'name' => '首页',
                'url' => ROOT_URL . '/'
            ]
        ]);
        $this->siteDisplay($this->siteConfig['tpl_index']);
    }

}