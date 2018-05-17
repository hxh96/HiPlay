<?php
/**
 * 接口控制器
 */

namespace Home\Controller;


class ApiController extends HomeController
{
    //申明跨域请求
    public function __construct()
    {
        // 指定允许其他域名访问
        header("Access-Control-Allow-Origin:*");
        // 响应类型
        header("Access-Control-Allow-Methods:POST,GET");
        // 响应头设置
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        parent::__construct();
    }


//    public function index()
//    {
//        $a = urlencode('http://hiwan.huangxh.top/index1.html');
//        var_dump($a);
//    }


    /**
     * 获取openID
     */
    public function getOpenId()
    {
        $appId = $this->appId();
        $secret = $this->secret();
        $secret = trim($secret);
        $data = I('post.');
        requestLog($data);
        //判断请求参数
        if(!$data['code']){
            echo  show(400, '缺少请求参数');exit;
        }
//        $this->signCheck($data);//签名验证
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appId . "&secret=" . $secret . "&code=" . $data['code'] . "&grant_type=authorization_code";
        $res = curlRequest($url);
        $res = json_decode($res, true);
        if (isset($res['errcode']) && $res['errcode'] == 40029) {
            echo  show(400, '获取openid失败');exit;
        } else {
            if ($this->test_token($res['openid'], $res['access_token'])) {
                session('secret', $secret);
                //判断是否用户分享页面进入的
                if ($data['source']) {
                    session('source', $data['source']);
                }
                echo $this->getWechatUserInfo($res['openid'], $res['access_token']);exit;
            }
        }
    }


    /**
     * 注册
     */
    public function register()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['phone'] || !$data['password']){
            echo show(400, '缺少请求参数');exit;
        }
        $data['user_name'] = 'Hi玩' . rand(10000, 999999);
        $data['sex'] = 1;
        $data['status'] = 1;
        $data['inputtime'] = date('Y-m-d H:i:s');
        $arr = D('User')->addUser($data);
        if (!$arr) {
            echo show(400, '注册失败');exit;
        } else {
            echo  show(200, '注册成功', $arr);exit;
        }
    }

    /**
     * 登录
     */
    public function login()
    {
        $data = I('get.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['phone'] || !$data['password']){
            echo show(400, '缺少请求参数');exit;
        }
        $arr = D('User')->getUserByPhone($data['phone']);
        if (!$arr) {
            echo show(400, '未找到该用户相关信息');exit;
        } else {
            if ($data['password'] === $arr['password']) {
                $map['id'] = $arr['id'];
                $map['lost_login_time'] = date('Y-m-d H:i:s');
                D('User')->updateUser($map);//更新用户最后登录时间
                echo  show(200, '登录成功', $arr);exit;
            } else {
                echo show(400, '密码错误');exit;
            }
        }
    }


    /**
     * 修改密码
     */
    public function changePwd()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['id'] || !$data['password']){
            echo show(400, '缺少请求参数');exit;
        }
        $arr = D('User')->updateUser($data);
        if ($arr) {
            echo show(200, '修改成功');exit;
        } else {
            echo  show(400, '修改失败');exit;
        }
    }


    /**
     * 获取用户信息
     */
    public function getUserInfo()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['id']){
            echo show(400, '缺少请求参数');exit;
        }
        $arr = D('User')->getUser($data);
        if ($arr) {
            //关注数量
            $UserAttention = count(D('UserAttention')->getAll(array('user_id'=>$arr['id'],'status'=>1)));
            $arr['attention_number'] = $UserAttention;
            //粉丝数量
            $UserFans = count(D('UserAttention')->getAll(array('attention_user_id'=>$arr['id'],'status'=>1)));
            $arr['fans_number'] = $UserFans;
            echo  show(200, '成功', $arr);
        } else {
            echo show(400, '获取用户信息失败');
        }
    }

    /**
     * 获取活动费用类型
     */
    public function getActivityCate()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        $map['status'] = 1;
        $arr = D('ActivityCate')->getAll($map);
        if ($arr) {
            echo show(200, '成功', $arr);
        } else {
            echo show(400, '获取费用类型失败');
        }
    }


    /**
     * 获取活动列表
     */
    public function getActivity()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //拼接条件
        $map = [];
        $map['hiwan_activity.start_time'] = array('gt',date('Y-m-d H:i:s'));//只展示未开始的
        if($data['start_time']){//开始时间
            $map['hiwan_activity.start_time'] = array(array('ELT',$data['start_time']),array('EGT',date('Y-m-d H:i:s')),'and');
        }
        if($data['category_id']){//活动类型
            $map['hiwan_activity.category_id'] = $data['category_id'];
        }
        if($data['activity_cate_id']){//费用类型类型
            $map['hiwan_activity.activity_cate_id'] = $data['activity_cate_id'];
        }
        if($data['sex']){//性别
            $map['hiwan_user.sex'] = $data['sex'];
        }
        //经纬度
        if($data['activity_start_longitude'] && $data['activity_end_longitude'] && $data['activity_start_latitude'] && $data['activity_end_latitude']){
            $map['hiwan_activity.activity_longitude'] = array(array('ELT',$data['activity_end_longitude']),array('EGT',$data['activity_start_longitude'],'and'));
            $map['hiwan_activity.activity_latitude'] = array(array('ELT',$data['activity_end_latitude']),array('EGT',$data['activity_start_latitude'],'and'));
        }
        //判断是否分页请求
        if(isset($data['limit_start']) && isset($data['limit_end'])){
            $limit = $data['limit_start'].','.$data['limit_end'];
        }else{
            $limit = '';
        }
//        var_dump($map);
        $map['hiwan_user.status'] = array('eq',1);
        $map['hiwan_activity.status']  = array(array('neq',2),array('neq',3),'and');
        $arr = D('Activity')->getAllJoinUser($map,$limit);
        if ($arr) {
            //找到对应的分类和费用名称
            foreach($arr as &$v){
                $v['category_name'] = D('Category')->getById($v['category_id'])['name'];//分类名称
                $v['activity_cate_name'] = D('ActivityCate')->getById($v['activity_cate_id'])['activity_cate_name'];//费用名称
                $v['start_unix_time'] = strtotime($v['activity_start_time']);//开始时间时间戳
                $v['input_unix_time'] = strtotime($v['activity_inputtime']);//发起时间时间戳
            }
            echo show(200, '成功', $arr);
        } else {
            echo show(400, '未找到相关活动信息');
        }
    }

    /**
     * 获取活动详情
     */
    public function getActivityById()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['id'] || !$data['user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        $where['hiwan_activity.id'] = $data['id'];
        $where['hiwan_activity.status']  = array(array('neq',2),array('neq',3),'and');
        $where['hiwan_user.status'] = array('eq',1);
        $arr = D('Activity')->getOneJoinUserById($where);
        if ($arr) {
            //找到对应的费用名称
            $arr['activity_cate_name'] = D('ActivityCate')->getById($arr['activity_cate_id'])['activity_cate_name'];
            //找到对应的类型名称
            $arr['category_name'] = D('Category')->getById($arr['category_id'])['name'];
            //判断是否收藏
            $map['user_id'] = $data['user_id'];
            $map['activity_id'] = $data['id'];
            $map['status'] = 1;
            $collection = D('UserCollection')->getOne($map);
            if ($collection) {
                $arr['is_collection'] = 1;
                $arr['collection_id'] = $collection['id'];//收藏ID
            } else {
                $arr['is_collection'] = 0;
            }
            //判断是否应邀
            $map2['user_id'] = $data['user_id'];
            $map2['activity_id'] = $data['id'];
            $invited = D('UserInvited')->getOne($map2);
            if ($invited) {
                $arr['is_invited'] = 1;
                $arr['invited_status'] = $invited['status'];//应邀状态
            } else {
                $arr['is_invited'] = 0;
            }
            echo show(200, '获取活动详情成功', $arr);
        } else {
            echo show(400, '获取活动详情失败');
        }
    }


    /**
     * 获取收藏列表
     */
    public function getUserCollection()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        $order = 'hiwan_user_collection.id desc';
        $map['hiwan_user_collection.status'] = 1;
        $map['hiwan_user_collection.user_id'] = $data['user_id'];
        $map['hiwan_activity.status']  = array(array('neq',2),array('neq',3),'and');
        $map['hiwan_user.status'] = array('eq',1);
        //判断是否分页请求
        if(isset($data['limit_start']) && isset($data['limit_end'])){
            $limit = $data['limit_start'].','.$data['limit_end'];
        }else{
            $limit = '';
        }
        $arr = D('UserCollection')->getAllJoinActivityAndJoinUser($map,$order,$limit);
        if ($arr) {
            echo show(200, '成功', $arr);
        } else {
            echo show(400, '没有收藏信息');
        }
    }


    /**
     * 获取取消列表
     */
    public function getUserCancel()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        //判断是否分页请求
        if(isset($data['limit_start']) && isset($data['limit_end'])){
            $limit = $data['limit_start'].','.$data['limit_end'];
        }else{
            $limit = '';
        }
        $arr = D('UserCancel')->getByUserId($data['user_id'],$limit);
        if ($arr) {
            echo show(200, '成功', $arr);
        } else {
            echo show(400, '没有取消信息');
        }
    }


    /**
     * 获取应邀列表
     */
    public function getUserInvited()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        $order = 'hiwan_user_invited.id desc';
//        $map['hiwan_user_invited.status'] = 1;
        $map['hiwan_user_invited.user_id'] = $data['user_id'];
        $map['hiwan_activity.status']  = array(array('neq',2),array('neq',3),'and');
        $map['hiwan_user.status'] = array('eq',1);
        //判断是否分页请求
        if(isset($data['limit_start']) && isset($data['limit_end'])){
            $limit = $data['limit_start'].','.$data['limit_end'];
        }else{
            $limit = '';
        }
        $arr = D('UserInvited')->getAllJoinActivityAndJoinUser($map,$order,$limit);
        if ($arr) {
            echo show(200, '成功', $arr);
        } else {
            echo show(400, '没有应邀信息');
        }
    }

    /*
     * 获取分类列表
     * */
    public function getCategory()
    {
        $map = I('post.');
        $this->signCheck($map);//签名验证
//        requestLog($map);
//        var_dump($map);exit;
        if (empty($map['pid'])) {
            $arr = D('Category')->getAll();
        } else {
            $arr = D('Category')->getAll($map);
        }
        if (!$arr) {
            echo show(400, '未找到所选分类');
        } else {
            if (empty($map['pid'])) {
                $arr = generateTree($arr);//无限极分类
            }
            echo show(200, '成功', $arr);
        }
    }


    /*
     * 获取二级分类列表
     * */
    public function getNextCategory()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
//        requestLog($map);
//        var_dump($map);exit;
        $map['pid'] = array('neq',0);
        $arr = D('Category')->getAll($map);
        if (!$arr) {
            echo show(400, '未找到所选分类');
        } else {
            echo show(200, '成功', $arr);
        }
    }


    /**
     * 获取微信jssdk配置
     */
    public function getJsSdk()
    {
        $data = I('post.');
        requestLog($data);
        //判断请求参数
        if(!$data['url']){
            echo show(400, '缺少请求参数');exit;
        }
//        $data['url'] = 'https://'.$this->domain().'/webapp/index.html';
//        $this->signCheck($data);//签名验证
        $signPackage = A('Jssdk')->getSignPackage($data['url']);
        if ($signPackage) {
            echo show(200, '成功', $signPackage);
        } else {
            echo show(400, '获取jssdk配置失败');
        }
    }


    /**
     * 发起活动
     */
    public function InitiateActivity()
    {
        $data = I('post.');
        requestLog($data);
        //判断请求参数
        if(
            !$data['activity_cate_id']
            || !$data['category_id']
            || !$data['join_number']
            || !$data['initiate_user_id']
            || !$data['start_time']
            || !$data['activity_address']
            || !$data['activity_longitude']
            || !$data['activity_latitude']
        ){
            echo show(400, '缺少请求参数');exit;
        }
//        $this->signCheck($data);//签名验证
        $user = D('User')->getById($data['initiate_user_id']);
        if(empty($user['phone'])){
            echo show(400, '请先完善手机信息');
        }elseif($user['status'] == 2){
            echo show(400, '该账户已被封停,如有疑问请联系官方客服人员');
        }else{
            //完成时间
            $data['end_time'] = date('Y-m-d H:i:s',strtotime($data['start_time'])+3600*6);
            //找到发起者发起的所有活动
            $initiate_activity = D('Activity')->getByInitiateUserId($data['initiate_user_id']);
            if($initiate_activity){
                //判断是否已发布相关时间段的活动
                foreach($initiate_activity as $v){
                    //不是官方账号发布限制
                    if($user['is_official'] != 1){
                        if($v['status'] == 0 || $v['status'] == 1){
                            if($data['start_time'] <= $v['start_time'] && $data['end_time'] >= $v['start_time']){
                                echo show(400,'您已发布该时间段的其他活动');exit;
                            }
                            if($data['start_time'] >= $v['start_time'] && $data['start_time'] <= $v['end_time']){
                                echo show(400,'您已发布该时间段的其他活动');exit;
                            }
                        }
                    }
                }
            }
            if($user['is_official'] == 1){//官方发布
                $arr = D('Activity')->getAll();
                $data['sort'] = $arr[0]['sort']+1;//置顶
                $data['status'] = 1;
            }
            $data['inputtime'] = date('Y-m-d H:i:s');
            $category = D('Category')->getById($data['category_id']);
            if(!$data['title']){
                $data['title'] = $category['name'];//生成默认标题
            }
//            $data['end_time'] = date('Y-m-d H:i:s',time()+3600*6);//完成时间
            $re = D('Activity')->addData($data);
            if ($re) {
                $now = date('Y-m-d');//当前日期
                $is_now = 0;
                //判断是否当日首次发布
                foreach($initiate_activity as $v){
                    $activityTime = date('Y-m-d',strtotime($v['inputtime']));
                    if($now == $activityTime){
                        $is_now = 1;
                    }
                }
                //当日首次发布积分
                if($is_now===0){
                    //获取发布活动积分规则
                    $IntegralRules = D('IntegralRules')->getByVar('release');
                    //插入积分表
                    $user_integral_data['user_id'] = $data['initiate_user_id'];
                    $user_integral_data['integral_rules_id'] = $IntegralRules['id'];
                    $user_integral_data['value'] = $IntegralRules['value'];
                    $user_integral_data['status'] = 1;
                    $user_integral_data['inputtime'] = date('Y-m-d H:i:s');
                    D('UserIntegral')->addData($user_integral_data);
                }
                echo show(200, '发布成功', $re);
            } else {
                echo show(400, '发布失败');
            }
        }
    }

    /**
     * 我发起的
     */
    public function myInitiateActivity()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        //拼接条件
        $map['hiwan_user.id'] = $data['user_id'] ;
//        var_dump($map);
        $order = 'hiwan_activity.id desc';
        $map['hiwan_user.status'] = array('eq',1);
        $map['hiwan_activity.status']  = array(array('neq',2),array('neq',3),'and');
        //判断是否分页请求
        if(isset($data['limit_start']) && isset($data['limit_end'])){
            $limit = $data['limit_start'].','.$data['limit_end'];
        }else{
            $limit = '';
        }
        $arr = D('Activity')->getAllJoinUser($map,$order,$limit);
        if ($arr) {
            //找到对应的分类和费用名称
            foreach($arr as &$v){
                $v['category_name'] = D('Category')->getById($v['category_id'])['name'];//分类名称
                $v['activity_cate_name'] = D('ActivityCate')->getById($v['activity_cate_id'])['activity_cate_name'];//费用名称
            }
            echo show(200, '成功', $arr);
        } else {
            echo show(400, '未找到发起的活动信息');
        }
    }


    /**
     * 取消我发起的活动
     */
    public function cancelMyInitiateActivity()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['id'] || !$data['why_cancel']){
            echo show(400,'缺少请求参数');exit;
        }
        //获取活动信息
        $activity = D('Activity')->getById($data['id']);
        //判断活动是否已完成
        if($activity['end_time'] <= date('Y-m-d H:i:s')){
            echo show(400, '取消失败,该活动已完成');exit;
        }
        M()->startTrans();//开启事务
        //修改活动状态
        $activityData['id'] = $activity['id'];
        $activityData['status'] = 2;
        $res = D('Activity')->updateData($activityData);
        if($res){
            //插入取消表
            $cancel['user_id'] = $activity['initiate_user_id'];
            $cancel['activity_id'] = $activity['id'];
            $cancel['inputtime'] = date('Y-m-d H:i:s');
            $cancel['why_cancel'] = $data['why_cancel'];
            $res = D('UserCancel')->addData($cancel);
            if($res){
                //找到已应邀者
                $map['activity_id'] = $activity['id'];
                $map['status'] = array(array('eq',0),array('eq',1),'or');
                $userInvited = D('UserInvited')->getAll($map);
                //判断是否有人应邀
                if($userInvited){
                    //找到发起者信息
                    $initiate_user = D('User')->getById($activity['initiate_user_id']);
                    foreach($userInvited as $v){
                        //修改应邀状态
                        $update['id'] = $v['id'];
                        $update['status'] = 4;
                        $re = D('UserInvited')->updateData($update);
                        if(!$re){
                            M()->rollback();//回滚事务
                            echo show(400, '取消失败');exit;
                        }else{
                            //找到应邀者
                            $userInvitedOne = D('User')->getById($v['user_id']);
                            //发送微信模板推送消息
                            $this->sendWechatCancelTemplate($userInvitedOne['openid'],'您好,发起者取消了您预约的活动',$initiate_user['user_name'],'无',date('Y-m-d H:i:s'));

                            //发送取消应邀短信消息
                            $data2 = [
                                $initiate_user['user_name'],
                                $activity['title'],
                                $data['why_cancel']==''?'对方未填写原因':$data['why_cancel'],
                            ];
                            $this->sendSmsDynamic($userInvitedOne['phone'],109848,$data2);
                        }
                    }
                }
                M()->commit();//提交事务
                echo show(200, '取消成功');exit;
            }
        }
        M()->rollback();//回滚事务
        echo show(400, '取消失败');
        exit;
    }



    /**
     * 等待发起者确认(新消息)
     */
    public function myMessage()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        $map['hiwan_activity.initiate_user_id'] = $data['user_id'];
        $map['hiwan_activity.start_time'] = array('gt',date('Y-m-d H:i:s'));
        $map['hiwan_user.status'] = array('eq',1);
        $map['hiwan_activity.status']  = array(array('neq',2),array('neq',3),'and');
        $arr = D('Activity')->getAllJoinUser($map);//找到发起的活动信息
        if($arr){
            $lists = [];
            foreach($arr as $k=>&$v){
                //找到费用类型名称
                $v['activity_cate_name'] = D('ActivityCate')->getById($v['activity_cate_id'])['activity_cate_name'];
                //找到对应的类型名称
                $arr['category_name'] = D('Category')->getById($arr['category_id'])['name'];
                $where['activity_id'] = $v['activity_id'];
                $where['status'] = 0;
                $re = D('UserInvited')->getAll($where);//找到等待确认的应邀信息
                if($re){
                    foreach($re as $z){
                        $v['user_invited_id'] = $z['id'];
                        $v['user_invited_user_id'] = $z['user_id'];
                        $v['user_invited_inputtime'] = $z['inputtime'];
                        $v['user_invited_status'] = $z['status'];
                        //找到应邀者信息
                        $invitedUser = D('User')->getById($z['user_id']);//找到等待确认的应邀信息
                        $v['user_invited_head_img'] = $invitedUser['head_img'];
                        $v['user_invited_sex'] = $invitedUser['sex'];
                        $v['user_invited_phone'] = $invitedUser['phone'];
                        $v['user_invited_user_name'] = $invitedUser['user_name'];
                        array_push($lists,$v);
                    }
                }
            }
            echo show(200,'成功',$lists);exit;
        }else{
            echo show(400,'未找到活动信息');exit;
        }
    }



    /**
     * 收藏活动
     */
    public function CollectionActivity()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id'] || !$data['activity_id']){
            echo show(400,'缺少请求参数');exit;
        }
        $data['inputtime'] = date('Y-m-d H:i:s');
        $user = D('User')->getById($data['user_id']);
        if(!$user['phone']){
            echo show(400, '请先完善手机信息');
        }elseif($user['status'] == 2){
            echo show(400, '该账户已被封停,如有疑问请联系官方客服人员');
        }else{
            //找到活动信息
            $activity = D('Activity')->getById($data['activity_id']);
            if($activity['status'] == 2 || $activity['status'] == 3){
                echo show(400, '该活动已被删除,无法收藏');exit;
            }else{
                //看是否已存在记录
                $where['user_id'] = $data['user_id'];
                $where['activity_id'] = $data['activity_id'];
                $where['status'] = 0;
                $re = D('UserCollection')->getOne($where);
                //存在就更新状态,不存在就新增数据
                if($re){
                    $data2['id'] = $re['id'];
                    $data2['status'] = 1;
                    $res = D('UserCollection')->updateData($data2);
                    if ($res) {
                        echo show(200, '收藏成功', $res);exit;
                    } else {
                        echo show(400, '收藏失败');exit;
                    }
                }else{
                    $res = D('UserCollection')->addData($data);
                    if ($res) {
                        echo show(200, '收藏成功', $res);exit;
                    } else {
                        echo show(400, '收藏失败');exit;
                    }
                }

            }

        }
    }

    /**
     * 取消收藏
     */
    public function cancelCollectionActivity()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['id']){
            echo show(400,'缺少请求参数');exit;
        }
        $data['status'] = 0;
        $re = D('UserCollection')->updateData($data);
        if ($re) {
            echo show(200, '取消收藏成功', $re);
        } else {
            echo show(400, '取消收藏失败');
        }
    }


    /**
     * 应邀活动
     */
    public function InvitedActivity()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id'] || !$data['activity_id']){
            echo show(400,'缺少请求参数');exit;
        }
        $data['inputtime'] = date('Y-m-d H:i:s');
        //找到应邀者信息
        $user = D('User')->getById($data['user_id']);
        if(!$user['phone']){
            echo  show(400, '请先完善手机信息');exit;
        }elseif($user['status'] == 2){
            echo show(400, '该账户已被封停,如有疑问请联系官方客服人员');
        }else {
            //找到应邀活动的信息
            $activity = D('Activity')->getById($data['activity_id']);
//            if($activity['status'] != 1){
//                $return = show(400, '该活动正在审核中,暂时无法应邀,您可以先加入收藏,待后台审核通过后方可应邀');
//                echo $return;
//            }else{
            $now = date('Y-m-d H:i:s');
            if ($activity['status'] == 2 || $activity['status'] == 3) {
                echo show(400, '该活动已被删除,请重试选择');exit;
            }elseif($data['user_id'] == $activity['initiate_user_id']){
                echo show(400, '不能应邀自己发布的活动');exit;
            }elseif($now >= $activity['end_time']){
                echo show(400, '该活动已结束');exit;
            } else {
                //判断是否重复应邀
                $where['user_id'] = $data['user_id'];
                $where['activity_id'] = $data['activity_id'];
                $re = D('UserInvited')->getOne($where);
                if($re){
                    echo show(400, '您已应邀过该活动,请勿重复应邀');exit;
                }else{
                    //判断是否存在当前应邀活动开始时间段内的其他应邀
                    $where2['user_id'] = $data['user_id'];
                    $where2['status'] = array(array('eq',0),array('eq',1),'or');
                    $initiate_user2 = D('UserInvited')->getAll($where2);//找到所有的应邀信息
                    if($initiate_user2){
                        foreach($initiate_user2 as $y){
                            $activity2 = D('Activity')->getById($y['activity_id']);//找到已应邀的活动信息
                            if($activity['start_time'] >= $activity2['start_time'] && $activity['start_time'] <= $activity2['end_time']){
                                echo show(400,'您已预约该时间段的其他活动');exit;
                            }
                            if($activity['start_time'] <= $activity2['start_time'] && $activity['end_time'] >= $activity2['start_time']){
                                echo show(400,'您已预约该时间段的其他活动');exit;
                            }
                        }
                    }
                    //判断已应邀人数
                    $invited_number = count(D('UserInvited')->getByActivityId($activity['id']));//已应邀人数
                    if ($invited_number >= $activity['join_number']) {
                        echo show(400, '该活动应邀人数已满');
                        exit;
                    } else {
                        //找到所有应邀信息
                        $where3['user_id'] = $data['user_id'];
                        $UserInvited = D('UserInvited')->getAll($where3);
                        //插入应邀表
                        $re = D('UserInvited')->addData($data);
                        if ($re) {
                            $now = date('Y-m-d');
                            $is_now = 0;
                            foreach($UserInvited as $z){
                                $invitedTime = date('Y-m-d',strtotime($z['inputtime']));
                                if($now == $invitedTime){
                                    $is_now = 1;
                                }
                            }
                            //判断是否当日首次应邀
                            if($is_now===0){
                                //获取发布活动积分规则
                                $IntegralRules = D('IntegralRules')->getByVar('invited');
                                //插入积分表
                                $user_integral_data['user_id'] = $data['user_id'];
                                $user_integral_data['integral_rules_id'] = $IntegralRules['id'];
                                $user_integral_data['value'] = $IntegralRules['value'];
                                $user_integral_data['status'] = 1;
                                $user_integral_data['inputtime'] = date('Y-m-d H:i:s');
                                D('UserIntegral')->addData($user_integral_data);
                            }
                            //判断是否机器人发布的
                            $robot = D('User')->getById($activity['initiate_user_id']);
                            if ($robot['is_robot'] != 1) {
                                //找到发起者信息
                                $initiate_user = D('User')->getById($activity['initiate_user_id']);
                                //找到活动类型
                                $category = D('Category')->getById($activity['category_id']);
                                //发送微信模板消息
                                $this->sendWechatInvitedTemplate($initiate_user['openid'], '您好,用户响应了您发起的预约', $user['user_name'], $user['phone'], $category['name'], $activity['content']);
                                //发送应邀短信消息
                                $data2 = [
                                    $user['user_name'],
                                    $activity['title'],
                                    $user['phone']
                                ];
                                $this->sendSmsDynamic($initiate_user['phone'], 110010, $data2);
                            }
                            echo show(200, '应邀成功,等待发起者响应', $re);
                        } else {
                            echo show(400, '应邀失败');
                        }
                    }

                }

            }
//        }
        }
    }


    /**
     * 改变应邀状态
     */
    public function checkInvitedStatus()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['id'] || !$data['status']){
            echo show(400,'缺少请求参数');exit;
        }else{
            //应邀者取消
            if($data['status'] == 3){
                //判断取消原因参数
                if(!$data['why_cancel']){
                    echo show(400,'缺少请求参数');exit;
                }
            }
        }
        //找到应邀者信息
        $invited = D('UserInvited')->getById($data['id']);
        $invited_user = D('User')->getById($invited['user_id']);
        //找到应邀活动的信息
        $activity = D('Activity')->getById($invited['activity_id']);
        //找到发起者信息
        $initiate_user = D('User')->getById($activity['initiate_user_id']);
        //找到活动类型
        $category = D('Category')->getById($activity['category_id']);
        //找到该活动已确认接受应邀的人数
        $number = count(D('UserInvited')->getByActivityId($invited['activity_id']));
        M()->startTrans();//开启事务
        //修改应邀状态
        $re = D('UserInvited')->updateData($data);
        if($re){
            //判断改变的状态
            if($data['status'] == 1){//发起者确认
                //判断已确认接受应邀人数
                if($number >= (int)$activity['join_number']){
                    M()->rollback();//回滚事务
                    echo show(400, '接受失败,活动人数已达上限');exit;
                }else{
                    M()->commit();//提交事务
                    $message = '接受成功';

                    //发送微信模板推送消息
                    $this->sendWechatInvitedTemplate($invited_user['openid'],'您好,发起者确认了您提交的预约',$initiate_user['user_name'],$initiate_user['phone'],$category['name'],$activity['content']);

                    //发送确认应邀短信消息
                    $data2 = [
                        $initiate_user['user_name'],
                        $activity['title'],
                        $initiate_user['phone']
                    ];
                    $this->sendSmsDynamic($invited_user['phone'],109782,$data2);
                }

            }elseif($data['status'] == 2){//发起者拒绝
                M()->commit();//提交事务
                $message = '拒绝成功';

                //发送微信模板推送消息
                $this->sendWechatCancelTemplate($invited_user['openid'],'您好,发起者拒绝了您提交的预约',$initiate_user['user_name'],'无',date('Y-m-d H:i:s'));

                //发送拒绝应邀短信消息
                $data2 = [
                    $initiate_user['user_name'],
                    $activity['title'],
                ];
                $this->sendSmsDynamic($invited_user['phone'],110013,$data2);
            }elseif($data['status'] == 3){//应邀者取消
                //判断活动是否已结束
                if($activity['end_time'] <= date('Y-m-d H:i:s')){
                    M()->rollback();//回滚事务
                    //修改应邀状态
                    $newData['id'] = $data['id'];
                    $newData['status'] = 5;//应邀超时
                    D('UserInvited')->updateData($newData);
                    echo show(400, '取消失败,该活动已结束');
                    exit;
                }
                //构造数据
                $cancel['user_id'] = $invited['user_id'];
                $cancel['activity_id'] = $invited['activity_id'];
                $cancel['inputtime'] = date('Y-m-d H:i:s');
                $cancel['why_cancel'] = $data['why_cancel'];
                $res = D('UserCancel')->addData($cancel);
                if($res){
                    M()->commit();//提交事务
                    $message = '取消成功';
                    //判断发起者是否是机器人
                    if($initiate_user['is_robot'] != 1){
                        //发送微信模板推送消息
                        $this->sendWechatCancelTemplate($initiate_user['openid'],'您好,应邀者取消了您发布的预约',$invited_user['user_name'],$invited_user['phone'],date('Y-m-d H:i:s'));

                        //发送取消应邀短信消息
                        $data2 = [
                            $invited_user['user_name'],
                            $activity['title'],
                            $data['why_cancel']==''?'对方未填写原因':$data['why_cancel'],
                        ];
                        $this->sendSmsDynamic($initiate_user['phone'],109848,$data2);
                    }

                }else{
                    M()->rollback();//回滚事务
                    echo show(400, '取消失败');
                    exit;
                }
            }
            echo show(200, $message, $re);
        } else {
            M()->rollback();//回滚事务
            echo show(400, '修改失败');
        }
    }

    /**
     * 获取积分规则列表
     */
    public function getIntegralRules()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断是否分页请求
        if(isset($data['limit_start']) && isset($data['limit_end'])){
            $limit = $data['limit_start'].','.$data['limit_end'];
        }else{
            $limit = '';
        }
        $map['status'] = 1;
        //获取积分规则
        $arr = D('IntegralRules')->getAll($map,$limit);
        if($arr){
            echo show(200, '成功',$arr);exit;
        }else{
            echo show(400, '获取积分列表失败');exit;
        }
    }


    /**
     * 判断是否已签到
     */
    public function checkSignIntegral()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        //获取签到积分规则
        $IntegralRules = D('IntegralRules')->getByVar('sign');
        //找到该用户所有的签到数据
        $where['user_id'] = $data['user_id'];
        $where['integral_rules_id'] = $IntegralRules['id'];
        $where['status'] = 1;
        $UserIntegral = D('UserIntegral')->getAll($where);
        $now = date('Y-m-d');
        foreach($UserIntegral as $v){
            $signTime = date('Y-m-d',strtotime($v['inputtime']));
            if($signTime == $now){
                echo show(400, '已签到');exit;
            }
        }
        echo show(200,'签到');exit;
    }

    /**
     * 签到积分
     */
    public function signIntegral()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        //获取签到积分规则
        $IntegralRules = D('IntegralRules')->getByVar('sign');
        //找到该用户所有的签到数据
        $where['user_id'] = $data['user_id'];
        $where['integral_rules_id'] = $IntegralRules['id'];
        $where['status'] = 1;
        $UserIntegral = D('UserIntegral')->getAll($where);
        $now = date('Y-m-d');
        foreach($UserIntegral as $v){
            $signTime = date('Y-m-d',strtotime($v['inputtime']));
            if($signTime == $now){
                echo show(400, '您今天已经签过到了,请明天再来吧');exit;
            }
        }
        //插入积分表
        $user_integral_data['user_id'] = $data['user_id'];
        $user_integral_data['integral_rules_id'] = $IntegralRules['id'];
        $user_integral_data['value'] = $IntegralRules['value'];
        $user_integral_data['status'] = 1;
        $user_integral_data['inputtime'] = date('Y-m-d H:i:s');
        $re = D('UserIntegral')->addData($user_integral_data);
        if ($re) {
            echo show(200, '签到成功');
        } else {
            echo show(400, '签到失败');
        }
    }

    /**
     * 用户反馈
     */
    public function userFeedback()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id'] || !$data['feedback_content']){
            echo show(400,'缺少请求参数');exit;
        }
        $data['inputtime'] = date('Y-m-d H:i:s');
        $re = D('UserFeedback')->addData($data);
        if ($re) {
            echo show(200, '反馈成功');
        } else {
            echo show(400, '反馈失败');
        }
    }


    /**
     * 修改个人信息
     */
    public function updateUserInfo()
    {
        $post = I('post.');
        $this->signCheck($post);//签名验证
        //判断请求参数
        if(!$post['user_id'] || !$post['update_class'] || !$post['update_content']){
            echo show(400,'缺少请求参数');exit;
        }
        if($post['update_class'] == 'phone'){
            $re = D('User')->getUserByPhone($post['update_content']);
            if($re){
                echo show(400, '该手机号已绑定,如有疑问请联系管理员');exit;
            }
        }
        $data['id'] = $post['user_id'];
        $data[$post['update_class']] = $post['update_content'];
        $re = D('User')->updateUser($data);
        if ($re) {
            //判断是否是完善的手机号
            if($post['update_class'] == 'phone'){
                //获取用户信息
                $user = D('User')->getById($post['user_id']);
                //判断是否是别人推荐的用户
                if($user['source']){
                    //给推荐用户加积分
                    //获取推荐用户积分规则
                    $IntegralRules = D('IntegralRules')->getByVar('share');
                    //插入积分表
                    $user_integral_data['user_id'] = $user['source'];
                    $user_integral_data['integral_rules_id'] = $IntegralRules['id'];
                    $user_integral_data['value'] = $IntegralRules['value'];
                    $user_integral_data['status'] = 1;
                    $user_integral_data['inputtime'] = date('Y-m-d H:i:s');
                    D('UserIntegral')->addData($user_integral_data);
                }
            }
            echo show(200, '修改成功');
        } else {
            echo show(400, '修改失败');
        }
    }


    /**
     * 修改头像
     */
    public function updateUserHeadImg()
    {
        $post = I('post.');
        requestLog($post);
        //判断请求参数
        if(!$post['user_id'] || !$post['headImg']){
            echo show(400,'缺少请求参数');exit;
        }

        $base64_image = str_replace(' ', '+', $post['headImg']);
        //post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)){
            //匹配成功
            if($result[2] == 'jpeg'){
                $image_name = uniqid().'.jpg';
                //纯粹是看jpeg不爽才替换的
            }else{
                $image_name = uniqid().'.'.$result[2];
            }
            //文件目录(绝对路径)
            $dir_path = "/Upload/".date('Y')."/".date('m')."/".date('d');
            $new_dir_path = '.'.$dir_path;//构造相对路径
            //判断文件目录是否存在  不存在就创建
            if(!is_dir($new_dir_path)){
                mkdir($new_dir_path);
            }
            $image_file = $new_dir_path."/{$image_name}";
            //服务器文件存储路径
            if (file_put_contents($image_file, base64_decode(str_replace($result[1], '', $base64_image)))){
                $data['id'] = $post['user_id'];
                $data['head_img'] = $dir_path."/{$image_name}";
                $re = D('User')->updateUser($data);
                if ($re) {
                    echo show(200, '修改成功',$data['head_img']);
                } else {
                    echo show(400, '修改失败');
                }
            }else{
                echo show(400,'上传图片失败');
            }
        }else{
            echo show(400,'获取图片信息失败');
        }
    }


    /**
     * 用户相册上传
     */
    public function uploadUserPhotoAlbum()
    {
        $post = I('post.');
        requestLog($post);
        //判断请求参数
        if(!$post['user_id'] || !$post['img']){
            echo show(400,'缺少请求参数');exit;
        }
        //查找用户已上传的相册数量
        $map['user_id'] = $post['user_id'];
        $map['status'] = 1;
        $user_photo_album = D('UserPhotoAlbum')->getAll($map);
        if(count($user_photo_album) >= 6){
            echo show(400,'相册数量已达上限');exit;
        }
        //使用base64编码上传
        $base64_image = str_replace(' ', '+', $post['img']);
        //post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)){
            //匹配成功
            if($result[2] == 'jpeg'){
                $image_name = uniqid().'.jpg';
                //纯粹是看jpeg不爽才替换的
            }else{
                $image_name = uniqid().'.'.$result[2];
            }
            //文件目录(绝对路径)
            $dir_path = "/Upload/".date('Y')."/".date('m')."/".date('d');
            $new_dir_path = '.'.$dir_path;//构造相对路径
            //判断文件目录是否存在  不存在就创建
            if(!is_dir($new_dir_path)){
                mkdir($new_dir_path);
            }
            $image_file = $new_dir_path."/{$image_name}";
            //服务器文件存储路径
            if (file_put_contents($image_file, base64_decode(str_replace($result[1], '', $base64_image)))){
                $data['user_id'] = $post['user_id'];
                $data['img'] = $dir_path."/{$image_name}";
                $data['status'] = 1;
                $data['inputtime'] = date('Y-m-d H:i:s');
                $re = D('UserPhotoAlbum')->addData($data);
                if ($re) {
                    echo show(200, '上传成功',$data['img']);
                } else {
                    echo show(400, '上传失败');
                }
            }else{
                echo show(400,'上传图片失败');
            }
        }else{
            echo show(400,'获取图片信息失败');
        }
    }

    /**
     * 修改用户相册
     */
    public function editUserPhotoAlbum()
    {
        $post = I('post.');
        requestLog($post);
        //判断请求参数
        if(!$post['id'] || !$post['img']){
            echo show(400,'缺少请求参数');exit;
        }

        //使用base64编码上传
        $base64_image = str_replace(' ', '+', $post['img']);
        //post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)){
            //匹配成功
            if($result[2] == 'jpeg'){
                $image_name = uniqid().'.jpg';
                //纯粹是看jpeg不爽才替换的
            }else{
                $image_name = uniqid().'.'.$result[2];
            }
            //文件目录(绝对路径)
            $dir_path = "/Upload/".date('Y')."/".date('m')."/".date('d');
            $new_dir_path = '.'.$dir_path;//构造相对路径
            //判断文件目录是否存在  不存在就创建
            if(!is_dir($new_dir_path)){
                mkdir($new_dir_path);
            }
            $image_file = $new_dir_path."/{$image_name}";
            //服务器文件存储路径
            if (file_put_contents($image_file, base64_decode(str_replace($result[1], '', $base64_image)))){
                $data['id'] = $post['id'];
                $data['img'] = $dir_path."/{$image_name}";
                $re = D('UserPhotoAlbum')->updateData($data);
                if ($re) {
                    echo show(200, '修改成功',$data['img']);
                } else {
                    echo show(400, '修改失败');
                }
            }else{
                echo show(400,'上传图片失败');
            }
        }else{
            echo show(400,'获取图片信息失败');
        }
    }

    /**
     * 删除相册相片
     */
    public function deleteUserPhotoAlbum()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['id']){
            echo show(400,'缺少请求参数');exit;
        }
        $data['status'] = 0;
        $re = D('UserPhotoAlbum')->updateData($data);
        if ($re) {
            echo show(200, '删除成功',$re);
        } else {
            echo show(400, '删除失败');
        }
    }



    /**
     * 获取用户相册
     */
    public function getUserPhotoAlbum()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        $data['status'] = 1;
        $re = D('UserPhotoAlbum')->getAll($data);
        if ($re) {
            echo show(200, '成功',$re);
        } else {
            echo show(400, '未找到该用户的相册信息');
        }
    }


    /**
     * 获取用户积分列表
     */
    public function getUserIntegral()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        //判断是否分页请求
        if(isset($data['limit_start']) && isset($data['limit_end'])){
            $limit = $data['limit_start'].','.$data['limit_end'];
        }else{
            $limit = '';
        }
        $data['status'] = 1;
        $arr = D('UserIntegral')->getAll($data,$limit);
        if ($arr) {
            echo show(200, '成功',$arr);
        } else {
            echo show(400, '未找到该用户的积分信息');
        }
    }


    /**
     * 使用积分置顶活动
     */
    public function useIntegralTopActivity()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id'] || !$data['activity_id']){
            echo show(400,'缺少请求参数');exit;
        }
        //计算用户积分
        $UserIntegral = D('UserIntegral')->getByUserId($data['user_id']);
        $IntegralNumber = 0;
        foreach($UserIntegral as $z){
            $IntegralNumber = $IntegralNumber+$z['value'];
        }
        //找到积分置顶活动的规则
        $IntegralRules = D('IntegralRules')->getByVar('top');
        //判断积分是否足够
        if($IntegralNumber < abs($IntegralRules['value'])){
            echo show(400,'您的积分不足');exit;
        }else{
            M()->startTrans();//开启事务
            //找到除开官方发布的所有
            $map['hiwan_user.is_official'] = 0;
            $map['hiwan_user.status'] = array('eq',1);
            $map['hiwan_activity.status']  = array(array('neq',2),array('neq',3),'and');
            $activity = D('Activity')->getAllJoinUser($map);
            $newData['id'] = $data['activity_id'];//活动ID
            $newData['sort'] = $activity[0]['sort']+0.001;//排序置顶
            //置顶活动
            $re = D('Activity')->updateData($newData);
            if($re){
                //插入积分表
                $user_integral_data['user_id'] = $data['user_id'];
                $user_integral_data['integral_rules_id'] = $IntegralRules['id'];
                $user_integral_data['value'] = $IntegralRules['value'];
                $user_integral_data['status'] = 1;
                $user_integral_data['inputtime'] = date('Y-m-d H:i:s');
                $re = D('UserIntegral')->addData($user_integral_data);
                if ($re) {
                    M()->commit();//提交事务
                    echo show(200, '置顶成功');exit;
                } else {
                    M()->rollback();//回滚事务
                    echo show(400, '置顶失败');exit;
                }
            }else{
                M()->rollback();//回滚事务
                echo show(400,'置顶失败');exit;
            }
        }
    }



    /**
     * 使用积分兑换话费
     */
    public function useIntegralExchangePhone()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id'] || !$data['money']){
            echo show(400,'缺少请求参数');exit;
        }
        //计算用户积分
        $UserIntegral = D('UserIntegral')->getByUserId($data['user_id']);
        $IntegralNumber = 0;
        foreach($UserIntegral as $z){
            $IntegralNumber = $IntegralNumber+$z['value'];
        }
        //找到积分兑换话费的规则
        $IntegralRules = D('IntegralRules')->getByVar('phone_'.$data['money']);
        //判断积分是否足够
        if($IntegralNumber < abs($IntegralRules['value'])){
            echo show(400,'您的积分不足');exit;
        }else{
            //插入积分表
            $user_integral_data['user_id'] = $data['user_id'];
            $user_integral_data['integral_rules_id'] = $IntegralRules['id'];
            $user_integral_data['value'] = $IntegralRules['value'];
            $user_integral_data['status'] = 1;
            $user_integral_data['inputtime'] = date('Y-m-d H:i:s');
            $re = D('UserIntegral')->addData($user_integral_data);
            if ($re) {
                echo show(200, '兑换成功');exit;
            } else {
                echo show(400, '兑换失败');exit;
            }
        }
    }



    /**
     * 使用积分兑换微信红包
     */
    public function useIntegralExchangeWxEnvelope()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['user_id'] || !$data['money']){
            echo show(400,'缺少请求参数');exit;
        }
        //计算用户积分
        $UserIntegral = D('UserIntegral')->getByUserId($data['user_id']);
        $IntegralNumber = 0;
        foreach($UserIntegral as $z){
            $IntegralNumber = $IntegralNumber+$z['value'];
        }
        //找到积分兑换红包的规则
        $IntegralRules = D('IntegralRules')->getByVar('envelope_'.$data['money']);
        if(!$IntegralRules){
            echo show(400,'未找到该金额的红包规则');exit;
        }
        //判断积分是否足够
        if($IntegralNumber < abs($IntegralRules['value'])){
            echo show(400,'您的积分不足');exit;
        }else{
            //插入积分表
            $user_integral_data['user_id'] = $data['user_id'];
            $user_integral_data['integral_rules_id'] = $IntegralRules['id'];
            $user_integral_data['value'] = $IntegralRules['value'];
            $user_integral_data['status'] = 1;
            $user_integral_data['inputtime'] = date('Y-m-d H:i:s');
            $re = D('UserIntegral')->addData($user_integral_data);
            if($re){
                //找到官方账号
                $map['is_official'] = 1;
                $map['status'] = 1;
                $user = D('User')->getAll($map);
                foreach($user as $v){
                    //给官方账号推送积分兑换通知
                    $this->sendWechatIntegral($v['openid'],$IntegralRules['name'],$IntegralRules['value']);
                }
                echo show(200,'兑换成功,等待后台审核发放',$re);exit;
            }else{
                echo show(400,'兑换失败');exit;
            }

        }
    }

    //微信发红包
    public function wxSendRedEnvelope()
    {
        $data = I('post.');
        $IntegralRules = D('IntegralRules')->getByVar('envelope_' . $data['money']);
        if (!$IntegralRules) {
            echo show(400, '未找到该金额的红包规则');
            exit;
        }
        //找到用户openid
        $user = D('User')->getById($data['user_id']);
        //构造金额(微信红包单位为分)
        $money = $data['money']*100;//转换为分
        //调用微信发红包
        $return = A('ComPay')->RedBag($user['openid'],$money);
        $return = json_decode($return,true);
        if($return['code'] == 200) {
            //插入微信红包发放记录表
            $wx_red_envelope['mch_billno'] = $return['data']['mch_billno'];
            $wx_red_envelope['send_listid'] = $return['data']['send_listid'];
            $wx_red_envelope['total_amount'] = $return['data']['total_amount'] * 0.01;//将分转换为元
            $wx_red_envelope['re_openid'] = $return['data']['re_openid'];
            $wx_red_envelope['inputtime'] = date('Y-m-d H:i:s');
            $re = D('WxRedEnvelope')->addData($wx_red_envelope);
            if($re){
                //修改发放状态
                $new['is_send'] = 1;
                $new['id'] = $data['user_integral_id'];
                D('UserIntegral')->updateData($new);
                echo show(200, '发放成功');
                exit;
            } else {
                echo show(400, '发放失败');
                exit;
            }
        }else{
            echo show(400, $return['message']);
            exit;
        }
    }



    /**
     * 获取banner图
     */
    public function getBanner()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        $map['status'] = 1;
        $re = D('Banner')->getAll($map);
        if (!$re) {
            echo show(200, '成功',$re);
        } else {
            echo show(400, '获取banner列表失败');
        }
    }


    /**
     * 用户评论
     */
    public function userComments()
    {
        $data = I('post.');
        requestLog($data);
        if(
            !$data['activity_id']
            ||!$data['comment_user_id']
            ||!$data['act_friendly']
            ||!$data['is_punctual']
            ||!$data['active_degree']
            ||!$data['comment_object_user_id']
        ){
            echo show(400, '缺少请求参数');exit;
        }
        //判断是否重复提交
        $map['activity_id'] = $data['activity_id'];
        $map['comment_user_id'] = $data['comment_user_id'];
        $res = D('UserComments')->getOne($map);
        if($res){
            echo show(400, '请勿重复提交');exit;
        }
        $data['status'] = 1;
        $data['inputtime'] = date('Y-m-d H:i:s');
        $re = D('UserComments')->addData($data);
        if ($re) {
            echo show(200, '评论成功',$re);
        } else {
            echo show(400, '评论失败');
        }
    }


    /**
     * 待评价列表
     */
    public function getWaitingComments()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        if(!$data['user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        //构造待评价数据
        $arr = [];
        //找到用户发起的并且已完成的活动
        $map['hiwan_activity.initiate_user_id'] = $data['user_id'];
        $map['hiwan_activity.status'] = array('neq',2);
        $map['hiwan_activity.end_time'] = array('lt',date('Y-m-d H:i:s'));
        $activity = D('Activity')->getAll($map);
        //追加数据
        if($activity){
            //找到已接受应邀该活动的人
            foreach($activity as $v){
                $where['activity_id'] = $v['id'];
                $where['status'] = 1;
                $UserInvited = D('UserInvited')->getAll($where);
                if($UserInvited){
                    foreach($UserInvited as $y){
                        //追加数组
                        $list['user_invited_id'] = $y['id'];
                        $list['activity_id'] = $y['activity_id'];
                        $list['comment_object_user_id'] = $y['user_id'];
                        $list['comment_cate_name'] = '我发起的';
                        array_push($arr,$list);
                    }
                }
            }
        }
        //找到已被发起者接受的应邀记录
        $map2['user_id'] = $data['user_id'];
        $map2['status'] = 1;
        $UserInvited = D('UserInvited')->getAll($map2);
        if($UserInvited){
            foreach($UserInvited as $z){
                $act = D('Activity')->getById($z['activity_id']);
                //判断活动是否已完成
                $now = date('Y-m-d H:i:s');
                if($now >= $act['end_time']){
                    //追加数组
                    $list['user_invited_id'] = $z['id'];
                    $list['activity_id'] = $z['activity_id'];
                    $list['comment_object_user_id'] = $act['initiate_user_id'];
                    $list['comment_cate_name'] = '我应邀的';
                    array_push($arr,$list);
                }
            }
        }
        if(!$arr){
            echo show(400,'您还没有待评价的信息');
        }
        //找到待评价的信息
        foreach($arr as $k=>$x){
            $where['activity_id'] = $x['activity_id'];
            $where['comment_object_user_id'] = $x['comment_object_user_id'];
            $where['status'] = 1;
            $re = D('UserComments')->getOne($where);
            //已评价则删除信息
            if($re){
                unset($arr[$k]);
            }
        }
//        var_dump($arr);exit;
        //最终的未评价数据
        $lists = [];
        //遍历未评价数据
        foreach($arr as $j){
            $Activity = D('Activity')->getById($j['activity_id']);//所属活动信息
            $User = D('User')->getById($j['comment_object_user_id']);//待评价用户的信息
            $end['comment_object_user_id'] = $User['id'];
            $end['user_name'] = $User['user_name'];
            $end['user_head_img'] = $User['head_img'];
            $end['activity_id'] = $Activity['id'];
            $end['activity_title'] = $Activity['title'];
            $end['activity_start_time'] = $Activity['start_time'];
            $end['activity_end_time'] = $Activity['end_time'];
            $end['activity_address'] = $Activity['activity_address'];
            array_push($lists,$end);
        }
        if($lists){
            echo show(200,'获取待评价列表成功',$lists);
        }else{
            echo show(400,'获取待评价列表失败');
        }
    }


//    /**
//     *待评价活动列表
//     */
//    public function getWaitingCommentsActivity()
//    {
//        $data = I('post.');
//        $this->signCheck($data);//签名验证
//        if(!$data['user_id']){
//            echo show(400,'缺少请求参数');exit;
//        }
//        //找到已被发起者接受的用户应邀记录
//        $map['user_id'] = $data['user_id'];
//        $map['status'] = 1;
//        $UserInvited = D('UserInvited')->getAll($map);
//        if(!$UserInvited){
//            echo show(400,'您还没有参与任何活动');exit;
//        }
//        //用于存储活动信息
//        $list = [];
//        //找到对应活动
//        foreach($UserInvited as $v){
//            $map2['hiwan_activity.id'] = $v['activity_id'];
//            $activity = D('Activity')->getOneJoinUserById($map2);
//            //判断活动是否已完成
//            $now = date('Y-m-d H:i:s');
//            if($now >= $activity['end_time']){
//                //追加数组
//                array_push($list,$activity);
//            }
//        }
//        if(!$list){
//            echo show(400, '未找到对应的活动信息');exit;
//        }
//        //用于存储未评价的活动信息
//        $lists = [];
//        //判断活动是否已评价
//        foreach($list as $z){
//            $where['activity_id'] = $z['activity_id'];
//            $where['comment_user_id'] = $data['user_id'];
//            $re = D('UserComments')->getOne($where);
//            if(!$re){
//                array_push($lists,$z);
//            }
//        }
//        if($lists){
//            echo show(200, '获取待评论活动成功',$lists);
//        }else{
//            echo show(400, '您没有待评论的活动');
//        }
//    }
//
//
//    /**
//     * 待评价用户列表
//     */
//    public function getWaitingCommentsUser()
//    {
//        $data = I('post.');
//        $this->signCheck($data);//签名验证
//        if(!$data['user_id']){
//            echo show(400,'缺少请求参数');exit;
//        }
//        //找到用户发起的并且已完成的活动
//        $map['hiwan_activity.initiate_user_id'] = $data['user_id'];
//        $map['hiwan_activity.status'] = array('neq',2);
//        $map['hiwan_activity.end_time'] = array('lt',date('Y-m-d H:i:s'));
//        $activity = D('Activity')->getAll($map);
//        //用于存储应邀者信息
//        $list = [];
//        //找到活动对应的应邀者
//        foreach($activity as $v){
//            $UserInvited = D('UserInvited')->getByActivityId($v['id']);
//            //判断是否存在应邀用户
//            if($UserInvited){
//                foreach($UserInvited as $z){
//                    array_push($list,$z);
//                }
//            }
//        }
//        if(!$list){
//            echo show(400,'未找到对应的用户信息');exit;
//        }
//        //用于存储未评价的用户
//        $lists = [];
//        //判断是否已评价用户
//        foreach($list as $y){
//            $where['comment_object_user_id'] = $y['user_id'];
//            $where['activity_id'] = $y['activity_id'];
//            $re = D('UserComments')->getOne($where);
//            if(!$re){
//                array_push($lists,$y);
//            }
//        }
//        if(!$lists){
//            echo show(400,'未找到待评价的用户信息');exit;
//        }
//        //用于存储最终的返回结果
//        $arr = [];
//        foreach($lists as $j){
//            //找到对应的活动信息
//            $activitys = D('Activity')->getById($j['activity_id']);
//            //找到应邀者的用户信息
//            $user = D('User')->getById($j['user_id']);
//            $activitys['invited_user_id'] = $user['id'];
//            $activitys['invited_user_name'] = $user['user_name'];
//            $activitys['invited_user_head_img'] = $user['head_img'];
//            array_push($arr,$activitys);
//        }
//        if($arr){
//            echo show(200,'获取待评价的用户成功',$arr);
//        }else{
//            echo show(400,'未找到待评价的用户信息');exit;
//        }
//
//    }


    /**
     * 获取我评论的列表
     */
    public function getMyComments()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        if(!$data['comment_user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        $map['hiwan_user_comments.comment_user_id'] = $data['comment_user_id'];
        $map['hiwan_user_comments.status'] = 1;
        //判断是否分页请求
        if(isset($data['limit_start']) && isset($data['limit_end'])){
            $limit = $data['limit_start'].','.$data['limit_end'];
        }else{
            $limit = '';
        }
        $re = D('UserComments')->getAllJoinActivityAndUser($map,$limit);
        if (!$re) {
            //找到被评论人的名称
            foreach($re as &$v){
                $user = D('User')->getById($v['comment_object_user_id']);
                $v['comment_object_user_name'] = $user['user_name'];
            }
            echo show(200, '获取我评论的列表成功',$re);
        } else {
            echo show(400, '获取我评论的列表失败');
        }
    }

    /**
     * 获取评论我的列表
     */
    public function getCommentsMe()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        if(!$data['comment_object_user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        $map['hiwan_user_comments.comment_object_user_id'] = $data['comment_object_user_id'];
        $map['hiwan_user_comments.status'] = 1;
        //判断是否分页请求
        if(isset($data['limit_start']) && isset($data['limit_end'])){
            $limit = $data['limit_start'].','.$data['limit_end'];
        }else{
            $limit = '';
        }
        $re = D('UserComments')->getAllJoinActivityAndUser($map,$limit);
        if (!$re) {
            //找到评论人的名称
            foreach($re as &$v){
                $user = D('User')->getById($v['comment_user_id']);
                $v['comment_user_name'] = $user['user_name'];
            }
            echo show(200, '获取评论我的列表成功',$re);
        } else {
            echo show(400, '获取评论我的列表失败');
        }
    }


    /**
     * 关注用户
     */
    public function attentionUser()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        if(!$data['user_id'] || !$data['attention_user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        //判断是否关注自己
        if($data['user_id'] == $data['attention_user_id']){
            echo show(400,'您不能关注自己');exit;
        }
        //判断是否已关注
        $re = D('UserAttention')->getOne($data);
        if($re){
            if($re['status'] == 1){
                echo show(400,'您已关注该用户');exit;
            }
            $data['status'] = 1;
            $res = D('UserAttention')->updateData($data);
            if($res){
                echo show(200,'关注成功');exit;
            }else{
                echo show(400,'关注失败');exit;
            }
        }else{
            $data['status'] = 1;
            $data['inputtime'] = date('Y-m-d H:i:s');
            $res = D('UserAttention')->addData($data);
            if($res){
                echo show(200,'关注成功');exit;
            }else{
                echo show(400,'关注失败');exit;
            }
        }
    }

    /**
     * 取消关注
     */
    public function cancelAttentionUser()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        if(!$data['user_id'] || !$data['attention_user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        $data['status'] = 0;
        $res = D('UserAttention')->updateData($data);
        if($res){
            echo show(200,'取消成功');exit;
        }else{
            echo show(400,'取消失败');exit;
        }
    }




    /**
     * 获取关注列表
     * @param int view_user_id 被查看的用户ID
     * @param int user_id 自己的ID
     */
    public function getAttentionUser()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        if(!$data['user_id'] || !$data['view_user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        //判断是否分页显示
        if($data['limit_start'] && $data['limit_end']){
            $limit = $data['limit_start'].','.$data['limit_end'];
        }else{
            $limit = '';
        }
        $map['hiwan_user_attention.user_id'] = $data['view_user_id'];//所查看的用户ID
        $map['hiwan_user_attention.status'] = 1;
        $map['hiwan_user.status'] = 1;
        $UserAttention = D('UserAttention')->getByAttentionUserIdJoinUser($map,$limit);
        if($UserAttention){
            foreach($UserAttention as &$v){
                //判断是否是查看自己的关注列表
                if($data['view_user_id'] == $data['user_id']){
                    $v['is_attention'] = 1;//已关注
                    //判断是否相互关注
                    $where['attention_user_id'] = $v['user_id'];
                    $where['user_id'] = $v['attention_user_id'];
                    $where['status'] = 1;
                    $re = D('UserAttention')->getOne($where);
                    //前端需要先判断是否已关注(is_attention=1)再进行判断是否相互关注
                    if($re){
                        $v['is_each'] = 1;//相互关注
                    }else{
                        $v['is_each'] = 0;
                    }
                }else{//查看别人的关注列表
                    //判断别人关注的用户是否自己也关注了
                    $map2['user_id'] = $data['user_id'];
                    $map2['attention_user_id'] = $v['attention_user_id'];
                    $map2['status'] = 1;
                    $re = D('UserAttention')->getOne($map2);
                    if($re){
                        $v['is_attention'] = 1;//已关注
                        //判断是否相互关注
                        $where2['user_id'] = $v['attention_user_id'];
                        $where2['attention_user_id'] = $data['user_id'];
                        $where2['status'] = 1;
                        $re = D('UserAttention')->getOne($where2);
                        //前端需要先判断是否已关注(is_attention=1)再进行判断是否相互关注
                        if($re){
                            $v['is_each'] = 1;//相互关注
                        }else{
                            $v['is_each'] = 0;
                        }
                    }else{
                        $v['is_attention'] = 0;//未关注
                    }
                }
            }
            echo show(200,'获取关注列表成功',$UserAttention);
//            var_dump($UserAttention);
        }else{
            echo show(400,'还没有关注任何人');
        }
    }


    /**
     * 获取粉丝列表
     * @param int view_user_id 被查看的用户ID
     * @param int user_id 自己的ID
     */
    public function getFansUser()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        if(!$data['user_id'] || !$data['view_user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        //判断是否分页显示
        if($data['limit_start'] && $data['limit_end']){
            $limit = $data['limit_start'].','.$data['limit_end'];
        }else{
            $limit = '';
        }
        $map['hiwan_user_attention.attention_user_id'] = $data['view_user_id'];//所查看的用户ID
        $map['hiwan_user_attention.status'] = 1;
        $map['hiwan_user.status'] = 1;
        $UserAttention = D('UserAttention')->getByUserIdJoinUser($map,$limit);
        if($UserAttention){
            foreach($UserAttention as &$v){
                //判断是否是查看自己的粉丝列表
                if($data['view_user_id'] == $data['user_id']){
                    //判断是否互相关注
                    $where['user_id'] = $v['attention_user_id'];
                    $where['attention_user_id'] = $v['user_id'];
                    $where['status'] = 1;
                    $re = D('UserAttention')->getOne($where);
                    if($re){
                        $v['is_each'] = 1;//相互关注
                        $v['is_attention'] = 1;//已关注
                    }else{
                        $v['is_each'] = 0;
                        $v['is_attention'] = 0;//未关注
                    }
                }else{//查看别人的粉丝列表
                    //判断查看者是否关注对方粉丝
                    $where2['user_id'] = $data['user_id'];
                    $where2['attention_user_id'] = $v['user_id'];
                    $where2['status'] = 1;
                    $re = D('UserAttention')->getOne($where2);
                    if($re){
                        $v['is_attention'] = 1;//已关注
                        //判断是否互相关注
                        $where['user_id'] = $v['user_id'];
                        $where['attention_user_id'] = $data['user_id'];
                        $where['status'] = 1;
                        $re = D('UserAttention')->getOne($where);
                        if($re){
                            $v['is_each'] = 1;//相互关注
                        }else{
                            $v['is_each'] = 0;
                        }
                    }else{
                        $v['is_attention'] = 0;//未关注
                    }
                }
            }
            echo show(200,'获取粉丝列表成功',$UserAttention);
        }else{
            echo show(400,'还没有任何粉丝');
        }
    }


    /**
     * 获取留言列表
     */
    public function getUserMessage()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        if(!$data['user_id'] || !$data['activity_id']){
            echo show(400,'缺少请求参数');exit;
        }
        $activity = D('Activity')->getById($data['activity_id']);
        //判断是否是自己发起的活动
        if($activity['initiate_user_id'] != $data['user_id']){
            $map['user_id'] = $data['user_id'];
        }
        $map['status'] = 1;
        $map['activity_id'] = $data['activity_id'];
        //判断是否分页显示
        if($data['limit_start'] && $data['limit_end']){
            $limit = $data['limit_start'].','.$data['limit_end'];
        }else{
            $limit = '';
        }
        $message = D('UserMessageBoard')->getAll($map,$limit);
        if($message){
            foreach($message as &$v){
                $user = D('User')->getById($v['user_id']);
                $v['user_name'] = $user['user_name'];
            }
            echo show(200,'获取留言列表成功',$message);
        }else{
            echo show(400,'没有留言记录');
        }
    }

    /**
     * 用户留言
     */
    public function userMessage()
    {
        $data = I('post.');
        requestLog($data);
        if(!$data['user_id'] || !$data['activity_id'] || !$data['content']){
            echo show(400,'缺少请求参数');exit;
        }
        $activity = D('Activity')->getById($data['activity_id']);
        //判断是否是自己发起的活动
        if($activity['initiate_user_id'] == $data['user_id']){
            echo show(400,'不能给自己留言');exit;
        }
        $map['activity_id'] = $data['activity_id'];
        $map['user_id'] = $data['user_id'];
        $map['status'] = 1;
        $map['reply'] = array(array('eq',''),array('eq',null),'or');
        $message = D('UserMessageBoard')->getAll($map);
        if(count($message) >= 3){
            echo show(400,'发起者未回复之前只能发送3条留言');exit;
        }
        $data['reply'] = '';
        $data['is_see'] = 0;
        $data['status'] = 1;
        $data['inputtime'] = date('Y-m-d H:i:s');
        $re = D('UserMessageBoard')->addData($data);
        if($re){
            echo show(200,'留言成功',$re);
        }else{
            echo show(400,'留言失败');
        }
    }

    /**
     * 回复用户留言
     */
    public function replyUserMessage()
    {
        $data = I('post.');
        requestLog($data);
        if(!$data['id'] || !$data['reply']){
            echo show(400,'缺少请求参数');exit;
        }
        $re = D('UserMessageBoard')->updateData($data);
        if($re){
            echo show(200,'回复成功',$re);
        }else{
            echo show(400,'回复失败');
        }
    }


    /**
     * 获取未读留言列表
     */
    public function getUnreadUserMessage()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        if(!$data['user_id']){
            echo show(400,'缺少请求参数');exit;
        }
        $map['hiwan_user_message_board.status'] = 1;
        $map['hiwan_user_message_board.is_see'] = 0;
        $map['hiwan_activity.initiate_user_id'] = $data['user_id'];
        $message = D('UserMessageBoard')->getAllJoinActivity($map);
        if($message){
            echo show(200,'获取未读留言成功',$message);
        }else{
            echo show(400,'没有未读留言');
        }
    }

    /**
     * 更新留言状态(未读->已读)
     */
    public function changeUserMessage()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        if(!$data['id']){
            echo show(400,'缺少请求参数');exit;
        }
        $data['is_see'] = 1;
        $re = D('UserMessageBoard')->updateData($data);
        if($re){
            echo show(200,'更新成功',$re);
        }else{
            echo show(400,'更新失败');
        }
    }





    /**
     * 验证敏感词汇
     */
    public function checkSensitive()
    {
        $data = I('post.');
        requestLog($data);
        //判断请求参数
        if(!$data['string']){
            echo show(400, '缺少请求参数');exit;
        }
//        $this->signCheck($data);//签名验证
        $re = keyWordCheck($data['string']);
        if (!$re) {
            echo show(200, '无敏感词汇');
        } else {
            echo show(400, '存在敏感词汇');
        }
    }


    /**
     * 签名验证
     * @param $arr 请求参数数组
     */
    public function signCheck(&$arr)
    {
        $sign = $arr['sign'];
        requestLog($arr);//请求参数日志
        $str = sign($arr);//生成签名
        //签名验证
        if (!checkSign($str, $sign)) {
            echo show(400, '签名失败');
            exit;
        }
        RemoveElement($arr);//条件去除时间戳和签名串
    }


    /**
     * 发送短信验证码
     */
    public function sendMsg()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['phone'] || !$data['templId']){
            echo show(400,'缺少请求参数');exit;
        }
        //随机验证码
        $rand = rand(1000,9999);
        S('mobileCode', $data['phone'] . $rand);//保存session
        $res = [
            $rand
        ];
        echo $this->sendSmsMsg($data['phone'],$data['templId'],$res);
    }


    /**
     * 发送短信
     * @param $phone  手机号
     * @param $templId  模板ID
     * @param $data  短信变量数据
     * @return string
     */
    public function sendSmsMsg($phone,$templId,$data)
    {
        $appid = 1400082825;
        $appkey = "c704bc0b3ae4056326c43cdbc96672cf";

//        $data['phone'] = "18483692324";
//        $data['templId'] = 105642;//正文模板ID
        try {
            $sender = new \Think\Sms\SmsSingleSender($appid, $appkey);
            $params = $data;//变量替换
            $result = $sender->sendWithParam("86", $phone, $templId, $params, "", "", "");
            $result = json_decode($result,true);
            if($result['result'] == 0 || $result['errmsg'] == 'OK'){
                return show(200,'发送成功');
            }else{
                return show(400,'发送失败');
            }
        } catch(\Exception $e) {
            return show(400,'短信异常,请稍后再试');
        }

    }




    /**
     * 短信验证
     */
    public function checkMobileCode()
    {
        $data = I('post.');
        $this->signCheck($data);//签名验证
        //判断请求参数
        if(!$data['phone'] || !$data['code']){
            echo show(400,'缺少请求参数');exit;
        }
        $sessionCode = S('mobileCode');
        if($sessionCode === $data['phone'].$data['code']){
            echo show(200,'验证通过');
        }else{
            echo show(400,'验证码错误');
        }
    }


    /**
     * 发送应邀短信
     * @param $phone 手机号
     * @param $templId 模板ID
     * @param $data 短信变量数据
     */
    public function sendSmsDynamic($phone,$templId,$data)
    {

        $this->sendSmsMsg($phone,$templId,$data);
    }


    /**
     * 发送微信应邀模板消息
     * @param $openid  openid
     * @param $title  标题
     * @param $name  活动名称
     * @param $phone  手机号
     * @param $category  活动类型
     * @param $content  活动说明
     * @return string
     */
    public function sendWechatInvitedTemplate($openid,$title,$name,$phone,$category,$content)
    {
        $AccessToken = $this->getAccessToken();
        if($AccessToken){
            $utl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$AccessToken;
            $data = [
                   "touser"=>$openid,//接受者openid
                   "template_id"=>'O6xr_vZu7kA4KIeqqs3JglZLfL0W6smWu3n0q2CM39U',//模板ID
                   "url"=>'https://www.hiwan.net.cn/index.html#/Message',
                //"url"=>"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx7b0e83e677e7f200&redirect_uri=https%3a%2f%2f".$this->domain()."%2findex.html&response_type=code&scope=snsapi_userinfo&state=0220#wechat_redirect",//模板跳转链接
                   "data"=>[
                            "first"=>[
                                "value"=>$title,
                                "color"=>"#173177"
                           ],
                           "keyword1"=>[
                                "value"=>$name,
                                "color"=>"#173177"
                           ],
                           "keyword2"=>[
                                "value"=>$phone,
                                "color"=>"#173177"
                           ],
                           "keyword3"=>[
                                "value"=>$category,
                                "color"=>"#173177"
                           ],
                           "keyword4"=>[
                                "value"=>$content,
                                "color"=>"#173177"
                           ],
                           "remark"=>[
                                "value"=>"感谢您使用Hi玩平台",
                                "color"=>"#173177"
                           ],
                   ]
            ];
            $res = curlRequest($utl,'post',json_encode($data));
            $res      = json_decode($res,true);
            if($res['errcode'] == 0){
                return  show(200,'模板消息发送成功');
            }else{
                return  show(400,'模板消息发送失败');
            };
        }else{
            return  show(400,'获取access_token失败');
        };
    }


    /**
     * 发送微信取消模板消息
     * @param $openid  openid
     * @param $title  标题
     * @param $name  预约人
     * @param $phone  手机号
     * @param $dateTime  预约时间
     * @return string
     */
    public function sendWechatCancelTemplate($openid,$title,$name,$phone,$dateTime)
    {
        $AccessToken = $this->getAccessToken();
        if($AccessToken){
            $utl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$AccessToken;
            $data = [
                "touser"=>$openid,//接受者openid
                "template_id"=>'LBadOBkDpfrhUS4C7pbchqUG98p5cVa7jaSeas0fWEI',//模板ID
                "url"=>"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx7b0e83e677e7f200&redirect_uri=https%3a%2f%2f".$this->domain()."%2findex.html&response_type=code&scope=snsapi_userinfo&state=0220#wechat_redirect",//模板跳转链接
                "data"=>[
                    "first"=>[
                        "value"=>$title,
                        "color"=>"#173177"
                    ],
                    "keyword1"=>[
                        "value"=>$name,
                        "color"=>"#173177"
                    ],
                    "keyword2"=>[
                        "value"=>$phone,
                        "color"=>"#173177"
                    ],
                    "keyword3"=>[
                        "value"=>$dateTime,
                        "color"=>"#173177"
                    ],
                    "remark"=>[
                        "value"=>"感谢您使用Hi玩平台",
                        "color"=>"#173177"
                    ],
                ]
            ];
            $res = curlRequest($utl,'post',json_encode($data));
            $res      = json_decode($res,true);
            if($res['errcode'] == 0){
                return  show(200,'模板消息发送成功');
            }else{
                return  show(400,'模板消息发送失败');
            };
        };
    }


    /**
     * 发送微信积分兑换模板消息
     * @param $openid  openid
     * @param $name  物品名称
     * @param $number 扣除积分
     * @return string
     */
    public function sendWechatIntegral($openid,$name,$number)
    {
        $AccessToken = $this->getAccessToken();
        if($AccessToken){
            $utl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$AccessToken;
            $data = [
                "touser"=>$openid,//接受者openid
                "template_id"=>'S839jsSJhnGDuectg7yWtZUPuCnCY2eHtsJGigyE3X4',//模板ID
//                "url"=>'https://hiwan.huangxh.top/index.html#/Message',
                //"url"=>"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx7b0e83e677e7f200&redirect_uri=https%3a%2f%2f".$this->domain()."%2findex.html&response_type=code&scope=snsapi_userinfo&state=0220#wechat_redirect",//模板跳转链接
                "data"=>[
                    "first"=>[
                        "value"=>'用户积分兑换',
                        "color"=>"#173177"
                    ],
                    "keyword1"=>[
                        "value"=>$name,
                        "color"=>"#173177"
                    ],
                    "keyword2"=>[
                        "value"=>$number,
                        "color"=>"#173177"
                    ],
                    "keyword3"=>[
                        "value"=>'...',
                        "color"=>"#173177"
                    ],
                    "keyword4"=>[
                        "value"=>'Hi玩平台',
                        "color"=>"#173177"
                    ],
                    "remark"=>[
                        "value"=>"感谢您使用Hi玩平台",
                        "color"=>"#173177"
                    ],
                ]
            ];
            $res = curlRequest($utl,'post',json_encode($data));
            $res      = json_decode($res,true);
            if($res['errcode'] == 0){
                return  show(200,'模板消息发送成功');
            }else{
                return  show(400,'模板消息发送失败');
            };
        }else{
            return  show(400,'获取access_token失败');
        };
    }



    /**
     *获取access_token
     */
    public function getAccessToken()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appId().'&secret='.$this->secret();
        $res      = curlRequest( $url);
        $res      = json_decode($res,true);
//        var_dump($res);

        if(isset($res['access_token'])){
            return $res['access_token'];
        }else{
            return  false;
        }
    }




    //验证access_token的有效性
    public function test_token( $openid, $access_token){

        $url    = "https://api.weixin.qq.com/sns/auth?access_token=".$access_token."&openid=".$openid;

        $res    = curlRequest( $url );

        $res    = json_decode($res,true);

        if( $res['errcode'] == 0 ){

            return true;

        }else{

            return false;
        }
    }

    //获取用户详细信息(作用域为snsapi_userinfo时)
    public function getWechatUserInfo( $openid, $access_token){

        $url      = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
//        $url      = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";

        $res      = curlRequest( $url);

        $res      = json_decode($res,true);

        if( isset($res['errcode']) && $res['errcode'] == 40003 ){

            session('appid',null);

            session('secret',null);

            return  show(400,'获取微信用户详细信息失败');

        } elseif($res['errcode'] == 40001){//access_token失效(重新获取)
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appId().'&secret='.$this->secret();
            $res      = curlRequest( $url);
            $res      = json_decode($res,true);

            if(isset($res['access_token'])){
                return $this->getWechatUserInfo($openid,$res['access_token']);
            }else{
                 return  show(400,'access_token失效');
            }
        }
        else{
            session('openid',$res['openid']);


//            if(!$res['subscribe']){
            $access_token = $this->getAccessToken();
//                var_dump($access_token);
            $url      = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
            $re      = curlRequest( $url);
            $re      = json_decode($re,true);
//            var_dump($re);
            if(isset($re['subscribe'])){
                $res['subscribe'] = $re['subscribe'];
            }else{
                $res['subscribe'] = 0;
            }
//            }
//            var_dump($res);exit;
//            if($res['subscribe'] == 0){
//                return show(401,'请先关注公众号');
//            }else{
                return $this->setMember( $res );
//            }
        }
    }



    //授权成功,业务逻辑代码(添加用户信息到本站用户系统中)
    public function setMember( $res ){
//        var_dump($res);exit;

        $map['unionid']    = $res['unionid'];
//        $map['openid']    = $res['openid'];

        $member = D('User')->getUser($map);//找到对应unionid的用户

        $source = session('source');
        //判断是否用户推荐
        if($source){
            $map['source'] = $source;
        }
        $map['openid']    = $res['openid'];
        $map['sex']           = $res['sex'];
        $map['user_name']         = $res['nickname'];
        $map['address']         = $res['city'];
        $map['status']         = 1;
        $map['subscribe']         = $res['subscribe'];
        //判断协议
        $headImg = parse_url($res['headimgurl']);
        //如果是http则拼接成https
        if($headImg['scheme'] == 'http'){
            $map['head_img']           = 'https://'.$headImg['host'].$headImg['path'];
        }else{
            $map['head_img']           = $res['headimgurl'];
        }

        $map['inputtime']           = date('Y-m-d H:i:s');
        $map['lost_login_time']           = date('Y-m-d H:i:s');

        if($member == null){
            $userId = D('User')->addUser($map);
            if( $userId ){
                $user = D('User')->getUser(['id'=>$userId]);
                //关注数量
                $UserAttention = count(D('UserAttention')->getAll(array('user_id'=>$user['id'],'status'=>1)));
                $user['attention_number'] = $UserAttention;
                //粉丝数量
                $UserFans = count(D('UserAttention')->getAll(array('attention_user_id'=>$user['id'],'status'=>1)));
                $user['fans_number'] = $UserFans;
                return show(200,'获取用户信息成功',$user);
            }else{
                return show(400,'保存用户信息失败');
            }
        }else{
            //关注数量
            $UserAttention = count(D('UserAttention')->getAll(array('user_id'=>$member['id'],'status'=>1)));
            $member['attention_number'] = $UserAttention;
            //粉丝数量
            $UserFans = count(D('UserAttention')->getAll(array('attention_user_id'=>$member['id'],'status'=>1)));
            $member['fans_number'] = $UserFans;
            //更新关注公众号的状态
            if($res['subscribe'] != $member['subscribe']){
                $data['subscribe'] = $res['subscribe'];
            }
            $data['id'] = $member['id'];
            $data['lost_login_time']    = date('Y-m-d H:i:s');
            $re = D('User')->updateUser($data);
            if( $re ){
                return show(200,'获取用户信息成功',$member);
            }else{
                return show(200,'获取用户信息成功,更新最后一次登录时间失败',$member);
            }
        }
    }

    //域名
    public function domain(){
        return 'www.hiwan.net.cn';
    }

    //APPID
    public function appId(){
        return 'wx7b0e83e677e7f200';
    }

    //secret
    public function secret(){
        return 'a35344f6d95c166e56fab2d741fa280a';
    }



}