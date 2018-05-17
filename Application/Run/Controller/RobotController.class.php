<?php
/**
 * 机器人管理控制器
 */

namespace Run\Controller;


use Think\Controller;

class RobotController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->module = '平台管理';
    }


    /**
     * 机器人管理
     */
    public function index()
    {
        $map['is_robot'] = 1;
        $arr = D('User')->getAll($map);
        $arr = Page($arr,15);
        $this->page = $arr['page'];// 赋值分页输出
        $this->lists = $arr['lists'];
        $this->menu = '机器人管理';
        $this->display();
    }

    /**
     * 添加机器人
     */
    public function add()
    {
        $this->menu = '添加机器人';
        $this->display();
    }


    /**
     * 添加机器人
     */
    public function addData()
    {
        $data = I('post.');
        if(!is_numeric($data['number'])){
            echo show(0,'个数只能是数字');
            exit;
        }
        for($i=0;$i<$data['number'];$i++){
            //构造数据
            // 读取名字库
            $content = @file_get_contents('robotUserName.txt');
            $arr = explode("\n", $content);//转成数组
            $arr_key = array_rand($arr);//取随机下标
            $data['user_name'] = $arr[$arr_key];
            $data['sex'] = rand(1,2);//随机性别
            // 读取头像库
            $data['head_img'] = '/Upload/2018/04/16/tx1 ('.rand(1,723).').jpg';//随机头像
            $data['is_robot'] = 1;//机器人标识
            $data['status'] = 1;//机器人状态
            $data['inputtime'] = date('Y-m-d H:i:s');//添加时间
            $re = D('User')->addUser($data);
            if(!$re){
                echo show(0,'添加失败');exit;
            }
        }
        echo show(1,'添加成功');
    }



    /**
     * 开始添加机器人
     */
//    public function addRobot()
//    {
//        header("Content-type:text/html;charset=utf-8");
//        $get = I('get.');
//        if($get['endTime'] <= date('Y-m-d H:i:s')){
//            echo '结束时间小于当前时间,请重新输入';
//            exit;
//        }
//        ignore_user_abort();//关闭浏览器后，继续执行php代码
//        set_time_limit(0);//程序执行时间无限制
//        $sleep_time = $get['sleepTime'];//多长时间执行一次
//        $end = $get['endTime'];
//        while(true){
//            $now = date('Y-m-d H:i:s');
//            if($now >= $end){
//                break;
//            }
//            //构造数据
//            // 读取名字库
//            $content = @file_get_contents('robotUserName.txt');
//            $arr = explode("\n", $content);//转成数组
//            $arr_key = array_rand($arr);//取随机下标
//            $data['user_name'] = $arr[$arr_key];
//            $data['sex'] = rand(1,2);//随机性别
//            // 读取头像库
////            $content = @file_get_contents('robotHeadImg.txt');
////            $arr = explode("\n", $content);//转成数组
////            $arr_key = array_rand($arr);//取随机下标
//            $data['head_img'] = 'https://'.$_SERVER['HTTP_HOST'].'/Upload/2018/04/16/tx1 ('.rand(1,723).').jpg';//随机头像
//            $data['is_robot'] = 1;//机器人标识
//            $data['status'] = 1;//机器人状态
//            $data['inputtime'] = $now;//添加时间
//            D('User')->addUser($data);
//            sleep($sleep_time);//等待时间，进行下一次操作。
//        }
//        exit();
//    }

    /**
     * 发布活动
     */
    public function addActivity()
    {
        $this->menu = '发布活动';
        $this->display();
    }

    /**
     * 开始发布活动
     */
    public function addRandActivity()
    {
        header("Content-type:text/html;charset=utf-8");
        $get = I('get.');
        if($get['endTime'] <= date('Y-m-d H:i:s')){
            echo '结束时间小于当前时间,请重新输入';
            exit;
        }
        ignore_user_abort();//关闭浏览器后，继续执行php代码
        set_time_limit(0);//程序执行时间无限制
        $sleep_time = $get['sleepTime'];//多长时间执行一次
        $end = $get['endTime'];
        $map['is_robot'] = 1;
        $robot_user = D('User')->getAll($map);
        while(true){
            $now = date('Y-m-d H:i:s');
            if($now >= $end){
                break;
            }
            //构造数据
            $robot_user_key = array_rand($robot_user);//取随机下标
            $data['initiate_user_id'] = $robot_user[$robot_user_key]['id'];
            //费用类型
            $activityCate = D('ActivityCate')->getAll(['status'=>1]);
            $activityCate_key = array_rand($activityCate);//取随机下标
            $data['activity_cate_id'] = $activityCate[$activityCate_key]['id'];
            //活动类型
            $category = D('Category')->getAll();
            $category_key = array_rand($category);//取随机下标
            $data['category_id'] = $category[$category_key]['id'];
            //活动标题
            $data['title'] = $category[$category_key]['name'];
            //随机参与人数
            $data['join_number'] = rand(1,5);
            // 读取内容详情库
//        $content = @file_get_contents('robotActivity.txt');
//        $arr = explode("\n", $content);//转成数组
//        $arr_key = array_rand($arr);//取随机下标
//        $data['content'] = $arr[$arr_key];
            $data['content'] = '';
            $data['inputtime'] = date('Y-m-d H:i:s');
            $data['status'] = 1;
            //开始时间
            $year = date('Y');
            $month = date('m');
            //随机一到三天
            $a = rand(1,3);
            $day = date('d')+$a;
            //判断是否日大于或等于30
            if($day >= 30){
                $day = $a;
                $month = $month+1;
            }
            //随机10点到18点
            $hour = rand(10,18);
            //随机10-59分钟
            $minutes = rand(10,59);
            $data['start_time'] = $year.'-'.$month.'-'.$day.' '.$hour.':'.$minutes.':00';
//            $data['start_time'] = date('Y-m-d H:i:s',time()+3600*$hour*$a*24);
            //随机经度
            $longitude = rand(7381,79609);
            //如果随机到四位就在前面加个0
            if(strlen($longitude) == 4)
            {
                $longitude = '0'.$longitude;
            }
            $longitude = '104.0'.$longitude;
            $data['activity_longitude'] = $longitude;
            //随机纬度
            $latitude = rand(596263,594515);
            $latitude = '30.'.$latitude;
            $data['activity_latitude'] = $latitude;
            $data['activity_address'] = '未知';
            $data['end_time'] = date('Y-m-d H:i:s',strtotime($data['start_time'])+3600*6);//完成时间
            D('Activity')->addData($data);
            sleep($sleep_time);//等待时间，进行下一次操作。
        }
        exit();
    }


    /**
     * 发布活动
     */
    public function addActivityOne()
    {
        $initiate_user_id = I('post.initiate_user_id');
        //构造数据
        $data['initiate_user_id'] = $initiate_user_id;
        //费用类型
        $activityCate = D('ActivityCate')->getAll(['status'=>1]);
        $activityCate_key = array_rand($activityCate);//取随机下标
        $data['activity_cate_id'] = $activityCate[$activityCate_key]['id'];
        //活动类型
        $map['pid'] = array('neq',0);
        $category = D('Category')->getAll($map);
        $category_key = array_rand($category);//取随机下标
        $data['category_id'] = $category[$category_key]['id'];
        //活动标题
        $data['title'] = $category[$category_key]['name'];
        //随机参与人数
        $data['join_number'] = rand(1,5);
        // 读取内容详情库
//        $content = @file_get_contents('robotActivity.txt');
//        $arr = explode("\n", $content);//转成数组
//        $arr_key = array_rand($arr);//取随机下标
//        $data['content'] = $arr[$arr_key];
        $data['content'] = '';
        $data['inputtime'] = date('Y-m-d H:i:s');
        $data['status'] = 1;
        //开始时间
        $year = date('Y');
        $month = date('m');
        //随机一到三天
        $a = rand(1,3);
        $day = date('d')+$a;
        //判断是否日大于或等于30
        if($day >= 30){
            $day = $a;
            $month = $month+1;
        }
        //随机10点到18点
        $hour = rand(10,18);
        //随机10-59分钟
        $minutes = rand(10,59);
        $data['start_time'] = $year.'-'.$month.'-'.$day.' '.$hour.':'.$minutes.':00';
//        $data['start_time'] = date('Y-m-d H:i:s',time()+3600*$hour*$a*24);
        $data['end_time'] = date('Y-m-d H:i:s',strtotime($data['start_time'])+3600*6);//完成时间
//        $data['end_time'] = $year.'-'.$month.'-'.$day.' '.($hour+6).':00:00';//完成时间
        //随机经度
        $longitude = rand(7381,79609);
        //如果随机到四位就在前面加个0
        if(strlen($longitude) == 4)
        {
            $longitude = '0'.$longitude;
        }
        $longitude = '104.0'.$longitude;
        $data['activity_longitude'] = $longitude;
        //随机纬度
        $latitude = rand(596263,594515);
        $latitude = '30.'.$latitude;
        $data['activity_latitude'] = $latitude;
        $data['activity_address'] = '未知';
//        $data['end_time'] = date('Y-m-d H:i:s',time()+3600*6);//完成时间
        $re = D('Activity')->addData($data);
        if($re){
            echo show(1,'发布成功');
        }else{
            echo show(0,'发布失败');
        }
    }


    /**
     * 删除机器人
     */
    public function delete()
    {
        $data = I('post.');
        M()->startTrans();//开启事务
        //删除机器人
        $re = D('User')->deleteRobot($data['id']);
        if($re){
            $activity = D('Activity')->getByInitiateUserId($data['id']);//找到机器人发布的所有活动信息
            //删除收藏信息  应邀信息   取消信息
            foreach($activity as $v){
                //判断是否存在收藏信息
                $collection = D('UserCollection')->getByUserId($v['initiate_user_id']);
                if($collection){
                    $re = D('UserCollection')->deleteRobot($v['id']);
                    if(!$re){
                        M()->rollback();//回滚事务
                        echo show(0,'删除收藏信息失败');exit;
                    }
                }
                //判断是否存在取消信息
                $cancel = D('UserCancel')->getByUserId($v['initiate_user_id']);
                if($cancel){
                    $re = D('UserCancel')->deleteRobot($v['id']);
                    if(!$re){
                        M()->rollback();//回滚事务
                        echo show(0,'删除取消信息失败');exit;
                    }
                }
                //判断是否存在应邀信息
                $invited = D('UserInvited')->getByUserId($v['initiate_user_id']);
                if($invited){
                    $re = D('UserInvited')->deleteRobot($v['id']);
                    if(!$re){
                        M()->rollback();//回滚事务
                        echo show(0,'删除应邀信息失败');exit;
                    }
                }
            }
            //删除机器人发布的活动信息
            $re = D('Activity')->deleteRobot($data['id']);
            if($re){
                M()->commit();//提交事务
                echo show(1,'删除成功');
            }else{
                M()->rollback();//回滚事务
                echo show(0,'删除失败');exit;
            }
        }else{
            M()->rollback();//回滚事务
            echo show(0,'删除失败');exit;
        }
    }


    /**
     * 批量发布
     */
    public function releaseAll()
    {
        $ids = I('post.ids');
        if(empty($ids)){
            echo show(0,'请先选择要发布的对象');
            exit;
        }else{
            foreach($ids as $id){
                //构造数据
                $data['initiate_user_id'] = $id;
                //费用类型
                $activityCate = D('ActivityCate')->getAll(['status'=>1]);
                $activityCate_key = array_rand($activityCate);//取随机下标
                $data['activity_cate_id'] = $activityCate[$activityCate_key]['id'];
                //活动类型
                $map['pid'] = array('neq',0);
                $category = D('Category')->getAll($map);
                $category_key = array_rand($category);//取随机下标
                $data['category_id'] = $category[$category_key]['id'];
                //活动标题
                $data['title'] = $category[$category_key]['name'];
                //随机参与人数
                $data['join_number'] = rand(1,5);
                // 读取内容详情库
                $data['content'] = '';
                $data['inputtime'] = date('Y-m-d H:i:s');
                $data['status'] = 1;
                //开始时间
                $year = date('Y');
                $month = date('m');
                //随机一到三天
                $a = rand(1,3);
                $day = date('d')+$a;
                //判断是否日大于或等于30
                if($day >= 30){
                    $day = $a;
                    $month = $month+1;
                }
                //随机10点到18点
                $hour = rand(10,18);
                //随机10-59分钟
                $minutes = rand(10,59);
                $data['start_time'] = $year.'-'.$month.'-'.$day.' '.$hour.':'.$minutes.':00';
//                $data['start_time'] = date('Y-m-d H:i:s',time()+3600*$hour*$a*24);
                $data['end_time'] = date('Y-m-d H:i:s',strtotime($data['start_time'])+3600*6);//完成时间
//                $data['end_time'] = $year.'-'.$month.'-'.$day.' '.($hour+6).':00:00';//完成时间
                //随机经度
                $longitude = rand(7381,79609);
                //如果随机到四位就在前面加个0
                if(strlen($longitude) == 4)
                {
                    $longitude = '0'.$longitude;
                }
                $longitude = '104.0'.$longitude;
                $data['activity_longitude'] = $longitude;
                //随机纬度
                $latitude = rand(596263,594515);
                $latitude = '30.'.$latitude;
                $data['activity_latitude'] = $latitude;
                $data['activity_address'] = '未知';
//                $data['end_time'] = date('Y-m-d H:i:s',time()+3600*6);//完成时间
                $re = D('Activity')->addData($data);
                if(!$re){
                    echo show(0,'发布失败');exit;
                }
            }
            echo show(1,'发布成功');
        }
    }


    /**
     * 批量删除
     */
    public function deleteAll()
    {
        $ids = I('post.ids');
        if(empty($ids)){
            echo show(0,'请先选择要删除的数据');
            exit;
        }else{
            foreach($ids as $id){
                M()->startTrans();//开启事务
                //删除机器人
                $re = D('User')->deleteRobot($id);
                if($re){
                    $activity = D('Activity')->getByInitiateUserId($id);//找到机器人发布的所有活动信息
                    if($activity){
                        //删除收藏信息  应邀信息   取消信息
                        foreach($activity as $v){
                            //判断是否存在收藏信息
                            $collection = D('UserCollection')->getByUserId($v['initiate_user_id']);
                            if($collection){
                                $re = D('UserCollection')->deleteRobot($v['id']);
                                if(!$re){
                                    M()->rollback();//回滚事务
                                    echo show(0,'删除收藏信息失败');exit;
                                }
                            }
                            //判断是否存在取消信息
                            $cancel = D('UserCancel')->getByUserId($v['initiate_user_id']);
                            if($cancel){
                                $re = D('UserCancel')->deleteRobot($v['id']);
                                if(!$re){
                                    M()->rollback();//回滚事务
                                    echo show(0,'删除取消信息失败');exit;
                                }
                            }
                            //判断是否存在应邀信息
                            $invited = D('UserInvited')->getByUserId($v['initiate_user_id']);
                            if($invited){
                                $re = D('UserInvited')->deleteRobot($v['id']);
                                if(!$re){
                                    M()->rollback();//回滚事务
                                    echo show(0,'删除应邀信息失败');exit;
                                }
                            }
                        }
                        //删除机器人发布的活动信息
                        $re = D('Activity')->deleteRobot($id);
                        if($re){
                            M()->commit();//提交事务
                        }else{
                            M()->rollback();//回滚事务
                            echo show(0,'删除活动信息失败');exit;
                        }
                    }else{
                        M()->commit();//提交事务
                    }
                }else{
                    M()->rollback();//回滚事务
                    echo show(0,'删除机器人失败');exit;
                }
            }
            echo show(1,'删除成功');
        }

    }
}