<?php
/**
 * 系统设置控制器
 */

namespace Run\Controller;


class SystemController extends RunController
{

    public function __construct()
    {
        parent::__construct();
        $this->module = '系统管理';
    }

    /**
     * 网站配置
     */
    public function index()
    {
        $arr = D('System')->getAll();
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '网站配置';
        $this->display();
    }

    /**
     * 添加网站配置
     */
    public function add()
    {
        $this->menu = '添加网站配置';
        $this->display();
    }


    /**
     * 添加数据
     */
    public function addData()
    {
        $data = I('post.');
        $re = D('System')->addData($data);
        if($re){
            echo show(1,'添加成功');
        }else{
            echo show(0,'添加失败');
        }
    }


    /**
     * 修改网站配置
     */
    public function edit()
    {
        $id = I('get.id');
        $arr = D('System')->getById($id);
        $this->assign($arr);
        $this->menu = '修改网站配置';
        $this->display();
    }

    /**
     * 修改数据
     */
    public function editData()
    {
        $data = I('post.');
        $re = D('System')->editData($data);
        if($re){
            echo show(1,'修改成功');
        }else{
            echo show(0,'修改失败');
        }
    }

    /**
     * 删除网站配置
     */
    public function delete()
    {
        $id = I('post.id');
        $re = D('System')->deleteData($id);
        if($re){
            echo show(1,'删除成功');
        }else{
            echo show(0,'删除失败');
        }
    }
}