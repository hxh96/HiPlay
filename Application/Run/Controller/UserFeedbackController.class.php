<?php
/**
 * 用户反馈控制器
 */

namespace Run\Controller;


class UserFeedbackController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '用户管理';
    }

    /**
     * 用户反馈
     */
    public function index()
    {
        $arr = D('UserFeedback')->getAll();
        foreach($arr as &$v){
            $user = D('User')->getById($v['user_id']);
            $v['user_name'] =$user['user_name'];
            $v['phone'] =$user['phone'];
        }
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '用户反馈';
        $this->display();
    }
}