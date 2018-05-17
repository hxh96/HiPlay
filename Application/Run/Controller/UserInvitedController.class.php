<?php
/**
 * 用户应邀控制器
 */

namespace Run\Controller;


class UserInvitedController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '内容管理';
    }


    /**
     * 应邀列表
     */
    public function index()
    {
        $map = I('get.');
        $arr = D('UserInvited')->getAll($map);
        //找到对应的活动和用户
        foreach($arr as &$v){
            $user = D('User')->getById($v['user_id']);
            $v['user_name'] = $user['user_name'];//应邀者名称
            $v['user_phone'] = $user['phone'];//应邀者手机
            $activity = D('Activity')->getById($v['activity_id']);
            $v['activity_title'] = $activity['title'];//活动标题
            $initiate = D('User')->getById($activity['initiate_user_id']);
            $v['initiate_user_phone'] = $initiate['phone'];//发起者手机
            $v['initiate_user_id'] = $initiate['id'];//发起者ID
            $v['initiate_user_name'] = $initiate['user_name'];//发起者名称
        }
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '应邀列表';
        $this->display();
    }

}