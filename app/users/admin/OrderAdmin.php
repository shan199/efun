<?php

/**
 * 抓取记录、订单
 */

namespace app\users\admin;

use app\system\admin\SystemExtendAdmin;

class OrderAdmin extends SystemExtendAdmin
{

    protected $_model = 'OrderInfo';

    /**
     * 模块信息
     */
    protected function _infoModule()
    {
        return [
            'info' => [
                'name' => '订单记录',
                'description' => '抓取记录',
//                'url' => url('system/User/index'),
            ],
            'fun' => [
                'index' => true,
            ]
        ];
    }

    protected function _indexAssign() {
        return array(
            'machineList' =>target('machine/MachineInfo')->loadList(['status'=>1])
        );
    }

    protected function _indexOrder()
    {
        return 'create_time DESC';
    }

    protected function _indexParam()
    {
        return [
            'keyword' => ['user_name','machine_name','doll_name','id'],
            'status'=>'status',
            'machineId'=>'machine_id'
        ];
    }

}