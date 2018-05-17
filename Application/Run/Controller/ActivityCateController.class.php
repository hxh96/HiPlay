<?php
/**
 * 活动费用类型控制器
 */

namespace Run\Controller;


class ActivityCateController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '平台管理';
    }

    /**
     * 活动费用类型
     */
    public function index()
    {
        $arr = D('ActivityCate')->getAll();
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '活动费用类型';
        $this->display();
    }


    /**
     * 添加
     */
    public function add()
    {
        $this->menu = '添加费用类型';
        $this->display();
    }

    /**
     * 添加数据
     */
    public function addData()
    {
        $data = I('post.');
        $data['inputtime'] = date('Y-m-d H:i:s');
        $re = D('ActivityCate')->addData($data);
        if($re){
            echo show(1,'添加成功');
        }else{
            echo show(0,'添加失败');
        }
    }

    /**
     * 修改数据
     */
    public function edit()
    {
        $id = I('get.id');
        $arr = D('ActivityCate')->getById($id);
        $this->assign($arr);
        $this->menu = '修改费用类型';
        $this->display();
    }

    /**
     * 修改数据
     */
    public function editData()
    {
        $data = I('post.');
        $re = D('ActivityCate')->updateData($data);
        if($re){
            echo show(1,'修改成功');
        }else{
            echo show(0,'修改失败');
        }
    }


    /**
     * 删除分类
     */
    public function delete()
    {
        $id = I('post.id');
        $map['status'] = array('neq',2);
        $map['activity_cate_id'] = $id;
        $arr = D('Activity')->getAll($map);
        if($arr){
            echo show(0,'该类型存在活动信息');exit;
        }
        $data['id'] = $id;
        $data['status'] = 0;
        $re = D('ActivityCate')->updateData($data);
        if($re){
            echo show(1,'删除成功');
        }else{
            echo show(0,'删除失败');
        }
    }


}