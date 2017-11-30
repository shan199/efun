<?php

/**
 * 用户管理
 */

namespace app\users\admin;

use app\system\admin\SystemExtendAdmin;

class UsersAdmin extends SystemExtendAdmin
{

    protected $_model = 'UserInfo';

    /**
     * 模块信息
     */
    protected function _infoModule()
    {
        return [
            'info' => [
                'name' => '会员列表',
                'description' => '登陆用户管理',
//                'url' => url('system/User/index'),
            ],
            'fun' => [
                'index' => true,
                'edit' => true,
            ]
        ];
    }

    protected function _indexOrder()
    {
        return 'A.id DESC';
    }

    public function _indexParam()
    {
        return [
            'keyword' => ['A.nick_name','A.phone_number','B.union_id','A.id']
        ];
    }

}