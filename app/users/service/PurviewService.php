<?php

namespace app\users\service;
/**
 * 权限接口
 */
class PurviewService
{
    /**
     * 获取模块权限
     */
    public function getSystemPurview()
    {
        return array(
            'Users' => array(
                'name' => '会员管理',
                'auth' => array(
                    'index' => '列表',
                    'add' => '新增',
                    'del' => '删除',
                )
            ),
            'Express' => array(
                'name' => '邮寄列表',
                'auth' => array(
                    'index' => '列表',
                )
            ),
            'Order' => array(
                'name' => '抓取记录',
                'auth' => array(
                    'index' => '列表',
                )
            ),
            'Recharge' => array(
                'name' => '充值记录',
                'auth' => array(
                    'index' => '列表',
                )
            ),
        );
    }


}
