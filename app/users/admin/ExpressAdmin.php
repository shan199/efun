<?php

/**
 * 用户管理
 */

namespace app\users\admin;

use app\system\admin\SystemExtendAdmin;

class ExpressAdmin extends SystemExtendAdmin
{

    protected $_model = 'Express';

    /**
     * 模块信息
     */
    protected function _infoModule()
    {
        return [
            'info' => [
                'name' => '邮寄列表',
                'description' => '娃娃邮寄列表管理',
                'url' => url('system/User/index'),
            ],
            'fun' => [
                'index' => true,
                'edit' => true,
                'del' => true,
                'status' => true,
            ]
        ];
    }

    protected function _indexAssign()
    {
        return array(
            'machineList' => target('machine/MachineInfo')->loadList(['status' => 1])
        );
    }


    protected function _indexOrder()
    {
        return 'A.id DESC';
    }

    public function _indexParam()
    {
        return [
            'keyword' => ['D.user_name', 'B.person', 'B.address', 'B.mobile', 'D.doll_name', 'D.machine_name'],
            'dateTime' => 'A.dateTime',
            'endDate' => 'A.endDate',
            'machineId' => 'D.machine_id'
        ];
    }

    public function deliver()
    {

        if (isPost()) {

            if (target('Express')->deliverData()) {
                $this->success('发货成功！', url('index'));
            } else {
                $this->error();
            }
//


        } else {
            $id = request('get', 'id');

            $where['A.id'] = $id;
            $info = target('Express')->getWhereInfo($where);

            $expressList = target('ExpressCompany')->loadList();
            $this->assign('info', $info);
            $this->assign('expressList', $expressList);
            $this->systemDisplay();
        }


    }

    public function exportExcel()
    {

        $where[] = 'A.create_time >="' . $_POST['exportDay'] . ' 00:00:00" AND A.create_time <= "' . $_POST['exportEndDay'] . ' 23:59:59"';
        $fileName = $_POST['exportDay'] . '_' . $_POST['exportEndDay'];

        $dataResult = array();      //todo:导出数据

        $list = target('Express')->loadList($where);

        if ($list) {
            foreach ($list as $item) {

                if ($item['status'] == 1) {
                    $status = '已申请';
                } elseif ($item['status'] == 2) {
                    $status = '已发货';
                } else {
                    $status = '未知状态';
                }

                $dataResult[] = [
                    $item['order_id'],//订单编号
                    $item['person'], //收件人
                    $item['mobile'],//联系电话
                    $item['address'],//收件人地址
                    $item['user_name'],//用户昵称
                    $item['doll_name'],//娃娃名称
                    $item['machine_name'],//机器名称
                    $item['create_time'],//申请时间
                    $status,//状态
                    ''
                ];
            }

        }

//        $headTitle =$fileName;
        $title = $fileName . "_发货表";
//        $headtitle = "<tr style='height:50px;border-style:none;><th border=\"0\" style='height:60px;width:270px;font-size:22px;' colspan='11' >{$headTitle}</th></tr>";
        $headtitle = "";
        $titlename = "<tr> 
               <th style='width:200px;font-weight: bold'>订单编号</th> 
               <th style='width:80px;font-weight: bold'>收件人</th> 
               <th style='width:70px;font-weight: bold'>联系电话</th> 
               <th style='width:70px;font-weight: bold'>收件地址</th> 
               <th style='width:70px;font-weight: bold'>昵称</th> 
               <th style='width:70px;font-weight: bold'>娃娃</th> 
               <th style='width:100px;font-weight: bold'>机器</th> 
               <th style='width:70px;font-weight: bold'>申请时间</th> 
               <th style='width:70px;font-weight: bold'>状态</th> 
               <th style='width:200px;font-weight: bold'>快递单号</th> 
           </tr>";


        $filename = $title . ".xls";
        $this->excelData($dataResult, $titlename, $headtitle, $filename);


    }
}