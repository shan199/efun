<?php

/**
 * 充值策略
 */

namespace app\configure\model;

use app\system\model\SystemModel;

class RechargePackageModel extends SystemModel
{

    protected function base($where)
    {
        return $this->table('recharge_package(A)')
            ->where((array)$where);
    }

    public function loadList($where = array(), $limit = 0, $order = '')
    {
        $list = $this->base($where)
            ->limit($limit)
            ->order($order)
            ->select();
        return $list;
    }

    public function countList($where = array())
    {
        return $this->base($where)->count();
    }

    public function getWhereInfo($where)
    {
        return $this->base($where)->find();
    }

    public function getInfo($id)
    {
        $where['id'] = $id;
        return $this->base($where)->find();
    }

    public function saveData($type = 'add', $data = [], $where = [])
    {

        if (empty($data)) {
            $data = $this->create();
        }

        if (empty($data)) {
            return false;
        }

        if ($type === 'add') {
            unset($data['id']);
            return $this->table('recharge_package')->data($data)->insert();
        } elseif ($type === 'edit') {

            if (empty($where)) {
                $where['id'] = $data['id'];
                unset($data['id']);
            }
            return $this->table('recharge_package')->data($data)->where($where)->update();
        }

    }

    public function delData($id)
    {
        $where['id'] = $id;
        return $this->table('recharge_package')->where($where)->delete();
    }

}