<?php
return array(
	//'配置项'=>'配置值'


    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'hiwan',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'hiwan_',    // 数据库表前缀


    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 2, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符


    /*绑定模块*/
    'MODULE_ALLOW_LIST'    =>    array('Home','Run'),
    'DEFAULT_MODULE'       =>    'Home',


//    'TMPL_EXCEPTION_FILE' => APP_PATH.'/Run/Public/404.html',
    'ERROR_PAGE' =>'/Public/404.html',




    define('WEB_HOST', 'https://www.hiwan.net.cn'),
    /*微信支付配置*/
    'WxPayConf_pub'=>array(
    'APPID' => 'wx7b0e83e677e7f200',
    'MCHID' => '1503382621',
    'KEY' => 'JkiyKL2rainZPPsL7CIfDYYVVE3eIcxj',
    'APPSECRET' => 'a35344f6d95c166e56fab2d741fa280a',
    'JS_API_CALL_URL' => WEB_HOST.'/WxjsAPI/jsApiCall/',
    'SSLCERT_PATH' => WEB_HOST.'/ThinkPHP/Library/Vendor/WxPayPubHelper/cacert/apiclient_cert.pem',
    'SSLKEY_PATH' => WEB_HOST.'/ThinkPHP/Library/Vendor/WxPayPubHelper/cacert/apiclient_key.pem',
    'NOTIFY_URL' =>  WEB_HOST.'/WxjsAPI/notify/',
    'CURL_TIMEOUT' => 30
    )
);