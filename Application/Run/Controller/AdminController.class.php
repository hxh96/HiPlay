<?php
/**
 * 管理员控制器
 */

namespace Run\Controller;


class AdminController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '系统管理';
    }

    /**
     * 管理员管理
     */
    public function index()
    {
        $arr = D('Admin')->getAll();
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '管理员管理';
        $this->display();
    }


    /**
     * 添加管理员
     */
    public function add()
    {
        $this->menu = '添加管理员';
        $this->display();
    }


    /**
     * 添加数据
     */
    public function addData()
    {
        $data = I('post.');
        if(strlen($data['password']) < 6 || strlen($data['password']) > 16){
            echo show(0,'密码必须是6-16位');exit;
        }
        if(!is_numeric($data['phone'])){
            echo show(0,'手机号只能是数字');exit;
        }
        if(strlen($data['phone']) >11){
            echo show(0,'手机号只能是11位数字');exit;
        }
        $data['password'] = md5($data['password']);
        $data['status'] = 1;
        $data['inputtime'] = date('Y-m-d H:i:s');
        $re = D('Admin')->addData($data);
        if($re){
            echo show(1,'添加成功');
        }else{
            echo show(0,'添加失败');
        }
    }


    /**
     * 修改管理员
     */
    public function edit()
    {
        $id = I('get.id');
        $arr = D('Admin')->getById($id);
        $this->assign($arr);
        $this->menu = '修改管理员';
        $this->display();
    }

    /**
     * 修改数据
     */
    public function editData()
    {
        $data = I('post.');
        if(!is_numeric($data['phone'])){
            echo show(0,'手机号只能是数字');exit;
        }
        if(strlen($data['phone']) >11){
            echo show(0,'手机号只能是11位数字');exit;
        }
        //是否修改密码
        if(!empty($data['password'])){
            if(strlen($data['password']) < 6 || strlen($data['password']) > 16){
                echo show(0,'密码必须是6-16位');exit;
            }
            $data['password'] = md5($data['password']);
        }else{
            $data['password'] = D('Admin')->getById($data['id'])['password'];
        }
        $re = D('Admin')->updateData($data);
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
        $arr = D('Admin')->getById($id);
        if($arr['status'] == 1){
            $data['id'] = $id;
            $data['status'] = 0;
            $re = D('Admin')->updateData($data);
            if($re){
                echo show(1,'删除成功');
            }else{
                echo show(0,'删除失败');
            }
        }else{
            $data['id'] = $id;
            $data['status'] = 1;
            $re = D('Admin')->updateData($data);
            if($re){
                echo show(1,'恢复成功');
            }else{
                echo show(0,'恢复失败');
            }
        }

    }
}