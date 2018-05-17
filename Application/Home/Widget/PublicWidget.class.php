<?php
namespace Home\Widget;
use Think\Controller;

class PublicWidget extends Controller
{
  //获取网站title
  public function web_name()
  {
    return D('System')->getData('web_name');
  }

  //获取网站logo
  public function web_logo()
  {
    return D('System')->getImg('web_logo');

  }


  //获取服务热线
  public function web_phone()
  {
    return D('System')->getData('web_phone');
  }


}