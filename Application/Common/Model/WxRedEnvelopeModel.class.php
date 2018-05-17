<?php
/**
 * 微信红包发放记录模型
 */

namespace Common\Model;


use Think\Model;

class WxRedEnvelopeModel extends Model
{
    private $_db = '';

    public function __construct(){
        $this->_db = M('WxRedEnvelope');
    }


    /**
     * 添加数据
     * @param $data 数据
     * @return mixed
     */
    public function addData($data)
    {
        $id = $this->_db->add($data);
        return $id;
    }

    /**
     * 根据条件获取所有
     * @param string $map 条件
     * @return mixed
     */
    public function getAll($map='')
    {
        $arr = $this->_db->where($map)->order('id desc')->select();
        return $arr;
    }

}