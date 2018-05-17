<?php
/**
 * 芝麻信用控制器
 */

namespace Home\Controller;


use Think\Controller;

class ZhiMaController extends Controller
{

    //芝麻信用网关地址
    public $gatewayUrl = "https://zmopenapi.zmxy.com.cn/openapi.do";
    //商户公钥文件
    //芝麻公钥文件
    public $privateKeyFile = "/rsa_private_key.pem";
    public $zmPublicKeyFile = "/rsa_public_key.pem";

    //数据编码格式
    public $charset = "UTF-8";
    //芝麻分配给商户的appId
    public $appId = "300002735";


    /**
     * 初始化
     */
    public function _initialize()
    {
        //引入ZmopClient
        vendor('zmop.ZmopClient');
        //引入ZhimaAuthInfoAuthorizeRequest
        vendor('zmop.request.ZhimaAuthInfoAuthorizeRequest');
        vendor('zmop.request.ZhimaCreditScoreGetRequest');
    }


    public function testZhimaCreditScoreGet(){
        $client = new \ZmopClient($this->gatewayUrl,$this->appId,$this->charset,$this->privateKeyFile,$this->zmPublicKeyFile);
        $request = new \ZhimaCreditScoreGetRequest();
        $request->setChannel("apppc");
        $request->setPlatform("zmop");
        $request->setTransactionId("201512100936588040000000465158");// 必要参数
        $request->setProductCode("w1010100100000000001");// 必要参数
        $request->setOpenId("268810000007909449496");// 必要参数
        $response = $client->execute($request);
        echo json_encode($response);
    }




    //生成移动端SDK 集成需要的sign 参数 ，并进行urlEncode
    public function generateSign($certNo,$name,$certType='IDENTITY_CARD'){
        $client = new \ZmopClient($this->gatewayUrl, $this->appId, $this->charset, $this->privateKeyFile,$this->zmPublicKeyFile);
        $request = new \ZhimaAuthInfoAuthorizeRequest();
        $request->setScene("test");
        // 授权来源渠道设置为appsdk
        $request->setChannel("appsdk");
        // 授权类型设置为2标识为证件号授权见“章节4中的业务入参说明identity_type”
        $request->setIdentityType("2");
        // 构造授权业务入参证件号，姓名，证件类型;“章节4中的业务入参说明identity_param”
        $request->setIdentityParam("{\"certNo\":\"$certNo\",\"certType\":\"IDENTITY_CARD\", \"name\":\"$name\"}");
        // 构造业务入参扩展参数“章节4中的业务入参说明biz_params”
        $request->setBizParams("{\"auth_code\":\"M_APPSDK\"}");

        $params = $client->generateEncryptedParamWithUrlEncode($request);
        $sign = $client->generateSignWithUrlEncode($request);

        $data['gatewayUrl'] = $this->gatewayUrl;
        $data['appId'] = $this->appId;
        $data['charset'] = $this->charset;
        $data['params']=$params;
        $data['sign'] = $sign;
        var_dump($data);exit;
        return $data;
    }




    // 解密
    public function zhimacallback($params){
//        $this->privateKeyFile= "path/rsa_private_key.pem";
        $client = new \ZmopClient($this->gatewayUrl, $this->appId, $this->charset, $this->privateKeyFile,$this->zmPublicKeyFile);
        $result=$client->generateSignCallBack($params,$this->privateKeyFile);
        return $result;
    }

}