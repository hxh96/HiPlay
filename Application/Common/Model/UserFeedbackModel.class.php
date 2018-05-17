<?php
/**
 * 用户反馈模型
 */

namespace Common\Model;


use Think\Model;

class UserFeedbackModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('UserFeedback');
    }


    /**
     * 获取所有信息
     * @return mixed
     */
    public function getAll()
    {
        $arr = $this->_db->order('id desc')->select();
        return $arr;
    }

    /**
     * 添加数据
     * @param $data 数据
     * @return mixed
     */
    public function addData($data)
    {
        $arr = $this->_db->add($data);
        return $arr;
    }
}