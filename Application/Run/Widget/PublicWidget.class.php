<?php
namespace Run\Widget;
use Think\Controller;

class PublicWidget extends Controller {

  //获取站点名称
  public function web_name()
  {
      return D('System')->getData('web_name');
  }

    //获取logo
    public function web_logo()
    {
        return D('System')->getImg('web_logo');
    }

}