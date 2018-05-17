<?php
/**
 * 测试控制器
 */

namespace Home\Controller;


class CeshiController extends HomeController
{


    public function index()
    {
        header("Content-type:text/html;charset=utf-8");
//        $this->display();
//        exit;


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
        var_dump($data);
        var_dump(count($data));exit;


        $url = 'https://www.hiwan.net.cn/Api/getFansUser';

//        $data['url'] = 'https://hiwan.huangxh.top/index.html';
//        $data['id'] = 66;
//        $data['status'] = 1;
//        $data['why_cancel'] = '不想去了';
        $data['user_id'] = 175	;
        $data['view_user_id'] = 174;
//        $data['attention_user_id'] = 158	;
//        $data['feedback_content'] = '啦啦啦';
//        $data['activity_id'] = 143;
//        $data['money'] = 1;
//        $data['category_id'] = 3;
//        $data['activity_cate_id'] = 1;
//        $data['join_number'] = 3;
//        $data['content'] = '';
//        $data['initiate_user_id'] = 142;
//        $data['start_time'] = '2018-04-19 15:00:00';
//        $data['activity_longitude'] = 104.049424;
//        $data['activity_latitude'] = 30.494742;

        $data['timeStamp'] = date('Y-m-d H:i:s');
//        $str = $data['id'].$data['status'].$data['timeStamp'];
        $str = $data['timeStamp'].$data['user_id'].$data['view_user_id'];
//        $str = $data['attention_user_id'].$data['timeStamp'].$data['user_id'];

//        $str= $data['timeStamp'];
        $sign = base64_encode(md5($str.'NDQ0NTlmMDk4MTU3NTg3NzFiZTFlZmQwNDFjNzA0ZGMyYWY0NzU5ZQ=='));

        $data['sign'] = $sign;



        $res = curlRequest($url,'post',$data);
        var_dump($res);exit;
//        exit;
//        $res = curlRequest($url);
//        $res = json_decode($res,true);
//        foreach($res['data']['movies'] as $v){
//            var_dump($v['nm']);
//        }




//        $this->display();
    }



































//    public function index()
//    {
//        $url1 = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//        $url = 'https://hiwan.huangxh.top/Api/getJsSdk';
//        $data['url'] = $url1	;

//        $str.=  $data['category_id'].$data['content'].$data['initiate_user_id'].$data['join_number'].$data['start_time'];
//        $str= $data['timeStamp'];

//        $res = curlRequest($url,'post',$data);
//        $res = json_decode($res,true);
//        var_dump($res['data']);exit;
//        var_dump($res);exit;
//        $signPackage = A('Jssdk')->getSignPackage($url);
//        $this->assign($res['data']);
//        $this->display();
//    }
}