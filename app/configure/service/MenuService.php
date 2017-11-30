<?php
namespace app\configure\service;
/**
 * 菜单接口
 */
class MenuService {

    /**
     * 获取菜单结构
     */
    public function getSystemMenu() {
        return array(
            'configure' => array(
                'name' => '其他配置',
                'icon' => 'user',
                'order' => 6,
                'menu' => array(
                    array(
                        'name' => '其他配置',
                        'order' => 0,
                        'menu' => array(
                            array(
                                'name' => '充值套餐',
                                'icon' => 'user',
                                'url' => url('configure/Index/index'),
                                'order' => 0
                            ),

                        )
                    ),

                ),
            ),
        );
    }
}

