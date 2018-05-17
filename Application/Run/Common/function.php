<?php
/**
 * 公共方法
 */

/*
 * 无限极分类
 * */
function generateTree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
    $tree     = array();
    $packData = array();
    foreach ($list as $data) {
        $packData[$data[$pk]] = $data;
    }
//    var_dump($packData);exit;
    foreach ($packData as $key => $val) {
//        var_dump($val);
        if ($val[$pid] == $root) {
            //代表跟节点, 重点一
            $tree[] = &$packData[$key];
        } else {
            //找到其父类,重点二
            $packData[$val[$pid]][$child][] = &$packData[$key];
        }
    }
    return $tree;
}


/**
 * 分页工具
 * @param $lists  数组
 * @param $pageSize  每页显示条数
 * @return array
 */
function Page($lists,$pageSize)
{
    import('ORG.Util.Page');// 导入分页类
    $count=count($lists);//得到数组元素个数
    $Page= new \Think\Page($count,$pageSize);// 实例化分页类 传入总记录数和每页显示的记录数
    $lists = array_slice($lists,$Page->firstRow,$Page->listRows);
    $show = $Page->show();// 分页显示输出﻿
    $arr = array(
        'lists' =>$lists,
        'page'=>$show."总记录数:".$count."条,每页显示记录数:".$pageSize."条"
    );
    return  $arr;
}


/*
*转json输出
*/
function  show($code, $message,$data=array()) {
    $reuslt = array(
        'code' => $code,
        'message' => $message,
        'data' => $data,
    );
    return json_encode($reuslt);
}

/**
 *  去除时间戳和签名串
 */
function RemoveElement(&$arr){
    unset($arr['timeStamp']);
    unset($arr['sign']);
}


/**
 *  去除时间戳
 */
function RemoveElement1(&$arr){
    unset($arr['sign']);
}

/**
 * 数组拼接字符串
 * @param $arr 数组
 * @return string 返回字符串
 */
function volist($arr)
{
    $str = '';
    foreach($arr as $v){
        $str .= $v;
    }
    beforeSignLog($str);
    return $str;
}

/**
 * 签名验证
 * @param $sign 生成签名
 * @param $requestSign 请求签名
 */
function checkSign($sign,$requestSign)
{
    if($sign === $requestSign){
        return true;
    }else{
        return false;
    }
}


/**
 * 生成签名
 * @param $arr 参数数组
 * @return string 签名字符串
 */
function sign($arr)
{
    RemoveElement1($arr);//去掉签名串
    ksort($arr, 4);//自然排序
    $str = volist($arr);//拼接字符串
    $str = base64_encode(md5($str.'NDQ0NTlmMDk4MTU3NTg3NzFiZTFlZmQwNDFjNzA0ZGMyYWY0NzU5ZQ=='));//加密生成签名
    afterSignLog($str);
    return $str;
}

/*curl操作
 *url---会话请求URL地址
 *method---请求方式，有POST和GET两种，默认get方式
 *res---返回数据类型，有json和array两种，默认返回json格式
 *data---POST请求时的参数，数组格式
 */
function curlRequest( $url, $method='get', $data=array()){

    //初始化一个会话操作
    $ch = curl_init();

    //设置会话操作的通用参数
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_URL , $url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //POST方式时参数设置
    if( $method == 'post' ) curl_setopt($ch, CURLOPT_POST, 1);

    if( !empty($data) ) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    //执行会话
    $data = curl_exec($ch);

    //关闭会话，释放资源
    curl_close($ch);

    if( curl_errno($ch) ) {

        return curl_error($ch);//异常处理
    }

    //返回指定格式数据
    return $data;
}