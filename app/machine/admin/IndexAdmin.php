<?php

/**
 * 用户管理
 */

namespace app\machine\admin;

use app\system\admin\SystemExtendAdmin;

class IndexAdmin extends SystemExtendAdmin
{
    protected $_model = 'MachineInfo';
    /**
     * 模块信息
     */
    protected function _infoModule()
    {
        return [
            'info' => [
                'name' => '设备管理',
                'description' => '娃娃机配置管理',
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

    protected function _indexOrder()
    {
        return 'id DESC';
    }

    public function _indexParam()
    {
        return [
            'keyword' => 'username'
        ];
    }

}