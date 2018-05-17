<?php
/**
 * 积分规则模型
 */

namespace Common\Model;


use Think\Model;

class IntegralRulesModel extends Model
{
    private $_db = '';

    public function __construct(){
        $this->_db = M('IntegralRules');
    }

    /**
     * 获取所有信息
     * @param $map 条件表达式
     * @param $limit 条数
     * @return mixed
     */
    public function getAll($map='',$limit='')
    {
        $arr = $this->_db->where($map)->order('id desc')->limit($limit)->select();
        return $arr;
    }

    /**
     * 根据规则变量获取数据
     * @param $var 规则变量
     * @return mixed
     */
    public function getByVar($var)
    {
        return $this->_db->where(array('var'=>$var))->find();
    }


    /**
     * 根据id获取一条数据
     * @param $id id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->_db->where(array('id'=>$id))->find();
    }

    /**
     * 添加数据
     * @param $data 数据
     * @return mixed
     */
    public function addData($data)
    {
        return $this->_db->add($data);
    }

    /**
     * 更新数据
     * @param $data 数据
     * @return bool
     */
    public function updateData($data)
    {
        return $this->_db->save($data);
    }

}