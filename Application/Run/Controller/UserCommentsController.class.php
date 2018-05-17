<?php
/**
 * 用户评论控制器
 */

namespace Run\Controller;


class UserCommentsController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '用户管理';
    }

    /**
     * 用户互评
     */
    public function index()
    {
        $arr = D('UserComments')->getAllJoinActivityAndUser();
        foreach($arr as &$v){
            $user = D('User')->getById($v['comment_object_user_id']);
            $v['comment_object_user_name'] = $user['user_name'];
        }
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '用户互评';
        $this->display();
    }


    /**
     * 删除或恢复
     */
    public function delete()
    {
        $id = I('post.id');
        $arr = D('UserComments')->getById($id);
        if($arr['status'] == 1){
            $data['id'] = $id;
            $data['status'] = 0;
            $re = D('UserComments')->updateData($data);
            if($re){
                echo show(1,'删除成功');
            }else{
                echo show(0,'删除失败');
            }
        }else{
            $data['id'] = $id;
            $data['status'] = 1;
            $re = D('UserComments')->updateData($data);
            if($re){
                echo show(1,'恢复成功');
            }else{
                echo show(0,'恢复失败');
            }
        }

    }
}