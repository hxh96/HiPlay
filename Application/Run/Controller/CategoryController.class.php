<?php
/**
 * 分类管理控制器
 */

namespace Run\Controller;


class CategoryController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '平台管理';
    }

    /**
     * 分类列表
     */
    public function index()
    {
        $pid = I('get.pid',0,int);
        $arr = D('Category')->getByPid($pid);
        foreach($arr as &$v){
            if($v['pid'] == 0){
                $v['p_name'] = '顶级分类';
            }else{
                $v['p_name'] = D('Category')->getById($v['pid'])['name'];
            }
        }
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->pid = $pid;
        $this->menu = '分类列表';
        $this->display();
    }


    /**
     * 添加分类
     */
    public function add()
    {
        $pid = I('get.pid',0,int);
        $this->pid = $pid;
        $arr = D('Category')->getAll();
        $arr = generateTree($arr);//无限极分类
        $this->assign('arr',$arr);
        $this->menu = '添加分类';
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
        $data['inputtime'] = date('Y-m-d H:i:s');
        $re = D('Category')->addData($data);
        if($re){
            echo show(1,'添加成功');
        }else{
            echo show(0,'添加失败');
        }
    }

    /**
     * 修改分类
     */
    public function edit()
    {
        $id = I('get.id');
        $arr = D('Category')->getById($id);
        $this->assign($arr);
        $arr = D('Category')->getAll();
        $arr = generateTree($arr);//无限极分类
        $this->assign('arr',$arr);
        $this->menu = '修改分类';
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
        //判断是否存在下级分类
        $category = D('Category')->getById($data['id']);
        if($data['pid'] != $category['pid'])
        {
            $arr = D('Category')->getByPid($data['id']);
            if($arr){
                echo show(0,'请先移动该分类下的子分类');exit;
            }
        }
        $re = D('Category')->updateData($data);
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
        $arr = D('Category')->getByPid($id);//查找下级分类
        if($arr){
            echo show(0,'请先删除下级分类');exit;
        }
        $arr = D('Activity')->getByCategoryId($id);//查找是否存在该分类的文章
        if($arr){
            echo show(0,'请先删除该分类下的活动信息');exit;
        }
        $re = D('Category')->deleteData($id);
        if($re){
            echo show(1,'删除成功');
        }else{
            echo show(0,'删除失败');
        }

    }

    /**
     * 判断是否存在下级分类
     */
    public function checkPid()
    {
        $pid = I('post.pid');
        $arr = D('Category')->getByPid($pid);
        if($arr){
            echo show(1,'存在下级分类');
        }else{
            echo show(0,'不存在下级分类');
        }
    }
}