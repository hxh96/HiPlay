<?php
/**
 * Banner图控制器
 */

namespace Run\Controller;


class BannerController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '平台管理';
    }


    /**
     * banner列表
     */
    public function index()
    {
        $arr = D('Banner')->getAll();
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = 'Banner列表';
        $this->display();
    }


    /**
     * 添加信息
     */
    public function add()
    {
        $this->menu = '添加Banner';
        $this->display();
    }


    /**
     * 添加数据
     */
    public function addData()
    {
        $data = I('post.');
        if(!is_numeric($data['sort'])){
            echo show(0,'排序只能是数字');exit;
        }
//        if($data['is_link'] == 1){
//            if(!$data['url']){
//                echo show(0,'要启用外链请先输入链接地址');exit;
//            }
//        }else{
//            if($data['url']){
//                echo show(0,'要使用外链请先选择启用外链');exit;
//            }
//        }
        $data['status'] = 1;
        $data['inputtime'] = date('Y-m-d H:i:s');
        $re = D('Banner')->addData($data);
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
        $arr = D('Banner')->getById($id);
        $this->assign($arr);
        $this->menu = '修改Banner';
        $this->display();
    }

    /**
     * 修改数据
     */
    public function editData()
    {
        $data = I('post.');
        if(!is_numeric($data['sort'])){
            echo show(0,'排序只能是数字');exit;
        }
//        if($data['is_link'] == 1){
//            if(!$data['url']){
//                echo show(0,'要启用外链请先输入链接地址');exit;
//            }
//        }else{
//            if($data['url']){
//                echo show(0,'要使用外链请先选择启用外链');exit;
//            }
//        }
        $re = D('Banner')->updateData($data);
        if($re){
            echo show(1,'修改成功');
        }else{
            echo show(0,'修改失败');
        }
    }

    /**
     * 删除管理员
     */
    public function delete()
    {
        $id = I('post.id');
        $arr = D('Banner')->getById($id);
        if($arr['status'] == 1){
            $data['id'] = $id;
            $data['status'] = 0;
            $re = D('Banner')->updateData($data);
            if($re){
                echo show(1,'删除成功');
            }else{
                echo show(0,'删除失败');
            }
        }else{
            $data['id'] = $id;
            $data['status'] = 1;
            $re = D('Banner')->updateData($data);
            if($re){
                echo show(1,'恢复成功');
            }else{
                echo show(0,'恢复失败');
            }
        }

    }
}