<?php
/**
 * 后台平台控制器
 */

namespace Run\Controller;


use Think\Controller;

class RunController extends Controller
{
    public function __construct(){
        header("Content-type:text/html;charset=utf-8");
        parent::__construct();
        $this->_init();
    }

    /**
     * 空方法
     */
    public function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:404");
    }

    /*
    *初始化
    */
    public function _init(){
        $islogin = $this->isLogin();
        if(!$islogin){
            redirect(U('Login/index'));
        }else{
            $this->username = session('adminUser')['username'];
        }
    }




    /*
    *判断是否登陆
    */
    public function isLogin(){
        $user = $this->getUserLogin();
        if($user && is_array($user)){
            return true;
        }else{
            return false;
        }
    }

    /*
    *获取用户登陆信息
    */
    public function getUserLogin(){
        return session('adminUser');
    }
}