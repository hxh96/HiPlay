<?php
/**
 * 用户关注控制器
 */

namespace Run\Controller;


class UserAttentionController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '用户管理';
    }

    /**
     * 关注列表
     */
    public function index()
    {
        $data = I('get.');
        $arr = D('UserAttention')->getAll($data);
        foreach($arr as &$v){
            $v['user_name'] = D('User')->getById($v['user_id'])['user_name'];
            $v['attention_user_name'] = D('User')->getById($v['attention_user_id'])['user_name'];
        }
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '关注列表';
        $this->display();
    }


    /**
     * 删除数据
     */
    public function delete()
    {
        $id = I('post.id');
        $arr = D('UserAttention')->getById($id);
        if($arr['status'] == 1){
            $data['id'] = $id;
            $data['status'] = 0;
            $re = D('UserAttention')->updateData($data);
            if($re){
                echo show(1,'删除成功');
            }else{
                echo show(0,'删除失败');
            }
        }else{
            $data['id'] = $id;
            $data['status'] = 1;
            $re = D('UserAttention')->updateData($data);
            if($re){
                echo show(1,'恢复成功');
            }else{
                echo show(0,'恢复失败');
            }
        }

    }
}