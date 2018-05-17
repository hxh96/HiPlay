<?php
/**
 * 积分规则控制器
 */

namespace Run\Controller;


class IntegralRulesController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '积分管理';
    }

    /**
     * 积分规则
     */
    public function index()
    {
        $arr = D('IntegralRules')->getAll();
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '积分规则';
        $this->display();
    }


    /**
     * 添加规则
     */
    public function add()
    {
        $this->menu = '添加规则';
        $this->display();
    }

    /**
     * 添加数据
     */
    public function addData()
    {
        $data = I('post.');
        if(!is_numeric($data['value'])){
            echo show(0,'规则值只能是数字');exit;
        }
        $data['status'] = 1;
        $data['inputtime'] = date('Y-m-d H:i:s');
        $re = D('IntegralRules')->addData($data);
        if($re){
            echo show(1,'添加成功');
        }else{
            echo show(0,'添加失败');
        }
    }


    /**
     * 修改规则
     */
    public function edit()
    {
        $id = I('get.id');
        $arr = D('IntegralRules')->getById($id);
        $this->assign($arr);
        $this->menu = '修改规则';
        $this->display();
    }


    /**
     * 修改数据
     */
    public function editData()
    {
        $data = I('post.');
        if(!is_numeric($data['value'])){
            echo show(0,'手机号只能是数字');exit;
        }
        $re = D('IntegralRules')->updateData($data);
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
        $arr = D('IntegralRules')->getById($id);
        if($arr['status'] == 1){
            $data['id'] = $id;
            $data['status'] = 0;
            $re = D('IntegralRules')->updateData($data);
            if($re){
                echo show(1,'删除成功');
            }else{
                echo show(0,'删除失败');
            }
        }else{
            $data['id'] = $id;
            $data['status'] = 1;
            $re = D('IntegralRules')->updateData($data);
            if($re){
                echo show(1,'恢复成功');
            }else{
                echo show(0,'恢复失败');
            }
        }

    }
}