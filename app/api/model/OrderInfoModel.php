<?php

/**
 * 订单列表
 */

namespace app\api\model;

use app\system\model\SystemModel;

class OrderInfoModel extends SystemModel
{

    protected $table = 'order_info';

    /**
     * @param $where
     * @return $this
     * 表基础表配置
     */
    protected function base($where)
    {
        return $this->table('order_info(A)')
            ->join('user_info(B)',['A.user_id','B.id'])
            ->field(['A.*','B.*','A.create_time as orderTime'])
            ->where((array)$where);
    }

    /**
     * @param $where
     * @return array
     * 获取单条消息
     */
    public function getWhereInfo($where)
    {
        return $this->base($where)->find();
    }

    /**
     * @param array $where
     * @param int $limit
     * @param string $order
     * @return array
     * 查询所有数据
     */
    public function loadList($where = array(), $limit = 0, $order = '')
    {
        $list = $this->base($where)
            ->limit($limit)
            ->order($order)
            ->select();
        return $list;
    }

}