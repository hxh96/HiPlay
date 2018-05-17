<?php
/**
 * 用户中心控制器
 */

namespace Run\Controller;


class UserController extends RunController
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '用户管理';
    }

    /**
     * 用户列表
     */
    public function index()
    {
        $data = I('get.');
        //计算真实用户个数
        $where['is_robot'] = 0;
        $userNumber = count(D('User')->getAll($where));
        $this->userNumber = $userNumber;
        //搜索
        foreach($data as $k=>$v){
            if(!empty($v)){
                $map[$k] = $v;
            }
        }
        //如果是根据id查找则不判断机器人
        if(!$map['id']){
            $map['is_robot'] = 0;
        }
        $arr = D('User')->getAll($map);
        foreach($arr as &$v){
            //发起条数
            $initiate = D('Activity')->getByInitiateUserId($v['id']);
            $v['initiate_number'] = count($initiate);
            //应邀条数
            $invited = D('UserInvited')->getByUserId($v['id']);
            $v['invited_number'] = count($invited);
            //收藏条数
            $collection = D('UserCollection')->getByUserId($v['id']);
            $v['collection_number'] = count($collection);
            //取消条数
            $cancel = D('UserCancel')->getByUserId($v['id']);
            $v['cancel_number'] = count($cancel);
            //推荐者
            if($v['source']){
                $v['source_name'] = D('User')->getById($v['source'])['user_name'];
            }
            //用户积分
            $UserIntegral = D('UserIntegral')->getByUserId($v['id']);
            $IntegralNumber = 0;
            foreach($UserIntegral as $z){
                $IntegralNumber = $IntegralNumber+$z['value'];
            }
            $v['user_integral_number'] = $IntegralNumber;
            //用户相册
            $UserPhotoAlbumNumber = count(D('UserPhotoAlbum')->getAll(array('user_id'=>$v['id'],'status'=>1)));
            $v['user_photo_album_number'] = $UserPhotoAlbumNumber;
            //关注数量
            $UserAttention = count(D('UserAttention')->getAll(array('user_id'=>$v['id'],'status'=>1)));
            $v['attention_number'] = $UserAttention;
            //粉丝数量
            $UserFans = count(D('UserAttention')->getAll(array('attention_user_id'=>$v['id'],'status'=>1)));
            $v['fans_number'] = $UserFans;
        }
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '用户列表';
        $this->display();
    }


    /**
     * 删除用户
     */
    public function delete()
    {
        $id = I('post.id');
        $arr = D('User')->getById($id);
        if($arr['status'] == 1){
            $data['id'] = $id;
            $data['status'] = 2;
            $re = D('User')->updateUser($data);
            if($re){
                echo show(1,'封停成功');
            }else{
                echo show(0,'封停失败');
            }
        }else{
            $data['id'] = $id;
            $data['status'] = 1;
            $re = D('User')->updateUser($data);
            if($re){
                echo show(1,'恢复成功');
            }else{
                echo show(0,'恢复失败');
            }
        }

    }


    /**
     * 修改官方账号
     */
    public function UserOfficial()
    {
        $data = I('post.');
        $re = D('User')->updateUser($data);
        if($re){
            echo show(1,'修改成功');
        }else{
            echo show(0,'修改失败');
        }
    }

    /**
     * 导出用户数据
     *@param $data    一个二维数组,结构如同从数据库查出来的数组
     *@param $title   excel的第一行标题,一个数组,如果为空则没有标题
     *@param $filename 下载的文件名
     *@examlpe
    $stu = M ('User');
    $arr = $stu -> select();
    exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
     */
    public function downUserInfo($data=array(),$title=array(),$filename='用户信息'){
        $title = array(
            'ID',
            '用户名',
            '身份证号',
            '真实姓名',
            '手机号',
            'QQ',
            '邮箱',
            '年龄',
            '性别',
            '所在地',
            '是否关注公众号',
            '推荐人',
            '状态',
            '注册时间',
            '最后一次登录时间',
            '官方账号',
        );

        $map['is_robot'] = 0;
//        $map= [];
        $data =   D('User')->getAll($map);
        foreach($data as &$v){
            unset($v['password']);
            unset($v['openid']);
            unset($v['unionid']);
            unset($v['head_img']);
            unset($v['personality_sign']);
            unset($v['is_robot']);
            $v['sex']==1?$v['sex']='男':$v['sex']='女';
            $v['subscribe']==1?$v['subscribe']='是':$v['subscribe']='否';
            $v['is_official']==1?$v['is_official']='是':$v['is_official']='否';
            $v['source'] = D('User')->getById($v['source'])['user_name'];
            $v['status']==1?$v['status']='正常':$v['status']='封停';
        }
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=".$filename.".xlsx");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        if (!empty($title)){
            foreach ($title as $k => $v) {
                $title[$k]=iconv("UTF-8", "GB2312",$v);
            }
            $title= implode("\t", $title);
            echo "$title\n";
        }
        if (!empty($data)){
            foreach($data as $key=>$val){
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key]=implode("\t", $data[$key]);
            }
//            var_dump($data);
            echo implode("\n",$data);
        }
    }

}