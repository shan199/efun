<?php

/**
 * 订单列表、抓取记录
 */
namespace app\Users\model;

use app\system\model\SystemModel;

class OrderInfoModel extends SystemModel {

    protected function base($where) {
        return $this->table('order_info(A)')
            ->where((array)$where);
    }

    public function loadList($where = array(), $limit = 0, $order = '') {
        $list = $this->base($where)
            ->limit($limit)
            ->order($order)
            ->select();
        return $list;
    }

    public function countList($where = array()) {
        return $this->base($where)->count();
    }

    public function getWhereInfo($where) {
        return $this->base($where)->find();
    }


}