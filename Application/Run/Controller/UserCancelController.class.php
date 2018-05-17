<?php
/**
 * 用户取消模型
 */

namespace Run\Controller;


class UserCancelController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '内容管理';
    }

    /**
     * 取消列表
     */
    public function index()
    {
        $map = I('get.');
        $arr = D('UserCancel')->getAll($map);
//        var_dump($arr);exit;
        //找到对应的活动和用户
        foreach($arr as &$v){
            $user = D('User')->getById($v['user_id']);
            $v['user_name'] = $user['user_name'];//收藏者名称
            $v['user_phone'] = $user['phone'];//收藏者手机
            $activity = D('Activity')->getById($v['activity_id']);
            $v['activity_title'] = $activity['title'];//活动标题
            $initiate = D('User')->getById($activity['initiate_user_id']);
            $v['initiate_user_phone'] = $initiate['phone'];//发起者手机
        }
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '取消列表';
        $this->display();
    }
}