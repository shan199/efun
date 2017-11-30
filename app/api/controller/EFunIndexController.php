<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 2017/11/27
 * Time: 下午2:35
 * Efun接口，全部都一个接口。分不通方法调用。
 *
 */


namespace app\api\controller;

use app\base\api\BaseApi;


class EFunIndexController extends BaseApi
{
//    首页机器列表
    public function getMachineList()
    {

//        $where['']= 'eFun';
        $resultData = [];
        $list = target('MachineInfo')->loadList();

        if ($list) {
            foreach ($list as $item) {
                $resultData[] = [
                    'machineName' => $item['machine_name'],//机器名字
                    'dollImg' => $item['doll_img'], //娃娃图片
                    'machinemg' => $item['machine_img'], //机器图片
                    'ipAddress' => $item['ip_address'],//控制机器的ip
                    'gameMoney' => $item['game_money'],//每次游戏币数量
                    'machineId' => $item['id'], //机器id
                    'faceCamera' => $item['video_1'],   //正面摄像头
                    'sideCamera' => $item['video_2'],   //侧面摄像头
                ];
            }
        }
        $this->_AppSuccess($resultData);

    }

    //用户登录和自动注册，没有注册的用户自动注册成为会员。
    public function userLogin()
    {
        $resultData = [];
        $unionId = 'oi39d0odDHGo_kdQtsvpSeQjSJic';
        $userInfoWhere['union_id'] = $unionId;
        $userInfo = target('UserThird')->getWhereInfo($userInfoWhere);

        if (!$userInfo) {
            $data = [];
            $userInfo = target('userThird')->registerUser($data);
        }

        $resultData = [
            'nickName' => $userInfo['nick_name'],
            'gameMoney' => $userInfo['game_money'],
            'userId' => $userInfo['user_id'],
            'headUrl' => $userInfo['head_url'] ?: '',

        ];

        $this->_AppSuccess($resultData);
    }


    //获取机器抓取娃娃记录 10条记录。
    public function getMachineOrderList()
    {
        $mId = 1;
        $listWhere['A.machine_id'] = $mId;
        $listWhere['A.status'] = 3;    //抓到的订单

        $list = target('OrderInfo')->loadList($listWhere, 10, 'A.create_time DESC');

        if ($list) {
            foreach ($list as $item) {
                $resultData[] = [
                    'userName' => $item['user_name'],
                    'headUrl' => $item['head_url'],
                    'createTime' => date('Y-m-d', strtotime($item['orderTime']))
                ];
            }
        }

        $this->_AppSuccess($resultData);

    }

}