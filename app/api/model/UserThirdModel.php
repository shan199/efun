<?php

/**
 * 机器列表
 */

namespace app\api\model;

use app\system\model\SystemModel;

class UserThirdModel extends SystemModel
{

    protected $table = 'user_third';

    /**
     * @param $where
     * @return $this
     * 表基础表配置
     */
    protected function base($where)
    {
        return $this->table($this->table)
            ->join('user_info(B)', ['A.user_id', 'B.id'])
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


    //注册用户
    public function registerUser($data)
    {
        $this->beginTransaction();
        $userData = [];
        $thirdData = [];

        $userData['nick_name'] = '';
        $userData['user_level'] = '';
        $userData['phone_number'] = '';
        $userData['user_point'] = '';
        $userData['head_url'] = '';
        $userData['gender'] = '';
        $userData['game_money'] = '';
        $userData['push_user_id'] = '';
        $userData['doll_count'] = '';
        $userData['status'] = '';
        $userData['token'] = '';
        $userData['create_time'] = date('Y-m-d H:i:s', time());
        $userData['invitation_code'] = '';
        $userId = $this->table('user_info')->data($userData)->insert();

        if (!$userId) {
            $this->rollBack();
        }

        //保存用户微信登录信息。
        $thirdData['user_id'] = $userId;
        $thirdData['union_id'] = '';
        $thirdData['third_type'] = '1';
        $thirdData['create_time'] = date('Y-m-d H:i:s', time());
        $userDataStatus = $this->table('user_third')->data($thirdData)->insert();

        if (!$userDataStatus) {
            $this->rollBack();
        }

        return $this->commit();

    }
}