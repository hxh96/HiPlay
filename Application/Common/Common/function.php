<?php
//关键字过滤
function keyWordCheck($str){
    // 去除空白
    $str = trim($str);
    // 读取关键字文本
    $content = @file_get_contents('sensitive.txt');
    // 转换成数组
    $arr = explode("\n", $content);
    // 遍历检测
    for($i=0,$k=count($arr);$i<$k;$i++){
        // 如果此数组元素为空则跳过此次循环
        if($arr[$i]==''){
            continue;
        }

        // 如果检测到关键字，则返回匹配的关键字,并终止运行
        // 这一次加了 trim()函数
        if(@strpos($str,trim($arr[$i]))!==false){
            //$i=$k;
            return $arr[$i];
        }
    }
    // 如果没有检测到关键字则返回false
    return false;
}


function log_result($file,$word)
{
    $fp = fopen($file,"a");
    flock($fp, LOCK_EX) ;
    fwrite($fp,"执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."\n".$word."\n\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}