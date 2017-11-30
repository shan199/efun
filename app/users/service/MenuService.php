<?php
namespace app\users\service;
/**
 * 菜单接口
 */
class MenuService {

    /**
     * 获取菜单结构
     */
    public function getSystemMenu() {
        return array(
            'users' => array(
                'name' => '会员管理',
                'icon' => 'user',
                'order' => 2,
                'menu' => array(
                    array(
                        'name' => '会员管理',
                        'order' => 0,
                        'menu' => array(
                            array(
                                'name' => '邮寄列表',
                                'icon' => 'user',
                                'url' => url('users/Express/index'),
                                'order' => 0
                            ),
                            array(
                                'name' => '会员列表',
                                'icon' => 'user',
                                'url' => url('users/Users/index'),
                                'order' => 1
                            ),
                            array(
                                'name' => '抓取记录',
                                'icon' => 'user',
                                'url' => url('users/Order/index'),
                                'order' => 2
                            ),
                            array(
                                'name' => '充值记录',
                                'icon' => 'user',
                                'url' => url('users/Recharge/index'),
                                'order' => 3
                            ),
                        )
                    ),

                ),
            ),
        );
    }
}

