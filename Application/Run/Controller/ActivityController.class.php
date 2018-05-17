<?php
/**
 * 活动管理控制器
 */

namespace Run\Controller;


class ActivityController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '内容管理';
    }

    /**
     * 活动列表
     */
    public function index()
    {
        $map = I('get.');
        $order = 'status asc,sort desc,id desc';
        $arr = D('Activity')->getAll($map,$order);
        //找到对应的发布者
        foreach($arr as $k=>&$v){
            $user = D('User')->getById($v['initiate_user_id']);
            //机器人发布的
//            if($user['is_robot'] == 1){
//                unset($arr[$k]);//去掉机器人发布的活动
//            }else{
                $v['initiate_user_name'] = $user['user_name'];//发起者名称
                $v['user_phone'] = $user['phone'];//发起者手机
                $v['activity_cate_name'] = D('ActivityCate')->getById($v['activity_cate_id'])['activity_cate_name'];//费用类型名称
                $v['category_name'] = D('Category')->getById($v['category_id'])['name'];//分类名称
                $v['invited_number'] = count(D('UserInvited')->getByActivityId($v['id']));//应邀人数

                $now = date('Y-m-d H:i:s');
                if($now > $v['end_time']){
                    $v['end_status'] = '已完成';
                }elseif($now < $v['start_time']){
                    $v['end_status'] = '未开始';
                }else{
                    $v['end_status'] = '进行中';
                }
//            $v['merchants_name'] = D('Category')->getById($v['merchants_id'])['name'];
//            }
        }
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '活动列表';
        $this->display();
    }


    /**
     * 发布活动
     */
    public function add()
    {
        //费用类型
        $activityCate = D('ActivityCate')->getAll();
        $this->assign('activityCate',$activityCate);
        //活动类型
        $category = D('Category')->getAll();
        $category = generateTree($category);//无限极分类
        $this->assign('category',$category);
        //官方账号
        $where['is_official'] = 1;
        $official = D('User')->getAll($where);
        $this->assign('official',$official);
        $this->menu='发布活动';
        $this->display();
    }


    /**
     * 发布活动
     */
    public function addData()
    {
        $data = I('post.');
        if(!is_numeric($data['join_number'])){
            echo show(0,'参与只能是数字');exit;
        }
        if(!$data['initiate_user_id']){
            echo show(0,'请先选择发布活动的账号');exit;
        }
        if(!$data['start_time']){
            echo show(0,'请先选择开始时间');exit;
        }
        if(!$data['activity_longitude'] || !$data['activity_latitude']){
            echo show(0,'请先输入经纬度');exit;
        }
        $data['is_official'] = 1;
        $data['status'] = 1;
        $arr = D('Activity')->getAll();
        $data['sort'] = $arr[0]['sort']+1;//置顶
        $data['inputtime'] = date('Y-m-d H:i:s');
        $data['end_time'] = date('Y-m-d H:i:s',time()+3600*6);//完成时间
        $re = D('Activity')->addData($data);
        if($re){
            echo show(1,'添加成功');
        }else{
            echo show(0,'添加失败');
        }
    }



    /**
     * 修改状态
     */
    public function delete()
    {
        $data = I('post.');
        $re = D('Activity')->updateData($data);
        if($re){
            echo show(1,'修改成功');
        }else{
            echo show(0,'修改失败');
        }

    }

    /**
     * 批量修改状态
     */
    public function auditAll()
    {
        $ids = I('post.ids');
        if(empty($ids)){
            echo show(0,'请先选择要通过审核的数据');
            exit;
        }else{
            foreach($ids as $id){
                $data['id'] = $id;
                $data['status'] = 1;
                $re = D('Activity')->updateData($data);
                if(!$re){
                    echo show(0,'审核失败');exit;
                }
            }
            echo show(1,'审核成功');
        }
    }
}