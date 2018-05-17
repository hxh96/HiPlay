<?php
namespace Home\Controller;
use Think\Controller;

/*
 *微信网页授权
 */
class WxController extends Controller {

     public function __construct(){
       
        parent::__construct();
    }

  
//    public function index(){
//
//       session('prev',$_SERVER['HTTP_REFERER']);//记录回调地址
//
//       $this->getFirst();
//    }

    
/*非静默授权，获取用户详细信息*/

  //获取code
  public function getFirst(){

      $appId        = $this->appid();

      session('appid',$appId);

      $re_url       = "http://".$this->domain()."/index.php/Home/Wx/getWbtoken";

      $redirect_uri = urlencode($re_url);

      $url          = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appId."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=0220#wechat_redirect";
      
      header("location:".$url);
  }

  //获取网页授权access_token
  public function getWbtoken(){

      $appId        = session('appid');

      $secret       = $this->secret();

      $secret       = trim($secret);

      $code         = $_GET['code']; 

      $url          = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appId."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
      
      $res          = curlRequest( $url );

      $res          = json_decode($res,true);

      if( isset($res['errcode']) && $res['errcode'] == 40029 ){

            session('appid',null);

            return false;

      }else{

        if( $this->test_token( $res['openid'], $res['access_token']) ){

            session('secret',$secret);

            $this->getUserInfo( $res['openid'], $res['access_token']);
          }
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
  public function getUserInfo( $openid, $access_token){

      $url      = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
      
      $res      = curlRequest( $url);

      $res      = json_decode($res,true);

      if( isset($res['errcode']) && $res['errcode'] == 40003 ){

        session('appid',null);

        session('secret',null);

        return false;

      }else{

        session('openid',$res['openid']);

        $this->setMember( $res );
      }
  }

 //授权成功,业务逻辑代码(添加用户信息到本站用户系统中)
    public function setMember( $res ){

        var_dump($res);exit;
          $map    = array('openid'=>$res['openid']);

          $member = M('member')->where($map)->find();

              $map['register_time'] = time();
              $map['status']        = 1;
              $map['sex']           = $res['sex'];
              $map['uname']         = $res['nickname'];
              if($res['img'] != null){
                $map['img']           = $res['img'];
              }
              

          if($member == null){

              if( M('member')->add($map) ){

                $mem_id   = M('member')->getLastInsID();

                $mapScore = array( 
                                   'mem_id'=>$mem_id,
                                   'score'=>50,
                                   'score_time'=>time(),
                                   'type'=>'绑定公众号'
                                   );

                M('score')->add($mapScore);
              }

          }else{

              $mem_id      = $member['id'];

              M('member')->where('id='.$mem_id)->save($map);
          }
              session('mem_id',$mem_id);

              $prev        = session('prev');

           if( $prev == null ){

              $prev        = U('Index/index');

           }

           header("location:".$prev);
    }

  //域名
    public function domain(){

//      if( session('?domian') ){

//        return session('domain');
//      }

        return 'www.hiwan.net.cn';
    }

  //APPID
    public function appId(){

//      if( session('?appid') ){

//         return session('appid');
//       }

         return 'wx7b0e83e677e7f200';
    }

  //secret
    public function secret(){

//      if( session('?secret') ){

//        return session('secret');
//      }
      
        return 'a35344f6d95c166e56fab2d741fa280a';
    }
}