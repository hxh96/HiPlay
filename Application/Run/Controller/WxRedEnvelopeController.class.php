<?php
/**
 * 微信红包发放记录控制器
 */

namespace Run\Controller;


class WxRedEnvelopeController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '积分管理';
    }

    //记录列表
    public function index()
    {
        $arr = D('WxRedEnvelope')->getAll();
        foreach($arr as &$v){
            $user = D('User')->getByOpenid($v['re_openid']);
            $v['username'] = $user['user_name'];
            $v['phone'] = $user['phone'];
            $v['user_id'] = $user['id'];
        }
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '记录列表';
        $this->display();
    }
}