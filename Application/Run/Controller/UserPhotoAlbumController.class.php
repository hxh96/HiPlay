<?php
/**
 * 用户相册控制器
 */

namespace Run\Controller;


class UserPhotoAlbumController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '用户管理';
    }

    /**
     * 相册列表
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
        $arr = D('UserPhotoAlbum')->getAll($map);
        foreach($arr as &$v){
            $user = D('User')->getById($v['user_id']);
            $v['username'] = $user['user_name'];//用户名
        }
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '用户相册';
        $this->display();
    }


    /**
     * 删除相册
     */
    public function delete()
    {
        $id = I('post.id');
        $arr = D('UserPhotoAlbum')->getById($id);
        if($arr['status'] == 1){
            $data['id'] = $id;
            $data['status'] = 0;
            $re = D('UserPhotoAlbum')->updateData($data);
            if($re){
                echo show(1,'删除成功');
            }else{
                echo show(0,'删除失败');
            }
        }else{
            $map['user_id'] = $arr['user_id'];
            $map['status'] = 1;
            $user_photo_album = D('UserPhotoAlbum')->getAll($map);
//            var_dump($user_photo_album);exit;
            if(count($user_photo_album) >= 6){
                echo show(400,'相册数量已达上限');exit;
            }
            $data['id'] = $id;
            $data['status'] = 1;
            $re = D('UserPhotoAlbum')->updateData($data);
            if($re){
                echo show(1,'恢复成功');
            }else{
                echo show(0,'恢复失败');
            }
        }

    }
}