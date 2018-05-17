<?php
/**
 * 用户留言板控制器
 */

namespace Run\Controller;


class UserMessageBoardController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '用户管理';
    }

    /**
     * 用户留言
     */
    public function index()
    {
//        $data = I('get.');
        $arr = D('UserMessageBoard')->getAll();
        foreach($arr as &$v){
            //找到对应的活动
            $activity = D('Activity')->getById($v['activity_id']);
            $v['activity_name'] = $activity['title'];
            //找到对应的用户
            $user = D('User')->getById($v['user_id']);
            $v['user_name'] = $user['user_name'];
            $v['phone'] = $user['phone'];
        }
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '用户留言';
        $this->display();
    }


    /**
     * 删除或恢复
     */
    public function delete()
    {
        $id = I('post.id');
        $arr = D('UserMessageBoard')->getById($id);
//        var_dump($arr);exit;
        if($arr['status'] == 1){
            $data['id'] = $id;
            $data['status'] = 0;
            $re = D('UserMessageBoard')->updateData($data);
            if($re){
                echo show(1,'删除成功');
            }else{
                echo show(0,'删除失败');
            }
        }else{
            $data['id'] = $id;
            $data['status'] = 1;
            $re = D('UserMessageBoard')->updateData($data);
            if($re){
                echo show(1,'恢复成功');
            }else{
                echo show(0,'恢复失败');
            }
        }

    }
}