<?php

/**
 * 邮寄列表
 */

namespace app\Users\model;

use app\system\model\SystemModel;

class ExpressModel extends SystemModel
{

    protected function base($where)
    {
        return $this->table('express(A)')
            ->join('user_adress(B)', ['A.adress_id', 'B.id'])
            ->join('express_company(C)',['A.express_id','C.id'],'>')
            ->join('order_info(D)', ['A.order_id', 'D.id'])
            ->field(['A.*', 'B.person', 'B.mobile', 'B.address', 'D.user_name', 'D.machine_name', 'D.doll_name','C.express_name'])
            ->where((array)$where);
    }

    public function loadList($where = array(), $limit = 0, $order = '')
    {

        if ($where['A.dateTime']) {
            $where[] = 'A.create_time >= "' . $where['A.dateTime'] . ' 00:00:00 "';
        }
        if ($where['A.endDate']) {
            $where[] = 'A.create_time <= "' . $where['A.endDate'] . ' 23:59:59 "';
        }

        unset($where['A.dateTime']);
        unset($where['A.endDate']);

        $list = $this->base($where)
            ->limit($limit)
            ->order($order)
            ->select();
        return $list;
    }

    public function countList($where = array())
    {
        if ($where['A.dateTime']) {
            $where[] = 'A.create_time >= "' . $where['A.dateTime'] . ' 00:00:00 "';
        }
        if ($where['A.endDate']) {
            $where[] = 'A.create_time <= "' . $where['A.endDate'] . ' 23:59:59 "';
        }

        unset($where['A.dateTime']);
        unset($where['A.endDate']);
        return $this->base($where)->count();
    }

    public function getWhereInfo($where)
    {
        return $this->base($where)->find();
    }


    public function saveData($type = 'add', $data = [], $where = [])
    {
        if (empty($data)) {
            $data = $this->create();
        }
        if ($type == 'add') {
            return $this->table('express')->data($data)->insert();
        } elseif ($type == 'edit') {
            return $this->table('express')->data($data)->where($where)->update();
        }
    }


    public function deliverData()
    {

        $data = $this->create();

        if (empty($data)) {
            return false;
        }

        $where['id'] = $data['id']; //快递信息id
        $data['status'] = 2;
        $orderWhere['id'] = $data['order_id'];    //订单id

        unset($data['id']);
        unset($data['order_id']);

        $this->beginTransaction();

        $status = $this->table('express')->data($data)->where($where)->update();

        if (!$status) {
            $this->rollBack();
        }


        $orderData['status'] = 5;
        $status = $this->table('order_info')->data($orderData)->where($orderWhere)->update();

        if (!$status) {
            $this->rollBack();
        }

        return $this->commit();

    }

}