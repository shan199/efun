<?php

/**
 * 充值记录
 */

namespace app\users\admin;

use app\system\admin\SystemExtendAdmin;

class RechargeAdmin extends SystemExtendAdmin
{

    protected $_model = 'RechargeOrder';

    /**
     * 模块信息
     */
    protected function _infoModule()
    {
        return [
            'info' => [
                'name' => '充值记录',
                'description' => '充值记录',
//                'url' => url('system/User/index'),
            ],
            'fun' => [
                'index' => true,
            ]
        ];
    }

    protected function _indexOrder()
    {
        return 'create_time DESC';
    }

    public function _indexParam()
    {
        return [
            'keyword' => ['user_name','package_name','id']
        ];
    }

}