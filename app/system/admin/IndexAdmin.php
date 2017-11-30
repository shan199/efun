<?php

/**
 * 系统首页
 */

namespace app\system\admin;

class IndexAdmin extends \app\system\admin\SystemAdmin {

    /**
     * 模块信息
     */
    protected function _infoModule(){
        return array(
            'info' => array(
                'name' => '系统首页',
                'description' => '系统基本信息参数',
            ),
        );
    }

    /**
     * 首页
     */
    public function index() {

        $this->systemDisplay();
    }

    /**
     * 个人资料
     */
    public function userData() {
        if(!isPost()) {
            $this->assign('info',  target('system/SystemUser')->getInfo(USER_ID));
            $this->systemDisplay();
        }else{
            $post = request('post');
            $data = array();
            $data['username'] = $post['username'];
            $data['nickname'] = $post['nickname'];
            $data['password'] =$post['password'];
            if($data['password']) {
                $data['password'] = md5($data['password']);
            }
            $data['user_id'] = USER_ID;
            $data = target('system/SystemUser')->create($data);
            if(!$data) {
                $this->error(target('system/SystemUser')->getError());
            }
            if(!target('system/SystemUser')->edit($data)) {
                $this->error('修改资料失败!');
            }
            $this->success('修改资料成功!');

        }
    }

}