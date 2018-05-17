<?php
/**
 * 用户积分控制器
 */

namespace Run\Controller;


class UserIntegralController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '积分管理';
    }

    /**
     * 积分列表
     */
    public function index()
    {
        $data = I('get.');
        //搜索
        foreach($data as $k=>$v){
            if(!empty($v)){
                $map[$k] = $v;
            }
        }
        $map['status'] = 1;
        $arr = D('UserIntegral')->getAll($map);
        foreach($arr as &$v){
            $user = D('User')->getById($v['user_id']);
            $v['username'] = $user['user_name'];
            $v['phone'] = $user['phone'];
            //获取积分规则
            $IntegralRules = D('IntegralRules')->getById($v['integral_rules_id']);
            $v['instructions'] = $IntegralRules['name'];
            //判断是否是红包
            if(strpos($IntegralRules['name'],'红包')!==false){
                //判断是否已发放
                if($v['is_send'] ==0){//未发放
                    $v['needAudit'] = 1;
                    //找到对应的金额
                    $v['money'] = explode("_",$IntegralRules['var'])[1];
                }

            }
        }
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '积分列表';
        $this->display();
    }


    /**
     * 删除积分
     */
    public function delete()
    {
        $id = I('post.id');
        $arr = D('UserIntegral')->getById($id);
        if($arr['status'] == 1){
            $data['id'] = $id;
            $data['status'] = 0;
            $re = D('UserIntegral')->updateData($data);
            if($re){
                echo show(1,'删除成功');
            }else{
                echo show(0,'删除失败');
            }
        }else{
            $data['id'] = $id;
            $data['status'] = 1;
            $re = D('UserIntegral')->updateData($data);
            if($re){
                echo show(1,'恢复成功');
            }else{
                echo show(0,'恢复失败');
            }
        }

    }
}