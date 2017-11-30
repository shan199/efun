<?php

/**
 * 活动四级分类
 */

namespace app\index\model;

use app\system\model\SystemModel;

class ApiDataModel extends SystemModel
{

    protected $table = 'api_data';

    /**
     * @param $where
     * @return $this
     * 表基础表配置
     */
    protected function base($where)
    {
        return $this->table($this->table)
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

    public function saveData($type = 'add', $data = [])
    {
        return $this->table('api_data')->data($data)->insert();

    }

}