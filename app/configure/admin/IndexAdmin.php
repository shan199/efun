<?php

/**
 * 用户管理
 */

namespace app\configure\admin;

use app\system\admin\SystemExtendAdmin;

class IndexAdmin extends SystemExtendAdmin
{
    protected $_model = 'RechargePackage';
    /**
     * 模块信息
     */
    protected function _infoModule()
    {
        return [
            'info' => [
                'name' => '其他配置',
                'description' => '充值策略',
//                'url' => url('system/User/index'),
            ],
            'fun' => [
                'index' => true,
                'edit' => true,
                'del' => true,
                'add' => true,
            ]
        ];
    }


}