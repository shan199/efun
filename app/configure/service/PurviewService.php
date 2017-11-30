<?php

namespace app\configure\service;
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
            'Index' => array(
                'name' => '机器列表',
                'auth' => array(
                    'index' => '列表',
                    'add' => '新增',
                    'del' => '删除',
                )
            ),

        );
    }


}
