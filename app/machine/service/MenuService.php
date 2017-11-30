<?php
namespace app\machine\service;
/**
 * 菜单接口
 */
class MenuService {

    /**
     * 获取菜单结构
     */
    public function getSystemMenu() {
        return array(
            'machine' => array(
                'name' => '机器管理',
                'icon' => 'user',
                'order' => 4,
                'menu' => array(
                    array(
                        'name' => '机器管理',
                        'order' => 0,
                        'menu' => array(
                            array(
                                'name' => '机器列表',
                                'icon' => 'user',
                                'url' => url('machine/Index/index'),
                                'order' => 0
                            ),

                        )
                    ),

                ),
            ),
        );
    }
}

