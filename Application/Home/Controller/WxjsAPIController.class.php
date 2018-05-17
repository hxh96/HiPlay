<?php
/**
 * WxjsAPI控制器
 */

namespace Home\Controller;


use Think\Controller;

class WxjsAPIController extends Controller
{
    /**
     * 初始化
     */
    public function _initialize()
    {
        //引入WxPayPubHelper
        vendor('WxPayPubHelper.WxPayPubHelper');
    }

    public function jsApiCall()
    {
        $data = I('post.');
        requestLog($data);
        //使用jsapi接口
        $jsApi = new \JsApi_pub();

        //=========步骤1：根据用户id找到openid============
        $user = D('User')->getById($data['user_id']);
        if(!$user){
            echo show(400,'未找到该用的信息');exit;
        }else{
            $openid = $user['openid'];
        }
        //=========步骤2：使用统一支付接口，获取prepay_id============
        //使用统一支付接口
        $unifiedOrder = new \UnifiedOrder_pub();
        //设置统一支付接口参数
        //设置必填参数
        //appid已填,商户无需重复填写
        //mch_id已填,商户无需重复填写
        //noncestr已填,商户无需重复填写
        //spbill_create_ip已填,商户无需重复填写
        //sign已填,商户无需重复填写
        $unifiedOrder->setParameter("openid",$openid);//商品描述
        $unifiedOrder->setParameter("body","Hi玩");//商品描述
        //自定义订单号，此处仅作举例
        $timeStamp = time();
        $total_fee = 100;
        $out_trade_no = C('WxPayConf_pub.MCHID').$timeStamp;
        $unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号
        $unifiedOrder->setParameter("total_fee",$total_fee);//总金额
        $unifiedOrder->setParameter("notify_url",C('WxPayConf_pub.NOTIFY_URL'));//通知地址
        $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
        $prepay_id = $unifiedOrder->getPrepayId();
        //=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);
//        var_dump('金额'.$total_fee);
        $jsApiParameters = $jsApi->getParameters();
        //日志
        $this->notify();
        echo show(200,'成功',json_decode($jsApiParameters,true));
//        var_dump(json_decode($jsApiParameters,true));
//        $this->jsapi = $jsApiParameters;
//        $this->assign(json_decode($jsApiParameters,true));
//        $this->display('pay');
        //echo $jsApiParameters;
    }


    public function notify()
    {
        //使用通用通知接口
        $notify = new \Notify_pub();

        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);

        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
//        echo $returnXml;

        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======

        //以log文件形式记录回调信息
//         $log_ = new Log_();
        $log_name= "./log/wx/notify_url.log";//log文件路径

        log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");

        if($notify->checkSign() == TRUE)
        {
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                log_result($log_name,"【通信出错】:\n".$xml."\n");
            }
            elseif($notify->data["result_code"] == "FAIL"){
                //此处应该更新一下订单状态，商户自行增删操作
                log_result($log_name,"【业务出错】:\n".$xml."\n");
            }
            else{
                //此处应该更新一下订单状态，商户自行增删操作
                log_result($log_name,"【支付成功】:\n".$xml."\n");
                //将xml转换成数组
                $order = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
            }

            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息
        }
    }
}